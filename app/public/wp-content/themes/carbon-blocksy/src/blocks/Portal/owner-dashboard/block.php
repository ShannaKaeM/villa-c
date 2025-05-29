<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory  
$category = basename(dirname(dirname(__FILE__)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields(array(
        Field::make('text', 'portal_title', 'Portal Title')
            ->set_default_value('Villa Capriani Owner Portal'),
        Field::make('textarea', 'welcome_message', 'Welcome Message')
            ->set_default_value('Welcome to your owner portal. Access your property information, submit tickets, and stay connected with the community.'),
        Field::make('checkbox', 'show_login_form', 'Show Login Form for Non-Authenticated Users')
            ->set_default_value(true),
    ))
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
