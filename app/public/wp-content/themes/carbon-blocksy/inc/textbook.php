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
            <button class="tab-button active" data-tab="foundation" onclick="switchTab('foundation')">🏗️ Foundation</button>
            <button class="tab-button" data-tab="semantic" onclick="switchTab('semantic')">🎯 Semantic Elements</button>
        </div>
        
        <!-- Foundation Tab -->
        <div id="foundation-tab" class="tab-content active">
            <form method="post" action="">
                <?php wp_nonce_field('textbook_foundation_action', 'textbook_foundation_nonce'); ?>
                
                <div class="foundation-grid">
                    <!-- Font Families Section -->
                    <div class="foundation-section">
                        <h2>🔤 Font Families</h2>
                        <div class="font-families-grid">
                            <?php 
                            foreach ($font_families as $index => $family): 
                            ?>
                            <div class="font-family-item">
                                <label>Family Name</label>
                                <input type="text" name="font_families[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($family['name']); ?>" placeholder="Inter">
                                
                                <label>Font Stack</label>
                                <input type="text" name="font_families[<?php echo $index; ?>][fontFamily]" 
                                       value="<?php echo esc_attr($family['fontFamily']); ?>" 
                                       placeholder="Inter, sans-serif">
                                
                                <label>Slug</label>
                                <input type="text" name="font_families[<?php echo $index; ?>][slug]" 
                                       value="<?php echo esc_attr($family['slug']); ?>" placeholder="inter">
                                
                                <div class="font-preview" style="font-family: <?php echo esc_attr($family['fontFamily']); ?>;">
                                    Text
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Font Sizes Section -->
                    <div class="foundation-section">
                        <h2>📏 Font Sizes</h2>
                        <div class="font-sizes-grid">
                            <?php 
                            foreach ($font_sizes as $index => $size): 
                            ?>
                            <div class="font-size-item">
                                <label>Size Name</label>
                                <input type="text" name="font_sizes[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($size['name']); ?>" placeholder="Large">
                                
                                <label>Slug</label>
                                <input type="text" name="font_sizes[<?php echo $index; ?>][slug]" 
                                       value="<?php echo esc_attr($size['slug']); ?>" placeholder="large">
                                
                                <label>Size Value</label>
                                <input type="text" name="font_sizes[<?php echo $index; ?>][size]" 
                                       value="<?php echo esc_attr($size['size']); ?>" placeholder="1.25rem">
                                
                                <div class="size-preview" style="font-size: <?php echo esc_attr($size['size']); ?>; padding: 0.75rem; margin: 0.5rem 0; border: 1px solid #ddd; border-radius: 4px; line-height: 1.2;">
                                    Text
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Font Weights Section -->
                    <div class="foundation-section">
                        <h2>⚖️ Font Weights</h2>
                        <div class="font-weights-grid">
                            <?php 
                            foreach ($font_weights as $index => $weight): 
                            ?>
                            <div class="font-weight-item">
                                <label>Weight Name</label>
                                <input type="text" name="font_weights[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($weight['name']); ?>" placeholder="Regular">
                                
                                <label>Slug</label>
                                <input type="text" name="font_weights[<?php echo $index; ?>][slug]" 
                                       value="<?php echo esc_attr($weight['slug']); ?>" placeholder="regular">
                                
                                <label>Weight Value</label>
                                <input type="text" name="font_weights[<?php echo $index; ?>][weight]" 
                                       value="<?php echo esc_attr($weight['weight']); ?>" placeholder="400">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Line Heights Section -->
                    <div class="foundation-section">
                        <h2>📈 Line Heights</h2>
                        <div class="line-heights-grid">
                            <?php 
                            foreach ($line_heights as $index => $line_height): 
                            ?>
                            <div class="line-height-item">
                                <label>Line Height Name</label>
                                <input type="text" name="line_heights[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($line_height['name']); ?>" placeholder="Normal">
                                
                                <label>Slug</label>
                                <input type="text" name="line_heights[<?php echo $index; ?>][slug]" 
                                       value="<?php echo esc_attr($line_height['slug']); ?>" placeholder="normal">
                                
                                <label>Line Height Value</label>
                                <input type="text" name="line_heights[<?php echo $index; ?>][height]" 
                                       value="<?php echo esc_attr($line_height['height']); ?>" placeholder="1.4">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Letter Spacing Section -->
                    <div class="foundation-section">
                        <h2>📊 Letter Spacing</h2>
                        <div class="letter-spacing-grid">
                            <?php 
                            foreach ($letter_spacing as $index => $spacing): 
                            ?>
                            <div class="letter-spacing-item">
                                <label>Letter Spacing Name</label>
                                <input type="text" name="letter_spacing[<?php echo $index; ?>][name]" 
                                       value="<?php echo esc_attr($spacing['name']); ?>" placeholder="Normal">
                                
                                <label>Slug</label>
                                <input type="text" name="letter_spacing[<?php echo $index; ?>][slug]" 
                                       value="<?php echo esc_attr($spacing['slug']); ?>" placeholder="normal">
                                
                                <label>Letter Spacing Value</label>
                                <input type="text" name="letter_spacing[<?php echo $index; ?>][spacing]" 
                                       value="<?php echo esc_attr($spacing['spacing']); ?>" placeholder="0">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="foundation-actions">
                    <?php submit_button('Save Foundation Settings', 'primary', 'save_foundation', false); ?>
                    <button type="button" class="button" onclick="resetFoundationDefaults()">Reset to Defaults</button>
                </div>
            </form>
        </div>
        
        <!-- Semantic Elements Tab -->
        <div id="semantic-tab" class="tab-content">
            <form method="post" action="">
                <?php wp_nonce_field('textbook_save', 'textbook_nonce'); ?>
                
                <div class="textbook-grid">
                    <?php foreach ($semantic_elements as $element_key => $element): ?>
                    <div class="semantic-element-card">
                        <div class="element-header">
                            <h3><?php echo $element['label']; ?></h3>
                            <p><?php echo $element['description']; ?></p>
                        </div>
                        
                        <div class="element-controls">
                            <table class="form-table">
                                <tr>
                                    <th>HTML Tag</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][tag]">
                                            <?php foreach ($options['tags'] as $tag): ?>
                                                <option value="<?php echo $tag; ?>" <?php selected($element['tag'], $tag); ?>>
                                                    &lt;<?php echo $tag; ?>&gt;
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Default Size</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][size]">
                                            <?php foreach ($options['sizes'] as $size): ?>
                                                <option value="<?php echo $size; ?>" <?php selected($element['size'], $size); ?>>
                                                    <?php echo strtoupper($size); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Font Weight</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][weight]">
                                            <?php foreach ($options['weights'] as $weight): ?>
                                                <option value="<?php echo $weight; ?>" <?php selected($element['weight'], $weight); ?>>
                                                    <?php echo $weight; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Color</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][color]">
                                            <?php foreach ($options['colors'] as $color): ?>
                                                <option value="<?php echo $color; ?>" <?php selected($element['color'], $color); ?>>
                                                    <?php echo ucfirst($color); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Text Transform</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][transform]">
                                            <?php foreach ($options['transforms'] as $transform): ?>
                                                <option value="<?php echo $transform; ?>" <?php selected($element['transform'], $transform); ?>>
                                                    <?php echo ucfirst($transform); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Font Family</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][font_family]">
                                            <?php foreach ($options['font_families'] as $family): ?>
                                                <option value="<?php echo $family; ?>" <?php selected($element['font_family'], $family); ?>>
                                                    <?php echo ucfirst($family); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Line Height</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][line_height]">
                                            <?php foreach ($options['line_heights'] as $line_height): ?>
                                                <option value="<?php echo $line_height; ?>" <?php selected($element['line_height'], $line_height); ?>>
                                                    <?php echo ucfirst($line_height); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Letter Spacing</th>
                                    <td>
                                        <select name="semantic_elements[<?php echo $element_key; ?>][letter_spacing]">
                                            <?php foreach ($options['letter_spacing'] as $spacing): ?>
                                                <option value="<?php echo $spacing; ?>" <?php selected($element['letter_spacing'], $spacing); ?>>
                                                    <?php echo ucfirst($spacing); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="element-preview">
                            <<?php echo $element['tag']; ?> 
                                class="text-<?php echo $element['size']; ?> font-<?php echo $element['weight']; ?> text-<?php echo $element['color']; ?> text-<?php echo $element['transform']; ?> letter-spacing-<?php echo $element['letter_spacing']; ?>"
                                style="font-size: <?php echo textbook_get_font_size_value($element['size']); ?>; font-family: <?php echo textbook_get_font_family_value($element['font_family']); ?>; font-weight: <?php echo textbook_get_font_weight_value($element['weight']); ?>; text-transform: <?php echo $element['transform']; ?>; line-height: <?php echo textbook_get_line_height_value($element['line_height']); ?>; letter-spacing: <?php echo textbook_get_letter_spacing_value($element['letter_spacing']); ?>;">
                                Text
                            </<?php echo $element['tag']; ?>>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="textbook-actions">
                    <?php submit_button('Save Semantic Elements', 'primary', 'submit', false); ?>
                    <button type="button" class="button" onclick="resetToDefaults()">Reset to Defaults</button>
                    <button type="button" class="button" onclick="generateCSS()">Generate CSS</button>
                </div>
            </form>
        </div>
        
        <div class="textbook-usage">
            <h2>📖 Usage in Components</h2>
            <p>In your Twig components, use semantic elements with optional size overrides:</p>
            <pre><code>
{# Use default settings #}
&lt;{{ textbook.pretitle.tag }} class="semantic-pretitle"&gt;{{ pretitle }}&lt;/{{ textbook.pretitle.tag }}&gt;
&lt;{{ textbook.title.tag }} class="semantic-title"&gt;{{ title }}&lt;/{{ textbook.title.tag }}&gt;

{# Override size only #}
&lt;{{ textbook.title.tag }} class="semantic-title text-xxl"&gt;{{ hero_title }}&lt;/{{ textbook.title.tag }}&gt;
&lt;{{ textbook.description.tag }} class="semantic-description text-lg"&gt;{{ large_description }}&lt;/{{ textbook.description.tag }}&gt;
            </code></pre>
        </div>
    </div>

    <style>
    .textbook-tabs {
        display: flex;
        justify-content: space-between;
        margin: 1rem 0;
    }
    
    .tab-button {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        background: #f9f9f9;
        cursor: pointer;
    }
    
    .tab-button.active {
        background: #fff;
        border-bottom: 2px solid #2271b1;
    }
    
    .tab-content {
        display: none;
        padding: 1rem;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .foundation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .foundation-section {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1.5rem;
    }
    
    .font-families-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .font-family-item {
        background: #f9f9f9;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .font-preview {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    
    .font-sizes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .font-size-item {
        background: #f9f9f9;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .size-preview {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin: 0.5rem 0;
    }
    
    .font-weights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .font-weight-item {
        background: #f9f9f9;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .line-heights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .line-height-item {
        background: #f9f9f9;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .letter-spacing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .letter-spacing-item {
        background: #f9f9f9;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .textbook-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .semantic-element-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .element-header h3 {
        margin: 0 0 0.5rem 0;
        color: #2271b1;
    }
    
    .element-header p {
        margin: 0 0 1rem 0;
        color: #666;
        font-size: 0.9rem;
    }
    
    .element-controls .form-table {
        margin: 0;
    }
    
    .element-controls th {
        width: 120px;
        font-size: 0.9rem;
        padding: 0.5rem 0;
    }
    
    .element-controls td {
        padding: 0.5rem 0;
    }
    
    .element-controls select {
        width: 100%;
        max-width: 200px;
    }
    
    .element-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: #f9f9f9;
        border-radius: 4px;
        border-left: 4px solid #2271b1;
    }
    
    .textbook-actions {
        margin: 2rem 0;
        padding: 1rem;
        background: #f9f9f9;
        border-radius: 4px;
    }
    
    .textbook-usage {
        margin-top: 3rem;
        padding: 2rem;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    
    .textbook-usage pre {
        background: #f4f4f4;
        padding: 1rem;
        border-radius: 4px;
        overflow-x: auto;
    }
    </style>

    <script>
    function switchTab(tab) {
        // Remove active class from all tab buttons
        var tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(function(tabButton) {
            tabButton.classList.remove('active');
        });
        
        // Remove active class from all tab contents
        var tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(function(tabContent) {
            tabContent.classList.remove('active');
        });
        
        // Add active class to clicked tab button
        document.querySelector('.tab-button[data-tab="' + tab + '"]').classList.add('active');
        
        // Add active class to corresponding tab content
        document.querySelector('#' + tab + '-tab').classList.add('active');
    }
    
    function resetToDefaults() {
        if (confirm('Reset all semantic elements to default settings?')) {
            location.href = '<?php echo admin_url('admin.php?page=textbook&reset=1'); ?>';
        }
    }
    
    function generateCSS() {
        // Trigger CSS generation
        jQuery.post(ajaxurl, {
            action: 'textbook_generate_css',
            nonce: '<?php echo wp_create_nonce('textbook_nonce'); ?>'
        }, function(response) {
            alert('CSS generated successfully!');
        });
    }
    
    // Live preview updates
    jQuery(document).ready(function($) {
        // Update preview when any select changes
        $('.element-controls select').on('change', function() {
            console.log('Select changed');
            var card = $(this).closest('.semantic-element-card');
            var elementKey = getElementKeyFromCard(card);
            updatePreview(card, elementKey);
        });
        
        function getElementKeyFromCard(card) {
            // Extract element key from the select name attribute
            var selectName = card.find('select').first().attr('name');
            var match = selectName.match(/semantic_elements\[([^\]]+)\]/);
            return match ? match[1] : '';
        }
        
        function updatePreview(card, elementKey) {
            console.log('Updating preview for element:', elementKey);
            var preview = card.find('.element-preview');
            var previewElement = preview.find('h1, h2, h3, h4, h5, h6, p, span, div, label').first();
            
            // Get current values from dropdowns
            var tag = card.find('select[name*="[tag]"]').val();
            var size = card.find('select[name*="[size]"]').val();
            var weight = card.find('select[name*="[weight]"]').val();
            var color = card.find('select[name*="[color]"]').val();
            var transform = card.find('select[name*="[transform]"]').val();
            var fontFamily = card.find('select[name*="[font_family]"]').val();
            var line_height = card.find('select[name*="[line_height]"]').val();
            var letterSpacing = card.find('select[name*="[letter_spacing]"]').val();
            
            // Get element label for preview text
            var elementLabel = card.find('.element-header h3').text();
            
            // Create new element with updated attributes
            var newElement = $('<' + tag + '>').addClass('text-' + size + ' font-' + weight + ' text-' + color + ' text-' + transform + ' letter-spacing-' + letterSpacing);
            newElement.text('Text');
            
            // Apply inline styles for immediate preview (since CSS variables might not be loaded)
            var styles = getPreviewStyles(size, weight, color, transform, fontFamily, line_height, letterSpacing);
            newElement.attr('style', styles);
            
            // Replace the preview element
            preview.html(newElement);
        }
        
        function getPreviewStyles(size, weight, color, transform, fontFamily, line_height, letterSpacing) {
            var styles = [];
            
            // Font sizes from foundation settings
            var sizeMap = {};
            <?php 
            $font_sizes = textbook_get_font_sizes();
            foreach ($font_sizes as $size): 
            ?>
                sizeMap['<?php echo $size['slug']; ?>'] = '<?php echo $size['size']; ?>';
            <?php endforeach; ?>
            
            // Line heights from foundation settings
            var lineHeightMap = {};
            <?php 
            $line_heights = textbook_get_line_heights();
            foreach ($line_heights as $line_height): 
            ?>
                lineHeightMap['<?php echo $line_height['slug']; ?>'] = '<?php echo $line_height['height']; ?>';
            <?php endforeach; ?>
            
            // Letter spacing from foundation settings
            var letterSpacingMap = {};
            <?php 
            $letter_spacing = textbook_get_letter_spacing();
            foreach ($letter_spacing as $spacing): 
            ?>
                letterSpacingMap['<?php echo $spacing['slug']; ?>'] = '<?php echo $spacing['spacing']; ?>';
            <?php endforeach; ?>
            
            // Color values (actual ColorBook colors)
            var colorMap = {};
            <?php if (function_exists('colorbook_get_current_colors')): ?>
                <?php $colors = colorbook_get_current_colors(); ?>
                <?php foreach ($colors as $color): ?>
                    colorMap['<?php echo $color['slug']; ?>'] = '<?php echo $color['hex']; ?>';
                <?php endforeach; ?>
            <?php endif; ?>
            
            // Fallback colors if ColorBook not available
            if (Object.keys(colorMap).length === 0) {
                colorMap = {
                    'primary': '#5a7f80',
                    'secondary': '#a36b57',
                    'base-darkest': '#4d4d4d',
                    'base-dark': '#737373',
                    'base': '#a3a3a3',
                    'base-light': '#d4d4d4',
                    'base-white': '#ffffff'
                };
            }
            
            // Font family values from foundation settings
            var fontFamilyMap = {};
            <?php 
            $font_families = textbook_get_font_families();
            foreach ($font_families as $family): 
            ?>
                fontFamilyMap['<?php echo $family['slug']; ?>'] = '<?php echo $family['fontFamily']; ?>';
            <?php endforeach; ?>
            
            styles.push('font-size: ' + (sizeMap[size] || '1rem'));
            styles.push('font-weight: ' + weight);
            styles.push('color: ' + (colorMap[color] || '#1f2937'));
            styles.push('text-transform: ' + transform);
            styles.push('font-family: ' + (fontFamilyMap[fontFamily] || 'Inter, sans-serif'));
            styles.push('line-height: ' + (lineHeightMap[line_height] || '1.4'));
            styles.push('margin: 0');
            styles.push('letter-spacing: ' + (letterSpacingMap[letterSpacing] || '0'));
            
            return styles.join('; ');
        }
    });
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
    
    foreach ($semantic_elements as $element_key => $element) {
        $css .= ".semantic-{$element_key} {\n";
        $css .= "    font-size: var(--text-{$element['size']}-size);\n";
        $css .= "    line-height: var(--text-{$element['size']}-line-height);\n";
        $css .= "    font-weight: var(--font-weight-{$element['weight']});\n";
        $css .= "    color: var(--wp--custom--color--{$element['color']});\n";
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

// Helper functions for backward compatibility
function textbook_get_current_settings() {
    return textbook_get_current_semantic_settings();
}
