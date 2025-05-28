<?php
/**
 * HeroBook Helper Functions
 * Easy access to hero template settings for blocks and templates
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get hero template setting
 * @param string $template Template name (home-hero, page-hero, etc.)
 * @param string $setting Setting key
 * @param string $format Return format ('value', 'css-var', 'class')
 * @return mixed
 */
function hb_get_template_setting($template, $setting, $format = 'value') {
    $templates = herobook_get_current_templates();
    $value = $templates[$template][$setting] ?? null;
    
    if ($format === 'css-var') {
        return "var(--hero-{$template}-{$setting}, {$value})";
    }
    
    if ($format === 'class') {
        return "hero-{$template}--{$setting}-{$value}";
    }
    
    return $value;
}

/**
 * Get active hero template
 * @return string
 */
function hb_get_active_template() {
    $templates = herobook_get_current_templates();
    return $templates['active_template'] ?? 'home-hero';
}

/**
 * Get home hero settings
 * @param string $setting Specific setting or null for all
 * @param string $format Return format
 * @return mixed
 */
function hb_get_home_hero($setting = null, $format = 'value') {
    if ($setting) {
        return hb_get_template_setting('home_hero', $setting, $format);
    }
    
    $templates = herobook_get_current_templates();
    return $templates['home_hero'] ?? array();
}

/**
 * Get hero layout CSS classes
 * @param string $template Template name
 * @return string
 */
function hb_get_layout_classes($template = null) {
    if (!$template) {
        $template = hb_get_active_template();
    }
    
    $template_key = str_replace('-', '_', $template);
    $settings = hb_get_template_setting($template_key, null);
    
    if (!$settings) return '';
    
    $classes = array();
    $classes[] = "hero-template--{$template}";
    $classes[] = "hero-layout--" . ($settings['layout_type'] ?? 'asymmetric-40-60');
    $classes[] = "hero-content--" . ($settings['content_alignment'] ?? 'left');
    $classes[] = "hero-height--" . ($settings['section_height'] ?? 'viewport-80');
    $classes[] = "hero-background--" . ($settings['background_style'] ?? 'solid');
    
    return implode(' ', $classes);
}

/**
 * Get hero CSS custom properties
 * @param string $template Template name
 * @return array
 */
function hb_get_css_properties($template = null) {
    if (!$template) {
        $template = hb_get_active_template();
    }
    
    $template_key = str_replace('-', '_', $template);
    $settings = hb_get_template_setting($template_key, null);
    
    if (!$settings) return array();
    
    $properties = array();
    
    // Layout properties
    switch ($settings['layout_type'] ?? 'asymmetric-40-60') {
        case 'asymmetric-40-60':
            $properties['--hero-left-width'] = '40%';
            $properties['--hero-right-width'] = '60%';
            break;
        case 'asymmetric-50-50':
            $properties['--hero-left-width'] = '50%';
            $properties['--hero-right-width'] = '50%';
            break;
        case 'centered':
            $properties['--hero-left-width'] = '100%';
            $properties['--hero-right-width'] = '0%';
            break;
    }
    
    // Height properties
    switch ($settings['section_height'] ?? 'viewport-80') {
        case 'viewport-80':
            $properties['--hero-height'] = '80vh';
            break;
        case 'viewport-100':
            $properties['--hero-height'] = '100vh';
            break;
        case 'auto':
            $properties['--hero-height'] = 'auto';
            break;
    }
    
    // Alignment properties
    $properties['--hero-content-align'] = $settings['content_alignment'] ?? 'left';
    
    return $properties;
}

/**
 * Generate hero CSS style attribute
 * @param string $template Template name
 * @return string
 */
function hb_get_style_attribute($template = null) {
    $properties = hb_get_css_properties($template);
    
    if (empty($properties)) return '';
    
    $styles = array();
    foreach ($properties as $property => $value) {
        $styles[] = "{$property}: {$value}";
    }
    
    return 'style="' . implode('; ', $styles) . '"';
}

/**
 * Check if hero component should be shown
 * @param string $component Component name (avatar_group, cta_button, product_cards)
 * @param string $template Template name
 * @return bool
 */
function hb_show_component($component, $template = null) {
    if (!$template) {
        $template = hb_get_active_template();
    }
    
    $template_key = str_replace('-', '_', $template);
    $settings = hb_get_template_setting($template_key, null);
    
    return $settings["show_{$component}"] ?? false;
}

/**
 * Get hero context for Timber templates
 * @param string $template Template name
 * @return array
 */
function hb_get_context($template = null) {
    if (!$template) {
        $template = hb_get_active_template();
    }
    
    $template_key = str_replace('-', '_', $template);
    $settings = hb_get_template_setting($template_key, null);
    
    return array(
        'template' => $template,
        'settings' => $settings,
        'classes' => hb_get_layout_classes($template),
        'css_properties' => hb_get_css_properties($template),
        'style_attribute' => hb_get_style_attribute($template),
        'components' => array(
            'avatar_group' => hb_show_component('avatar_group', $template),
            'cta_button' => hb_show_component('cta_button', $template),
            'product_cards' => hb_show_component('product_cards', $template)
        )
    );
}

/**
 * Generate hero grid CSS
 * @param string $template Template name
 * @return string
 */
function hb_generate_grid_css($template = null) {
    if (!$template) {
        $template = hb_get_active_template();
    }
    
    $template_key = str_replace('-', '_', $template);
    $settings = hb_get_template_setting($template_key, null);
    
    if (!$settings) return '';
    
    $css = '';
    
    // Base hero container
    $css .= ".hero-template--{$template} {\n";
    $css .= "  display: grid;\n";
    
    // Grid template columns based on layout
    switch ($settings['layout_type'] ?? 'asymmetric-40-60') {
        case 'asymmetric-40-60':
            $css .= "  grid-template-columns: 40% 60%;\n";
            break;
        case 'asymmetric-50-50':
            $css .= "  grid-template-columns: 50% 50%;\n";
            break;
        case 'centered':
            $css .= "  grid-template-columns: 1fr;\n";
            $css .= "  text-align: center;\n";
            break;
    }
    
    $css .= "  gap: 2rem;\n";
    $css .= "  align-items: center;\n";
    
    // Height
    switch ($settings['section_height'] ?? 'viewport-80') {
        case 'viewport-80':
            $css .= "  min-height: 80vh;\n";
            break;
        case 'viewport-100':
            $css .= "  min-height: 100vh;\n";
            break;
        case 'auto':
            $css .= "  min-height: auto;\n";
            $css .= "  padding: 4rem 0;\n";
            break;
    }
    
    $css .= "}\n\n";
    
    // Content alignment
    $css .= ".hero-content--{$settings['content_alignment']} .hero-left {\n";
    $css .= "  text-align: {$settings['content_alignment']};\n";
    $css .= "}\n\n";
    
    // Right side layout
    if ($settings['right_layout'] === 'product-grid') {
        $css .= ".hero-template--{$template} .hero-right {\n";
        $css .= "  display: grid;\n";
        $css .= "  grid-template-columns: 1fr 1fr;\n";
        $css .= "  gap: 1rem;\n";
        $css .= "}\n\n";
        
        $css .= ".hero-template--{$template} .product-card.large {\n";
        $css .= "  grid-row: span 2;\n";
        $css .= "}\n\n";
    }
    
    // Responsive
    $css .= "@media (max-width: 768px) {\n";
    $css .= "  .hero-template--{$template} {\n";
    $css .= "    grid-template-columns: 1fr;\n";
    $css .= "    text-align: center;\n";
    $css .= "  }\n";
    $css .= "}\n";
    
    return $css;
}

/**
 * Get hero template options for select fields
 * @return array
 */
function hb_get_template_options() {
    return array(
        'home-hero' => 'Home Hero',
        'page-hero' => 'Page Hero',
        'product-hero' => 'Product Hero',
        'contact-hero' => 'Contact Hero'
    );
}

/**
 * Get layout type options
 * @return array
 */
function hb_get_layout_options() {
    return array(
        'asymmetric-40-60' => 'Asymmetric 40/60',
        'asymmetric-50-50' => 'Split 50/50',
        'centered' => 'Centered'
    );
}

/**
 * Get right layout options
 * @return array
 */
function hb_get_right_layout_options() {
    return array(
        'product-grid' => 'Product Grid',
        'feature-cards' => 'Feature Cards',
        'single-image' => 'Single Image',
        'gallery' => 'Image Gallery'
    );
}
