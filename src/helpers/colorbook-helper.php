<?php
/**
 * ColorBook Helper for Blocks - Blocksy Integration
 * Easy access to Villa colors through Blocksy's theming system
 */

if (!function_exists('cb_get_color')) {
    /**
     * Get a Villa color from Blocksy's theme system
     * 
     * @param string $slug Color slug (e.g., 'primary', 'secondary-light')
     * @param string $format 'hex'|'css-var'|'theme-mod' - Format to return
     * @return string|null
     */
    function cb_get_color($slug, $format = 'hex') {
        // Primary: Get from Blocksy theme mods (Villa integration)
        $villa_theme_mod = 'villa_color_' . str_replace('-', '_', $slug);
        $color_value = get_theme_mod($villa_theme_mod);
        
        if ($color_value) {
            switch ($format) {
                case 'css-var':
                    return "var(--color-{$slug})";
                case 'theme-mod':
                    return $villa_theme_mod;
                case 'hex':
                default:
                    return $color_value;
            }
        }
        
        // Fallback: Use main colorbook function if available
        if (function_exists('get_colorbook_color')) {
            if ($format === 'css-var') {
                return "var(--color-{$slug})";
            }
            return get_colorbook_color($slug, $format === 'theme-mod' ? 'hex' : $format);
        }
        
        // Last resort: Direct JSON file read
        $colorbook_path = get_stylesheet_directory() . '/miDocs/SITE DATA/colorbook.json';
        
        if (!file_exists($colorbook_path)) {
            return null;
        }
        
        $data = json_decode(file_get_contents($colorbook_path), true);
        
        if (!isset($data['colors'])) {
            return null;
        }
        
        foreach ($data['colors'] as $color) {
            if ($color['slug'] === $slug) {
                switch ($format) {
                    case 'css-var':
                        return "var(--color-{$slug})";
                    case 'theme-mod':
                        return $villa_theme_mod;
                    case 'hex':
                    default:
                        return $color['hex'];
                }
            }
        }
        
        return null;
    }
}

if (!function_exists('cb_get_palette_color')) {
    /**
     * Get a color by Blocksy palette number (1-16)
     * 
     * @param int $number Palette color number (1-16)
     * @param string $format 'hex'|'css-var'|'theme-mod' - Format to return
     * @return string|null
     */
    function cb_get_palette_color($number, $format = 'hex') {
        // Primary: Get from Blocksy theme mods
        $color_value = get_theme_mod('palette_color_' . $number);
        
        if ($color_value) {
            switch ($format) {
                case 'css-var':
                    return "var(--theme-palette-color-{$number})";
                case 'theme-mod':
                    return 'palette_color_' . $number;
                case 'hex':
                default:
                    return $color_value;
            }
        }
        
        // Fallback: Use main colorbook function if available
        if (function_exists('get_colorbook_data')) {
            $variables = get_colorbook_data('variables');
            $color_value = isset($variables["--theme-palette-color-{$number}"]) 
                ? $variables["--theme-palette-color-{$number}"] 
                : null;
                
            if ($color_value) {
                switch ($format) {
                    case 'css-var':
                        return "var(--theme-palette-color-{$number})";
                    case 'theme-mod':
                        return 'palette_color_' . $number;
                    case 'hex':
                    default:
                        return $color_value;
                }
            }
        }
        
        return null;
    }
}

if (!function_exists('cb_get_blocksy_color')) {
    /**
     * Get a color directly from Blocksy's color palette
     * 
     * @param int $position Blocksy color position (1-16)
     * @return string|null
     */
    function cb_get_blocksy_color($position) {
        $palette = get_theme_mod('colorPalette', []);
        $color_key = 'color' . $position;
        
        return isset($palette[$color_key]['color']) ? $palette[$color_key]['color'] : null;
    }
}

if (!function_exists('cb_is_villa_integrated')) {
    /**
     * Check if Villa ColorBook is integrated with Blocksy
     * 
     * @return bool
     */
    function cb_is_villa_integrated() {
        return get_theme_mod('villa_colorbook_integrated', false);
    }
}

if (!function_exists('cb_get_villa_colors')) {
    /**
     * Get all Villa colors from Blocksy theme mods
     * 
     * @return array
     */
    function cb_get_villa_colors() {
        $villa_colors = [];
        $theme_mods = get_theme_mods();
        
        foreach ($theme_mods as $mod_key => $mod_value) {
            if (strpos($mod_key, 'villa_color_') === 0) {
                $color_slug = str_replace(['villa_color_', '_'], ['', '-'], $mod_key);
                $villa_colors[$color_slug] = $mod_value;
            }
        }
        
        return $villa_colors;
    }
}

// Example usage in your blocks:
/*
// In your block.php file:
require_once get_stylesheet_directory() . '/src/helpers/colorbook-helper.php';

// Get Villa colors through Blocksy integration
$context['primary_color'] = cb_get_color('primary');                    // #5a7f80 from Blocksy
$context['secondary_css'] = cb_get_color('secondary', 'css-var');       // var(--color-secondary)
$context['palette_1'] = cb_get_palette_color(1);                       // Primary color from Blocksy palette
$context['blocksy_color_2'] = cb_get_blocksy_color(2);                 // Direct Blocksy palette access
$context['is_integrated'] = cb_is_villa_integrated();                   // Check integration status
$context['all_villa_colors'] = cb_get_villa_colors();                  // All Villa colors from Blocksy

// In your Twig templates:
<div style="background-color: {{ primary_color }};">
<div style="color: {{ secondary_css }};">
*/
