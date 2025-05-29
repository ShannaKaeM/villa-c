<?php
/**
 * DesignBook CSS Generation
 * Generate CSS variables from admin settings
 */

/**
 * Generate and enqueue DesignBook CSS variables
 */
function db_enqueue_css_variables() {
    if (!db_is_enabled()) {
        return;
    }
    
    $css = db_generate_color_css() . db_generate_typography_css();
    
    if ($css) {
        wp_add_inline_style('blocksy-main-styles', $css);
    }
}
add_action('wp_enqueue_scripts', 'db_enqueue_css_variables');

/**
 * Generate CSS variables for colors
 */
function db_generate_color_css() {
    if (!db_is_enabled()) return '';
    
    $colors = db_get_colors();
    if (!$colors) return '';
    
    $css = ":root {\n";
    
    foreach ($colors as $color) {
        $slug = $color['slug'];
        $hex = $color['hex'];
        $css .= "  --db-color-{$slug}: {$hex};\n";
    }
    
    $css .= "}\n";
    return $css;
}

/**
 * Generate CSS variables for typography
 */
function db_generate_typography_css() {
    if (!db_is_enabled()) return '';
    
    $fonts = db_get_fonts();
    if (!$fonts) return '';
    
    $css = ":root {\n";
    
    foreach ($fonts as $font) {
        $name = strtolower(str_replace(' ', '-', $font['name']));
        $stack = $font['stack'];
        $css .= "  --db-font-{$name}: {$stack};\n";
    }
    
    $css .= "}\n";
    return $css;
}

/**
 * Add DesignBook CSS variables to Gutenberg editor
 */
function db_enqueue_editor_css_variables() {
    if (!db_is_enabled()) {
        return;
    }
    
    $css = db_generate_color_css() . db_generate_typography_css();
    
    if ($css) {
        wp_add_inline_style('wp-edit-blocks', $css);
    }
}
add_action('enqueue_block_editor_assets', 'db_enqueue_editor_css_variables');
