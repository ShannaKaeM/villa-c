<?php
/**
 * LayoutBook - Layout and Grid Management System
 * Manages grid presets, spacing systems, and layout configurations
 */

// Add LayoutBook admin menu
add_action('admin_menu', 'layoutbook_admin_menu');
function layoutbook_admin_menu() {
    add_menu_page(
        'LayoutBook',
        'LayoutBook',
        'manage_options',
        'layoutbook',
        'layoutbook_admin_page',
        'dashicons-grid-view',
        8
    );
}

// Initialize LayoutBook on admin_init
add_action('admin_init', 'layoutbook_admin_init');
function layoutbook_admin_init() {
    // Ensure the menu is registered
    if (current_user_can('manage_options')) {
        // Menu will be added by admin_menu hook
    }
}

// LayoutBook admin page
function layoutbook_admin_page() {
    // Handle form submission
    if (isset($_POST['save_layouts']) && wp_verify_nonce($_POST['layoutbook_nonce'], 'save_layouts')) {
        layoutbook_save_layouts_handler();
    }

    $layouts = layoutbook_get_current_layouts();
    ?>
    <div class="wrap layoutbook-admin">
        <h1>LayoutBook - Layout & Grid Management</h1>
        <p>Manage grid presets, spacing systems, and layout configurations for your site.</p>

        <form method="post" action="">
            <?php wp_nonce_field('save_layouts', 'layoutbook_nonce'); ?>
            
            <div class="layoutbook-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#grid-presets" class="nav-tab nav-tab-active">Grid Presets</a>
                    <a href="#spacing-system" class="nav-tab">Spacing System</a>
                    <a href="#breakpoints" class="nav-tab">Breakpoints</a>
                    <a href="#layout-templates" class="nav-tab">Layout Templates</a>
                </nav>

                <!-- Grid Presets Tab -->
                <div id="grid-presets" class="tab-content active">
                    <h2>Grid Presets</h2>
                    <p>Define reusable grid configurations for components and blocks.</p>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">Default Grid Min Width</th>
                            <td>
                                <input type="text" name="grid_min_width" value="<?php echo esc_attr($layouts['grid_min_width'] ?? '300px'); ?>" class="regular-text" />
                                <p class="description">Default minimum width for grid items</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Default Grid Max Width</th>
                            <td>
                                <input type="text" name="grid_max_width" value="<?php echo esc_attr($layouts['grid_max_width'] ?? '400px'); ?>" class="regular-text" />
                                <p class="description">Default maximum width for grid items (use "none" for no limit)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Container Max Width</th>
                            <td>
                                <input type="text" name="container_max_width" value="<?php echo esc_attr($layouts['container_max_width'] ?? '1280px'); ?>" class="regular-text" />
                                <p class="description">Maximum width for page containers</p>
                            </td>
                        </tr>
                    </table>

                    <h3>Grid Presets</h3>
                    <div class="grid-presets">
                        <?php
                        $presets = $layouts['grid_presets'] ?? [
                            'cards' => ['min' => '280px', 'max' => '350px', 'gap_x' => '2rem', 'gap_y' => '2rem'],
                            'features' => ['min' => '250px', 'max' => '300px', 'gap_x' => '1.5rem', 'gap_y' => '2rem'],
                            'gallery' => ['min' => '200px', 'max' => '250px', 'gap_x' => '1rem', 'gap_y' => '1rem'],
                            'hero' => ['min' => '400px', 'max' => 'none', 'gap_x' => '3rem', 'gap_y' => '3rem']
                        ];
                        
                        foreach ($presets as $preset_name => $preset_values): ?>
                            <div class="grid-preset">
                                <h4><?php echo ucfirst($preset_name); ?> Grid</h4>
                                <div class="preset-controls">
                                    <label>Min Width: <input type="text" name="grid_presets[<?php echo $preset_name; ?>][min]" value="<?php echo esc_attr($preset_values['min']); ?>" /></label>
                                    <label>Max Width: <input type="text" name="grid_presets[<?php echo $preset_name; ?>][max]" value="<?php echo esc_attr($preset_values['max']); ?>" /></label>
                                    <label>Gap X: <input type="text" name="grid_presets[<?php echo $preset_name; ?>][gap_x]" value="<?php echo esc_attr($preset_values['gap_x']); ?>" /></label>
                                    <label>Gap Y: <input type="text" name="grid_presets[<?php echo $preset_name; ?>][gap_y]" value="<?php echo esc_attr($preset_values['gap_y']); ?>" /></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Spacing System Tab -->
                <div id="spacing-system" class="tab-content">
                    <h2>Spacing System</h2>
                    <p>Define consistent spacing values used throughout your site.</p>
                    
                    <table class="form-table">
                        <?php
                        $spacing = $layouts['spacing'] ?? [
                            'xs' => '0.5rem',
                            'sm' => '1rem',
                            'md' => '1.5rem',
                            'lg' => '2rem',
                            'xl' => '3rem',
                            'xxl' => '4rem'
                        ];
                        
                        foreach ($spacing as $size => $value): ?>
                            <tr>
                                <th scope="row">Spacing <?php echo strtoupper($size); ?></th>
                                <td>
                                    <input type="text" name="spacing[<?php echo $size; ?>]" value="<?php echo esc_attr($value); ?>" class="regular-text" />
                                    <div class="spacing-preview" style="width: <?php echo $value; ?>; height: 20px; background: #3b82f6; margin-top: 5px;"></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Breakpoints Tab -->
                <div id="breakpoints" class="tab-content">
                    <h2>Responsive Breakpoints</h2>
                    <p>Define screen size breakpoints for responsive design.</p>
                    
                    <table class="form-table">
                        <?php
                        $breakpoints = $layouts['breakpoints'] ?? [
                            'sm' => '640px',
                            'md' => '768px',
                            'lg' => '1024px',
                            'xl' => '1280px',
                            'xxl' => '1536px'
                        ];
                        
                        foreach ($breakpoints as $size => $value): ?>
                            <tr>
                                <th scope="row"><?php echo strtoupper($size); ?> Breakpoint</th>
                                <td>
                                    <input type="text" name="breakpoints[<?php echo $size; ?>]" value="<?php echo esc_attr($value); ?>" class="regular-text" />
                                    <p class="description"><?php echo ucfirst($size); ?> screens and up</p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Layout Templates Tab -->
                <div id="layout-templates" class="tab-content">
                    <h2>Layout Templates</h2>
                    <p>Pre-configured layout templates for common use cases.</p>
                    
                    <div class="layout-templates">
                        <div class="template-grid">
                            <div class="template-card">
                                <h4>Hero Section</h4>
                                <div class="template-preview">
                                    <div class="preview-grid hero-preview">
                                        <div class="preview-item full">Hero Content</div>
                                        <div class="preview-item">CTA</div>
                                        <div class="preview-item">CTA</div>
                                        <div class="preview-item">CTA</div>
                                    </div>
                                </div>
                                <p>Large hero content with action buttons</p>
                            </div>
                            
                            <div class="template-card">
                                <h4>Feature Grid</h4>
                                <div class="template-preview">
                                    <div class="preview-grid features-preview">
                                        <div class="preview-item">🏠 Feature</div>
                                        <div class="preview-item">📍 Feature</div>
                                        <div class="preview-item">💰 Feature</div>
                                        <div class="preview-item">🛏️ Feature</div>
                                        <div class="preview-item">👥 Feature</div>
                                        <div class="preview-item">✨ Feature</div>
                                    </div>
                                </div>
                                <p>Icon + text features in grid layout</p>
                            </div>
                            
                            <div class="template-card">
                                <h4>Card Gallery</h4>
                                <div class="template-preview">
                                    <div class="preview-grid cards-preview">
                                        <div class="preview-item">Card 1</div>
                                        <div class="preview-item">Card 2</div>
                                        <div class="preview-item">Card 3</div>
                                        <div class="preview-item">Card 4</div>
                                    </div>
                                </div>
                                <p>Responsive card grid layout</p>
                            </div>
                            
                            <div class="template-card">
                                <h4>Mixed Content</h4>
                                <div class="template-preview">
                                    <div class="preview-grid mixed-preview">
                                        <div class="preview-item span-2">Large Content</div>
                                        <div class="preview-item">Side</div>
                                        <div class="preview-item">Text</div>
                                        <div class="preview-item">Text</div>
                                        <div class="preview-item">Button</div>
                                    </div>
                                </div>
                                <p>Mixed content with spanning elements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="submit">
                <input type="submit" name="save_layouts" class="button-primary" value="Save Layout Settings" />
                <button type="button" class="button" id="export-layouts">Export Settings</button>
                <button type="button" class="button" id="reset-layouts">Reset to Defaults</button>
            </p>
        </form>

        <div id="layout-preview" class="layout-preview-section">
            <h2>Live Preview</h2>
            <div class="preview-container">
                <div class="grid-preview" id="grid-preview">
                    <div class="preview-item">Item 1</div>
                    <div class="preview-item">Item 2</div>
                    <div class="preview-item">Item 3</div>
                    <div class="preview-item">Item 4</div>
                    <div class="preview-item">Item 5</div>
                    <div class="preview-item">Item 6</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .layoutbook-admin { max-width: 1200px; }
        .layoutbook-tabs { margin-top: 20px; }
        .tab-content { display: none; padding: 20px 0; }
        .tab-content.active { display: block; }
        .nav-tab-active { background: #fff !important; }
        
        .grid-presets { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .grid-preset { background: #f9f9f9; padding: 15px; border-radius: 8px; }
        .preset-controls { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .preset-controls label { display: flex; flex-direction: column; font-size: 12px; }
        .preset-controls input { margin-top: 5px; padding: 5px; }
        
        .spacing-preview { border-radius: 4px; }
        
        .template-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .template-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; }
        .template-preview { margin: 10px 0; }
        .preview-grid { display: grid; gap: 5px; }
        .hero-preview { grid-template-columns: 1fr; }
        .features-preview { grid-template-columns: repeat(3, 1fr); }
        .cards-preview { grid-template-columns: repeat(2, 1fr); }
        .mixed-preview { grid-template-columns: repeat(3, 1fr); }
        .preview-item { background: #e5e7eb; padding: 10px; border-radius: 4px; text-align: center; font-size: 12px; }
        .preview-item.full { grid-column: 1 / -1; }
        .preview-item.span-2 { grid-column: span 2; }
        
        .layout-preview-section { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; }
        .preview-container { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .grid-preview { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .grid-preview .preview-item { background: #3b82f6; color: white; padding: 20px; border-radius: 8px; }
    </style>

    <script>
        // Tab functionality
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and content
                document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('nav-tab-active');
                
                // Show corresponding content
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).classList.add('active');
            });
        });

        // Live preview updates
        function updatePreview() {
            const minWidth = document.querySelector('input[name="grid_min_width"]').value || '300px';
            const maxWidth = document.querySelector('input[name="grid_max_width"]').value || '400px';
            const preview = document.getElementById('grid-preview');
            
            if (maxWidth === 'none') {
                preview.style.gridTemplateColumns = `repeat(auto-fit, minmax(${minWidth}, 1fr))`;
            } else {
                preview.style.gridTemplateColumns = `repeat(auto-fit, minmax(${minWidth}, ${maxWidth}))`;
            }
        }

        // Update preview on input change
        document.querySelectorAll('input[name="grid_min_width"], input[name="grid_max_width"]').forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        // Initialize preview
        updatePreview();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                if (e.key === 's') {
                    e.preventDefault();
                    document.querySelector('input[name="save_layouts"]').click();
                }
            }
        });
    </script>
    <?php
}

// Save layouts handler
function layoutbook_save_layouts_handler() {
    $layouts = [
        'grid_min_width' => sanitize_text_field($_POST['grid_min_width'] ?? '300px'),
        'grid_max_width' => sanitize_text_field($_POST['grid_max_width'] ?? '400px'),
        'container_max_width' => sanitize_text_field($_POST['container_max_width'] ?? '1280px'),
        'grid_presets' => [],
        'spacing' => [],
        'breakpoints' => []
    ];

    // Save grid presets
    if (isset($_POST['grid_presets'])) {
        foreach ($_POST['grid_presets'] as $preset_name => $preset_data) {
            $layouts['grid_presets'][sanitize_key($preset_name)] = [
                'min' => sanitize_text_field($preset_data['min']),
                'max' => sanitize_text_field($preset_data['max']),
                'gap_x' => sanitize_text_field($preset_data['gap_x']),
                'gap_y' => sanitize_text_field($preset_data['gap_y'])
            ];
        }
    }

    // Save spacing
    if (isset($_POST['spacing'])) {
        foreach ($_POST['spacing'] as $size => $value) {
            $layouts['spacing'][sanitize_key($size)] = sanitize_text_field($value);
        }
    }

    // Save breakpoints
    if (isset($_POST['breakpoints'])) {
        foreach ($_POST['breakpoints'] as $size => $value) {
            $layouts['breakpoints'][sanitize_key($size)] = sanitize_text_field($value);
        }
    }

    // Save to theme.json
    layoutbook_save_to_theme_json($layouts);
    
    // Save to layoutbook.json
    layoutbook_save_to_json($layouts);

    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Layout settings saved successfully!</p></div>';
    });
}

// Get current layouts
function layoutbook_get_current_layouts() {
    // Try to read from JSON file first
    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/layoutbook.json';
    if (file_exists($json_file)) {
        $json_data = json_decode(file_get_contents($json_file), true);
        if ($json_data && isset($json_data['layouts'])) {
            return $json_data['layouts'];
        }
    }

    // Fallback to theme mods
    return get_theme_mod('layoutbook_settings', []);
}

// Save to theme.json
function layoutbook_save_to_theme_json($layouts) {
    $theme_json_path = get_stylesheet_directory() . '/miDocs/theme.json';
    $theme_json = [];
    
    if (file_exists($theme_json_path)) {
        $theme_json = json_decode(file_get_contents($theme_json_path), true) ?: [];
    }

    // Update layout settings in theme.json
    if (!isset($theme_json['settings'])) {
        $theme_json['settings'] = [];
    }
    if (!isset($theme_json['settings']['custom'])) {
        $theme_json['settings']['custom'] = [];
    }

    $theme_json['settings']['custom']['layout'] = $layouts;

    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT));
}

// Save to layoutbook.json
function layoutbook_save_to_json($layouts) {
    $json_data = [
        'version' => '1.0',
        'updated' => current_time('c'),
        'layouts' => $layouts,
        'css_variables' => layoutbook_generate_css_variables($layouts)
    ];

    $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/layoutbook.json';
    $dir = dirname($json_file);
    
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }

    file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));
}

// Generate CSS variables
function layoutbook_generate_css_variables($layouts) {
    $variables = [];

    // Grid variables
    $variables['--layout-grid-min-width'] = $layouts['grid_min_width'] ?? '300px';
    $variables['--layout-grid-max-width'] = $layouts['grid_max_width'] ?? '400px';
    $variables['--layout-container-max-width'] = $layouts['container_max_width'] ?? '1280px';

    // Spacing variables
    if (isset($layouts['spacing'])) {
        foreach ($layouts['spacing'] as $size => $value) {
            $variables["--layout-spacing-{$size}"] = $value;
        }
    }

    // Breakpoint variables
    if (isset($layouts['breakpoints'])) {
        foreach ($layouts['breakpoints'] as $size => $value) {
            $variables["--layout-breakpoint-{$size}"] = $value;
        }
    }

    return $variables;
}
?>
