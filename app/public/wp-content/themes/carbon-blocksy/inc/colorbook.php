<?php
/**
 * ColorBook - Admin page for managing the 16-color system
 * Adapted from Villa Stylebook for Carbon Blocksy theme
 */

// Add admin menu
add_action('admin_menu', function() {
    add_menu_page(
        'ColorBook',
        'ColorBook',
        'manage_options',
        'colorbook',
        'colorbook_render_page',
        'dashicons-art',
        5
    );
});

// Enqueue admin styles and scripts
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'toplevel_page_colorbook') return;
    
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
    
    // Sync to Blocksy if requested
    if (!empty($_POST['sync_blocksy'])) {
        colorbook_sync_with_blocksy($colors);
    }
    
    wp_send_json_success(['message' => 'Colors saved successfully']);
}

// Sync colors with Blocksy
function colorbook_sync_with_blocksy($colors) {
    // Get current theme mods
    $theme_mods = get_theme_mods();
    
    // Initialize colorPalette if it doesn't exist
    if (!isset($theme_mods['colorPalette'])) {
        $theme_mods['colorPalette'] = [];
    }
    
    // Define the expected color order
    $color_order = [
        'primary-light', 'primary', 'primary-dark',
        'secondary-light', 'secondary', 'secondary-dark',
        'neutral-light', 'neutral', 'neutral-dark',
        'base-white', 'base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest', 'base-black'
    ];
    
    // Map colors to Blocksy palette (1-16)
    foreach ($color_order as $index => $slug) {
        $color_data = array_filter($colors, function($c) use ($slug) {
            return $c['slug'] === $slug;
        });
        $color_data = array_values($color_data);
        
        if (!empty($color_data)) {
            $palette_key = 'color' . ($index + 1); // color1 through color16
            $theme_mods['colorPalette'][$palette_key] = [
                'color' => $color_data[0]['hex']
            ];
            
            // Add titles for better organization
            if ($index < 3) {
                $theme_mods['colorPalette'][$palette_key]['title'] = ['Primary Light', 'Primary', 'Primary Dark'][$index];
            } elseif ($index < 6) {
                $theme_mods['colorPalette'][$palette_key]['title'] = ['Secondary Light', 'Secondary', 'Secondary Dark'][$index - 3];
            } elseif ($index < 9) {
                $theme_mods['colorPalette'][$palette_key]['title'] = ['Neutral Light', 'Neutral', 'Neutral Dark'][$index - 6];
            } else {
                $base_names = ['Base White', 'Base Lightest', 'Base Light', 'Base', 'Base Dark', 'Base Darkest', 'Base Black'];
                $theme_mods['colorPalette'][$palette_key]['title'] = $base_names[$index - 9];
            }
        }
    }
    
    // Save the updated theme mods
    set_theme_mod('colorPalette', $theme_mods['colorPalette']);
    
    return true;
}

// Get current colors from theme.json or defaults
function colorbook_get_current_colors() {
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
function colorbook_render_page() {
    $colors = colorbook_get_current_colors();
    ?>
    <div class="wrap colorbook">
        <h1>ColorBook</h1>
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
                <label>
                    <input type="checkbox" id="sync-blocksy" checked>
                    Sync to Blocksy Customizer
                </label>
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
        'mods' => [
            'colorPalette' => []
        ]
    ];
    
    $color_order = [
        'primary-light', 'primary', 'primary-dark',
        'secondary-light', 'secondary', 'secondary-dark',
        'neutral-light', 'neutral', 'neutral-dark',
        'base-white', 'base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest', 'base-black'
    ];
    
    foreach ($color_order as $index => $slug) {
        $color_data = array_filter($colors, function($c) use ($slug) {
            return $c['slug'] === $slug;
        });
        $color_data = array_values($color_data);
        
        if (!empty($color_data)) {
            $palette_key = 'color' . ($index + 1);
            $export_data['mods']['colorPalette'][$palette_key] = [
                'color' => $color_data[0]['hex'],
                'title' => $color_data[0]['name']
            ];
        }
    }
    
    wp_send_json_success($export_data);
}
