<?php

// Load theme setup and dependencies
require_once get_stylesheet_directory() . '/src/config/setup.php';

// Load DesignBook System (moved out of src to avoid Carbon Fields conflicts)
require_once get_stylesheet_directory() . '/designbook/helpers/designbook-helper.php';
require_once get_stylesheet_directory() . '/designbook/helpers/designbook-css.php';
require_once get_stylesheet_directory() . '/designbook/admin-pages/page.php';
require_once get_stylesheet_directory() . '/designbook/admin-pages/colorbook.php';
require_once get_stylesheet_directory() . '/designbook/admin-pages/textbook.php';

// Load CSV Importer
// require_once get_stylesheet_directory() . '/src/import/villa-csv-importer.php'; // File missing - commented out

// Enqueue theme integration CSS
add_action('wp_enqueue_scripts', function() {
    // Enqueue DesignBook CSS
    wp_enqueue_style(
        'designbook-styles',
        get_stylesheet_directory_uri() . '/designbook/styles/designbook.css',
        [],
        '1.0.0'
    );
});

// Output DesignBook CSS variables
add_action('wp_head', function() {
    if (db_is_enabled()) {
        echo "<style id='designbook-variables'>\n";
        echo db_generate_color_css();
        echo db_generate_typography_css();
        echo "</style>\n";
    }
}, 5);
