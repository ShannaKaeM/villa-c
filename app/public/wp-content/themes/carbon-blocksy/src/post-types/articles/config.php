<?php
/**
 * Articles Custom Post Type Configuration
 *
 * @package CarbonBlocks
 */

if (! defined('ABSPATH')) {
    exit;
}

// Get post type slug from directory name
$post_type_slug = basename(dirname(__FILE__));

carbon_create_post_type($post_type_slug, [
    'labels' => [
        'name' => 'Articles',
        'singular_name' => 'Article',
        'add_new' => 'Add New Article',
        'add_new_item' => 'Add New Article',
        'edit_item' => 'Edit Article',
        'new_item' => 'New Article',
        'view_item' => 'View Article',
        'search_items' => 'Search Articles',
        'not_found' => 'No articles found',
        'not_found_in_trash' => 'No articles found in trash',
        'all_items' => 'All Articles',
        'menu_name' => 'Articles',
        'name_admin_bar' => 'Article'
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-admin-post',
    'menu_position' => 5,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments'],
    'rewrite' => ['slug' => 'articles'],
    'show_in_rest' => true
]);
