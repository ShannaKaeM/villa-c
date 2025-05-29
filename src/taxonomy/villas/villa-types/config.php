<?php
/**
 * Villa Types Taxonomy Configuration
 */

// Get taxonomy slug from directory name
$taxonomy_slug = basename(dirname(__FILE__));

// Get post types from parent directory name
$post_types = explode(',', basename(dirname(dirname(__FILE__))));

carbon_create_taxonomy($taxonomy_slug, $post_types, [
    'labels' => [
        'name' => 'Villa Types',
        'singular_name' => 'Villa Type',
        'search_items' => 'Search Villa Types',
        'all_items' => 'All Villa Types',
        'parent_item' => 'Parent Villa Type',
        'parent_item_colon' => 'Parent Villa Type:',
        'edit_item' => 'Edit Villa Type',
        'update_item' => 'Update Villa Type',
        'add_new_item' => 'Add New Villa Type',
        'new_item_name' => 'New Villa Type Name',
        'menu_name' => 'Villa Types'
    ],
    'hierarchical' => true,
    'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => false,
    'show_in_rest' => true,
    'rewrite' => [
        'slug' => 'villa-type',
        'with_front' => false
    ]
]);
