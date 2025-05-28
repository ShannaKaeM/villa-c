<?php
/**
 * CompBook - Component Configuration & Management
 * Admin-first component architecture with auto-compilation.
 */

// Add CompBook admin menu
add_action('admin_menu', 'compbook_admin_menu');
function compbook_admin_menu() {
    add_menu_page(
        'CompBook',
        'CompBook',
        'manage_options',
        'compbook',
        'compbook_admin_page',
        'dashicons-layout',
        31
    );
}

// Get component categories
function compbook_get_categories() {
    return [
        'text-group' => [
            'label' => 'TitleBlock',
            'description' => 'Simple title block with pretitle, title, subtitle, and description',
            'icon' => 'dashicons-editor-textcolor',
            'components' => [
                'text-group' => [
                    'label' => 'TitleBlock',
                    'description' => 'Simple title block with pretitle, title, subtitle, and description',
                    'fields' => [
                        // Element Toggles (Checkboxes)
                        'show_pretitle' => [
                            'type' => 'checkbox',
                            'label' => 'Show Pretitle',
                            'default' => true
                        ],
                        
                        'show_title' => [
                            'type' => 'checkbox',
                            'label' => 'Show Title',
                            'default' => true
                        ],
                        
                        'show_subtitle' => [
                            'type' => 'checkbox',
                            'label' => 'Show Subtitle',
                            'default' => true
                        ],
                        
                        'show_description' => [
                            'type' => 'checkbox',
                            'label' => 'Show Description',
                            'default' => true
                        ],
                        
                        // Size Controls
                        'pretitle_size' => [
                            'type' => 'select',
                            'label' => 'Pretitle Size',
                            'options' => [
                                'xs' => 'Extra Small',
                                'sm' => 'Small',
                                'md' => 'Medium',
                                'lg' => 'Large'
                            ],
                            'default' => 'sm'
                        ],
                        
                        'title_size' => [
                            'type' => 'select',
                            'label' => 'Title Size',
                            'options' => [
                                'lg' => 'Large',
                                'xl' => 'Extra Large',
                                'xxl' => 'XX Large',
                                'xxxl' => 'XXX Large'
                            ],
                            'default' => 'xxl'
                        ],
                        
                        'subtitle_size' => [
                            'type' => 'select',
                            'label' => 'Subtitle Size',
                            'options' => [
                                'sm' => 'Small',
                                'md' => 'Medium',
                                'lg' => 'Large',
                                'xl' => 'Extra Large'
                            ],
                            'default' => 'lg'
                        ],
                        
                        'description_size' => [
                            'type' => 'select',
                            'label' => 'Description Size',
                            'options' => [
                                'sm' => 'Small',
                                'md' => 'Medium',
                                'lg' => 'Large'
                            ],
                            'default' => 'md'
                        ],
                        
                        // Variant System
                        'variant_name' => [
                            'type' => 'text',
                            'label' => 'Save as Variant',
                            'help' => 'Enter name to save current settings as a reusable variant'
                        ]
                    ]
                ]
            ]
        ]
    ];
}

// Save component configuration
function compbook_save_config($category, $component, $config) {
    $option_name = "compbook_{$category}_{$component}_config";
    update_option($option_name, $config);
    compbook_generate_css($category, $component, $config);
}

// Get saved component configuration
function compbook_get_config($category, $component) {
    $option_name = "compbook_{$category}_{$component}_config";
    $saved = get_option($option_name, []);
    
    // Get defaults from component definition
    $categories = compbook_get_categories();
    $component_config = $categories[$category]['components'][$component] ?? null;
    
    if (!$component_config) {
        return $saved;
    }
    
    $defaults = [];
    foreach ($component_config['fields'] as $field_key => $field_config) {
        $defaults[$field_key] = $field_config['default'] ?? '';
    }
    
    return array_merge($defaults, $saved);
}

// Generate CSS for component
function compbook_generate_css($category, $component, $config = []) {
    // If no config passed, get from database
    if (empty($config)) {
        $config = compbook_get_config($category, $component);
    }
    
    if ($category === 'text-group' && $component === 'text-group') {
        // Set defaults if config is empty
        $defaults = [
            'show_pretitle' => true,
            'show_title' => true,
            'show_subtitle' => true,
            'show_description' => true,
            'pretitle_size' => 'sm',
            'title_size' => 'xxl',
            'subtitle_size' => 'lg',
            'description_size' => 'md'
        ];
        
        $config = array_merge($defaults, $config);
        
        $css = "
.compbook-text-group {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    text-align: left;
    padding: var(--spacing-md, 1rem) var(--spacing-sm, 0.5rem);
    max-width: var(--width-lg, 32rem);
    margin-left: auto;
    margin-right: auto;
}

.compbook-text-group__pretitle {
    font-size: var(--text-{$config['pretitle_size']}, 0.875rem);
    margin-bottom: var(--spacing-sm, 0.5rem);
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.compbook-text-group__title {
    font-size: var(--text-{$config['title_size']}, 2.25rem);
    margin-bottom: var(--spacing-md, 1rem);
    color: #333;
    font-weight: 700;
    line-height: 1.2;
}

.compbook-text-group__subtitle {
    font-size: var(--text-{$config['subtitle_size']}, 1.25rem);
    margin-bottom: var(--spacing-sm, 0.5rem);
    color: #555;
    font-weight: 600;
    line-height: 1.3;
}

.compbook-text-group__description {
    font-size: var(--text-{$config['description_size']}, 1rem);
    margin-bottom: var(--spacing-md, 1rem);
    color: #666;
    line-height: 1.5;
}

/* Fallback sizes if CSS custom properties aren't available */
.compbook-text-group__pretitle { font-size: " . ($config['pretitle_size'] === 'xs' ? '0.75rem' : ($config['pretitle_size'] === 'sm' ? '0.875rem' : ($config['pretitle_size'] === 'md' ? '1rem' : '1.125rem'))) . "; }
.compbook-text-group__title { font-size: " . ($config['title_size'] === 'lg' ? '1.125rem' : ($config['title_size'] === 'xl' ? '1.25rem' : ($config['title_size'] === 'xxl' ? '2.25rem' : '3rem'))) . "; }
.compbook-text-group__subtitle { font-size: " . ($config['subtitle_size'] === 'sm' ? '0.875rem' : ($config['subtitle_size'] === 'md' ? '1rem' : ($config['subtitle_size'] === 'lg' ? '1.125rem' : '1.25rem'))) . "; }
.compbook-text-group__description { font-size: " . ($config['description_size'] === 'sm' ? '0.875rem' : ($config['description_size'] === 'md' ? '1rem' : '1.125rem')) . "; }
";
        
        // Save CSS to file
        $upload_dir = wp_upload_dir();
        $css_file = $upload_dir['basedir'] . '/compbook-css/text-group.css';
        
        if (!file_exists(dirname($css_file))) {
            wp_mkdir_p(dirname($css_file));
        }
        
        file_put_contents($css_file, $css);
        
        return $css;
    }
    
    return '';
}

// Admin page
function compbook_admin_page() {
    // Handle form submission
    if (isset($_POST['save_component_config']) && wp_verify_nonce($_POST['_wpnonce'], 'compbook_save_config')) {
        $category = sanitize_text_field($_POST['category']);
        $component = sanitize_text_field($_POST['component']);
        
        $config = [];
        $categories = compbook_get_categories();
        $component_config = $categories[$category]['components'][$component] ?? null;
        
        if ($component_config) {
            foreach ($component_config['fields'] as $field_key => $field_config) {
                if ($field_config['type'] === 'checkbox') {
                    $config[$field_key] = isset($_POST[$field_key]) ? true : false;
                } elseif ($field_config['type'] === 'multiselect') {
                    $config[$field_key] = array_map('sanitize_text_field', $_POST[$field_key] ?? []);
                } else {
                    $config[$field_key] = sanitize_text_field($_POST[$field_key] ?? '');
                }
            }
            
            // Save main configuration
            compbook_save_config($category, $component, $config);
            
            // Save variant if name provided
            if (!empty($config['variant_name'])) {
                $variant_name = sanitize_text_field($config['variant_name']);
                $variants = get_option('compbook_titleblock_variants', []);
                $variants[$variant_name] = $config;
                update_option('compbook_titleblock_variants', $variants);
                
                echo '<div class="notice notice-success"><p>Configuration saved and variant "' . esc_html($variant_name) . '" created!</p></div>';
            } else {
                echo '<div class="notice notice-success"><p>Configuration saved successfully!</p></div>';
            }
        }
    }
    
    $categories = compbook_get_categories();
    $active_tab = $_GET['tab'] ?? 'text-group';
    ?>
    
    <div class="wrap">
        <h1>🧩 CompBook</h1>
        <p>Manage component configurations and generate optimized CSS for your design system.</p>
        
        <nav class="nav-tab-wrapper">
            <?php foreach ($categories as $category_key => $category): ?>
                <a href="?page=compbook&tab=<?php echo $category_key; ?>" 
                   class="nav-tab <?php echo $active_tab === $category_key ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons <?php echo $category['icon']; ?>"></span>
                    <?php echo $category['label']; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        
        <div class="tab-content">
            <?php if (isset($categories[$active_tab])): ?>
                <div class="category-info">
                    <h2><?php echo $categories[$active_tab]['label']; ?></h2>
                    <p><?php echo $categories[$active_tab]['description']; ?></p>
                </div>
                
                <?php if (!empty($categories[$active_tab]['components'])): ?>
                    <?php foreach ($categories[$active_tab]['components'] as $component_key => $component): ?>
                        <div class="component-section">
                            <div style="display: flex; gap: 40px;">
                                <!-- Left Column: Settings -->
                                <div style="flex: 1;">
                                    <h3><?php echo $component['label']; ?></h3>
                                    <p><?php echo $component['description']; ?></p>
                                    
                                    <form method="post" id="compbook-config-form">
                                        <?php wp_nonce_field('compbook_save_config'); ?>
                                        <input type="hidden" name="category" value="<?php echo $active_tab; ?>">
                                        <input type="hidden" name="component" value="<?php echo $component_key; ?>">
                                        
                                        <table class="form-table">
                                            <?php 
                                            $saved_config = compbook_get_config($active_tab, $component_key);
                                            compbook_render_fields($component['fields'], $saved_config); 
                                            ?>
                                        </table>
                                        
                                        <p class="submit">
                                            <input type="submit" name="save_component_config" class="button-primary" value="Save Configuration">
                                        </p>
                                    </form>
                                </div>
                                
                                <!-- Right Column: Preview -->
                                <div style="flex: 0 0 400px;">
                                    <div class="compbook-preview-section">
                                        <h3>Live Preview</h3>
                                        <div class="compbook-preview-container" style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9; border-radius: 8px; min-height: 300px;">
                                            <?php compbook_render_text_group_preview($active_tab, $component_key); ?>
                                        </div>
                                        <p class="description">This preview shows how your TitleBlock will appear.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><em>No components available in this category yet.</em></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
    .compbook-preview-container {
        min-height: 200px;
        position: relative;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .compbook-preview-container .compbook-text-group {
        padding: 20px;
    }
    
    .compbook-preview-container .compbook-text-group__pretitle {
        color: #666;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .compbook-preview-container .compbook-text-group__title {
        color: #333;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .compbook-preview-container .compbook-text-group__subtitle {
        color: #555;
        font-weight: 600;
    }
    
    .compbook-preview-container .compbook-text-group__description {
        color: #666;
        line-height: 1.5;
    }
    
    .component-section {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
    }
    
    .compbook-admin-tabs {
        margin-bottom: 20px;
    }
    
    .compbook-admin-tabs .nav-tab {
        margin-right: 5px;
    }
    
    .compbook-preview-section h3 {
        margin-bottom: 15px;
        color: #23282d;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Auto-refresh preview when settings change
        $('.compbook-conditional-trigger, input[type="checkbox"], input[type="text"], textarea, select').on('change input', function() {
            setTimeout(function() {
                refreshPreview();
            }, 300);
        });
        
        // Initial preview load
        refreshPreview();
        
        function refreshPreview() {
            // Get all form data
            var formData = $('#compbook-config-form').serialize();
            
            // Add action and nonce
            formData += '&action=compbook_preview&nonce=' + '<?php echo wp_create_nonce('compbook_preview'); ?>';
            
            // Send AJAX request
            $.post(ajaxurl, formData, function(response) {
                if (response.success) {
                    $('.compbook-preview-container').html(response.data);
                }
            }).fail(function() {
                console.log('Preview update failed');
            });
        }
    });
    </script>
    <?php
}

// Render form fields
function compbook_render_fields($fields, $saved_config = []) {
    foreach ($fields as $field_key => $field_config) {
        // Get value from saved config or use default
        $value = isset($saved_config[$field_key]) ? $saved_config[$field_key] : ($field_config['default'] ?? '');
        
        echo '<tr>';
        echo '<th scope="row">' . $field_config['label'] . '</th>';
        echo '<td>';
        
        // Add conditional attribute if specified
        if (!empty($field_config['conditional'])) {
            echo '<tr data-conditional="' . $field_config['conditional'] . '">';
        }
        
        switch ($field_config['type']) {
            case 'text':
                echo '<input type="text" name="' . $field_key . '" id="' . $field_key . '" value="' . esc_attr($value) . '" class="regular-text">';
                break;
                
            case 'textarea':
                echo '<textarea name="' . $field_key . '" id="' . $field_key . '" rows="3" class="large-text">' . esc_textarea($value) . '</textarea>';
                break;
                
            case 'select':
                echo '<select name="' . $field_key . '" id="' . $field_key . '" class="compbook-conditional-trigger">';
                
                // Handle dynamic options (function calls)
                if (is_string($field_config['options']) && function_exists($field_config['options'])) {
                    $options = call_user_func($field_config['options']);
                } else {
                    $options = $field_config['options'];
                }
                
                foreach ($options as $option_value => $option_label) {
                    $selected = $value === $option_value ? 'selected' : '';
                    echo '<option value="' . $option_value . '" ' . $selected . '>' . $option_label . '</option>';
                }
                echo '</select>';
                break;
                
            case 'multiselect':
                echo '<select name="' . $field_key . '[]" id="' . $field_key . '" multiple style="height: 120px;">';
                
                // Handle dynamic options (function calls)
                if (is_string($field_config['options']) && function_exists($field_config['options'])) {
                    $options = call_user_func($field_config['options']);
                } else {
                    $options = $field_config['options'];
                }
                
                // Ensure value is array
                if (!is_array($value)) {
                    $value = $field_config['default'] ?? [];
                }
                
                foreach ($options as $option_value => $option_label) {
                    $selected = in_array($option_value, $value) ? 'selected' : '';
                    echo '<option value="' . $option_value . '" ' . $selected . '>' . $option_label . '</option>';
                }
                echo '</select>';
                echo '<p class="description">Hold Ctrl/Cmd to select multiple elements</p>';
                break;
                
            case 'checkbox':
                $checked = !empty($value) ? 'checked' : '';
                echo '<input type="checkbox" name="' . $field_key . '" id="' . $field_key . '" value="1" ' . $checked . '>';
                echo '<label for="' . $field_key . '" style="margin-left: 8px;"> ' . $field_config['label'] . '</label>';
                break;
        }
        
        // Add help text if available
        if (!empty($field_config['help'])) {
            echo '<p class="description">' . $field_config['help'] . '</p>';
        }
        
        echo '</td>';
        echo '</tr>';
    }
    
    // Add JavaScript for conditional fields
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.compbook-conditional-trigger').on('change', function() {
            var fieldName = $(this).attr('name');
            var fieldValue = $(this).val();
            
            // Hide all conditional fields for this trigger
            $('tr[data-conditional^="' + fieldName + ':"]').hide();
            
            // Show matching conditional fields
            $('tr[data-conditional="' + fieldName + ':' + fieldValue + '"]').show();
        });
        
        // Initialize on page load
        $('.compbook-conditional-trigger').trigger('change');
    });
    </script>
    <?php
}

// Render Text Group preview
function compbook_render_text_group_preview($category, $component) {
    $sample_content = [
        'pretitle' => 'Sample Pretitle',
        'title' => 'Sample Title',
        'subtitle' => 'Sample Subtitle',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet nulla auctor, vestibulum magna sed, convallis ex.',
    ];
    
    $config = compbook_get_config($category, $component);
    
    // Set defaults if config is empty
    $defaults = [
        'show_pretitle' => true,
        'show_title' => true,
        'show_subtitle' => true,
        'show_description' => true,
        'pretitle_size' => 'sm',
        'title_size' => 'xxl',
        'subtitle_size' => 'lg',
        'description_size' => 'md'
    ];
    
    $config = array_merge($defaults, $config);
    
    $html = '<div class="compbook-text-group">';
    
    // Add elements only if they're enabled
    if (!empty($config['show_pretitle'])) {
        $html .= '<div class="compbook-text-group__pretitle">' . $sample_content['pretitle'] . '</div>';
    }
    
    if (!empty($config['show_title'])) {
        $html .= '<div class="compbook-text-group__title">' . $sample_content['title'] . '</div>';
    }
    
    if (!empty($config['show_subtitle'])) {
        $html .= '<div class="compbook-text-group__subtitle">' . $sample_content['subtitle'] . '</div>';
    }
    
    if (!empty($config['show_description'])) {
        $html .= '<div class="compbook-text-group__description">' . $sample_content['description'] . '</div>';
    }
    
    $html .= '</div>';
    
    echo $html;
}

// Render Text Group preview with specific config (for AJAX)
function compbook_render_text_group_preview_with_config($config) {
    $sample_content = [
        'pretitle' => 'Sample Pretitle',
        'title' => 'Sample Title',
        'subtitle' => 'Sample Subtitle',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet nulla auctor, vestibulum magna sed, convallis ex.',
    ];
    
    $html = '<div class="compbook-text-group">';
    
    // Add elements only if they're enabled
    if (!empty($config['show_pretitle'])) {
        $html .= '<div class="compbook-text-group__pretitle">' . $sample_content['pretitle'] . '</div>';
    }
    
    if (!empty($config['show_title'])) {
        $html .= '<div class="compbook-text-group__title">' . $sample_content['title'] . '</div>';
    }
    
    if (!empty($config['show_subtitle'])) {
        $html .= '<div class="compbook-text-group__subtitle">' . $sample_content['subtitle'] . '</div>';
    }
    
    if (!empty($config['show_description'])) {
        $html .= '<div class="compbook-text-group__description">' . $sample_content['description'] . '</div>';
    }
    
    $html .= '</div>';
    
    echo $html;
}

// AJAX handler for live preview
add_action('wp_ajax_compbook_preview', 'compbook_ajax_preview');
function compbook_ajax_preview() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'compbook_preview')) {
        wp_die('Security check failed');
    }
    
    // Get form data and simulate config
    $config = [];
    $categories = compbook_get_categories();
    $component_config = $categories['text-group']['components']['text-group'] ?? null;
    
    // Set defaults first
    $defaults = [
        'show_pretitle' => true,
        'show_title' => true,
        'show_subtitle' => true,
        'show_description' => true,
        'pretitle_size' => 'sm',
        'title_size' => 'xxl',
        'subtitle_size' => 'lg',
        'description_size' => 'md'
    ];
    
    $config = $defaults;
    
    if ($component_config) {
        foreach ($component_config['fields'] as $field_key => $field_config) {
            if ($field_config['type'] === 'checkbox') {
                $config[$field_key] = isset($_POST[$field_key]) ? true : false;
            } elseif (isset($_POST[$field_key])) {
                if ($field_config['type'] === 'multiselect') {
                    $config[$field_key] = array_map('sanitize_text_field', $_POST[$field_key]);
                } else {
                    $config[$field_key] = sanitize_text_field($_POST[$field_key]);
                }
            }
        }
    }
    
    // Generate CSS for preview
    $css = compbook_generate_css('text-group', 'text-group', $config);
    
    // Render preview with inline CSS
    ob_start();
    echo '<style>' . $css . '</style>';
    compbook_render_text_group_preview_with_config($config);
    $html = ob_get_clean();
    
    wp_send_json_success($html);
}

// Enqueue admin CSS for CompBook
add_action('admin_enqueue_scripts', 'compbook_enqueue_admin_css');
function compbook_enqueue_admin_css($hook) {
    if ($hook !== 'design-system_page_compbook') {
        return;
    }
    
    // Enqueue generated CSS for preview
    $upload_dir = wp_upload_dir();
    $css_file = $upload_dir['basedir'] . '/compbook-css/text-group.css';
    
    if (file_exists($css_file)) {
        $css_url = $upload_dir['baseurl'] . '/compbook-css/text-group.css';
        wp_enqueue_style('compbook-text-group', $css_url, [], filemtime($css_file));
    }
}

// Enqueue CompBook CSS
add_action('wp_enqueue_scripts', 'compbook_enqueue_css');
function compbook_enqueue_css() {
    $upload_dir = wp_upload_dir();
    $css_dir = $upload_dir['basedir'] . '/compbook-css/';
    
    if (is_dir($css_dir)) {
        $css_files = glob($css_dir . '*.css');
        foreach ($css_files as $css_file) {
            $css_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $css_file);
            $handle = 'compbook-' . basename($css_file, '.css');
            wp_enqueue_style($handle, $css_url, [], filemtime($css_file));
        }
    }
}

// Get font family options from TextBook
function compbook_get_font_families() {
    if (function_exists('textbook_get_current_fonts')) {
        $fonts = textbook_get_current_fonts();
        $options = ['inherit' => 'Inherit'];
        foreach ($fonts as $key => $font) {
            $options[$key] = $font['name'] ?? $key;
        }
        return $options;
    }
    
    return [
        'inherit' => 'Inherit',
        'system' => 'System Font',
        'serif' => 'Serif',
        'sans-serif' => 'Sans Serif',
        'monospace' => 'Monospace'
    ];
}

// Get color options from ColorBook
function compbook_get_color_options() {
    if (function_exists('colorbook_get_current_colors')) {
        $colors = colorbook_get_current_colors();
        $options = ['inherit' => 'Inherit'];
        foreach ($colors as $key => $color) {
            $options[$key] = ucfirst(str_replace('-', ' ', $key));
        }
        return $options;
    }
    
    return [
        'inherit' => 'Inherit',
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'text' => 'Text',
        'text-light' => 'Text Light'
    ];
}

// Get saved text variants
function compbook_get_text_variants() {
    $variants = get_option('compbook_text_variants', []);
    $options = ['' => 'Select a variant...'];
    
    foreach ($variants as $key => $variant) {
        $options[$key] = $variant['name'] ?? $key;
    }
    
    return $options;
}

// Save text variant
function compbook_save_text_variant($name, $config) {
    $variants = get_option('compbook_text_variants', []);
    $key = sanitize_title($name);
    
    $variants[$key] = [
        'name' => $name,
        'config' => $config,
        'created' => current_time('mysql')
    ];
    
    update_option('compbook_text_variants', $variants);
    return $key;
}
