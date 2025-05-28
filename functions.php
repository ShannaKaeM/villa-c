<?php

// Load theme setup and dependencies
require_once get_stylesheet_directory() . '/src/config/setup.php';

// Load Design System Menu (must be loaded first)
require_once get_stylesheet_directory() . '/inc/design-system-menu.php';

// Load ColorBook system
require_once get_stylesheet_directory() . '/inc/colorbook.php';

// Load TextBook system
require_once get_stylesheet_directory() . '/inc/textbook.php';

// Load UiBook system
require_once get_stylesheet_directory() . '/inc/uibook.php';

// Load LayoutBook system
require_once get_stylesheet_directory() . '/inc/layoutbook.php';

// Load HeroBook system
require_once get_stylesheet_directory() . '/inc/herobook.php';

// Load helper functions
require_once get_stylesheet_directory() . '/src/helpers/colorbook-helper.php';
require_once get_stylesheet_directory() . '/src/helpers/textbook-helper.php';
require_once get_stylesheet_directory() . '/src/helpers/uibook-helper.php';
require_once get_stylesheet_directory() . '/src/helpers/layoutbook-helper.php';
require_once get_stylesheet_directory() . '/src/helpers/herobook-helper.php';

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
