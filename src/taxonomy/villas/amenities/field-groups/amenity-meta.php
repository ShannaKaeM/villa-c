<?php
/**
 * Amenity Meta Fields
 */

use Carbon_Fields\Field;

// Get taxonomy slug from parent directory
$taxonomy_slug = basename(dirname(dirname(__FILE__)));

carbon_create_taxonomy_meta($taxonomy_slug, 'Amenity Details', [
    Field::make('text', 'icon', 'Icon')
        ->set_help_text('Emoji or icon for this amenity (e.g., 🏊, 🌊, 🐶, 🛁)')
        ->set_default_value('✨'),
    Field::make('textarea', 'description', 'Description')
        ->set_help_text('Brief description of this amenity')
        ->set_rows(3),
    Field::make('text', 'display_order', 'Display Order')
        ->set_attribute('type', 'number')
        ->set_attribute('min', 1)
        ->set_default_value(1)
        ->set_help_text('Order for displaying in lists (lower numbers first)'),
    Field::make('checkbox', 'is_featured', 'Featured Amenity')
        ->set_help_text('Show this amenity prominently in filters'),
    Field::make('select', 'category', 'Amenity Category')
        ->set_options([
            'comfort' => 'Comfort & Convenience',
            'recreation' => 'Recreation & Entertainment',
            'location' => 'Location & Views',
            'services' => 'Services & Support',
            'accessibility' => 'Accessibility'
        ])
        ->set_default_value('comfort')
        ->set_help_text('Group amenities by category for better organization')
]);
