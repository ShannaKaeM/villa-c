<?php
/**
 * HeroBook - Section Book for Hero Layout Management
 * Manages hero section templates and configurations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add HeroBook to admin menu as submenu under Design System
add_action('admin_menu', 'herobook_admin_menu');
function herobook_admin_menu() {
    add_submenu_page(
        'design-system',  // Parent slug (we'll create this main menu)
        'HeroBook - Section Templates',
        'HeroBook',
        'manage_options',
        'herobook',
        'herobook_admin_page',
        30
    );
}

// Admin page content
function herobook_admin_page() {
    // Handle form submission
    if (isset($_POST['save_hero_template']) && wp_verify_nonce($_POST['herobook_nonce'], 'herobook_save')) {
        herobook_save_template_handler();
    }
    
    $current_templates = herobook_get_current_templates();
    ?>
    <div class="wrap herobook-admin">
        <div class="herobook-header">
            <h1>🏗️ HeroBook - Section Templates</h1>
            <p>Create and manage hero section layouts with live preview</p>
        </div>

        <div class="herobook-container">
            <div class="herobook-controls">
                <form method="post" action="">
                    <?php wp_nonce_field('herobook_save', 'herobook_nonce'); ?>
                    
                    <div class="herobook-section">
                        <h2>Hero Templates</h2>
                        
                        <!-- Template Selector -->
                        <div class="control-group">
                            <label>Active Template</label>
                            <select id="hero-template" name="active_template">
                                <option value="home-hero" <?php selected($current_templates['active_template'] ?? '', 'home-hero'); ?>>Home Hero</option>
                                <option value="page-hero" <?php selected($current_templates['active_template'] ?? '', 'page-hero'); ?>>Page Hero</option>
                                <option value="product-hero" <?php selected($current_templates['active_template'] ?? '', 'product-hero'); ?>>Product Hero</option>
                            </select>
                        </div>

                        <!-- Home Hero Configuration -->
                        <div id="home-hero-config" class="template-config">
                            <h3>Home Hero Configuration</h3>
                            
                            <div class="control-group">
                                <label>Layout Type</label>
                                <select name="home_hero[layout_type]">
                                    <option value="asymmetric-40-60" <?php selected($current_templates['home_hero']['layout_type'] ?? '', 'asymmetric-40-60'); ?>>Asymmetric 40/60</option>
                                    <option value="asymmetric-50-50" <?php selected($current_templates['home_hero']['layout_type'] ?? '', 'asymmetric-50-50'); ?>>Split 50/50</option>
                                    <option value="centered" <?php selected($current_templates['home_hero']['layout_type'] ?? '', 'centered'); ?>>Centered</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label>Content Alignment</label>
                                <select name="home_hero[content_alignment]">
                                    <option value="left" <?php selected($current_templates['home_hero']['content_alignment'] ?? '', 'left'); ?>>Left</option>
                                    <option value="center" <?php selected($current_templates['home_hero']['content_alignment'] ?? '', 'center'); ?>>Center</option>
                                    <option value="right" <?php selected($current_templates['home_hero']['content_alignment'] ?? '', 'right'); ?>>Right</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label>Right Side Layout</label>
                                <select name="home_hero[right_layout]">
                                    <option value="product-grid" <?php selected($current_templates['home_hero']['right_layout'] ?? '', 'product-grid'); ?>>Product Grid</option>
                                    <option value="feature-cards" <?php selected($current_templates['home_hero']['right_layout'] ?? '', 'feature-cards'); ?>>Feature Cards</option>
                                    <option value="single-image" <?php selected($current_templates['home_hero']['right_layout'] ?? '', 'single-image'); ?>>Single Image</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label>Section Height</label>
                                <select name="home_hero[section_height]">
                                    <option value="viewport-80" <?php selected($current_templates['home_hero']['section_height'] ?? '', 'viewport-80'); ?>>80vh</option>
                                    <option value="viewport-100" <?php selected($current_templates['home_hero']['section_height'] ?? '', 'viewport-100'); ?>>100vh</option>
                                    <option value="auto" <?php selected($current_templates['home_hero']['section_height'] ?? '', 'auto'); ?>>Auto</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label>Background Style</label>
                                <select name="home_hero[background_style]">
                                    <option value="solid" <?php selected($current_templates['home_hero']['background_style'] ?? '', 'solid'); ?>>Solid Color</option>
                                    <option value="gradient" <?php selected($current_templates['home_hero']['background_style'] ?? '', 'gradient'); ?>>Gradient</option>
                                    <option value="image" <?php selected($current_templates['home_hero']['background_style'] ?? '', 'image'); ?>>Background Image</option>
                                </select>
                            </div>

                            <!-- Component Integration -->
                            <h4>Component Integration</h4>
                            
                            <div class="control-group">
                                <label>
                                    <input type="checkbox" name="home_hero[show_avatar_group]" value="1" <?php checked($current_templates['home_hero']['show_avatar_group'] ?? false, 1); ?>>
                                    Show Avatar Group
                                </label>
                            </div>

                            <div class="control-group">
                                <label>
                                    <input type="checkbox" name="home_hero[show_cta_button]" value="1" <?php checked($current_templates['home_hero']['show_cta_button'] ?? false, 1); ?>>
                                    Show CTA Button
                                </label>
                            </div>

                            <div class="control-group">
                                <label>
                                    <input type="checkbox" name="home_hero[show_product_cards]" value="1" <?php checked($current_templates['home_hero']['show_product_cards'] ?? false, 1); ?>>
                                    Show Product Cards
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="herobook-actions">
                        <button type="submit" name="save_hero_template" class="button-primary">
                            💾 Save Hero Template
                        </button>
                        <button type="button" id="reset-defaults" class="button-secondary">
                            🔄 Reset to Defaults
                        </button>
                        <button type="button" id="export-template" class="button-secondary">
                            📤 Export Template
                        </button>
                    </div>
                </form>
            </div>

            <div class="herobook-preview">
                <div class="preview-header">
                    <h3>Live Preview</h3>
                    <div class="preview-controls">
                        <button class="preview-device active" data-device="desktop">🖥️</button>
                        <button class="preview-device" data-device="tablet">📱</button>
                        <button class="preview-device" data-device="mobile">📱</button>
                    </div>
                </div>
                
                <div class="preview-frame" id="hero-preview">
                    <div class="hero-preview-content">
                        <!-- Live preview will be generated here -->
                        <div class="preview-hero">
                            <div class="hero-left">
                                <h1>Exquisite design combined with functionalities</h1>
                                <p>Pellentesque ullamcorper dignissim condimentum volutpat consectetur mauris nunc lacinia quis.</p>
                                <div class="avatar-group">
                                    <div class="avatars">👤👤👤</div>
                                    <span>Contact with our expert</span>
                                </div>
                                <button class="cta-button">Shop Now</button>
                            </div>
                            <div class="hero-right">
                                <div class="product-grid">
                                    <div class="product-card large">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDIwMCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRjNGNEY2Ii8+Cjx0ZXh0IHg9IjEwMCIgeT0iNzUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzlDQTNBRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+UHJvZHVjdCBJbWFnZTwvdGV4dD4KPC9zdmc+" alt="Product">
                                        <div class="price-tag">Wooden Chair<br>$199</div>
                                    </div>
                                    <div class="product-card">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDE1MCA1MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxNTAiIGhlaWdodD0iMTIwIiBmaWxsPSIjRTVFN0VCIi8+Cjx0ZXh0IHg9Ijc1IiB5PSI2MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNkI3Mjg0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Qcm9kdWN0PC90ZXh0Pgo8L3N2Zz4=" alt="Product">
                                        <div class="price-tag">Premium Elite<br>$130</div>
                                    </div>
                                    <div class="promo-card">
                                        <h3>25% OFF</h3>
                                        <p>Done ac odio tempor dapibus</p>
                                        <button>Explore Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .herobook-admin {
            max-width: none;
            margin: 0;
        }
        
        .herobook-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            margin: 0 -20px 2rem -20px;
        }
        
        .herobook-header h1 {
            color: white;
            margin: 0 0 0.5rem 0;
        }
        
        .herobook-container {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 2rem;
            height: calc(100vh - 200px);
        }
        
        .herobook-controls {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        .herobook-section h2 {
            margin: 0 0 1rem 0;
            color: #374151;
        }
        
        .control-group {
            margin-bottom: 1rem;
        }
        
        .control-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        
        .control-group select,
        .control-group input[type="text"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }
        
        .template-config {
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        
        .herobook-actions {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .herobook-actions button {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .herobook-preview {
            background: white;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .preview-controls button {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 0.5rem;
            margin-left: 0.25rem;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .preview-controls button.active {
            background: #3b82f6;
            color: white;
        }
        
        .preview-frame {
            flex: 1;
            padding: 1rem;
            overflow: auto;
        }
        
        .preview-hero {
            display: grid;
            grid-template-columns: 40% 60%;
            gap: 2rem;
            min-height: 400px;
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
        
        .hero-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .hero-left h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .hero-left p {
            margin-bottom: 1.5rem;
            color: #6b7280;
        }
        
        .avatar-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .cta-button {
            background: #5a7f80;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            width: fit-content;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .product-card.large {
            grid-row: span 2;
        }
        
        .product-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        
        .product-card.large img {
            height: 200px;
        }
        
        .price-tag {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: white;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .promo-card {
            background: #1f2937;
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        
        .promo-card h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }
        
        .promo-card p {
            margin: 0 0 1rem 0;
            opacity: 0.8;
        }
        
        .promo-card button {
            background: #5a7f80;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Template switching
            const templateSelect = document.getElementById('hero-template');
            const templateConfigs = document.querySelectorAll('.template-config');
            
            function showTemplateConfig() {
                templateConfigs.forEach(config => config.style.display = 'none');
                const activeConfig = document.getElementById(templateSelect.value + '-config');
                if (activeConfig) {
                    activeConfig.style.display = 'block';
                }
            }
            
            templateSelect.addEventListener('change', showTemplateConfig);
            showTemplateConfig();
            
            // Preview device switching
            const deviceButtons = document.querySelectorAll('.preview-device');
            const previewFrame = document.getElementById('hero-preview');
            
            deviceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deviceButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const device = this.dataset.device;
                    previewFrame.className = 'preview-frame ' + device;
                });
            });
            
            // Reset defaults
            document.getElementById('reset-defaults').addEventListener('click', function() {
                if (confirm('Reset all hero settings to defaults?')) {
                    // Reset form to defaults
                    location.reload();
                }
            });
        });
    </script>
    <?php
}

// Save hero template handler
function herobook_save_template_handler() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    $templates = array();
    
    // Save active template
    if (isset($_POST['active_template'])) {
        $templates['active_template'] = sanitize_text_field($_POST['active_template']);
    }
    
    // Save home hero configuration
    if (isset($_POST['home_hero'])) {
        $home_hero = $_POST['home_hero'];
        $templates['home_hero'] = array(
            'layout_type' => sanitize_text_field($home_hero['layout_type'] ?? 'asymmetric-40-60'),
            'content_alignment' => sanitize_text_field($home_hero['content_alignment'] ?? 'left'),
            'right_layout' => sanitize_text_field($home_hero['right_layout'] ?? 'product-grid'),
            'section_height' => sanitize_text_field($home_hero['section_height'] ?? 'viewport-80'),
            'background_style' => sanitize_text_field($home_hero['background_style'] ?? 'solid'),
            'show_avatar_group' => isset($home_hero['show_avatar_group']),
            'show_cta_button' => isset($home_hero['show_cta_button']),
            'show_product_cards' => isset($home_hero['show_product_cards'])
        );
    }
    
    // Save to JSON file
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/herobook.json';
    $json_data = array(
        'version' => '1.0.0',
        'last_updated' => current_time('mysql'),
        'templates' => $templates
    );
    
    // Ensure directory exists
    wp_mkdir_p(dirname($json_file));
    
    // Save JSON file
    file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));
    
    // Also save to WordPress options as backup
    update_option('herobook_templates', $templates);
    
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success"><p>Hero templates saved successfully!</p></div>';
    });
}

// Get current hero templates
function herobook_get_current_templates() {
    // Try to read from JSON file first
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/herobook.json';
    
    if (file_exists($json_file)) {
        $json_content = file_get_contents($json_file);
        $data = json_decode($json_content, true);
        if ($data && isset($data['templates'])) {
            return $data['templates'];
        }
    }
    
    // Fallback to WordPress options
    $templates = get_option('herobook_templates', array());
    
    // Default values
    if (empty($templates)) {
        $templates = array(
            'active_template' => 'home-hero',
            'home_hero' => array(
                'layout_type' => 'asymmetric-40-60',
                'content_alignment' => 'left',
                'right_layout' => 'product-grid',
                'section_height' => 'viewport-80',
                'background_style' => 'solid',
                'show_avatar_group' => true,
                'show_cta_button' => true,
                'show_product_cards' => true
            )
        );
    }
    
    return $templates;
}
