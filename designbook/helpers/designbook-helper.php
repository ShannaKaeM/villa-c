<?php
/**
 * DesignBook Helper Functions
 * Simple data access for design system
 */

/**
 * Get a color by slug
 */
function db_get_color($slug, $format = 'hex') {
    $colors = carbon_get_theme_option('db_colors');
    
    if (!$colors) return null;
    
    foreach ($colors as $color) {
        if ($color['slug'] === $slug) {
            return $color['hex'];
        }
    }
    
    return null;
}

/**
 * Get all colors
 */
function db_get_colors() {
    return carbon_get_theme_option('db_colors') ?: [];
}

/**
 * Get a font family by name
 */
function db_get_font($name) {
    $fonts = carbon_get_theme_option('db_fonts');
    
    if (!$fonts) return null;
    
    foreach ($fonts as $font) {
        if ($font['name'] === $name) {
            return $font['stack'];
        }
    }
    
    return null;
}

/**
 * Get all fonts
 */
function db_get_fonts() {
    return carbon_get_theme_option('db_fonts') ?: [];
}

/**
 * Check if DesignBook is enabled
 */
function db_is_enabled() {
    return carbon_get_theme_option('db_enabled');
}

/**
 * Sync DesignBook data to theme.json
 */
function db_sync_to_theme_json() {
    if (!db_is_enabled()) {
        return;
    }
    
    $theme_json_path = get_stylesheet_directory() . '/theme.json';
    
    // Read existing theme.json
    if (file_exists($theme_json_path)) {
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
    } else {
        return; // Don't create theme.json if it doesn't exist
    }
    
    // Sync colors to theme.json
    $colors = db_get_colors();
    if ($colors && !empty($colors)) {
        if (!isset($theme_json['settings'])) {
            $theme_json['settings'] = [];
        }
        if (!isset($theme_json['settings']['color'])) {
            $theme_json['settings']['color'] = [];
        }
        
        $theme_json['settings']['color']['palette'] = [];
        foreach ($colors as $color) {
            if (!empty($color['slug']) && !empty($color['hex'])) {
                $theme_json['settings']['color']['palette'][] = [
                    'slug' => $color['slug'],
                    'color' => $color['hex'],
                    'name' => $color['name'] ?: ucfirst(str_replace('-', ' ', $color['slug']))
                ];
            }
        }
    }
    
    // Sync fonts to theme.json
    $fonts = db_get_fonts();
    if ($fonts && !empty($fonts)) {
        if (!isset($theme_json['settings']['typography'])) {
            $theme_json['settings']['typography'] = [];
        }
        
        $theme_json['settings']['typography']['fontFamilies'] = [];
        foreach ($fonts as $font) {
            if (!empty($font['name']) && !empty($font['stack'])) {
                $theme_json['settings']['typography']['fontFamilies'][] = [
                    'slug' => strtolower(str_replace(' ', '-', $font['name'])),
                    'fontFamily' => $font['stack'],
                    'name' => $font['name']
                ];
            }
        }
    }
    
    // Write updated theme.json
    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Hook to sync when DesignBook options are saved
add_action('carbon_fields_theme_options_container_saved', 'db_sync_to_theme_json');
