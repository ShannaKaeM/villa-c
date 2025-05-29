<?php
/**
 * TextBook System - Typography Management
 * Manages typography foundation settings and semantic text elements for consistent design.
 */

// Add TextBook as submenu under DesignBook
add_action('admin_menu', 'textbook_admin_submenu');
function textbook_admin_submenu() {
    add_submenu_page(
        'designbook',           // Parent slug
        'TextBook',             // Page title
        'TextBook',             // Menu title
        'manage_options',       // Capability
        'textbook',             // Menu slug
        'textbook_admin_page'   // Function
    );
}

// Get semantic text elements with smart defaults
function textbook_get_semantic_elements() {
    return [
        'pretitle' => [
            'label' => 'Pretitle',
            'description' => 'Small text above main title',
            'tag' => 'span',
            'size' => 'sm',
            'weight' => 500,
            'color' => 'primary',
            'transform' => 'uppercase',
            'font_family' => 'primary',
            'letter_spacing' => 'wide'
        ],
        'title' => [
            'label' => 'Title',
            'description' => 'Main heading text',
            'tag' => 'h1',
            'size' => 'xl',
            'weight' => 700,
            'color' => 'base-darkest',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'tight'
        ],
        'subtitle' => [
            'label' => 'Subtitle',
            'description' => 'Secondary heading text',
            'tag' => 'h2',
            'size' => 'lg',
            'weight' => 600,
            'color' => 'base-darkest',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ],
        'description' => [
            'label' => 'Description',
            'description' => 'Descriptive paragraph text',
            'tag' => 'p',
            'size' => 'md',
            'weight' => 400,
            'color' => 'base-dark',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ],
        'body' => [
            'label' => 'Body',
            'description' => 'Main body content text',
            'tag' => 'p',
            'size' => 'md',
            'weight' => 400,
            'color' => 'base-darkest',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ],
        'button' => [
            'label' => 'Button',
            'description' => 'Button and CTA text',
            'tag' => 'span',
            'size' => 'sm',
            'weight' => 600,
            'color' => 'base-white',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ],
        'caption' => [
            'label' => 'Caption',
            'description' => 'Small caption or meta text',
            'tag' => 'span',
            'size' => 'xs',
            'weight' => 400,
            'color' => 'base',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ],
        'label' => [
            'label' => 'Label',
            'description' => 'Form labels and small headings',
            'tag' => 'label',
            'size' => 'sm',
            'weight' => 500,
            'color' => 'base-darkest',
            'transform' => 'none',
            'font_family' => 'primary',
            'letter_spacing' => 'normal'
        ]
    ];
}

// Get available options for dropdowns
function textbook_get_options() {
    // Get colors from ColorBook if available
    $colors = [];
    if (function_exists('colorbook_get_current_colors')) {
        $colorbook_colors = colorbook_get_current_colors();
        foreach ($colorbook_colors as $color) {
            $colors[] = $color['slug'];
        }
    } else {
        // Fallback colors
        $colors = ['primary', 'secondary', 'base-darkest', 'base-dark', 'base', 'base-light', 'base-white'];
    }
    
    // Get font sizes from foundation settings
    $font_sizes = textbook_get_font_sizes();
    $size_slugs = [];
    foreach ($font_sizes as $size) {
        $size_slugs[] = $size['slug'];
    }
    
    // Get font families from foundation settings
    $font_families = textbook_get_font_families();
    $family_slugs = [];
    foreach ($font_families as $family) {
        $family_slugs[] = $family['slug'];
    }
    
    // Get font weights from foundation settings
    $font_weights = textbook_get_font_weights();
    $weight_values = [];
    foreach ($font_weights as $weight) {
        $weight_values[] = $weight['weight'];
    }
    
    // Get line heights from foundation settings
    $line_heights = textbook_get_line_heights();
    $line_height_slugs = [];
    foreach ($line_heights as $line_height) {
        $line_height_slugs[] = $line_height['slug'];
    }
    
    // Get letter spacing from foundation settings
    $letter_spacing = textbook_get_letter_spacing();
    $letter_spacing_slugs = [];
    foreach ($letter_spacing as $spacing) {
        $letter_spacing_slugs[] = $spacing['slug'];
    }
    
    return [
        'sizes' => $size_slugs,
        'weights' => $weight_values,
        'transforms' => ['none', 'uppercase', 'lowercase', 'capitalize'],
        'tags' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div', 'label'],
        'font_families' => $family_slugs,
        'line_heights' => $line_height_slugs,
        'letter_spacing' => $letter_spacing_slugs,
        'colors' => $colors
    ];
}

// Get font families
function textbook_get_font_families() {
    $font_families = get_option('textbook_font_families', [
        [
            'name' => 'Inter',
            'fontFamily' => 'Inter, sans-serif',
            'slug' => 'inter'
        ],
        [
            'name' => 'Georgia',
            'fontFamily' => 'Georgia, serif',
            'slug' => 'georgia'
        ],
        [
            'name' => 'Monaco',
            'fontFamily' => 'Monaco, monospace',
            'slug' => 'monaco'
        ]
    ]);
    
    return $font_families;
}

// Get font sizes
function textbook_get_font_sizes() {
    $font_sizes = get_option('textbook_font_sizes', [
        [
            'name' => 'Extra Small',
            'slug' => 'xs',
            'size' => '0.75rem'
        ],
        [
            'name' => 'Small',
            'slug' => 'sm',
            'size' => '0.875rem'
        ],
        [
            'name' => 'Medium',
            'slug' => 'md',
            'size' => '1rem'
        ],
        [
            'name' => 'Large',
            'slug' => 'lg',
            'size' => '1.125rem'
        ],
        [
            'name' => 'Extra Large',
            'slug' => 'xl',
            'size' => '1.25rem'
        ],
        [
            'name' => '2X Large',
            'slug' => '2xl',
            'size' => '1.5rem'
        ]
    ]);
    return $font_sizes;
}

// Get font weights
function textbook_get_font_weights() {
    $font_weights = get_option('textbook_font_weights', [
        [
            'name' => 'Light',
            'slug' => 'light',
            'weight' => '300'
        ],
        [
            'name' => 'Normal',
            'slug' => 'normal',
            'weight' => '400'
        ],
        [
            'name' => 'Medium',
            'slug' => 'medium',
            'weight' => '500'
        ],
        [
            'name' => 'Semibold',
            'slug' => 'semibold',
            'weight' => '600'
        ],
        [
            'name' => 'Bold',
            'slug' => 'bold',
            'weight' => '700'
        ]
    ]);
    return $font_weights;
}

// Get line heights
function textbook_get_line_heights() {
    $line_heights = get_option('textbook_line_heights', [
        [
            'name' => 'Tight',
            'slug' => 'tight',
            'height' => '1.2'
        ],
        [
            'name' => 'Normal',
            'slug' => 'normal',
            'height' => '1.4'
        ],
        [
            'name' => 'Relaxed',
            'slug' => 'relaxed',
            'height' => '1.6'
        ],
        [
            'name' => 'Loose',
            'slug' => 'loose',
            'height' => '1.8'
        ]
    ]);
    return $line_heights;
}

// Get letter spacing
function textbook_get_letter_spacing() {
    $letter_spacing = get_option('textbook_letter_spacing', [
        [
            'name' => 'Tight',
            'slug' => 'tight',
            'spacing' => '-0.05em'
        ],
        [
            'name' => 'Normal',
            'slug' => 'normal',
            'spacing' => '0'
        ],
        [
            'name' => 'Wide',
            'slug' => 'wide',
            'spacing' => '0.05em'
        ],
        [
            'name' => 'Wider',
            'slug' => 'wider',
            'spacing' => '0.1em'
        ],
        [
            'name' => 'Widest',
            'slug' => 'widest',
            'spacing' => '0.15em'
        ]
    ]);
    return $letter_spacing;
}

// Get current semantic settings
function textbook_get_current_semantic_settings() {
    $saved_settings = get_option('textbook_semantic_elements', []);
    $default_elements = textbook_get_semantic_elements();
    
    // Merge saved settings with defaults
    foreach ($default_elements as $key => $default) {
        if (!isset($saved_settings[$key])) {
            $saved_settings[$key] = $default;
        }
    }
    
    return $saved_settings;
}

// Save semantic settings
function textbook_save_semantic_settings() {
    if (isset($_POST['semantic_elements'])) {
        update_option('textbook_semantic_elements', $_POST['semantic_elements']);
        return true;
    }
    return false;
}

// Save foundation settings
function textbook_save_foundation_settings() {
    if (isset($_POST['font_families'])) {
        update_option('textbook_font_families', $_POST['font_families']);
    }
    if (isset($_POST['font_sizes'])) {
        update_option('textbook_font_sizes', $_POST['font_sizes']);
    }
    if (isset($_POST['font_weights'])) {
        update_option('textbook_font_weights', $_POST['font_weights']);
    }
    if (isset($_POST['line_heights'])) {
        update_option('textbook_line_heights', $_POST['line_heights']);
    }
    if (isset($_POST['letter_spacing'])) {
        update_option('textbook_letter_spacing', $_POST['letter_spacing']);
    }
    return true;
}

// Main TextBook admin page
function textbook_admin_page() {
    // Handle form submission
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['textbook_nonce'], 'textbook_save')) {
        textbook_save_semantic_settings();
        echo '<div class="notice notice-success"><p>TextBook semantic elements saved!</p></div>';
    }
    
    if (isset($_POST['save_foundation']) && wp_verify_nonce($_POST['textbook_foundation_nonce'], 'textbook_foundation_action')) {
        textbook_save_foundation_settings();
        echo '<div class="notice notice-success"><p>TextBook foundation settings saved!</p></div>';
    }

    $semantic_elements = textbook_get_current_semantic_settings();
    $options = textbook_get_options();
    $font_families = textbook_get_font_families();
    $font_sizes = textbook_get_font_sizes();
    $font_weights = textbook_get_font_weights();
    $line_heights = textbook_get_line_heights();
    $letter_spacing = textbook_get_letter_spacing();
    ?>
    <div class="wrap textbook-admin">
        <h1>📖 TextBook - Typography System</h1>
        <p>Manage typography foundation settings and semantic text elements for consistent design.</p>
        
        <!-- Tab Navigation -->
        <div class="textbook-tabs">
            <nav class="nav-tab-wrapper textbook-nav-tabs">
                <a href="#foundation" class="nav-tab nav-tab-active" data-tab="foundation">Foundation</a>
                <a href="#semantic" class="nav-tab" data-tab="semantic">Semantic Elements</a>
            </nav>
            
            <!-- Foundation Tab -->
            <div id="foundation" class="tab-content active">
                <div class="tab-header">
                    <h2>🏗️ Typography Foundation</h2>
                    <p>Define the core typography settings that power your semantic elements.</p>
                </div>
                
                <form method="post" action="">
                    <?php wp_nonce_field('textbook_foundation_action', 'textbook_foundation_nonce'); ?>
                    
                    <div class="foundation-sections">
                        <!-- Font Families Section -->
                        <div class="foundation-section">
                            <h3>🔤 Font Families</h3>
                            <p>Define the font families available in your design system.</p>
                            
                            <div class="font-families-grid">
                                <?php foreach ($font_families as $index => $family): ?>
                                <div class="font-family-item">
                                    <div class="control-group">
                                        <label>Font Family Name</label>
                                        <input type="text" name="font_families[<?php echo $index; ?>][name]" 
                                               value="<?php echo esc_attr($family['name']); ?>" placeholder="Inter">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Slug</label>
                                        <input type="text" name="font_families[<?php echo $index; ?>][slug]" 
                                               value="<?php echo esc_attr($family['slug']); ?>" placeholder="inter">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Font Stack</label>
                                        <input type="text" name="font_families[<?php echo $index; ?>][fontFamily]" 
                                               value="<?php echo esc_attr($family['fontFamily']); ?>" placeholder="Inter, sans-serif">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Font Sizes Section -->
                        <div class="foundation-section">
                            <h3>📏 Font Sizes</h3>
                            <p>Define the font size scale used throughout your design system.</p>
                            
                            <div class="font-sizes-grid">
                                <?php foreach ($font_sizes as $index => $size): ?>
                                <div class="font-size-item">
                                    <div class="control-group">
                                        <label>Size Name</label>
                                        <input type="text" name="font_sizes[<?php echo $index; ?>][name]" 
                                               value="<?php echo esc_attr($size['name']); ?>" placeholder="Medium">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Slug</label>
                                        <input type="text" name="font_sizes[<?php echo $index; ?>][slug]" 
                                               value="<?php echo esc_attr($size['slug']); ?>" placeholder="md">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Size Value</label>
                                        <input type="text" name="font_sizes[<?php echo $index; ?>][size]" 
                                               value="<?php echo esc_attr($size['size']); ?>" placeholder="1rem">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <p class="submit">
                        <input type="submit" name="save_foundation" class="button-primary" value="Save Foundation Settings" />
                        <button type="button" class="button" onclick="location.reload()">Reset</button>
                    </p>
                </form>
            </div>

            <!-- Semantic Elements Tab -->
            <div id="semantic" class="tab-content">
                <div class="tab-header">
                    <h2>🎯 Semantic Elements</h2>
                    <p>Define semantic text elements with smart defaults for consistent typography.</p>
                </div>

                <form id="textbook-semantic-form" method="post" action="">
                    <?php wp_nonce_field('textbook_save', 'textbook_nonce'); ?>
                    
                    <div class="semantic-elements">
                        <?php
                        $semantic_elements = textbook_get_semantic_elements();
                        $current_settings = textbook_get_current_semantic_settings();
                        $options = textbook_get_options();
                        
                        foreach ($semantic_elements as $key => $element):
                            $current = $current_settings[$key] ?? $element;
                        ?>
                            <div class="semantic-element-item">
                                <div class="element-header">
                                    <h3><?php echo $element['label']; ?></h3>
                                    <p><?php echo $element['description']; ?></p>
                                </div>
                                
                                <div class="element-controls">
                                    <div class="control-group">
                                        <label>HTML Tag</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][tag]">
                                            <?php foreach ($options['tags'] as $tag): ?>
                                                <option value="<?php echo $tag; ?>" <?php selected($current['tag'], $tag); ?>><?php echo $tag; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Size</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][size]">
                                            <?php foreach ($options['sizes'] as $size): ?>
                                                <option value="<?php echo $size; ?>" <?php selected($current['size'], $size); ?>><?php echo strtoupper($size); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Weight</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][weight]">
                                            <?php foreach ($options['weights'] as $weight): ?>
                                                <option value="<?php echo $weight; ?>" <?php selected($current['weight'], $weight); ?>><?php echo $weight; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Color</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][color]">
                                            <?php foreach ($options['colors'] as $color): ?>
                                                <option value="<?php echo $color; ?>" <?php selected($current['color'], $color); ?>><?php echo ucfirst($color); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Transform</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][transform]">
                                            <?php foreach ($options['transforms'] as $transform): ?>
                                                <option value="<?php echo $transform; ?>" <?php selected($current['transform'], $transform); ?>><?php echo ucfirst($transform); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Font Family</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][font_family]">
                                            <?php foreach ($options['font_families'] as $family): ?>
                                                <option value="<?php echo $family; ?>" <?php selected($current['font_family'], $family); ?>><?php echo ucfirst($family); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Line Height</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][line_height]">
                                            <?php foreach ($options['line_heights'] as $height): ?>
                                                <option value="<?php echo $height; ?>" <?php selected($current['line_height'], $height); ?>><?php echo ucfirst($height); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Letter Spacing</label>
                                        <select name="semantic_elements[<?php echo $key; ?>][letter_spacing]">
                                            <?php foreach ($options['letter_spacing'] as $spacing): ?>
                                                <option value="<?php echo $spacing; ?>" <?php selected($current['letter_spacing'], $spacing); ?>><?php echo ucfirst($spacing); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p class="submit">
                        <input type="submit" name="submit" class="button-primary" value="Save Semantic Elements" />
                        <button type="button" class="button" onclick="location.reload()">Reset</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <style>
    .textbook-admin {
        max-width: 1200px;
    }
    
    .textbook-tabs {
        margin-top: 20px;
    }
    
    .textbook-nav-tabs {
        margin-bottom: 0;
    }
    
    .tab-content {
        display: none;
        background: white;
        border: 1px solid #ccd0d4;
        border-top: none;
        padding: 20px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .tab-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
    }
    
    .tab-header h2 {
        margin: 0 0 10px 0;
        color: #1d2327;
    }
    
    .foundation-sections {
        display: grid;
        gap: 30px;
    }
    
    .foundation-section {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
    }
    
    .foundation-section h3 {
        margin: 0 0 10px 0;
        color: #1d2327;
    }
    
    .font-families-grid,
    .font-sizes-grid {
        display: grid;
        gap: 15px;
        margin-top: 15px;
    }
    
    .font-family-item,
    .font-size-item {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr;
        gap: 15px;
        background: white;
        padding: 15px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    
    .control-group {
        display: flex;
        flex-direction: column;
    }
    
    .control-group label {
        font-weight: 500;
        margin-bottom: 5px;
        color: #1d2327;
    }
    
    .control-group input,
    .control-group select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .semantic-elements {
        display: grid;
        gap: 20px;
    }
    
    .semantic-element-item {
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .element-header {
        background: #fff;
        padding: 15px 20px;
        border-bottom: 1px solid #ddd;
    }
    
    .element-header h3 {
        margin: 0 0 5px 0;
        color: #1d2327;
    }
    
    .element-header p {
        margin: 0;
        color: #646970;
        font-size: 14px;
    }
    
    .element-controls {
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .submit {
        padding-top: 20px;
        border-top: 1px solid #ddd;
        margin-top: 30px;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.textbook-nav-tabs .nav-tab').click(function(e) {
            e.preventDefault();
            
            var targetTab = $(this).data('tab');
            
            // Update tab buttons
            $('.textbook-nav-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Update tab content
            $('.tab-content').removeClass('active');
            $('#' + targetTab).addClass('active');
        });
    });
    </script>
    <?php
}
