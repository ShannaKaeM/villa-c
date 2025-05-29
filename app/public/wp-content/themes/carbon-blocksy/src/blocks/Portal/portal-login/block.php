<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory  
$category = basename(dirname(dirname(__FILE__)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields(array(
        Field::make('text', 'login_title', 'Login Title')
            ->set_default_value('Portal Login'),
        Field::make('textarea', 'login_message', 'Login Message')
            ->set_default_value('Please log in to access the Villa Capriani Owner Portal.'),
        Field::make('text', 'redirect_url', 'Redirect After Login')
            ->set_help_text('URL to redirect to after successful login (optional)'),
    ))
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
