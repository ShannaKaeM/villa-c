<?php

// Load theme setup and dependencies
require_once get_stylesheet_directory() . '/src/config/setup.php';

// Load ColorBook system
require_once get_stylesheet_directory() . '/inc/colorbook.php';

// Load Property Filter Widget
require_once get_stylesheet_directory() . '/inc/widgets/property-filter-widget.php';

// Load Property Sidebar Integration
require_once get_stylesheet_directory() . '/inc/property-sidebar.php';

// Enqueue theme integration CSS
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'colorbook-theme-integration',
        get_stylesheet_directory_uri() . '/assets/css/theme-integration.css',
        [],
        '1.0.0'
    );
});
