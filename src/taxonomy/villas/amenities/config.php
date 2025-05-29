<?php
/**
 * Amenities Taxonomy Configuration
 */

// Get taxonomy slug from directory name
$taxonomy_slug = basename(dirname(__FILE__));

// Get post types from parent directory name
$post_types = explode(',', basename(dirname(dirname(__FILE__))));

carbon_create_taxonomy($taxonomy_slug, $post_types, [
    'labels' => [
        'name' => 'Amenities',
        'singular_name' => 'Amenity',
        'search_items' => 'Search Amenities',
        'all_items' => 'All Amenities',
        'parent_item' => 'Parent Amenity',
        'parent_item_colon' => 'Parent Amenity:',
        'edit_item' => 'Edit Amenity',
        'update_item' => 'Update Amenity',
        'add_new_item' => 'Add New Amenity',
        'new_item_name' => 'New Amenity Name',
        'menu_name' => 'Amenities'
    ],
    'hierarchical' => true,
    'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => true,
    'show_in_rest' => true,
    'rewrite' => [
        'slug' => 'amenity',
        'with_front' => false
    ]
]);
