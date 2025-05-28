<?php
/**
 * TextBook Helper Functions
 * Easy access to typography settings for Carbon Blocks
 */

/**
 * Get typography setting by key
 * @param string $setting The typography setting key
 * @param string $format Return format: 'value', 'css-var', 'css-property'
 * @return string|null
 */
function tb_get_typography($setting, $format = 'value') {
    $typography = get_textbook_typography($setting);
    
    if (!$typography) {
        return null;
    }
    
    switch ($format) {
        case 'css-var':
            return "var(--theme-{$setting})";
        case 'css-property':
            return "--theme-{$setting}: {$typography}";
        default:
            return $typography;
    }
}

/**
 * Get font family setting
 * @param string $type 'primary' or 'body'
 * @param string $format Return format
 * @return string
 */
function tb_get_font_family($type = 'primary', $format = 'css-var') {
    $setting = $type === 'body' ? 'body_font' : 'primary_font';
    $typography = get_textbook_typography($setting);
    
    if (!$typography) {
        return $type === 'body' ? 'var(--wp--preset--font-family--inter)' : 'var(--wp--preset--font-family--inter)';
    }
    
    switch ($format) {
        case 'value':
            return $typography;
        case 'css-var':
            return "var(--wp--preset--font-family--{$typography})";
        case 'full':
            $fonts = [
                'inter' => 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                'system-font' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
            ];
            return $fonts[$typography] ?? $fonts['inter'];
        default:
            return "var(--wp--preset--font-family--{$typography})";
    }
}

/**
 * Get font size setting
 * @param string $element 'h1', 'h2', 'h3', 'body'
 * @param string $format Return format
 * @return string
 */
function tb_get_font_size($element = 'body', $format = 'css-var') {
    $setting = $element === 'body' ? 'body_size' : "{$element}_size";
    $typography = get_textbook_typography($setting);
    
    if (!$typography) {
        $defaults = ['h1' => 'xx-large', 'h2' => 'x-large', 'h3' => 'large', 'body' => 'medium'];
        $typography = $defaults[$element] ?? 'medium';
    }
    
    switch ($format) {
        case 'value':
            return $typography;
        case 'css-var':
            return "var(--wp--preset--font-size--{$typography})";
        case 'rem':
            $sizes = [
                'small' => '0.875rem',
                'medium' => '1rem',
                'large' => '1.25rem',
                'x-large' => '1.5rem',
                'xx-large' => '2.5rem',
                'huge' => '3rem'
            ];
            return $sizes[$typography] ?? '1rem';
        default:
            return "var(--wp--preset--font-size--{$typography})";
    }
}

/**
 * Get font weight setting
 * @param string $type 'heading' or 'body'
 * @param string $format Return format
 * @return string
 */
function tb_get_font_weight($type = 'body', $format = 'value') {
    $setting = $type === 'body' ? 'body_weight' : 'heading_weight';
    $typography = get_textbook_typography($setting);
    
    if (!$typography) {
        return $type === 'body' ? '400' : '600';
    }
    
    switch ($format) {
        case 'css-var':
            return "var(--theme-{$setting})";
        case 'name':
            $weights = [
                '300' => 'Light',
                '400' => 'Regular',
                '500' => 'Medium',
                '600' => 'Semi-Bold',
                '700' => 'Bold',
                '900' => 'Black'
            ];
            return $weights[$typography] ?? 'Regular';
        default:
            return $typography;
    }
}

/**
 * Get line height setting
 * @param string $type 'heading' or 'body'
 * @param string $format Return format
 * @return string
 */
function tb_get_line_height($type = 'body', $format = 'value') {
    $setting = $type === 'body' ? 'body_line_height' : 'heading_line_height';
    $typography = get_textbook_typography($setting);
    
    if (!$typography) {
        return $type === 'body' ? '1.6' : '1.2';
    }
    
    switch ($format) {
        case 'css-var':
            return "var(--theme-{$setting})";
        default:
            return $typography;
    }
}

/**
 * Get all typography settings as CSS custom properties
 * @return string CSS custom properties
 */
function tb_get_css_properties() {
    $data = get_textbook_data();
    
    if (!$data || !isset($data['css_variables'])) {
        return '';
    }
    
    $css = ":root {\n";
    foreach ($data['css_variables'] as $property => $value) {
        $css .= "  {$property}: {$value};\n";
    }
    $css .= "}\n";
    
    return $css;
}

/**
 * Get typography settings for Timber context
 * @return array Typography context for Twig templates
 */
function tb_get_context() {
    return [
        'fonts' => [
            'primary' => tb_get_font_family('primary'),
            'body' => tb_get_font_family('body')
        ],
        'sizes' => [
            'h1' => tb_get_font_size('h1'),
            'h2' => tb_get_font_size('h2'),
            'h3' => tb_get_font_size('h3'),
            'body' => tb_get_font_size('body')
        ],
        'weights' => [
            'heading' => tb_get_font_weight('heading'),
            'body' => tb_get_font_weight('body')
        ],
        'line_heights' => [
            'heading' => tb_get_line_height('heading'),
            'body' => tb_get_line_height('body')
        ]
    ];
}

/**
 * Output inline CSS for typography
 * @param string $selector CSS selector to apply typography to
 * @param array $options Typography options
 * @return string CSS rules
 */
function tb_generate_css($selector, $options = []) {
    $defaults = [
        'font_family' => 'body',
        'font_size' => 'body',
        'font_weight' => 'body',
        'line_height' => 'body'
    ];
    
    $options = array_merge($defaults, $options);
    
    $css = "{$selector} {\n";
    $css .= "  font-family: " . tb_get_font_family($options['font_family']) . ";\n";
    $css .= "  font-size: " . tb_get_font_size($options['font_size']) . ";\n";
    $css .= "  font-weight: " . tb_get_font_weight($options['font_weight']) . ";\n";
    $css .= "  line-height: " . tb_get_line_height($options['line_height']) . ";\n";
    $css .= "}\n";
    
    return $css;
}

/**
 * Quick typography classes for common elements
 * @param string $element Element type
 * @return string CSS class string
 */
function tb_get_classes($element) {
    $classes = [];
    
    switch ($element) {
        case 'heading':
        case 'h1':
        case 'h2':
        case 'h3':
            $classes[] = 'font-family-primary';
            $classes[] = 'font-weight-heading';
            $classes[] = 'line-height-heading';
            if ($element !== 'heading') {
                $classes[] = "font-size-{$element}";
            }
            break;
        case 'body':
        case 'text':
            $classes[] = 'font-family-body';
            $classes[] = 'font-size-body';
            $classes[] = 'font-weight-body';
            $classes[] = 'line-height-body';
            break;
        case 'button':
            $classes[] = 'font-family-primary';
            $classes[] = 'font-size-body';
            $classes[] = 'font-weight-heading';
            break;
    }
    
    return implode(' ', $classes);
}

/**
 * Generate utility CSS classes
 * @return string CSS utility classes
 */
function tb_generate_utility_classes() {
    $css = "/* TextBook Utility Classes */\n";
    
    // Font families
    $css .= ".font-family-primary { font-family: " . tb_get_font_family('primary') . "; }\n";
    $css .= ".font-family-body { font-family: " . tb_get_font_family('body') . "; }\n";
    
    // Font sizes
    $css .= ".font-size-h1 { font-size: " . tb_get_font_size('h1') . "; }\n";
    $css .= ".font-size-h2 { font-size: " . tb_get_font_size('h2') . "; }\n";
    $css .= ".font-size-h3 { font-size: " . tb_get_font_size('h3') . "; }\n";
    $css .= ".font-size-body { font-size: " . tb_get_font_size('body') . "; }\n";
    
    // Font weights
    $css .= ".font-weight-heading { font-weight: " . tb_get_font_weight('heading') . "; }\n";
    $css .= ".font-weight-body { font-weight: " . tb_get_font_weight('body') . "; }\n";
    
    // Line heights
    $css .= ".line-height-heading { line-height: " . tb_get_line_height('heading') . "; }\n";
    $css .= ".line-height-body { line-height: " . tb_get_line_height('body') . "; }\n";
    
    return $css;
}
