<?php
/**
 * TextBook - Typography System for Villa Community
 * Manages typography settings, font sizes, and text styling
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get typography data from JSON file
 */
function tb_get_typography_data() {
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/textbook.json';
    
    if (!file_exists($json_file)) {
        return array();
    }
    
    $json_content = file_get_contents($json_file);
    $data = json_decode($json_content, true);
    
    return $data ? $data : array();
}

/**
 * Get typography setting by key
 */
function tb_get_typography($key, $default = '') {
    $data = tb_get_typography_data();
    
    if (isset($data['typography'][$key])) {
        return $data['typography'][$key];
    }
    
    return $default;
}

/**
 * Get CSS variable by key
 */
function tb_get_css_variable($key, $default = '') {
    $data = tb_get_typography_data();
    
    if (isset($data['css_variables'][$key])) {
        return $data['css_variables'][$key];
    }
    
    return $default;
}

/**
 * Get all typography settings for template context
 */
function tb_get_context() {
    $data = tb_get_typography_data();
    return array(
        'typography' => $data['typography'] ?? array(),
        'css_variables' => $data['css_variables'] ?? array(),
        'size_scale' => $data['size_scale'] ?? array()
    );
}

/**
 * TextBook Admin Page
 */
function tb_admin_page() {
    // Handle form submission
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['textbook_nonce'], 'textbook_save')) {
        // Save typography settings
        $typography_data = array(
            'primary_font' => sanitize_text_field($_POST['primary_font'] ?? 'montserrat'),
            'body_font' => sanitize_text_field($_POST['body_font'] ?? 'montserrat'),
            'h1_size' => sanitize_text_field($_POST['h1_size'] ?? 'x-large'),
            'h2_size' => sanitize_text_field($_POST['h2_size'] ?? 'x-large'),
            'h3_size' => sanitize_text_field($_POST['h3_size'] ?? 'large'),
            'h4_size' => sanitize_text_field($_POST['h4_size'] ?? 'medium'),
            'h5_size' => sanitize_text_field($_POST['h5_size'] ?? 'small'),
            'body_size' => sanitize_text_field($_POST['body_size'] ?? 'medium'),
            'heading_weight' => sanitize_text_field($_POST['heading_weight'] ?? '600'),
            'body_weight' => sanitize_text_field($_POST['body_weight'] ?? '400'),
            'heading_line_height' => sanitize_text_field($_POST['heading_line_height'] ?? '1.2'),
            'body_line_height' => sanitize_text_field($_POST['body_line_height'] ?? '1.6')
        );
        
        update_option('textbook_typography_settings', $typography_data);
        echo '<div class="notice notice-success"><p>Typography settings saved!</p></div>';
    }
    
    // Get current data
    $data = tb_get_typography_data();
    $typography = $data['typography'] ?? array();
    $saved_settings = get_option('textbook_typography_settings', array());
    $current_typography = wp_parse_args($saved_settings, $typography);
    
    // Enqueue admin assets
    wp_enqueue_style('textbook-admin', get_stylesheet_directory_uri() . '/assets/css/textbook-admin.css');
    wp_enqueue_script('textbook-admin', get_stylesheet_directory_uri() . '/assets/js/textbook-admin.js', array('jquery'), '1.0.0', true);
    ?>
    
    <div class="wrap textbook-admin">
        <div class="textbook-header">
            <h1>📝 TextBook - Typography System</h1>
            <p>Manage typography settings, font sizes, and text styling for Villa Community</p>
        </div>
        
        <div class="textbook-content">
            <div class="textbook-main">
                <form method="post" action="">
                    <?php wp_nonce_field('textbook_save', 'textbook_nonce'); ?>
                    
                    <div class="typography-section">
                        <h2>🔤 Font Settings</h2>
                        <div class="typography-grid">
                            <div class="typography-field">
                                <label for="primary_font">Primary Font</label>
                                <select name="primary_font" id="primary_font">
                                    <option value="montserrat" <?php selected($current_typography['primary_font'], 'montserrat'); ?>>Montserrat</option>
                                    <option value="inter" <?php selected($current_typography['primary_font'], 'inter'); ?>>Inter</option>
                                    <option value="roboto" <?php selected($current_typography['primary_font'], 'roboto'); ?>>Roboto</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="body_font">Body Font</label>
                                <select name="body_font" id="body_font">
                                    <option value="montserrat" <?php selected($current_typography['body_font'], 'montserrat'); ?>>Montserrat</option>
                                    <option value="inter" <?php selected($current_typography['body_font'], 'inter'); ?>>Inter</option>
                                    <option value="roboto" <?php selected($current_typography['body_font'], 'roboto'); ?>>Roboto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="typography-section">
                        <h2>📏 Size Scale</h2>
                        <div class="typography-grid">
                            <div class="typography-field">
                                <label for="h1_size">H1 Size</label>
                                <select name="h1_size" id="h1_size">
                                    <option value="xx-large" <?php selected($current_typography['h1_size'], 'xx-large'); ?>>XX-Large</option>
                                    <option value="x-large" <?php selected($current_typography['h1_size'], 'x-large'); ?>>X-Large</option>
                                    <option value="large" <?php selected($current_typography['h1_size'], 'large'); ?>>Large</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="h2_size">H2 Size</label>
                                <select name="h2_size" id="h2_size">
                                    <option value="x-large" <?php selected($current_typography['h2_size'], 'x-large'); ?>>X-Large</option>
                                    <option value="large" <?php selected($current_typography['h2_size'], 'large'); ?>>Large</option>
                                    <option value="medium" <?php selected($current_typography['h2_size'], 'medium'); ?>>Medium</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="h3_size">H3 Size</label>
                                <select name="h3_size" id="h3_size">
                                    <option value="large" <?php selected($current_typography['h3_size'], 'large'); ?>>Large</option>
                                    <option value="medium" <?php selected($current_typography['h3_size'], 'medium'); ?>>Medium</option>
                                    <option value="small" <?php selected($current_typography['h3_size'], 'small'); ?>>Small</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="body_size">Body Size</label>
                                <select name="body_size" id="body_size">
                                    <option value="large" <?php selected($current_typography['body_size'], 'large'); ?>>Large</option>
                                    <option value="medium" <?php selected($current_typography['body_size'], 'medium'); ?>>Medium</option>
                                    <option value="small" <?php selected($current_typography['body_size'], 'small'); ?>>Small</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="typography-section">
                        <h2>⚖️ Font Weights & Line Heights</h2>
                        <div class="typography-grid">
                            <div class="typography-field">
                                <label for="heading_weight">Heading Weight</label>
                                <select name="heading_weight" id="heading_weight">
                                    <option value="400" <?php selected($current_typography['heading_weight'], '400'); ?>>400 (Normal)</option>
                                    <option value="500" <?php selected($current_typography['heading_weight'], '500'); ?>>500 (Medium)</option>
                                    <option value="600" <?php selected($current_typography['heading_weight'], '600'); ?>>600 (Semi-Bold)</option>
                                    <option value="700" <?php selected($current_typography['heading_weight'], '700'); ?>>700 (Bold)</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="body_weight">Body Weight</label>
                                <select name="body_weight" id="body_weight">
                                    <option value="300" <?php selected($current_typography['body_weight'], '300'); ?>>300 (Light)</option>
                                    <option value="400" <?php selected($current_typography['body_weight'], '400'); ?>>400 (Normal)</option>
                                    <option value="500" <?php selected($current_typography['body_weight'], '500'); ?>>500 (Medium)</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="heading_line_height">Heading Line Height</label>
                                <select name="heading_line_height" id="heading_line_height">
                                    <option value="1.1" <?php selected($current_typography['heading_line_height'], '1.1'); ?>>1.1</option>
                                    <option value="1.2" <?php selected($current_typography['heading_line_height'], '1.2'); ?>>1.2</option>
                                    <option value="1.3" <?php selected($current_typography['heading_line_height'], '1.3'); ?>>1.3</option>
                                </select>
                            </div>
                            
                            <div class="typography-field">
                                <label for="body_line_height">Body Line Height</label>
                                <select name="body_line_height" id="body_line_height">
                                    <option value="1.4" <?php selected($current_typography['body_line_height'], '1.4'); ?>>1.4</option>
                                    <option value="1.5" <?php selected($current_typography['body_line_height'], '1.5'); ?>>1.5</option>
                                    <option value="1.6" <?php selected($current_typography['body_line_height'], '1.6'); ?>>1.6</option>
                                    <option value="1.7" <?php selected($current_typography['body_line_height'], '1.7'); ?>>1.7</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="typography-actions">
                        <button type="submit" name="submit" class="button button-primary">Save Typography Settings</button>
                    </div>
                </form>
            </div>
            
            <div class="textbook-sidebar">
                <div class="typography-preview">
                    <h3>📖 Typography Preview</h3>
                    <div class="preview-content">
                        <h1 style="font-family: var(--wp--preset--font-family--<?php echo esc_attr($current_typography['primary_font']); ?>); font-weight: <?php echo esc_attr($current_typography['heading_weight']); ?>; line-height: <?php echo esc_attr($current_typography['heading_line_height']); ?>;">Heading 1 Sample</h1>
                        <h2 style="font-family: var(--wp--preset--font-family--<?php echo esc_attr($current_typography['primary_font']); ?>); font-weight: <?php echo esc_attr($current_typography['heading_weight']); ?>; line-height: <?php echo esc_attr($current_typography['heading_line_height']); ?>;">Heading 2 Sample</h2>
                        <h3 style="font-family: var(--wp--preset--font-family--<?php echo esc_attr($current_typography['primary_font']); ?>); font-weight: <?php echo esc_attr($current_typography['heading_weight']); ?>; line-height: <?php echo esc_attr($current_typography['heading_line_height']); ?>;">Heading 3 Sample</h3>
                        <p style="font-family: var(--wp--preset--font-family--<?php echo esc_attr($current_typography['body_font']); ?>); font-weight: <?php echo esc_attr($current_typography['body_weight']); ?>; line-height: <?php echo esc_attr($current_typography['body_line_height']); ?>;">This is body text sample showing how your typography settings will look on the frontend. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                
                <div class="typography-info">
                    <h3>ℹ️ Typography Info</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Total Fonts:</strong>
                            <span><?php echo count(array_unique(array($current_typography['primary_font'], $current_typography['body_font']))); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Size Scale:</strong>
                            <span>5 levels (H1-H5 + Body)</span>
                        </div>
                        <div class="info-item">
                            <strong>Weight Range:</strong>
                            <span><?php echo $current_typography['body_weight']; ?> - <?php echo $current_typography['heading_weight']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}

/**
 * Add TextBook submenu to Design System
 */
add_action('admin_menu', function() {
    add_submenu_page(
        'design-system',
        'TextBook',
        '📝 TextBook',
        'manage_options',
        'textbook',
        'tb_admin_page'
    );
});
