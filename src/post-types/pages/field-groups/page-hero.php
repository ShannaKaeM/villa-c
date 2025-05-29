<?php
/**
 * Page Hero Fields for Pages
 */

use Carbon_Fields\Field;

// Get post type slug from parent directory
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta($post_type_slug, 'Page Hero', [
    // Hero Text Elements
    Field::make('text', 'page_hero_pretitle', 'Hero Pretitle')
        ->set_help_text('Small text above the main title (e.g., "Welcome to Villa Community")')
        ->set_default_value('Welcome to Villa Community'),
    
    Field::make('text', 'page_hero_title', 'Hero Title')
        ->set_help_text('Main hero title. Leave blank to use page title.')
        ->set_default_value('Luxury Villa Rentals'),
    
    Field::make('text', 'page_hero_subtitle', 'Hero Subtitle')
        ->set_help_text('Secondary title below the main title')
        ->set_default_value('Experience Paradise'),
    
    Field::make('textarea', 'page_hero_description', 'Hero Description')
        ->set_help_text('Description text for the page hero. Leave blank to use page excerpt.')
        ->set_rows(3)
        ->set_default_value('Discover our collection of stunning villas in the most beautiful destinations. Perfect for your next getaway.'),
    
    // Hero Layout & Styling
    Field::make('image', 'page_hero_image', 'Hero Background Image')
        ->set_help_text('Background image for the page hero section.')
        ->set_value_type('url'),
    
    Field::make('color', 'page_hero_overlay_color', 'Hero Overlay Color')
        ->set_help_text('Optional overlay color for better text readability')
        ->set_default_value('#000000'),
    
    Field::make('text', 'page_hero_overlay_opacity', 'Hero Overlay Opacity')
        ->set_help_text('Overlay opacity (0.0 to 1.0)')
        ->set_default_value('0.5')
        ->set_attribute('type', 'number')
        ->set_attribute('step', '0.1')
        ->set_attribute('min', '0')
        ->set_attribute('max', '1'),
    
    Field::make('select', 'page_hero_height', 'Hero Height')
        ->set_help_text('Height of the hero section')
        ->set_options([
            'sm' => 'Small',
            'md' => 'Medium', 
            'lg' => 'Large',
            'xl' => 'Extra Large',
            'xxl' => 'XXL',
            'xxxl' => 'XXXL',
            'full' => 'Full Screen'
        ])
        ->set_default_value('lg'),
    
    Field::make('select', 'page_hero_width', 'Hero Width')
        ->set_help_text('Container width for the hero content')
        ->set_options([
            'content' => 'Content Width',
            'full' => 'Full Width'
        ])
        ->set_default_value('content'),
]);
