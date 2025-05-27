<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

$component = basename(dirname(__FILE__));
$category = basename(dirname(dirname(__FILE__)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->set_category('carbon-blocks-' . $category)
    ->add_fields(array(
        Field::make('text', 'section_title', __('Section Title'))
            ->set_default_value('Featured Properties'),
        
        Field::make('textarea', 'section_description', __('Section Description'))
            ->set_default_value('Discover our handpicked selection of premium vacation rentals in North Topsail Beach.')
            ->set_rows(3),
        
        Field::make('select', 'layout_style', __('Layout Style'))
            ->set_options(array(
                'grid' => 'Grid Layout',
                'list' => 'List Layout',
                'carousel' => 'Carousel Layout'
            ))
            ->set_default_value('grid'),
        
        Field::make('select', 'columns', __('Columns'))
            ->set_options(array(
                '2' => '2 Columns',
                '3' => '3 Columns', 
                '4' => '4 Columns'
            ))
            ->set_default_value('3')
            ->set_conditional_logic(array(
                array(
                    'field' => 'layout_style',
                    'value' => 'grid'
                )
            )),
        
        Field::make('text', 'properties_to_show', __('Number of Properties'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 1)
            ->set_attribute('max', 12)
            ->set_default_value(6),
        
        Field::make('multiselect', 'property_types', __('Property Types'))
            ->set_options(array(
                'house' => '🏠 House',
                'condo' => '🏢 Condo', 
                'cottage' => '🏡 Cottage'
            ))
            ->set_default_value(array('house', 'condo', 'cottage')),
        
        Field::make('multiselect', 'locations', __('Locations'))
            ->set_options(array(
                'north-topsail-beach' => '🌊 North Topsail Beach',
                'surf-city' => '📍 Surf City',
                'topsail-beach' => '🏖️ Topsail Beach'
            ))
            ->set_default_value(array('north-topsail-beach')),
        
        Field::make('checkbox', 'show_featured_only', __('Show Featured Properties Only'))
            ->set_default_value(false),
        
        Field::make('checkbox', 'show_amenities', __('Show Amenities'))
            ->set_default_value(true),
        
        Field::make('checkbox', 'show_price', __('Show Nightly Rate'))
            ->set_default_value(true),
        
        Field::make('checkbox', 'show_details', __('Show Property Details'))
            ->set_help_text('Bedrooms, bathrooms, max guests')
            ->set_default_value(true),
        
        Field::make('text', 'cta_text', __('Call to Action Text'))
            ->set_default_value('View Property'),
        
        Field::make('color', 'accent_color', __('Accent Color'))
            ->set_default_value('#5a7f80')
            ->set_help_text('Uses Villa primary color by default')
    ))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
