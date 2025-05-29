<?php
/**
 * Debug script to check DesignBook registration
 * Run this from WordPress admin or via WP-CLI
 */

// Check if Carbon Fields is loaded
if (class_exists('Carbon_Fields\Container')) {
    echo "✅ Carbon Fields is loaded\n";
} else {
    echo "❌ Carbon Fields is NOT loaded\n";
}

// Check if DesignBook functions exist
if (function_exists('register_designbook_admin_page')) {
    echo "✅ register_designbook_admin_page function exists\n";
} else {
    echo "❌ register_designbook_admin_page function does NOT exist\n";
}

if (function_exists('db_is_enabled')) {
    echo "✅ db_is_enabled function exists\n";
} else {
    echo "❌ db_is_enabled function does NOT exist\n";
}

// Check if DesignBook files exist
$designbook_admin_file = get_stylesheet_directory() . '/designbook/admin-pages/page.php';
if (file_exists($designbook_admin_file)) {
    echo "✅ DesignBook admin page file exists at: $designbook_admin_file\n";
} else {
    echo "❌ DesignBook admin page file does NOT exist at: $designbook_admin_file\n";
}

// Check current theme
echo "Current theme: " . get_stylesheet() . "\n";
echo "Theme directory: " . get_stylesheet_directory() . "\n";

// List all admin menu items to see if DesignBook appears
global $menu, $submenu;
echo "\n=== Admin Menu Items ===\n";
foreach ($menu as $item) {
    if (isset($item[0]) && $item[0]) {
        echo "Menu: " . strip_tags($item[0]) . " (slug: " . $item[2] . ")\n";
    }
}
