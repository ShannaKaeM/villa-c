<?php
/**
 * UiBook Helper Functions
 * Easy access to component design system settings
 */

// Get UiBook settings from JSON file
function ub_get_settings() {
    static $settings = null;
    
    if ($settings === null) {
        $json_file = get_stylesheet_directory() . '/miDocs/SITE DATA/uibook.json';
        
        if (file_exists($json_file)) {
            $json_data = json_decode(file_get_contents($json_file), true);
            $settings = isset($json_data['settings']) ? $json_data['settings'] : array();
        } else {
            // Default settings
            $settings = array(
                'card_padding_x' => '2',
                'card_padding_y' => '1.5',
                'card_radius' => '12',
                'card_shadow' => 'medium',
                'card_spacing' => '0.75',
                'button_full_width' => 'true',
                'button_padding_x' => '1',
                'button_padding_y' => '0.5',
                'button_radius' => '6',
                'button_style' => 'primary',
                'tag_style1_radius' => '20',
                'tag_style1_padding_x' => '0.75',
                'tag_style1_padding_y' => '0.25',
                'tag_style2_radius' => '6',
                'tag_style2_padding_x' => '0.5',
                'tag_style2_padding_y' => '0.25',
                'tag_spacing' => '0.5',
                'grid_gap' => '1.5',
                'content_spacing' => '1',
                'section_padding' => '4'
            );
        }
    }
    
    return $settings;
}

// Get specific UiBook setting
function ub_get_setting($key, $format = 'value') {
    $settings = ub_get_settings();
    $value = isset($settings[$key]) ? $settings[$key] : '';
    
    switch ($format) {
        case 'css':
            $unit = (strpos($key, 'radius') !== false) ? 'px' : 'rem';
            return $value . $unit;
        case 'var':
            return 'var(--ui-' . str_replace('_', '-', $key) . ')';
        default:
            return $value;
    }
}

// Get card settings
function ub_get_card_padding_x($format = 'css') {
    return ub_get_setting('card_padding_x', $format);
}

function ub_get_card_padding_y($format = 'css') {
    return ub_get_setting('card_padding_y', $format);
}

function ub_get_card_radius($format = 'css') {
    return ub_get_setting('card_radius', $format);
}

function ub_get_card_shadow($format = 'css') {
    $intensity = ub_get_setting('card_shadow');
    
    if ($format === 'css') {
        $shadows = array(
            'none' => 'none',
            'small' => '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
            'medium' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'large' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            'xlarge' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'
        );
        return isset($shadows[$intensity]) ? $shadows[$intensity] : $shadows['medium'];
    }
    
    return $intensity;
}

function ub_get_card_spacing($format = 'css') {
    return ub_get_setting('card_spacing', $format);
}

// Get button settings
function ub_get_button_full_width($format = 'css') {
    $value = ub_get_setting('button_full_width');
    
    if ($format === 'css') {
        return ($value === 'true') ? '100%' : 'auto';
    }
    
    return $value === 'true';
}

function ub_get_button_padding_x($format = 'css') {
    return ub_get_setting('button_padding_x', $format);
}

function ub_get_button_padding_y($format = 'css') {
    return ub_get_setting('button_padding_y', $format);
}

function ub_get_button_radius($format = 'css') {
    return ub_get_setting('button_radius', $format);
}

// Get tag settings
function ub_get_tag_style1_radius($format = 'css') {
    return ub_get_setting('tag_style1_radius', $format);
}

function ub_get_tag_style1_padding_x($format = 'css') {
    return ub_get_setting('tag_style1_padding_x', $format);
}

function ub_get_tag_style1_padding_y($format = 'css') {
    return ub_get_setting('tag_style1_padding_y', $format);
}

function ub_get_tag_style2_radius($format = 'css') {
    return ub_get_setting('tag_style2_radius', $format);
}

function ub_get_tag_style2_padding_x($format = 'css') {
    return ub_get_setting('tag_style2_padding_x', $format);
}

function ub_get_tag_style2_padding_y($format = 'css') {
    return ub_get_setting('tag_style2_padding_y', $format);
}

function ub_get_tag_spacing($format = 'css') {
    return ub_get_setting('tag_spacing', $format);
}

// Get layout settings
function ub_get_grid_gap($format = 'css') {
    return ub_get_setting('grid_gap', $format);
}

function ub_get_content_spacing($format = 'css') {
    return ub_get_setting('content_spacing', $format);
}

function ub_get_section_padding($format = 'css') {
    return ub_get_setting('section_padding', $format);
}

// Get all CSS variables for Timber context
function ub_get_context() {
    $settings = ub_get_settings();
    $context = array();
    
    // Card variables
    $context['card_padding_x'] = ub_get_card_padding_x();
    $context['card_padding_y'] = ub_get_card_padding_y();
    $context['card_radius'] = ub_get_card_radius();
    $context['card_shadow'] = ub_get_card_shadow();
    $context['card_spacing'] = ub_get_card_spacing();
    
    // Button variables
    $context['button_full_width'] = ub_get_button_full_width();
    $context['button_padding_x'] = ub_get_button_padding_x();
    $context['button_padding_y'] = ub_get_button_padding_y();
    $context['button_radius'] = ub_get_button_radius();
    
    // Tag variables
    $context['tag_style1_radius'] = ub_get_tag_style1_radius();
    $context['tag_style1_padding_x'] = ub_get_tag_style1_padding_x();
    $context['tag_style1_padding_y'] = ub_get_tag_style1_padding_y();
    $context['tag_style2_radius'] = ub_get_tag_style2_radius();
    $context['tag_style2_padding_x'] = ub_get_tag_style2_padding_x();
    $context['tag_style2_padding_y'] = ub_get_tag_style2_padding_y();
    $context['tag_spacing'] = ub_get_tag_spacing();
    
    // Layout variables
    $context['grid_gap'] = ub_get_grid_gap();
    $context['content_spacing'] = ub_get_content_spacing();
    $context['section_padding'] = ub_get_section_padding();
    
    return $context;
}

// Generate CSS rules for a component
function ub_generate_css($selector, $options = array()) {
    $css = array();
    
    // Default options
    $defaults = array(
        'card' => false,
        'button' => false,
        'tag_style1' => false,
        'tag_style2' => false,
        'grid' => false
    );
    
    $options = array_merge($defaults, $options);
    
    if ($options['card']) {
        $css[] = $selector . ' {';
        $css[] = '    padding: ' . ub_get_card_padding_y() . ' ' . ub_get_card_padding_x() . ';';
        $css[] = '    border-radius: ' . ub_get_card_radius() . ';';
        $css[] = '    box-shadow: ' . ub_get_card_shadow() . ';';
        $css[] = '}';
    }
    
    if ($options['button']) {
        $css[] = $selector . ' {';
        $css[] = '    width: ' . ub_get_button_full_width() . ';';
        $css[] = '    padding: ' . ub_get_button_padding_y() . ' ' . ub_get_button_padding_x() . ';';
        $css[] = '    border-radius: ' . ub_get_button_radius() . ';';
        $css[] = '}';
    }
    
    if ($options['tag_style1']) {
        $css[] = $selector . ' {';
        $css[] = '    padding: ' . ub_get_tag_style1_padding_y() . ' ' . ub_get_tag_style1_padding_x() . ';';
        $css[] = '    border-radius: ' . ub_get_tag_style1_radius() . ';';
        $css[] = '}';
    }
    
    if ($options['tag_style2']) {
        $css[] = $selector . ' {';
        $css[] = '    padding: ' . ub_get_tag_style2_padding_y() . ' ' . ub_get_tag_style2_padding_x() . ';';
        $css[] = '    border-radius: ' . ub_get_tag_style2_radius() . ';';
        $css[] = '}';
    }
    
    if ($options['grid']) {
        $css[] = $selector . ' {';
        $css[] = '    gap: ' . ub_get_grid_gap() . ';';
        $css[] = '}';
    }
    
    return implode("\n", $css);
}

// Get utility classes for components
function ub_get_classes($component) {
    $classes = array();
    
    switch ($component) {
        case 'card':
            $classes[] = 'ui-card';
            break;
        case 'button':
            $classes[] = 'ui-button';
            if (ub_get_button_full_width()) {
                $classes[] = 'ui-button--full-width';
            }
            break;
        case 'tag-style1':
            $classes[] = 'ui-tag ui-tag--style1';
            break;
        case 'tag-style2':
            $classes[] = 'ui-tag ui-tag--style2';
            break;
        case 'grid':
            $classes[] = 'ui-grid';
            break;
    }
    
    return implode(' ', $classes);
}
