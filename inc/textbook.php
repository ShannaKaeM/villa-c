<?php
/**
 * TextBook System - Typography Management for Villa Community
 * Manages typography settings and syncs with Blocksy theme customizer
 */

// Add TextBook admin menu
add_action('admin_menu', 'textbook_admin_menu');
function textbook_admin_menu() {
    add_menu_page(
        'TextBook',
        'TextBook',
        'manage_options',
        'textbook',
        'textbook_admin_page',
        'dashicons-book',
        6
    );
}

// Enqueue admin assets
add_action('admin_enqueue_scripts', 'textbook_admin_assets');
function textbook_admin_assets($hook) {
    if ($hook !== 'toplevel_page_textbook') {
        return;
    }
    
    wp_enqueue_style('textbook-admin', get_stylesheet_directory_uri() . '/assets/css/textbook-admin.css', [], '1.0.0');
    wp_enqueue_script('textbook-admin', get_stylesheet_directory_uri() . '/assets/js/textbook-admin.js', ['jquery'], '1.0.0', true);
    
    wp_localize_script('textbook-admin', 'textbook_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('textbook_nonce')
    ]);
}

// TextBook admin page
function textbook_admin_page() {
    $typography_settings = textbook_get_current_typography();
    ?>
    <div class="wrap textbook-admin">
        <h1>TextBook - Typography Management</h1>
        <p>Manage your site's typography settings. Changes sync with Blocksy customizer and theme.json.</p>
        
        <div class="textbook-container">
            <div class="textbook-preview">
                <h2>Typography Preview</h2>
                <div id="typography-preview" class="preview-content">
                    <h1 class="preview-h1">Heading 1 - Main Title</h1>
                    <h2 class="preview-h2">Heading 2 - Section Title</h2>
                    <h3 class="preview-h3">Heading 3 - Subsection</h3>
                    <h4 class="preview-h4">Heading 4 - Subsubsection</h4>
                    <h5 class="preview-h5">Heading 5 - Subsubsubsection</h5>
                    <p class="preview-body">Body text - This is how your regular paragraph text will appear on the website. It should be easy to read and comfortable for visitors.</p>
                    <a href="#" class="preview-link">Link text example</a>
                    <button class="preview-button">Button Example</button>
                </div>
            </div>
            
            <div class="textbook-controls">
                <form id="textbook-form">
                    <?php wp_nonce_field('textbook_nonce', 'textbook_nonce'); ?>
                    
                    <!-- Font Families Section -->
                    <div class="typography-section">
                        <h3>Font Families</h3>
                        
                        <div class="typography-control">
                            <label for="primary_font">Primary Font (Headings)</label>
                            <select id="primary_font" name="primary_font">
                                <option value="montserrat" <?php selected($typography_settings['primary_font'], 'montserrat'); ?>>Montserrat</option>
                                <option value="inter" <?php selected($typography_settings['primary_font'], 'inter'); ?>>Inter</option>
                                <option value="system-font" <?php selected($typography_settings['primary_font'], 'system-font'); ?>>System Font</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="body_font">Body Font (Paragraphs)</label>
                            <select id="body_font" name="body_font">
                                <option value="montserrat" <?php selected($typography_settings['body_font'], 'montserrat'); ?>>Montserrat</option>
                                <option value="inter" <?php selected($typography_settings['body_font'], 'inter'); ?>>Inter</option>
                                <option value="system-font" <?php selected($typography_settings['body_font'], 'system-font'); ?>>System Font</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Font Sizes Section -->
                    <div class="typography-section">
                        <h3>Font Sizes</h3>
                        
                        <div class="typography-control">
                            <label for="h1_size">H1 Size</label>
                            <select id="h1_size" name="h1_size">
                                <option value="x-large" <?php selected($typography_settings['h1_size'], 'x-large'); ?>>X-Large (24px)</option>
                                <option value="xx-large" <?php selected($typography_settings['h1_size'], 'xx-large'); ?>>XX-Large (40px)</option>
                                <option value="huge" <?php selected($typography_settings['h1_size'], 'huge'); ?>>Huge (48px)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="h2_size">H2 Size</label>
                            <select id="h2_size" name="h2_size">
                                <option value="large" <?php selected($typography_settings['h2_size'], 'large'); ?>>Large (20px)</option>
                                <option value="x-large" <?php selected($typography_settings['h2_size'], 'x-large'); ?>>X-Large (24px)</option>
                                <option value="xx-large" <?php selected($typography_settings['h2_size'], 'xx-large'); ?>>XX-Large (40px)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="h3_size">H3 Size</label>
                            <select id="h3_size" name="h3_size">
                                <option value="medium" <?php selected($typography_settings['h3_size'], 'medium'); ?>>Medium (16px)</option>
                                <option value="large" <?php selected($typography_settings['h3_size'], 'large'); ?>>Large (20px)</option>
                                <option value="x-large" <?php selected($typography_settings['h3_size'], 'x-large'); ?>>X-Large (24px)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="h4_size">H4 Size</label>
                            <select id="h4_size" name="h4_size">
                                <option value="small" <?php selected($typography_settings['h4_size'], 'small'); ?>>Small (14px)</option>
                                <option value="medium" <?php selected($typography_settings['h4_size'], 'medium'); ?>>Medium (16px)</option>
                                <option value="large" <?php selected($typography_settings['h4_size'], 'large'); ?>>Large (20px)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="h5_size">H5 Size</label>
                            <select id="h5_size" name="h5_size">
                                <option value="small" <?php selected($typography_settings['h5_size'], 'small'); ?>>Small (14px)</option>
                                <option value="medium" <?php selected($typography_settings['h5_size'], 'medium'); ?>>Medium (16px)</option>
                                <option value="large" <?php selected($typography_settings['h5_size'], 'large'); ?>>Large (20px)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="body_size">Body Size</label>
                            <select id="body_size" name="body_size">
                                <option value="small" <?php selected($typography_settings['body_size'], 'small'); ?>>Small (14px)</option>
                                <option value="medium" <?php selected($typography_settings['body_size'], 'medium'); ?>>Medium (16px)</option>
                                <option value="large" <?php selected($typography_settings['body_size'], 'large'); ?>>Large (20px)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Font Weights Section -->
                    <div class="typography-section">
                        <h3>Font Weights</h3>
                        
                        <div class="typography-control">
                            <label for="heading_weight">Heading Weight</label>
                            <select id="heading_weight" name="heading_weight">
                                <option value="300" <?php selected($typography_settings['heading_weight'], '300'); ?>>Light (300)</option>
                                <option value="400" <?php selected($typography_settings['heading_weight'], '400'); ?>>Regular (400)</option>
                                <option value="500" <?php selected($typography_settings['heading_weight'], '500'); ?>>Medium (500)</option>
                                <option value="600" <?php selected($typography_settings['heading_weight'], '600'); ?>>Semi-Bold (600)</option>
                                <option value="700" <?php selected($typography_settings['heading_weight'], '700'); ?>>Bold (700)</option>
                                <option value="800" <?php selected($typography_settings['heading_weight'], '800'); ?>>Extra Bold (800)</option>
                                <option value="900" <?php selected($typography_settings['heading_weight'], '900'); ?>>Black (900)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="body_weight">Body Weight</label>
                            <select id="body_weight" name="body_weight">
                                <option value="300" <?php selected($typography_settings['body_weight'], '300'); ?>>Light (300)</option>
                                <option value="400" <?php selected($typography_settings['body_weight'], '400'); ?>>Regular (400)</option>
                                <option value="500" <?php selected($typography_settings['body_weight'], '500'); ?>>Medium (500)</option>
                                <option value="600" <?php selected($typography_settings['body_weight'], '600'); ?>>Semi-Bold (600)</option>
                                <option value="700" <?php selected($typography_settings['body_weight'], '700'); ?>>Bold (700)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Line Heights Section -->
                    <div class="typography-section">
                        <h3>Line Heights</h3>
                        
                        <div class="typography-control">
                            <label for="heading_line_height">Heading Line Height</label>
                            <select id="heading_line_height" name="heading_line_height">
                                <option value="1.1" <?php selected($typography_settings['heading_line_height'], '1.1'); ?>>Tight (1.1)</option>
                                <option value="1.2" <?php selected($typography_settings['heading_line_height'], '1.2'); ?>>Snug (1.2)</option>
                                <option value="1.3" <?php selected($typography_settings['heading_line_height'], '1.3'); ?>>Normal (1.3)</option>
                            </select>
                        </div>
                        
                        <div class="typography-control">
                            <label for="body_line_height">Body Line Height</label>
                            <select id="body_line_height" name="body_line_height">
                                <option value="1.4" <?php selected($typography_settings['body_line_height'], '1.4'); ?>>Compact (1.4)</option>
                                <option value="1.6" <?php selected($typography_settings['body_line_height'], '1.6'); ?>>Normal (1.6)</option>
                                <option value="1.8" <?php selected($typography_settings['body_line_height'], '1.8'); ?>>Relaxed (1.8)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="textbook-actions">
                        <button type="submit" class="button button-primary">Save Typography Settings</button>
                        <button type="button" id="reset-typography" class="button">Reset to Defaults</button>
                        <button type="button" id="export-typography" class="button">Export Settings</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div id="textbook-status" class="notice" style="display: none;"></div>
    </div>
    <?php
}

// Get current typography settings
function textbook_get_current_typography() {
    // Try to read from textbook.json first
    $textbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/textbook.json';
    
    if (file_exists($textbook_json_path)) {
        $textbook_data = json_decode(file_get_contents($textbook_json_path), true);
        if ($textbook_data && isset($textbook_data['typography'])) {
            return $textbook_data['typography'];
        }
    }
    
    // Fallback to defaults
    return [
        'primary_font' => 'montserrat',
        'body_font' => 'montserrat',
        'h1_size' => 'xx-large',
        'h2_size' => 'x-large',
        'h3_size' => 'large',
        'h4_size' => 'medium',
        'h5_size' => 'small',
        'body_size' => 'medium',
        'heading_weight' => '600',
        'body_weight' => '400',
        'heading_line_height' => '1.2',
        'body_line_height' => '1.6'
    ];
}

// AJAX handler for saving typography
add_action('wp_ajax_textbook_save_typography', 'textbook_save_typography_handler');
function textbook_save_typography_handler() {
    check_ajax_referer('textbook_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    $typography = [
        'primary_font' => sanitize_text_field($_POST['primary_font']),
        'body_font' => sanitize_text_field($_POST['body_font']),
        'h1_size' => sanitize_text_field($_POST['h1_size']),
        'h2_size' => sanitize_text_field($_POST['h2_size']),
        'h3_size' => sanitize_text_field($_POST['h3_size']),
        'h4_size' => sanitize_text_field($_POST['h4_size']),
        'h5_size' => sanitize_text_field($_POST['h5_size']),
        'body_size' => sanitize_text_field($_POST['body_size']),
        'heading_weight' => sanitize_text_field($_POST['heading_weight']),
        'body_weight' => sanitize_text_field($_POST['body_weight']),
        'heading_line_height' => sanitize_text_field($_POST['heading_line_height']),
        'body_line_height' => sanitize_text_field($_POST['body_line_height'])
    ];
    
    // Save to dedicated TextBook JSON file
    $textbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/textbook.json';
    
    // Ensure directory exists
    $textbook_dir = dirname($textbook_json_path);
    if (!file_exists($textbook_dir)) {
        wp_mkdir_p($textbook_dir);
    }
    
    // Create TextBook JSON structure
    $textbook_data = [
        'version' => '1.0.0',
        'updated' => current_time('mysql'),
        'theme' => 'carbon-blocksy',
        'typography' => $typography,
        'css_variables' => []
    ];
    
    // Generate CSS variables for easy access
    $textbook_data['css_variables']['--theme-font-primary'] = "var(--wp--preset--font-family--{$typography['primary_font']})";
    $textbook_data['css_variables']['--theme-font-body'] = "var(--wp--preset--font-family--{$typography['body_font']})";
    $textbook_data['css_variables']['--theme-h1-size'] = "var(--wp--preset--font-size--{$typography['h1_size']})";
    $textbook_data['css_variables']['--theme-h2-size'] = "var(--wp--preset--font-size--{$typography['h2_size']})";
    $textbook_data['css_variables']['--theme-h3-size'] = "var(--wp--preset--font-size--{$typography['h3_size']})";
    $textbook_data['css_variables']['--theme-h4-size'] = "var(--wp--preset--font-size--{$typography['h4_size']})";
    $textbook_data['css_variables']['--theme-h5-size'] = "var(--wp--preset--font-size--{$typography['h5_size']})";
    $textbook_data['css_variables']['--theme-body-size'] = "var(--wp--preset--font-size--{$typography['body_size']})";
    
    // Save TextBook JSON
    file_put_contents($textbook_json_path, json_encode($textbook_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
    // Update theme.json
    textbook_sync_with_theme_json($typography);
    
    // Sync to Blocksy
    textbook_sync_with_blocksy($typography);
    
    wp_send_json_success([
        'message' => 'Typography saved successfully to TextBook, theme.json, and Blocksy',
        'textbook_path' => $textbook_json_path
    ]);
}

// Sync typography with theme.json
function textbook_sync_with_theme_json($typography) {
    $theme_json_path = get_stylesheet_directory() . '/theme.json';
    
    if (file_exists($theme_json_path)) {
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
        
        // Update typography styles in theme.json
        if (!isset($theme_json['styles'])) {
            $theme_json['styles'] = [];
        }
        
        // Update global typography
        $theme_json['styles']['typography'] = [
            'fontFamily' => "var(--wp--preset--font-family--{$typography['body_font']})",
            'fontSize' => "var(--wp--preset--font-size--{$typography['body_size']})",
            'fontWeight' => $typography['body_weight'],
            'lineHeight' => $typography['body_line_height']
        ];
        
        // Update heading styles
        $theme_json['styles']['elements']['heading'] = [
            'typography' => [
                'fontFamily' => "var(--wp--preset--font-family--{$typography['primary_font']})",
                'fontWeight' => $typography['heading_weight'],
                'lineHeight' => $typography['heading_line_height']
            ]
        ];
        
        // Update specific heading sizes
        $theme_json['styles']['elements']['h1']['typography']['fontSize'] = "var(--wp--preset--font-size--{$typography['h1_size']})";
        $theme_json['styles']['elements']['h2']['typography']['fontSize'] = "var(--wp--preset--font-size--{$typography['h2_size']})";
        $theme_json['styles']['elements']['h3']['typography']['fontSize'] = "var(--wp--preset--font-size--{$typography['h3_size']})";
        $theme_json['styles']['elements']['h4']['typography']['fontSize'] = "var(--wp--preset--font-size--{$typography['h4_size']})";
        $theme_json['styles']['elements']['h5']['typography']['fontSize'] = "var(--wp--preset--font-size--{$typography['h5_size']})";
        
        file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// Sync typography with Blocksy customizer
function textbook_sync_with_blocksy($typography) {
    // Map TextBook typography to Blocksy theme mods
    
    // Font families
    set_theme_mod('headings_font_family', $typography['primary_font'] === 'montserrat' ? 'Montserrat' : ($typography['primary_font'] === 'inter' ? 'Inter' : 'System'));
    set_theme_mod('body_font_family', $typography['body_font'] === 'montserrat' ? 'Montserrat' : ($typography['body_font'] === 'inter' ? 'Inter' : 'System'));
    
    // Font sizes (convert to Blocksy format)
    $size_map = [
        'small' => '14',
        'medium' => '16', 
        'large' => '20',
        'x-large' => '24',
        'xx-large' => '40',
        'huge' => '48'
    ];
    
    set_theme_mod('h1_font_size', $size_map[$typography['h1_size']] ?? '40');
    set_theme_mod('h2_font_size', $size_map[$typography['h2_size']] ?? '24');
    set_theme_mod('h3_font_size', $size_map[$typography['h3_size']] ?? '20');
    set_theme_mod('h4_font_size', $size_map[$typography['h4_size']] ?? '18');
    set_theme_mod('h5_font_size', $size_map[$typography['h5_size']] ?? '16');
    set_theme_mod('body_font_size', $size_map[$typography['body_size']] ?? '16');
    
    // Font weights
    set_theme_mod('headings_font_weight', $typography['heading_weight']);
    set_theme_mod('body_font_weight', $typography['body_weight']);
    
    // Line heights
    set_theme_mod('headings_line_height', $typography['heading_line_height']);
    set_theme_mod('body_line_height', $typography['body_line_height']);
}

// Helper function to get typography data
function get_textbook_data($format = 'array') {
    $textbook_json_path = get_stylesheet_directory() . '/miDocs/SITE DATA/textbook.json';
    
    if (file_exists($textbook_json_path)) {
        $data = json_decode(file_get_contents($textbook_json_path), true);
        
        switch ($format) {
            case 'json':
                return json_encode($data);
            case 'css_vars':
                return $data['css_variables'] ?? [];
            default:
                return $data;
        }
    }
    
    return null;
}

// Helper function to get specific typography setting
function get_textbook_typography($setting) {
    $data = get_textbook_data();
    return $data['typography'][$setting] ?? null;
}
