<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory  
$category = basename(dirname(dirname(__FILE__)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields([
        Field::make('text', 'title', __('Section Title'))
            ->set_default_value('Featured Villas'),
        Field::make('textarea', 'subtitle', __('Section Subtitle'))
            ->set_default_value('Discover our handpicked collection of luxury vacation rentals'),
        Field::make('select', 'columns_lg', __('Columns (Large Screens)'))
            ->set_options([
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ])
            ->set_default_value('3'),
        Field::make('select', 'columns_md', __('Columns (Medium Screens)'))
            ->set_options([
                '1' => '1 Column',
                '2' => '2 Columns',
                '3' => '3 Columns'
            ])
            ->set_default_value('2'),
        Field::make('select', 'columns_sm', __('Columns (Small Screens)'))
            ->set_options([
                '1' => '1 Column',
                '2' => '2 Columns'
            ])
            ->set_default_value('1'),
        Field::make('text', 'max_width', __('Max Width'))
            ->set_default_value('1280px')
            ->set_help_text('Maximum width of the grid container'),
        Field::make('text', 'villas_limit', __('Number of Villas'))
            ->set_default_value('6')
            ->set_help_text('How many villas to display'),
        Field::make('checkbox', 'show_view_all_button', __('Show View All Button'))
            ->set_default_value(true),
        Field::make('text', 'view_all_text', __('View All Button Text'))
            ->set_default_value('View All Villas')
            ->set_conditional_logic([
                [
                    'field' => 'show_view_all_button',
                    'value' => true
                ]
            ]),
        Field::make('text', 'view_all_url', __('View All Button URL'))
            ->set_default_value('/villas')
            ->set_conditional_logic([
                [
                    'field' => 'show_view_all_button',
                    'value' => true
                ]
            ])
    ])
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
