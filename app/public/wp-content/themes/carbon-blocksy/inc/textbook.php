<?php
/**
 * TextBook System - Typography Management
 * Manages typography foundation settings and semantic text elements for consistent design.
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
        'dashicons-editor-textcolor',
        30
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
            'name' => 'Extra Extra Large',
            'slug' => 'xxl',
            'size' => '1.5rem'
        ],
        [
            'name' => 'Extra Extra Extra Large',
            'slug' => 'xxxl',
            'size' => '2rem'
        ]
    ]);
    
    return $font_sizes;
}

// Get font weights
function textbook_get_font_weights() {
    $font_weights = get_option('textbook_font_weights', [
        [
            'name' => 'Extra Light',
            'slug' => 'extra-light',
            'weight' => '200'
        ],
        [
            'name' => 'Light',
            'slug' => 'light',
            'weight' => '300'
        ],
        [
            'name' => 'Regular',
            'slug' => 'regular',
            'weight' => '400'
        ],
        [
            'name' => 'Medium',
            'slug' => 'medium',
            'weight' => '500'
        ],
        [
            'name' => 'Semi Bold',
            'slug' => 'semi-bold',
            'weight' => '600'
        ],
        [
            'name' => 'Bold',
            'slug' => 'bold',
            'weight' => '700'
        ],
        [
            'name' => 'Extra Bold',
            'slug' => 'extra-bold',
            'weight' => '800'
        ],
        [
            'name' => 'Black',
            'slug' => 'black',
            'weight' => '900'
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
            'height' => '1.1'
        ],
        [
            'name' => 'Snug',
            'slug' => 'snug',
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

// Get font size value by slug
function textbook_get_font_size_value($slug) {
    $font_sizes = textbook_get_font_sizes();
    foreach ($font_sizes as $size) {
        if ($size['slug'] === $slug) {
            return $size['size'];
        }
    }
    return '1rem'; // fallback
}

// Get font family value by slug
function textbook_get_font_family_value($slug) {
    $font_families = textbook_get_font_families();
    foreach ($font_families as $family) {
        if ($family['slug'] === $slug) {
            return $family['fontFamily'];
        }
    }
    return 'Inter, sans-serif'; // fallback
}

// Get font weight value by slug
function textbook_get_font_weight_value($slug) {
    $font_weights = textbook_get_font_weights();
    foreach ($font_weights as $weight) {
        if ($weight['slug'] === $slug) {
            return $weight['weight'];
        }
    }
    return '400'; // fallback
}

// Get line height value by slug
function textbook_get_line_height_value($slug) {
    $line_heights = textbook_get_line_heights();
    foreach ($line_heights as $line_height) {
        if ($line_height['slug'] === $slug) {
            return $line_height['height'];
        }
    }
    return '1.4'; // fallback
}

// Get letter spacing value by slug
function textbook_get_letter_spacing_value($slug) {
    $letter_spacing = textbook_get_letter_spacing();
    foreach ($letter_spacing as $spacing) {
        if ($spacing['slug'] === $slug) {
            return $spacing['spacing'];
        }
    }
    return '0'; // fallback
}

// TextBook admin page
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
                        
                        <!-- Font Weights Section -->
                        <div class="foundation-section">
                            <h3>⚖️ Font Weights</h3>
                            <p>Define the font weight scale for consistent typography hierarchy.</p>
                            
                            <div class="font-weights-grid">
                                <?php foreach ($font_weights as $index => $weight): ?>
                                <div class="font-weight-item">
                                    <div class="control-group">
                                        <label>Weight Name</label>
                                        <input type="text" name="font_weights[<?php echo $index; ?>][name]" 
                                               value="<?php echo esc_attr($weight['name']); ?>" placeholder="Medium">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Slug</label>
                                        <input type="text" name="font_weights[<?php echo $index; ?>][slug]" 
                                               value="<?php echo esc_attr($weight['slug']); ?>" placeholder="medium">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Weight Value</label>
                                        <input type="text" name="font_weights[<?php echo $index; ?>][weight]" 
                                               value="<?php echo esc_attr($weight['weight']); ?>" placeholder="500">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Line Heights Section -->
                        <div class="foundation-section">
                            <h3>📐 Line Heights</h3>
                            <p>Define line height values for optimal readability.</p>
                            
                            <div class="line-heights-grid">
                                <?php foreach ($line_heights as $index => $height): ?>
                                <div class="line-height-item">
                                    <div class="control-group">
                                        <label>Height Name</label>
                                        <input type="text" name="line_heights[<?php echo $index; ?>][name]" 
                                               value="<?php echo esc_attr($height['name']); ?>" placeholder="Normal">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Slug</label>
                                        <input type="text" name="line_heights[<?php echo $index; ?>][slug]" 
                                               value="<?php echo esc_attr($height['slug']); ?>" placeholder="normal">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Height Value</label>
                                        <input type="text" name="line_heights[<?php echo $index; ?>][height]" 
                                               value="<?php echo esc_attr($height['height']); ?>" placeholder="1.4">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Letter Spacing Section -->
                        <div class="foundation-section">
                            <h3>🔤 Letter Spacing</h3>
                            <p>Define letter spacing values for typography fine-tuning.</p>
                            
                            <div class="letter-spacing-grid">
                                <?php foreach ($letter_spacing as $index => $spacing): ?>
                                <div class="letter-spacing-item">
                                    <div class="control-group">
                                        <label>Spacing Name</label>
                                        <input type="text" name="letter_spacing[<?php echo $index; ?>][name]" 
                                               value="<?php echo esc_attr($spacing['name']); ?>" placeholder="Normal">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Slug</label>
                                        <input type="text" name="letter_spacing[<?php echo $index; ?>][slug]" 
                                               value="<?php echo esc_attr($spacing['slug']); ?>" placeholder="normal">
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Spacing Value</label>
                                        <input type="text" name="letter_spacing[<?php echo $index; ?>][spacing]" 
                                               value="<?php echo esc_attr($spacing['spacing']); ?>" placeholder="0">
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
                                
                                <div class="element-preview">
                                    <div class="preview-label">Preview:</div>
                                    <?php
                                    // Get current values with fallbacks
                                    $preview_font_family = textbook_get_font_family_value($current['font_family'] ?? 'primary');
                                    $preview_font_size = textbook_get_font_size_value($current['size'] ?? 'md');
                                    $preview_font_weight = textbook_get_font_weight_value($current['weight'] ?? '400');
                                    $preview_line_height = textbook_get_line_height_value($current['line_height'] ?? 'normal');
                                    $preview_letter_spacing = textbook_get_letter_spacing_value($current['letter_spacing'] ?? 'normal');
                                    $preview_transform = $current['transform'] ?? 'none';
                                    $preview_color = $current['color'] ?? 'base';
                                    
                                    // Get actual color value (prefer OKLCH)
                                    $preview_color_value = 'oklch(66% 0 0)'; // default fallback
                                    if (function_exists('colorbook_get_current_colors')) {
                                        $colorbook_colors = colorbook_get_current_colors();
                                        foreach ($colorbook_colors as $color) {
                                            if ($color['slug'] === $preview_color) {
                                                // Use OKLCH if available, otherwise hex
                                                if (isset($color['oklch']) && is_array($color['oklch'])) {
                                                    $oklch = $color['oklch'];
                                                    $preview_color_value = "oklch({$oklch[0]}% {$oklch[1]} {$oklch[2]})";
                                                } else {
                                                    $preview_color_value = $color['hex'] ?? 'oklch(66% 0 0)';
                                                }
                                                break;
                                            }
                                        }
                                    } else {
                                        // Fallback color mapping with OKLCH
                                        $fallback_colors = [
                                            'primary' => 'oklch(56% 0.064 194)',
                                            'secondary' => 'oklch(60% 0.089 64)', 
                                            'base-darkest' => 'oklch(35% 0 0)',
                                            'base-dark' => 'oklch(50% 0 0)',
                                            'base' => 'oklch(66% 0 0)',
                                            'base-light' => 'oklch(80% 0 0)',
                                            'base-white' => 'oklch(100% 0 0)'
                                        ];
                                        $preview_color_value = $fallback_colors[$preview_color] ?? 'oklch(66% 0 0)';
                                    }
                                    ?>
                                    <div class="text-preview semantic-<?php echo $key; ?>" 
                                         data-element="<?php echo $key; ?>"
                                         style="
                                            font-family: <?php echo $preview_font_family; ?>;
                                            font-size: <?php echo $preview_font_size; ?>;
                                            font-weight: <?php echo $preview_font_weight; ?>;
                                            line-height: <?php echo $preview_line_height; ?>;
                                            letter-spacing: <?php echo $preview_letter_spacing; ?>;
                                            text-transform: <?php echo $preview_transform; ?>;
                                            color: <?php echo $preview_color_value; ?>;
                                         ">
                                        <?php echo $element['label']; ?> Sample Text
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p class="submit">
                        <input type="submit" name="submit" class="button-primary" value="Save Semantic Elements" />
                        <button type="button" class="button" onclick="textbook_reset_semantic()">Reset to Defaults</button>
                        <button type="button" class="button" onclick="textbook_generate_css()">Generate CSS</button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <style>
        .textbook-admin .textbook-tabs {
            margin-top: 20px;
        }
        
        .textbook-nav-tabs {
            border-bottom: 1px solid #ccd0d4;
            margin-bottom: 0;
        }
        
        .textbook-nav-tabs .nav-tab {
            position: relative;
            display: inline-block;
            padding: 12px 20px;
            margin: 0 5px -1px 0;
            background: #f1f1f1;
            border: 1px solid #ccd0d4;
            border-bottom: none;
            color: #555;
            text-decoration: none;
            font-weight: 500;
            border-radius: 6px 6px 0 0;
            transition: all 0.2s ease;
        }
        
        .textbook-nav-tabs .nav-tab:hover {
            background: #e8e8e8;
            color: #333;
        }
        
        .textbook-nav-tabs .nav-tab.nav-tab-active {
            background: white;
            border-bottom: 1px solid white;
            color: #333;
            font-weight: 600;
        }
        
        .tab-content {
            display: none;
            background: white;
            border: 1px solid #ccd0d4;
            border-top: none;
            padding: 30px;
            border-radius: 0 0 6px 6px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .tab-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .tab-header h2 {
            margin: 0 0 10px 0;
            color: #1e293b;
        }
        
        .tab-header p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .foundation-sections {
            display: grid;
            gap: 40px;
        }
        
        .foundation-section {
            background: #f8fafc;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .foundation-section h3 {
            margin: 0 0 10px 0;
            color: #1e293b;
            font-size: 18px;
        }
        
        .foundation-section > p {
            margin: 0 0 20px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .font-families-grid,
        .font-sizes-grid,
        .font-weights-grid,
        .line-heights-grid,
        .letter-spacing-grid {
            display: grid;
            gap: 20px;
        }
        
        .font-family-item,
        .font-size-item,
        .font-weight-item,
        .line-height-item,
        .letter-spacing-item {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .control-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #374151;
            font-size: 13px;
        }
        
        .control-group input,
        .control-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .semantic-elements {
            display: grid;
            gap: 30px;
        }
        
        .semantic-element-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .element-header {
            padding: 20px 25px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .element-header h3 {
            margin: 0 0 5px 0;
            color: #1e293b;
            font-size: 16px;
        }
        
        .element-header p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }
        
        .element-controls {
            padding: 20px 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .element-preview {
            padding: 20px 25px;
            background: #f1f5f9;
            border-top: 1px solid #e2e8f0;
        }
        
        .preview-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .text-preview {
            background: white;
            border: 2px dashed #cbd5e1;
            border-radius: 6px;
            padding: 15px;
            font-size: 16px;
            color: #1e293b;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.textbook-nav-tabs .nav-tab');
            const contents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('nav-tab-active'));
                    contents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('nav-tab-active');
                    
                    // Show corresponding content
                    const targetId = this.getAttribute('data-tab');
                    document.getElementById(targetId).classList.add('active');
                });
            });
            
            // Live preview updates
            const controls = document.querySelectorAll('.element-controls select, .element-controls input');
            
            // Font size mapping
            const fontSizes = {
                'xs': '0.75rem',
                'sm': '0.875rem', 
                'md': '1rem',
                'lg': '1.125rem',
                'xl': '1.25rem',
                'xxl': '1.5rem',
                'xxxl': '2rem'
            };
            
            // Font weight mapping
            const fontWeights = {
                '300': '300',
                '400': '400',
                '500': '500',
                '600': '600',
                '700': '700',
                '800': '800',
                '900': '900'
            };
            
            // Line height mapping
            const lineHeights = {
                'tight': '1.2',
                'normal': '1.4',
                'relaxed': '1.6',
                'loose': '1.8'
            };
            
            // Letter spacing mapping
            const letterSpacing = {
                'tight': '-0.025em',
                'normal': '0',
                'wide': '0.025em',
                'wider': '0.05em',
                'widest': '0.1em'
            };
            
            // Font family mapping
            const fontFamilies = {
                'primary': 'Montserrat, sans-serif',
                'secondary': 'Georgia, serif',
                'mono': 'Monaco, monospace'
            };
            
            // Color mapping - get actual color values from ColorBook
            const colorValues = {
                <?php
                $colors = [];
                if (function_exists('colorbook_get_current_colors')) {
                    $colorbook_colors = colorbook_get_current_colors();
                    foreach ($colorbook_colors as $color) {
                        // Use OKLCH if available, otherwise hex
                        if (isset($color['oklch']) && is_array($color['oklch'])) {
                            $oklch = $color['oklch'];
                            $color_value = "oklch({$oklch[0]}% {$oklch[1]} {$oklch[2]})";
                        } else {
                            $color_value = $color['hex'] ?? 'oklch(66% 0 0)';
                        }
                        $colors[] = "'{$color['slug']}': '{$color_value}'";
                    }
                } else {
                    // Fallback colors with OKLCH values
                    $colors = [
                        "'primary': 'oklch(56% 0.064 194)'",
                        "'secondary': 'oklch(60% 0.089 64)'", 
                        "'base-darkest': 'oklch(35% 0 0)'",
                        "'base-dark': 'oklch(50% 0 0)'",
                        "'base': 'oklch(66% 0 0)'",
                        "'base-light': 'oklch(80% 0 0)'",
                        "'base-white': 'oklch(100% 0 0)'"
                    ];
                }
                echo implode(",\n                ", $colors);
                ?>
            };
            
            controls.forEach(control => {
                control.addEventListener('change', function() {
                    const element = this.closest('.semantic-element-item');
                    const preview = element.querySelector('.text-preview');
                    
                    if (!preview) return;
                    
                    // Update preview styles based on control type
                    if (this.name.includes('font_family')) {
                        preview.style.fontFamily = fontFamilies[this.value] || fontFamilies['primary'];
                    } else if (this.name.includes('size')) {
                        preview.style.fontSize = fontSizes[this.value] || fontSizes['md'];
                    } else if (this.name.includes('weight')) {
                        preview.style.fontWeight = fontWeights[this.value] || fontWeights['400'];
                    } else if (this.name.includes('line_height')) {
                        preview.style.lineHeight = lineHeights[this.value] || lineHeights['normal'];
                    } else if (this.name.includes('letter_spacing')) {
                        preview.style.letterSpacing = letterSpacing[this.value] || letterSpacing['normal'];
                    } else if (this.name.includes('transform')) {
                        preview.style.textTransform = this.value;
                    } else if (this.name.includes('color')) {
                        preview.style.color = colorValues[this.value];
                    }
                    
                    // Auto-save changes after a short delay
                    clearTimeout(window.textbookSaveTimeout);
                    window.textbookSaveTimeout = setTimeout(function() {
                        autoSaveTextbookSettings();
                    }, 1000); // 1 second delay
                });
            });
            
            // Auto-save function
            function autoSaveTextbookSettings() {
                const formData = new FormData(document.getElementById('textbook-semantic-form'));
                formData.append('action', 'textbook_auto_save');
                formData.append('nonce', '<?php echo wp_create_nonce('textbook_nonce'); ?>');
                
                fetch(ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show brief success indicator
                        showSaveIndicator('Auto-saved');
                    }
                })
                .catch(error => {
                    console.log('Auto-save failed:', error);
                });
            }
            
            // Show save indicator
            function showSaveIndicator(message) {
                let indicator = document.getElementById('textbook-save-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.id = 'textbook-save-indicator';
                    indicator.style.cssText = 'position: fixed; top: 32px; right: 20px; background: #00a32a; color: white; padding: 8px 12px; border-radius: 4px; font-size: 12px; z-index: 9999; opacity: 0; transition: opacity 0.3s;';
                    document.body.appendChild(indicator);
                }
                
                indicator.textContent = message;
                indicator.style.opacity = '1';
                
                setTimeout(() => {
                    indicator.style.opacity = '0';
                }, 2000);
            }
        });
        
        function textbook_reset_semantic() {
            if (confirm('Reset all semantic elements to defaults?')) {
                location.reload();
            }
        }
        
        function textbook_generate_css() {
            const data = new FormData();
            data.append('action', 'textbook_generate_css');
            data.append('nonce', '<?php echo wp_create_nonce('textbook_nonce'); ?>');
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('CSS generated successfully!');
                } else {
                    alert('Error generating CSS');
                }
            });
        }
    </script>
    <?php
}

// Save semantic settings
function textbook_save_semantic_settings() {
    if (!isset($_POST['semantic_elements'])) {
        return;
    }
    
    $semantic_elements = $_POST['semantic_elements'];
    update_option('textbook_semantic_elements', $semantic_elements);
    
    // Generate CSS automatically
    textbook_generate_semantic_css();
}

// Save foundation settings
function textbook_save_foundation_settings() {
    if (!isset($_POST['font_families']) || !isset($_POST['font_sizes']) || !isset($_POST['font_weights']) || !isset($_POST['line_heights']) || !isset($_POST['letter_spacing'])) {
        return;
    }
    
    $font_families = $_POST['font_families'];
    $font_sizes = $_POST['font_sizes'];
    $font_weights = $_POST['font_weights'];
    $line_heights = $_POST['line_heights'];
    $letter_spacing = $_POST['letter_spacing'];
    
    // Save to WordPress options
    update_option('textbook_font_families', $font_families);
    update_option('textbook_font_sizes', $font_sizes);
    update_option('textbook_font_weights', $font_weights);
    update_option('textbook_line_heights', $line_heights);
    update_option('textbook_letter_spacing', $letter_spacing);
    
    // Save to theme.json
    textbook_update_theme_json($font_families, $font_sizes, $font_weights, $line_heights, $letter_spacing);
}

// Update theme.json with foundation settings
function textbook_update_theme_json($font_families, $font_sizes, $font_weights, $line_heights, $letter_spacing) {
    $theme_json_path = get_stylesheet_directory() . '/theme.json';
    
    // Read existing theme.json
    $theme_json = [];
    if (file_exists($theme_json_path)) {
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
    }
    
    // Ensure structure exists
    if (!isset($theme_json['settings'])) {
        $theme_json['settings'] = [];
    }
    if (!isset($theme_json['settings']['typography'])) {
        $theme_json['settings']['typography'] = [];
    }
    
    // Update font families
    $theme_json['settings']['typography']['fontFamilies'] = $font_families;
    
    // Update font sizes
    $theme_json['settings']['typography']['fontSizes'] = $font_sizes;
    
    // Update font weights
    $theme_json['settings']['typography']['fontWeights'] = $font_weights;
    
    // Update line heights
    $theme_json['settings']['typography']['lineHeights'] = $line_heights;
    
    // Update letter spacing
    $theme_json['settings']['typography']['letterSpacing'] = $letter_spacing;
    
    // Save theme.json
    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Get current semantic settings
function textbook_get_current_semantic_settings() {
    $saved_settings = get_option('textbook_semantic_elements', []);
    $default_elements = textbook_get_semantic_elements();
    
    // Merge saved settings with defaults
    foreach ($default_elements as $key => $default) {
        if (isset($saved_settings[$key])) {
            $default_elements[$key] = array_merge($default, $saved_settings[$key]);
        }
    }
    
    return $default_elements;
}

// Generate CSS for semantic elements
function textbook_generate_semantic_css() {
    $semantic_elements = textbook_get_current_semantic_settings();
    $css = "/* TextBook Semantic Elements CSS */\n\n";
    
    // Get colors from ColorBook for direct OKLCH usage
    $colorbook_colors = [];
    if (function_exists('colorbook_get_current_colors')) {
        $colors = colorbook_get_current_colors();
        foreach ($colors as $color) {
            $colorbook_colors[$color['slug']] = $color['oklch'];
        }
    }
    
    foreach ($semantic_elements as $element_key => $element) {
        $css .= ".semantic-{$element_key} {\n";
        $css .= "    font-size: var(--text-{$element['size']}-size);\n";
        $css .= "    line-height: var(--text-{$element['size']}-line-height);\n";
        $css .= "    font-weight: var(--font-weight-{$element['weight']});\n";
        
        // Use OKLCH color directly from ColorBook
        if (isset($colorbook_colors[$element['color']])) {
            $css .= "    color: {$colorbook_colors[$element['color']]};\n";
        } else {
            // Fallback to CSS custom property
            $css .= "    color: var(--wp--custom--color--{$element['color']});\n";
        }
        
        $css .= "    text-transform: {$element['transform']};\n";
        $css .= "    font-family: var(--wp--custom--typography--font-family--{$element['font_family']});\n";
        $css .= "    letter-spacing: var(--letter-spacing-{$element['letter_spacing']});\n";
        $css .= "}\n\n";
        
        // Size variants for this semantic element
        $sizes = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl', 'xxxl'];
        foreach ($sizes as $size) {
            $css .= ".semantic-{$element_key}.text-{$size} {\n";
            $css .= "    font-size: var(--text-{$size}-size);\n";
            $css .= "    line-height: var(--text-{$size}-line-height);\n";
            $css .= "}\n\n";
        }
    }
    
    // Save CSS file
    $css_dir = get_stylesheet_directory() . '/assets/css/';
    if (!file_exists($css_dir)) {
        wp_mkdir_p($css_dir);
    }
    
    file_put_contents($css_dir . 'textbook-semantic.css', $css);
    
    return $css;
}

// Get semantic context for Twig templates
function textbook_get_semantic_context() {
    $semantic_elements = textbook_get_current_semantic_settings();
    $context = ['textbook' => []];
    
    foreach ($semantic_elements as $key => $element) {
        $context['textbook'][$key] = $element;
    }
    
    return $context;
}

// Enqueue semantic CSS
add_action('wp_enqueue_scripts', 'textbook_enqueue_semantic_css');
function textbook_enqueue_semantic_css() {
    $css_file = get_stylesheet_directory() . '/assets/css/textbook-semantic.css';
    if (file_exists($css_file)) {
        wp_enqueue_style(
            'textbook-semantic',
            get_stylesheet_directory_uri() . '/assets/css/textbook-semantic.css',
            [],
            filemtime($css_file)
        );
    }
}

// AJAX handler for CSS generation
add_action('wp_ajax_textbook_generate_css', 'textbook_ajax_generate_css');
function textbook_ajax_generate_css() {
    if (!wp_verify_nonce($_POST['nonce'], 'textbook_nonce')) {
        wp_die('Security check failed');
    }
    
    textbook_generate_semantic_css();
    wp_send_json_success('CSS generated successfully');
}

// AJAX handler for auto-save
add_action('wp_ajax_textbook_auto_save', 'textbook_ajax_auto_save');
function textbook_ajax_auto_save() {
    if (!wp_verify_nonce($_POST['nonce'], 'textbook_nonce')) {
        wp_die('Security check failed');
    }
    
    textbook_save_semantic_settings();
    wp_send_json_success('Settings auto-saved');
}

// Helper functions for backward compatibility
function textbook_get_current_settings() {
    return textbook_get_current_semantic_settings();
}
