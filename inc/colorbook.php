<?php
/**
 * ColorBook System - OKLCH Color Management
 * 
 * Integrates with existing ColorBook assets:
 * - /assets/js/colorbook-admin.js (OKLCH editing interface)
 * - /assets/css/colorbook-admin.css (admin styles)
 * - /miDocs/SITE DATA/colorbook.json (color data)
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get color by slug from ColorBook JSON
 */
function cb_get_color($slug, $format = 'hex') {
    $colors = cb_get_all_colors();
    
    foreach ($colors as $color) {
        if ($color['slug'] === $slug) {
            switch ($format) {
                case 'hex':
                    return $color['hex'];
                case 'oklch':
                    return $color['oklch'];
                case 'object':
                    return $color;
                default:
                    return $color['hex'];
            }
        }
    }
    
    // Fallback colors
    $fallbacks = [
        'base-white' => '#ffffff',
        'base-black' => '#000000',
        'primary-dark' => '#425a5b',
        'primary' => '#5a7f80',
        'primary-light' => '#8dabac'
    ];
    
    return isset($fallbacks[$slug]) ? $fallbacks[$slug] : '#000000';
}

/**
 * Get all colors from ColorBook JSON
 */
function cb_get_all_colors() {
    $json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/colorbook.json';
    
    if (!file_exists($json_path)) {
        return [];
    }
    
    $json_content = file_get_contents($json_path);
    $data = json_decode($json_content, true);
    
    return isset($data['colors']) ? $data['colors'] : [];
}

/**
 * Get ColorBook context for templates
 */
function cb_get_context() {
    $colors = cb_get_all_colors();
    $context = [];
    
    foreach ($colors as $color) {
        $context[$color['slug']] = $color['hex'];
    }
    
    return $context;
}

/**
 * ColorBook Admin Page
 */
function cb_admin_page() {
    // Enqueue ColorBook assets
    wp_enqueue_script(
        'colorbook-admin',
        get_stylesheet_directory_uri() . '/assets/js/colorbook-admin.js',
        ['jquery'],
        '1.0.0',
        true
    );
    
    wp_enqueue_style(
        'colorbook-admin',
        get_stylesheet_directory_uri() . '/assets/css/colorbook-admin.css',
        [],
        '1.0.0'
    );
    
    $colors = cb_get_all_colors();
    ?>
    <div class="wrap colorbook-admin">
        <div class="colorbook-header">
            <h1>🎨 ColorBook</h1>
            <p>OKLCH Color Management System</p>
        </div>
        
        <div class="colorbook-content">
            <?php if (empty($colors)): ?>
                <div class="colorbook-empty">
                    <h3>No colors found</h3>
                    <p>ColorBook JSON file not found or empty.</p>
                    <p><strong>Expected location:</strong> <code>/miDocs/SITE DATA/colorbook.json</code></p>
                </div>
            <?php else: ?>
                <div class="colorbook-grid">
                    <?php foreach ($colors as $color): ?>
                        <div class="color-card" data-slug="<?php echo esc_attr($color['slug']); ?>">
                            <div class="color-preview" style="background-color: <?php echo esc_attr($color['hex']); ?>"></div>
                            <div class="color-info">
                                <h4><?php echo esc_html($color['name']); ?></h4>
                                <div class="color-values">
                                    <div class="color-value">
                                        <label>Slug:</label>
                                        <code><?php echo esc_html($color['slug']); ?></code>
                                    </div>
                                    <div class="color-value">
                                        <label>Hex:</label>
                                        <code><?php echo esc_html($color['hex']); ?></code>
                                    </div>
                                    <?php if (isset($color['oklch'])): ?>
                                        <div class="color-value">
                                            <label>OKLCH:</label>
                                            <code>oklch(<?php echo implode(' ', $color['oklch']); ?>)</code>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="colorbook-info">
                    <h3>🔧 Developer Reference</h3>
                    <div class="code-examples">
                        <h4>Helper Functions:</h4>
                        <pre><code>// Get specific color
$primary = cb_get_color('primary');           // Returns hex: #5a7f80
$primary_oklch = cb_get_color('primary', 'oklch'); // Returns OKLCH array

// Get all colors for templates
$context['colors'] = cb_get_context();        // Returns slug => hex pairs

// Usage in blocks
$hero_bg = cb_get_color('primary-dark');
$hero_text = cb_get_color('base-white');</code></pre>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        .colorbook-admin {
            max-width: 1200px;
        }
        
        .colorbook-header {
            margin-bottom: 30px;
        }
        
        .colorbook-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .colorbook-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .color-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .color-preview {
            height: 80px;
            width: 100%;
        }
        
        .color-info {
            padding: 15px;
        }
        
        .color-info h4 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }
        
        .color-value {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .color-value label {
            font-weight: 600;
            color: #666;
        }
        
        .color-value code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
        }
        
        .colorbook-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #5a7f80;
        }
        
        .code-examples pre {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            font-size: 0.9em;
        }
        
        .colorbook-empty {
            text-align: center;
            padding: 60px 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
    </style>
    <?php
}
