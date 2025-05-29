<?php
/**
 * HomeHero Block Cleanup Script
 * 
 * This script removes all old villa-cards data from the home page
 * and resets the HomeHero block to use the new hero-cards system
 * 
 * Run this script once to clean up after the block structure changes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // If running from command line, define WordPress constants
    require_once(dirname(__FILE__) . '/../../../../wp-config.php');
}

function cleanup_home_hero_data() {
    echo "🧹 Starting HomeHero block cleanup...\n";
    
    // Get the home page
    $home_page = get_option('page_on_front');
    if (!$home_page) {
        $home_page = get_option('page_for_posts');
    }
    
    if (!$home_page) {
        echo "❌ Could not find home page\n";
        return false;
    }
    
    echo "🏠 Found home page ID: {$home_page}\n";
    
    // Get all meta keys for this page
    $meta_keys = get_post_meta($home_page);
    $cleaned_keys = [];
    
    foreach ($meta_keys as $key => $value) {
        // Remove old villa-cards related fields
        if (strpos($key, 'villa_cards') !== false || 
            strpos($key, 'villa_count') !== false ||
            strpos($key, '_villa_cards') !== false ||
            strpos($key, '_villa_count') !== false) {
            
            delete_post_meta($home_page, $key);
            $cleaned_keys[] = $key;
            echo "🗑️  Removed: {$key}\n";
        }
        
        // Update right_content_type if it's set to villa-cards
        if ($key === '_right_content_type' || $key === 'right_content_type') {
            $current_value = get_post_meta($home_page, $key, true);
            if ($current_value === 'villa-cards') {
                update_post_meta($home_page, $key, 'hero-cards');
                echo "🔄 Updated {$key}: villa-cards → hero-cards\n";
            }
        }
    }
    
    // Clean up any Carbon Fields data
    $carbon_keys = [];
    foreach ($meta_keys as $key => $value) {
        if (strpos($key, '_villa') !== false && strpos($key, 'carbon') !== false) {
            delete_post_meta($home_page, $key);
            $carbon_keys[] = $key;
        }
    }
    
    if (!empty($carbon_keys)) {
        echo "🧼 Cleaned Carbon Fields data: " . count($carbon_keys) . " keys\n";
    }
    
    echo "✅ Cleanup complete!\n";
    echo "📝 Cleaned " . count($cleaned_keys) . " old villa-cards fields\n";
    echo "🎯 Home page is now ready for new hero-cards configuration\n\n";
    
    echo "Next steps:\n";
    echo "1. Go to WordPress Admin > Pages > Edit Home Page\n";
    echo "2. Find the HomeHero block\n";
    echo "3. Add new hero cards using the updated interface\n";
    echo "4. The 'Right Content Type' should now be set to 'Hero Cards'\n";
    
    return true;
}

// Run the cleanup if this file is executed directly
if (php_sapi_name() === 'cli' || (isset($_GET['cleanup']) && $_GET['cleanup'] === 'hero')) {
    cleanup_home_hero_data();
}

// Also provide a WordPress admin function
function add_hero_cleanup_admin_notice() {
    if (isset($_GET['cleanup_hero']) && $_GET['cleanup_hero'] === 'run') {
        cleanup_home_hero_data();
        echo '<div class="notice notice-success"><p>HomeHero block cleanup completed!</p></div>';
    }
}
add_action('admin_notices', 'add_hero_cleanup_admin_notice');

// Add cleanup link to admin bar for convenience
function add_hero_cleanup_admin_bar($wp_admin_bar) {
    if (current_user_can('manage_options')) {
        $wp_admin_bar->add_node([
            'id' => 'cleanup-hero',
            'title' => 'Cleanup HomeHero',
            'href' => admin_url('?cleanup_hero=run'),
            'meta' => ['title' => 'Clean up old HomeHero block data']
        ]);
    }
}
add_action('admin_bar_menu', 'add_hero_cleanup_admin_bar', 100);
?>
