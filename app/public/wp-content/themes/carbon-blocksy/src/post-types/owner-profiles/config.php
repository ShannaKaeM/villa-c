<?php
/**
 * Owner Profiles Post Type Configuration
 * Auto-discovered by Carbon Blocks Framework
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get post type slug from directory name (auto-discovery pattern)
$post_type_slug = basename(dirname(__FILE__));

carbon_create_post_type($post_type_slug, [
    'labels' => [
        'name' => 'Owner Profiles',
        'singular_name' => 'Owner Profile',
        'menu_name' => 'Owner Profiles',
        'add_new' => 'Add New Profile',
        'add_new_item' => 'Add New Owner Profile',
        'edit_item' => 'Edit Owner Profile',
        'new_item' => 'New Owner Profile',
        'view_item' => 'View Owner Profile',
        'search_items' => 'Search Owner Profiles',
        'not_found' => 'No owner profiles found',
        'not_found_in_trash' => 'No owner profiles found in trash',
        'all_items' => 'All Owner Profiles',
        'archives' => 'Owner Profile Archives',
        'attributes' => 'Owner Profile Attributes',
        'insert_into_item' => 'Insert into owner profile',
        'uploaded_to_this_item' => 'Uploaded to this owner profile',
    ],
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => false,
    'show_in_admin_bar' => false,
    'show_in_rest' => false,
    'has_archive' => false,
    'exclude_from_search' => true,
    'menu_icon' => 'dashicons-admin-users',
    'menu_position' => 25,
    'supports' => ['title'],
    'capabilities' => [
        'edit_post' => 'edit_owner_profiles',
        'read_post' => 'read_owner_profiles',
        'delete_post' => 'delete_owner_profiles',
        'edit_posts' => 'edit_owner_profiles',
        'edit_others_posts' => 'edit_others_owner_profiles',
        'publish_posts' => 'publish_owner_profiles',
        'read_private_posts' => 'read_private_owner_profiles',
        'delete_posts' => 'delete_owner_profiles',
        'delete_private_posts' => 'delete_private_owner_profiles',
        'delete_published_posts' => 'delete_published_owner_profiles',
        'delete_others_posts' => 'delete_others_owner_profiles',
        'edit_private_posts' => 'edit_private_owner_profiles',
        'edit_published_posts' => 'edit_published_owner_profiles',
    ],
    'map_meta_cap' => true,
]);
