<?php
/**
 * LayoutBook Helper Functions
 * Easy access to layout settings for blocks and components
 */

/**
 * Get layout setting by key
 */
function lb_get_setting($setting, $format = 'value') {
    $layouts = layoutbook_get_current_layouts();
    $value = $layouts[$setting] ?? null;
    
    if ($format === 'css-var') {
        return "var(--layout-{$setting}, {$value})";
    }
    
    return $value;
}

/**
 * Get grid preset by name
 */
function lb_get_grid_preset($preset_name, $format = 'array') {
    $layouts = layoutbook_get_current_layouts();
    $preset = $layouts['grid_presets'][$preset_name] ?? null;
    
    if (!$preset) {
        return null;
    }
    
    switch ($format) {
        case 'css-vars':
            return [
                '--grid-min-width' => $preset['min'],
                '--grid-max-width' => $preset['max'],
                '--gap-x' => $preset['gap_x'],
                '--gap-y' => $preset['gap_y']
            ];
        case 'css-string':
            return "--grid-min-width: {$preset['min']}; --grid-max-width: {$preset['max']}; --gap-x: {$preset['gap_x']}; --gap-y: {$preset['gap_y']};";
        default:
            return $preset;
    }
}

/**
 * Get spacing value by size
 */
function lb_get_spacing($size, $format = 'value') {
    $layouts = layoutbook_get_current_layouts();
    $value = $layouts['spacing'][$size] ?? null;
    
    if ($format === 'css-var') {
        return "var(--layout-spacing-{$size}, {$value})";
    }
    
    return $value;
}

/**
 * Get breakpoint value by size
 */
function lb_get_breakpoint($size, $format = 'value') {
    $layouts = layoutbook_get_current_layouts();
    $value = $layouts['breakpoints'][$size] ?? null;
    
    if ($format === 'css-var') {
        return "var(--layout-breakpoint-{$size}, {$value})";
    }
    
    return $value;
}

/**
 * Get all layout data for Timber context
 */
function lb_get_context() {
    $layouts = layoutbook_get_current_layouts();
    
    return [
        'grid_min_width' => $layouts['grid_min_width'] ?? '300px',
        'grid_max_width' => $layouts['grid_max_width'] ?? '400px',
        'container_max_width' => $layouts['container_max_width'] ?? '1280px',
        'spacing' => $layouts['spacing'] ?? [],
        'breakpoints' => $layouts['breakpoints'] ?? [],
        'grid_presets' => $layouts['grid_presets'] ?? []
    ];
}

/**
 * Generate CSS custom properties string
 */
function lb_generate_css_props($settings = []) {
    $layouts = layoutbook_get_current_layouts();
    $props = [];
    
    // Add default layout props
    $props[] = "--layout-grid-min-width: " . ($layouts['grid_min_width'] ?? '300px');
    $props[] = "--layout-grid-max-width: " . ($layouts['grid_max_width'] ?? '400px');
    $props[] = "--layout-container-max-width: " . ($layouts['container_max_width'] ?? '1280px');
    
    // Add spacing props
    if (isset($layouts['spacing'])) {
        foreach ($layouts['spacing'] as $size => $value) {
            $props[] = "--layout-spacing-{$size}: {$value}";
        }
    }
    
    // Add custom settings
    foreach ($settings as $key => $value) {
        $props[] = "--{$key}: {$value}";
    }
    
    return implode('; ', $props);
}

/**
 * Get grid CSS for auto-fit layout
 */
function lb_get_grid_css($preset = null, $custom_min = null, $custom_max = null) {
    if ($preset) {
        $preset_data = lb_get_grid_preset($preset);
        if ($preset_data) {
            $min = $preset_data['min'];
            $max = $preset_data['max'];
        }
    }
    
    $min = $custom_min ?: ($min ?? lb_get_setting('grid_min_width'));
    $max = $custom_max ?: ($max ?? lb_get_setting('grid_max_width'));
    
    if ($max === 'none') {
        return "repeat(auto-fit, minmax({$min}, 1fr))";
    }
    
    return "repeat(auto-fit, minmax({$min}, {$max}))";
}

/**
 * Get common layout utility classes
 */
function lb_get_utility_classes() {
    return [
        'container' => 'lb-container',
        'grid' => 'lb-grid',
        'grid-auto' => 'lb-grid-auto',
        'flex' => 'lb-flex',
        'flex-col' => 'lb-flex-col',
        'gap-sm' => 'lb-gap-sm',
        'gap-md' => 'lb-gap-md',
        'gap-lg' => 'lb-gap-lg',
        'spacing-sm' => 'lb-spacing-sm',
        'spacing-md' => 'lb-spacing-md',
        'spacing-lg' => 'lb-spacing-lg'
    ];
}

/**
 * Generate responsive grid template for component
 */
function lb_grid_template($preset = 'default', $options = []) {
    $preset_data = lb_get_grid_preset($preset) ?: [
        'min' => lb_get_setting('grid_min_width'),
        'max' => lb_get_setting('grid_max_width'),
        'gap_x' => '2rem',
        'gap_y' => '2rem'
    ];
    
    $min_width = $options['min_width'] ?? $preset_data['min'];
    $max_width = $options['max_width'] ?? $preset_data['max'];
    $gap_x = $options['gap_x'] ?? $preset_data['gap_x'];
    $gap_y = $options['gap_y'] ?? $preset_data['gap_y'];
    $alignment = $options['alignment'] ?? 'stretch';
    $justify = $options['justify'] ?? 'start';
    
    return [
        'style' => lb_generate_css_props([
            'grid-min-width' => $min_width,
            'grid-max-width' => $max_width,
            'gap-x' => $gap_x,
            'gap-y' => $gap_y,
            'grid-alignment' => $alignment,
            'grid-justify' => $justify
        ]),
        'class' => 'lb-grid-auto',
        'grid_css' => lb_get_grid_css(null, $min_width, $max_width)
    ];
}

/**
 * Get layout data for specific component type
 */
function lb_get_component_layout($component_type) {
    $layouts = [
        'card' => [
            'preset' => 'cards',
            'min_width' => '280px',
            'max_width' => '350px',
            'gap' => '2rem'
        ],
        'feature' => [
            'preset' => 'features',
            'min_width' => '250px',
            'max_width' => '300px',
            'gap' => '1.5rem'
        ],
        'gallery' => [
            'preset' => 'gallery',
            'min_width' => '200px',
            'max_width' => '250px',
            'gap' => '1rem'
        ],
        'hero' => [
            'preset' => 'hero',
            'min_width' => '400px',
            'max_width' => 'none',
            'gap' => '3rem'
        ]
    ];
    
    return $layouts[$component_type] ?? $layouts['card'];
}
