<?php
/**
 * ColorBook System - OKLCH Color Management
 * Advanced color management with live preview and CSS variable generation.
 */

// Add ColorBook as submenu under DesignBook
add_action('admin_menu', 'colorbook_admin_submenu');
function colorbook_admin_submenu() {
    add_submenu_page(
        'designbook',           // Parent slug
        'ColorBook',            // Page title
        'ColorBook',            // Menu title
        'manage_options',       // Capability
        'colorbook',            // Menu slug
        'colorbook_admin_page'  // Function
    );
}

// Enqueue admin styles and scripts
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'designbook_page_colorbook') return;
    
    wp_enqueue_style('colorbook-admin', get_stylesheet_directory_uri() . '/assets/css/colorbook-admin.css');
    wp_enqueue_script('colorbook-admin', get_stylesheet_directory_uri() . '/assets/js/colorbook-admin.js', ['jquery'], '1.0.0', true);
    wp_localize_script('colorbook-admin', 'colorbook', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('colorbook_nonce')
    ]);
});

// AJAX handler for saving colors
add_action('wp_ajax_colorbook_save_colors', 'colorbook_save_colors_handler');
function colorbook_save_colors_handler() {
    check_ajax_referer('colorbook_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    $colors = json_decode(stripslashes($_POST['colors']), true);
    
    // Save to dedicated ColorBook JSON file
    $colorbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/colorbook.json';
    
    // Ensure directory exists
    $colorbook_dir = dirname($colorbook_json_path);
    if (!file_exists($colorbook_dir)) {
        wp_mkdir_p($colorbook_dir);
    }
    
    // Create ColorBook JSON structure
    $colorbook_data = [
        'version' => '1.0.0',
        'updated' => current_time('mysql'),
        'theme' => 'carbon-blocksy',
        'colors' => $colors,
        'css_variables' => []
    ];
    
    // Generate CSS variables for easy access
    foreach ($colors as $index => $color) {
        $colorbook_data['css_variables']['--theme-palette-color-' . ($index + 1)] = $color['hex'];
        $colorbook_data['css_variables']['--color-' . $color['slug']] = $color['hex'];
    }
    
    // Save ColorBook JSON
    file_put_contents($colorbook_json_path, json_encode($colorbook_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    // Update theme.json
    colorbook_sync_with_theme_json($colors);
    
    wp_send_json_success([
        'message' => 'Colors saved successfully to ColorBook and theme.json',
        'colorbook_path' => $colorbook_json_path
    ]);
}

// Sync colors with theme.json
function colorbook_sync_with_theme_json($colors) {
    // Update theme.json
    $theme_json_path = get_stylesheet_directory() . '/theme.json';
    
    // Create theme.json if it doesn't exist
    if (!file_exists($theme_json_path)) {
        $theme_json = [
            'version' => 2,
            'settings' => [
                'color' => [
                    'palette' => []
                ]
            ]
        ];
    } else {
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
    }
    
    // Update the palette
    $theme_json['settings']['color']['palette'] = [];
    
    foreach ($colors as $color) {
        $theme_json['settings']['color']['palette'][] = [
            'slug' => $color['slug'],
            'color' => $color['hex'],
            'name' => $color['name']
        ];
    }
    
    // Save theme.json
    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    return true;
}

// Get current colors from theme.json or defaults
function colorbook_get_current_colors() {
    // First try to load from ColorBook JSON file
    $colorbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/colorbook.json';
    
    if (file_exists($colorbook_json_path)) {
        $colorbook_data = json_decode(file_get_contents($colorbook_json_path), true);
        if (isset($colorbook_data['colors']) && is_array($colorbook_data['colors'])) {
            return $colorbook_data['colors'];
        }
    }
    
    // Fallback to theme.json
    $theme_json_path = get_stylesheet_directory() . '/theme.json';
    
    $default_colors = [
        ['slug' => 'primary-light', 'name' => 'Primary Light', 'hex' => '#8dabac', 'oklch' => [70, 0.045, 194]],
        ['slug' => 'primary', 'name' => 'Primary', 'hex' => '#5a7f80', 'oklch' => [56, 0.064, 194]],
        ['slug' => 'primary-dark', 'name' => 'Primary Dark', 'hex' => '#425a5b', 'oklch' => [40, 0.075, 194]],
        ['slug' => 'secondary-light', 'name' => 'Secondary Light', 'hex' => '#d1a896', 'oklch' => [75, 0.065, 64]],
        ['slug' => 'secondary', 'name' => 'Secondary', 'hex' => '#a36b57', 'oklch' => [60, 0.089, 64]],
        ['slug' => 'secondary-dark', 'name' => 'Secondary Dark', 'hex' => '#744d3e', 'oklch' => [45, 0.095, 64]],
        ['slug' => 'neutral-light', 'name' => 'Neutral Light', 'hex' => '#c4b5a0', 'oklch' => [75, 0.035, 90]],
        ['slug' => 'neutral', 'name' => 'Neutral', 'hex' => '#9b8974', 'oklch' => [60, 0.040, 90]],
        ['slug' => 'neutral-dark', 'name' => 'Neutral Dark', 'hex' => '#736654', 'oklch' => [45, 0.035, 90]],
        ['slug' => 'base-white', 'name' => 'Base White', 'hex' => '#ffffff', 'oklch' => [100, 0, 0]],
        ['slug' => 'base-lightest', 'name' => 'Base Lightest', 'hex' => '#e5e5e5', 'oklch' => [92, 0, 0]],
        ['slug' => 'base-light', 'name' => 'Base Light', 'hex' => '#bfbfbf', 'oklch' => [80, 0, 0]],
        ['slug' => 'base', 'name' => 'Base', 'hex' => '#9c9c9c', 'oklch' => [66, 0, 0]],
        ['slug' => 'base-dark', 'name' => 'Base Dark', 'hex' => '#737373', 'oklch' => [50, 0, 0]],
        ['slug' => 'base-darkest', 'name' => 'Base Darkest', 'hex' => '#4d4d4d', 'oklch' => [35, 0, 0]],
        ['slug' => 'base-black', 'name' => 'Base Black', 'hex' => '#000000', 'oklch' => [0, 0, 0]]
    ];
    
    // Merge with existing colors from theme.json if it exists
    if (file_exists($theme_json_path)) {
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
        if (isset($theme_json['settings']['color']['palette'])) {
            foreach ($theme_json['settings']['color']['palette'] as $color) {
                foreach ($default_colors as &$default) {
                    if ($default['slug'] === $color['slug']) {
                        $default['hex'] = $color['color'];
                        // TODO: Convert hex to OKLCH
                    }
                }
            }
        }
    }
    
    return $default_colors;
}

// Render the admin page
function colorbook_admin_page() {
    $colors = colorbook_get_current_colors();
    ?>
    <div class="wrap colorbook">
        <h1>🎨 ColorBook</h1>
        <p>Manage your 16-color system with live OKLCH editing for Carbon Blocksy theme</p>
        
        <div class="colorbook-container">
            <!-- Color Groups -->
            <div class="color-groups">
                <!-- Primary Colors -->
                <div class="color-group">
                    <h2>Primary Colors</h2>
                    <div class="color-cards">
                        <?php foreach ($colors as $color): ?>
                            <?php if (strpos($color['slug'], 'primary') === 0): ?>
                                <?php colorbook_render_color_card($color); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Secondary Colors -->
                <div class="color-group">
                    <h2>Secondary Colors</h2>
                    <div class="color-cards">
                        <?php foreach ($colors as $color): ?>
                            <?php if (strpos($color['slug'], 'secondary') === 0): ?>
                                <?php colorbook_render_color_card($color); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Neutral Colors -->
                <div class="color-group">
                    <h2>Neutral Colors</h2>
                    <div class="color-cards">
                        <?php foreach ($colors as $color): ?>
                            <?php if (strpos($color['slug'], 'neutral') === 0): ?>
                                <?php colorbook_render_color_card($color); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Base Colors -->
                <div class="color-group">
                    <h2>Base Colors</h2>
                    <div class="color-cards">
                        <?php foreach ($colors as $color): ?>
                            <?php if (strpos($color['slug'], 'base') === 0): ?>
                                <?php colorbook_render_color_card($color); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="colorbook-actions">
                <button class="button button-primary" id="save-colors">Save Colors</button>
                <button class="button" id="export-colors">Export Colors</button>
                <button class="button" id="import-colors">Import Colors</button>
                <input type="file" id="import-file" style="display: none;" accept=".json">
            </div>
            
            <!-- Color Editor Modal -->
            <div id="color-editor-modal" class="color-editor-modal" style="display: none;">
                <div class="modal-content">
                    <h3>Edit Color: <span id="editing-color-name"></span></h3>
                    <div class="color-preview" id="color-preview"></div>
                    
                    <div class="oklch-controls">
                        <div class="slider-group">
                            <label>Lightness: <span id="l-value">0</span>%</label>
                            <input type="range" id="l-slider" min="0" max="100" step="1">
                        </div>
                        <div class="slider-group">
                            <label>Chroma: <span id="c-value">0</span></label>
                            <input type="range" id="c-slider" min="0" max="0.4" step="0.001">
                        </div>
                        <div class="slider-group">
                            <label>Hue: <span id="h-value">0</span>°</label>
                            <input type="range" id="h-slider" min="0" max="360" step="1">
                        </div>
                    </div>
                    
                    <div class="color-values">
                        <div>OKLCH: <code id="oklch-value">oklch(0% 0 0)</code></div>
                        <div>HEX: <code id="hex-value">#000000</code></div>
                    </div>
                    
                    <div class="modal-actions">
                        <button class="button button-primary" id="apply-color">Apply</button>
                        <button class="button" id="cancel-edit">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .colorbook-container {
            max-width: 1200px;
            margin: 20px 0;
        }
        
        .color-groups {
            display: grid;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .color-group h2 {
            margin: 0 0 15px 0;
            color: #1d2327;
            font-size: 18px;
        }
        
        .color-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .color-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .color-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .color-swatch {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .color-info {
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .color-info code {
            background: #f0f0f1;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .colorbook-actions {
            padding: 20px 0;
            border-top: 1px solid #ddd;
        }
        
        .colorbook-actions .button {
            margin-right: 10px;
        }
        
        .color-editor-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 100000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 500px;
            max-width: 90vw;
        }
        
        .color-preview {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        .oklch-controls {
            margin: 20px 0;
        }
        
        .slider-group {
            margin-bottom: 15px;
        }
        
        .slider-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .slider-group input[type="range"] {
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: #ddd;
            outline: none;
        }
        
        .color-values {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .color-values div {
            margin-bottom: 8px;
        }
        
        .color-values code {
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 10px;
        }
        
        .modal-actions {
            text-align: right;
            margin-top: 20px;
        }
        
        .modal-actions .button {
            margin-left: 10px;
        }
        </style>
    </div>
    <?php
}

// Render individual color card
function colorbook_render_color_card($color) {
    $text_color = $color['oklch'][0] > 60 ? '#000' : '#fff';
    ?>
    <div class="color-card" data-color='<?php echo json_encode($color); ?>'>
        <div class="color-swatch" style="background-color: <?php echo $color['hex']; ?>; color: <?php echo $text_color; ?>;">
            <span class="color-name"><?php echo $color['name']; ?></span>
        </div>
        <div class="color-info">
            <code><?php echo $color['hex']; ?></code>
            <button class="button button-small edit-color">Edit</button>
        </div>
    </div>
    <?php
}

// AJAX handler for exporting colors
add_action('wp_ajax_colorbook_export_colors', 'colorbook_export_colors_handler');
function colorbook_export_colors_handler() {
    check_ajax_referer('colorbook_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    $colors = colorbook_get_current_colors();
    
    // Create Blocksy-compatible export format
    $export_data = [
        'template' => 'carbon-blocksy',
        'version' => '1.0.0',
        'exported' => current_time('mysql'),
        'colors' => $colors
    ];
    
    wp_send_json_success($export_data);
}

// Helper function to get ColorBook color by slug
function get_colorbook_color($slug, $format = 'hex') {
    $colors = colorbook_get_current_colors();
    
    foreach ($colors as $color) {
        if ($color['slug'] === $slug) {
            switch ($format) {
                case 'oklch':
                    return 'oklch(' . $color['oklch'][0] . '% ' . $color['oklch'][1] . ' ' . $color['oklch'][2] . ')';
                case 'hex':
                default:
                    return $color['hex'];
            }
        }
    }
    
    return null;
}

// Helper function to get all ColorBook data
function get_colorbook_data() {
    $colorbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/colorbook.json';
    
    if (file_exists($colorbook_json_path)) {
        return json_decode(file_get_contents($colorbook_json_path), true);
    }
    
    return null;
}
