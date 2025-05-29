<?php

// Load theme setup and dependencies
require_once get_stylesheet_directory() . '/src/config/setup.php';

// Load Portal System
require_once get_stylesheet_directory() . '/src/config/user_roles.php';
require_once get_stylesheet_directory() . '/src/config/sample_data_generator.php';

// Load ColorBook system
require_once get_stylesheet_directory() . '/inc/colorbook.php';

// Load CSV Importer
require_once get_stylesheet_directory() . '/src/import/villa-csv-importer.php';

// Enqueue theme integration CSS
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'colorbook-theme-integration',
        get_stylesheet_directory_uri() . '/assets/css/theme-integration.css',
        [],
        '1.0.0'
    );
});
