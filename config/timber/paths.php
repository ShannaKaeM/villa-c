<?php

/**
 * Timber paths configuration for Carbon Blocks.
 *
 * This file sets up the paths for Timber to locate Carbon Blocks templates.
 *
 * @package CarbonBlocks
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_filter('timber/locations', function ($paths) {
    $blocks_base_dir = get_template_directory() . '/carbon-blocks';
    $stylesheet_blocks_dir = get_stylesheet_directory() . '/carbon-blocks';
    
    // Initialize blocks array
    $paths['blocks'] = [];
    
    // Add main blocks directory first (for backward compatibility)
    $paths['blocks'][] = $blocks_base_dir;
    if ($stylesheet_blocks_dir !== $blocks_base_dir) {
        $paths['blocks'][] = $stylesheet_blocks_dir;
    }
    
    // Auto-discover category directories and add them to paths
    if (is_dir($blocks_base_dir)) {
        $category_dirs = glob($blocks_base_dir . '/*', GLOB_ONLYDIR);
        
        foreach ($category_dirs as $category_dir) {
            $paths['blocks'][] = $category_dir;
        }
    }
    
    // Do the same for child theme if different
    if ($stylesheet_blocks_dir !== $blocks_base_dir && is_dir($stylesheet_blocks_dir)) {
        $stylesheet_category_dirs = glob($stylesheet_blocks_dir . '/*', GLOB_ONLYDIR);
        
        foreach ($stylesheet_category_dirs as $category_dir) {
            $paths['blocks'][] = $category_dir;
        }
    }

    // Create a filter to allow themes to add their own block paths
    $custom_paths = apply_filters('carbon_blocks_paths', []);

    if (is_array($custom_paths) && ! empty($custom_paths)) {
        $paths['blocks'] = array_merge($paths['blocks'], $custom_paths);
    }

    return $paths;
});
