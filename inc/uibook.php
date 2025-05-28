<?php
/**
 * UiBook - Component Design System Manager
 * Manages component styles, spacing, and design tokens
 */

// Add UiBook admin menu
add_action('admin_menu', 'uibook_admin_menu');

function uibook_admin_menu() {
    add_menu_page(
        'UiBook',
        'UiBook',
        'manage_options',
        'uibook',
        'uibook_admin_page',
        'dashicons-layout',
        7
    );
}

// Enqueue admin assets
add_action('admin_enqueue_scripts', 'uibook_admin_assets');

function uibook_admin_assets($hook) {
    if ($hook !== 'toplevel_page_uibook') {
        return;
    }
    
    wp_enqueue_style(
        'uibook-admin-css',
        get_stylesheet_directory_uri() . '/assets/css/uibook-admin.css',
        array(),
        '1.0.0'
    );
    
    wp_enqueue_script(
        'uibook-admin-js',
        get_stylesheet_directory_uri() . '/assets/js/uibook-admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_localize_script('uibook-admin-js', 'uibook_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('uibook_save_nonce')
    ));
}

// Handle AJAX save
add_action('wp_ajax_uibook_save_settings', 'uibook_save_settings_handler');

function uibook_save_settings_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'uibook_save_nonce')) {
        wp_die('Security check failed');
    }
    
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    
    $settings = array(
        // Card Settings
        'card_padding_x' => sanitize_text_field($_POST['card_padding_x']),
        'card_padding_y' => sanitize_text_field($_POST['card_padding_y']),
        'card_radius' => sanitize_text_field($_POST['card_radius']),
        'card_shadow' => sanitize_text_field($_POST['card_shadow']),
        'card_spacing' => sanitize_text_field($_POST['card_spacing']),
        
        // Button Settings
        'button_full_width' => sanitize_text_field($_POST['button_full_width']),
        'button_padding_x' => sanitize_text_field($_POST['button_padding_x']),
        'button_padding_y' => sanitize_text_field($_POST['button_padding_y']),
        'button_radius' => sanitize_text_field($_POST['button_radius']),
        'button_style' => sanitize_text_field($_POST['button_style']),
        
        // Tag Settings
        'tag_style1_radius' => sanitize_text_field($_POST['tag_style1_radius']),
        'tag_style1_padding_x' => sanitize_text_field($_POST['tag_style1_padding_x']),
        'tag_style1_padding_y' => sanitize_text_field($_POST['tag_style1_padding_y']),
        'tag_style2_radius' => sanitize_text_field($_POST['tag_style2_radius']),
        'tag_style2_padding_x' => sanitize_text_field($_POST['tag_style2_padding_x']),
        'tag_style2_padding_y' => sanitize_text_field($_POST['tag_style2_padding_y']),
        'tag_spacing' => sanitize_text_field($_POST['tag_spacing']),
        
        // Layout Settings
        'grid_gap' => sanitize_text_field($_POST['grid_gap']),
        'content_spacing' => sanitize_text_field($_POST['content_spacing']),
        'section_padding' => sanitize_text_field($_POST['section_padding']),
    );
    
    // Save to JSON file
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/uibook.json';
    $json_data = array(
        'version' => '1.0.0',
        'updated' => current_time('mysql'),
        'settings' => $settings,
        'css_variables' => uibook_generate_css_variables($settings)
    );
    
    // Ensure directory exists
    $json_dir = dirname($json_file);
    if (!file_exists($json_dir)) {
        wp_mkdir_p($json_dir);
    }
    
    file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));
    
    // Update theme.json with UI variables
    uibook_update_theme_json($settings);
    
    wp_send_json_success(array(
        'message' => 'UiBook settings saved successfully!',
        'css_variables' => $json_data['css_variables']
    ));
}

// Generate CSS variables from settings
function uibook_generate_css_variables($settings) {
    return array(
        // Card Variables
        '--ui-card-padding-x' => $settings['card_padding_x'] . 'rem',
        '--ui-card-padding-y' => $settings['card_padding_y'] . 'rem',
        '--ui-card-radius' => $settings['card_radius'] . 'px',
        '--ui-card-shadow' => uibook_get_shadow_value($settings['card_shadow']),
        '--ui-card-spacing' => $settings['card_spacing'] . 'rem',
        
        // Button Variables
        '--ui-button-width' => $settings['button_full_width'] === 'true' ? '100%' : 'auto',
        '--ui-button-padding-x' => $settings['button_padding_x'] . 'rem',
        '--ui-button-padding-y' => $settings['button_padding_y'] . 'rem',
        '--ui-button-radius' => $settings['button_radius'] . 'px',
        
        // Tag Variables
        '--ui-tag-style1-radius' => $settings['tag_style1_radius'] . 'px',
        '--ui-tag-style1-padding-x' => $settings['tag_style1_padding_x'] . 'rem',
        '--ui-tag-style1-padding-y' => $settings['tag_style1_padding_y'] . 'rem',
        '--ui-tag-style2-radius' => $settings['tag_style2_radius'] . 'px',
        '--ui-tag-style2-padding-x' => $settings['tag_style2_padding_x'] . 'rem',
        '--ui-tag-style2-padding-y' => $settings['tag_style2_padding_y'] . 'rem',
        '--ui-tag-spacing' => $settings['tag_spacing'] . 'rem',
        
        // Layout Variables
        '--ui-grid-gap' => $settings['grid_gap'] . 'rem',
        '--ui-content-spacing' => $settings['content_spacing'] . 'rem',
        '--ui-section-padding' => $settings['section_padding'] . 'rem',
    );
}

// Get shadow CSS value based on intensity
function uibook_get_shadow_value($intensity) {
    $shadows = array(
        'none' => 'none',
        'small' => '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
        'medium' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'large' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
        'xlarge' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
    );
    
    return isset($shadows[$intensity]) ? $shadows[$intensity] : $shadows['medium'];
}

// Update theme.json with UI variables
function uibook_update_theme_json($settings) {
    $theme_json_file = get_stylesheet_directory() . '/theme.json';
    
    if (file_exists($theme_json_file)) {
        $theme_json = json_decode(file_get_contents($theme_json_file), true);
        
        // Add UI variables to custom section
        if (!isset($theme_json['settings']['custom'])) {
            $theme_json['settings']['custom'] = array();
        }
        
        $theme_json['settings']['custom']['ui'] = uibook_generate_css_variables($settings);
        
        file_put_contents($theme_json_file, json_encode($theme_json, JSON_PRETTY_PRINT));
    }
}

// Get current UiBook settings
function uibook_get_current_settings() {
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/uibook.json';
    
    if (file_exists($json_file)) {
        $json_data = json_decode(file_get_contents($json_file), true);
        if (isset($json_data['settings'])) {
            return $json_data['settings'];
        }
    }
    
    // Default settings
    return array(
        'card_padding_x' => '2',
        'card_padding_y' => '1.5',
        'card_radius' => '12',
        'card_shadow' => 'medium',
        'card_spacing' => '0.75',
        'button_full_width' => 'true',
        'button_padding_x' => '1',
        'button_padding_y' => '0.5',
        'button_radius' => '6',
        'button_style' => 'primary',
        'tag_style1_radius' => '20',
        'tag_style1_padding_x' => '0.75',
        'tag_style1_padding_y' => '0.25',
        'tag_style2_radius' => '6',
        'tag_style2_padding_x' => '0.5',
        'tag_style2_padding_y' => '0.25',
        'tag_spacing' => '0.5',
        'grid_gap' => '1.5',
        'content_spacing' => '1',
        'section_padding' => '4'
    );
}

// Admin page HTML
function uibook_admin_page() {
    $settings = uibook_get_current_settings();
    ?>
    <div class="wrap uibook-admin">
        <div class="uibook-header">
            <h1><span class="dashicons dashicons-layout"></span> UiBook</h1>
            <p class="uibook-subtitle">Component Design System Manager</p>
        </div>
        
        <div class="uibook-container">
            <div class="uibook-main">
                <form id="uibook-form" method="post">
                    <?php wp_nonce_field('uibook_save_nonce', 'uibook_nonce'); ?>
                    
                    <!-- Card Settings -->
                    <div class="uibook-section">
                        <h2><span class="dashicons dashicons-id-alt"></span> Card Components</h2>
                        <div class="uibook-controls">
                            <div class="uibook-control-group">
                                <label for="card_padding_x">Horizontal Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="card_padding_x" name="card_padding_x" 
                                           min="0.5" max="4" step="0.25" value="<?php echo esc_attr($settings['card_padding_x']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['card_padding_x']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="card_padding_y">Vertical Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="card_padding_y" name="card_padding_y" 
                                           min="0.5" max="4" step="0.25" value="<?php echo esc_attr($settings['card_padding_y']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['card_padding_y']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="card_radius">Border Radius</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="card_radius" name="card_radius" 
                                           min="0" max="24" step="2" value="<?php echo esc_attr($settings['card_radius']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['card_radius']); ?>px</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="card_shadow">Shadow Intensity</label>
                                <select id="card_shadow" name="card_shadow">
                                    <option value="none" <?php selected($settings['card_shadow'], 'none'); ?>>None</option>
                                    <option value="small" <?php selected($settings['card_shadow'], 'small'); ?>>Small</option>
                                    <option value="medium" <?php selected($settings['card_shadow'], 'medium'); ?>>Medium</option>
                                    <option value="large" <?php selected($settings['card_shadow'], 'large'); ?>>Large</option>
                                    <option value="xlarge" <?php selected($settings['card_shadow'], 'xlarge'); ?>>X-Large</option>
                                </select>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="card_spacing">Content Spacing</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="card_spacing" name="card_spacing" 
                                           min="0.25" max="2" step="0.25" value="<?php echo esc_attr($settings['card_spacing']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['card_spacing']); ?>rem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Button Settings -->
                    <div class="uibook-section">
                        <h2><span class="dashicons dashicons-button"></span> Button Components</h2>
                        <div class="uibook-controls">
                            <div class="uibook-control-group">
                                <label for="button_full_width">Full Width</label>
                                <div class="uibook-toggle">
                                    <input type="checkbox" id="button_full_width" name="button_full_width" 
                                           value="true" <?php checked($settings['button_full_width'], 'true'); ?>>
                                    <label for="button_full_width" class="uibook-toggle-label">
                                        <span class="uibook-toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="button_padding_x">Horizontal Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="button_padding_x" name="button_padding_x" 
                                           min="0.5" max="3" step="0.25" value="<?php echo esc_attr($settings['button_padding_x']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['button_padding_x']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="button_padding_y">Vertical Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="button_padding_y" name="button_padding_y" 
                                           min="0.25" max="1.5" step="0.25" value="<?php echo esc_attr($settings['button_padding_y']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['button_padding_y']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="button_radius">Border Radius</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="button_radius" name="button_radius" 
                                           min="0" max="20" step="2" value="<?php echo esc_attr($settings['button_radius']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['button_radius']); ?>px</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tag Settings -->
                    <div class="uibook-section">
                        <h2><span class="dashicons dashicons-tag"></span> Tag Components</h2>
                        
                        <h3>Style 1 - Amenity Tags</h3>
                        <div class="uibook-controls">
                            <div class="uibook-control-group">
                                <label for="tag_style1_radius">Border Radius</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style1_radius" name="tag_style1_radius" 
                                           min="0" max="30" step="2" value="<?php echo esc_attr($settings['tag_style1_radius']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style1_radius']); ?>px</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="tag_style1_padding_x">Horizontal Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style1_padding_x" name="tag_style1_padding_x" 
                                           min="0.25" max="2" step="0.25" value="<?php echo esc_attr($settings['tag_style1_padding_x']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style1_padding_x']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="tag_style1_padding_y">Vertical Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style1_padding_y" name="tag_style1_padding_y" 
                                           min="0.125" max="1" step="0.125" value="<?php echo esc_attr($settings['tag_style1_padding_y']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style1_padding_y']); ?>rem</span>
                                </div>
                            </div>
                        </div>
                        
                        <h3>Style 2 - Spec Tags</h3>
                        <div class="uibook-controls">
                            <div class="uibook-control-group">
                                <label for="tag_style2_radius">Border Radius</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style2_radius" name="tag_style2_radius" 
                                           min="0" max="20" step="2" value="<?php echo esc_attr($settings['tag_style2_radius']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style2_radius']); ?>px</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="tag_style2_padding_x">Horizontal Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style2_padding_x" name="tag_style2_padding_x" 
                                           min="0.25" max="1.5" step="0.25" value="<?php echo esc_attr($settings['tag_style2_padding_x']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style2_padding_x']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="tag_style2_padding_y">Vertical Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_style2_padding_y" name="tag_style2_padding_y" 
                                           min="0.125" max="0.75" step="0.125" value="<?php echo esc_attr($settings['tag_style2_padding_y']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_style2_padding_y']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="tag_spacing">Tag Spacing</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="tag_spacing" name="tag_spacing" 
                                           min="0.25" max="1.5" step="0.25" value="<?php echo esc_attr($settings['tag_spacing']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['tag_spacing']); ?>rem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layout Settings -->
                    <div class="uibook-section">
                        <h2><span class="dashicons dashicons-grid-view"></span> Layout Settings</h2>
                        <div class="uibook-controls">
                            <div class="uibook-control-group">
                                <label for="grid_gap">Grid Gap</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="grid_gap" name="grid_gap" 
                                           min="0.5" max="4" step="0.5" value="<?php echo esc_attr($settings['grid_gap']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['grid_gap']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="content_spacing">Content Spacing</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="content_spacing" name="content_spacing" 
                                           min="0.5" max="3" step="0.25" value="<?php echo esc_attr($settings['content_spacing']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['content_spacing']); ?>rem</span>
                                </div>
                            </div>
                            
                            <div class="uibook-control-group">
                                <label for="section_padding">Section Padding</label>
                                <div class="uibook-slider-container">
                                    <input type="range" id="section_padding" name="section_padding" 
                                           min="2" max="8" step="0.5" value="<?php echo esc_attr($settings['section_padding']); ?>">
                                    <span class="uibook-value"><?php echo esc_html($settings['section_padding']); ?>rem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uibook-actions">
                        <button type="submit" class="button button-primary button-large">
                            <span class="dashicons dashicons-saved"></span> Save UiBook Settings
                        </button>
                        <button type="button" id="uibook-reset" class="button button-secondary">
                            <span class="dashicons dashicons-undo"></span> Reset to Defaults
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="uibook-preview">
                <h3>Component Preview</h3>
                <div class="uibook-preview-content">
                    <!-- Card Preview -->
                    <div class="uibook-preview-card" id="preview-card">
                        <div class="uibook-preview-card-image">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/miDocs/SITE DATA/Images/Properties-Featured/villa-featured-2.png" alt="Preview">
                            <div class="uibook-preview-tag-style1" id="preview-tag-style1">Ocean View</div>
                        </div>
                        <div class="uibook-preview-card-content" id="preview-card-content">
                            <h3>Villa Sample</h3>
                            <p>Beautiful oceanfront villa with stunning views and modern amenities.</p>
                            <div class="uibook-preview-tags" id="preview-tags">
                                <span class="uibook-preview-tag-style2" id="preview-tag-style2">3 beds</span>
                                <span class="uibook-preview-tag-style2">2 baths</span>
                                <span class="uibook-preview-tag-style2">6 guests</span>
                            </div>
                            <div class="uibook-preview-button-container">
                                <button class="uibook-preview-button" id="preview-button">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="uibook-message" class="notice" style="display: none;"></div>
    </div>
    <?php
}
