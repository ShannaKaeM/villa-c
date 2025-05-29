<?php
/**
 * Villa Details Field Group with Tabs
 */

use Carbon_Fields\Field;

// Get post type slug from parent directory
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta_with_tabs($post_type_slug, 'Villa Details', [
    'basic_info' => [
        'title' => 'Basic Information',
        'fields' => [
            Field::make('text', 'villa_name', 'Villa Name')
                ->set_help_text('Display name for the property'),
            Field::make('text', 'unit_number', 'Unit Number')
                ->set_help_text('Unit identifier (e.g., 209A, 301B)'),
            Field::make('text', 'nightly_rate', 'Nightly Rate')
                ->set_help_text('Price per night in USD')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 0),
            Field::make('text', 'bedrooms', 'Bedrooms')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'bathrooms', 'Bathrooms')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'max_guests', 'Max Guests')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('checkbox', 'is_featured', 'Featured Property')
                ->set_help_text('Mark as featured property'),
            Field::make('select', 'status', 'Property Status')
                ->set_options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'maintenance' => 'Under Maintenance'
                ])
                ->set_default_value('draft')
        ]
    ],
    'location' => [
        'title' => 'Location & Address',
        'fields' => [
            Field::make('text', 'address', 'Street Address'),
            Field::make('text', 'city', 'City')
                ->set_default_value('North Topsail Beach'),
            Field::make('text', 'state', 'State')
                ->set_default_value('NC'),
            Field::make('text', 'zip_code', 'ZIP Code'),
            Field::make('text', 'latitude', 'Latitude')
                ->set_help_text('GPS coordinate for mapping'),
            Field::make('text', 'longitude', 'Longitude')
                ->set_help_text('GPS coordinate for mapping')
        ]
    ],
    'booking' => [
        'title' => 'Booking & Availability',
        'fields' => [
            Field::make('text', 'booking_url', 'Booking URL')
                ->set_help_text('Direct booking link'),
            Field::make('text', 'ical_url', 'iCal URL')
                ->set_help_text('Calendar sync URL'),
            Field::make('checkbox', 'has_direct_booking', 'Direct Booking Available')
                ->set_help_text('Property accepts direct bookings'),
            Field::make('textarea', 'booking_notes', 'Booking Notes')
                ->set_help_text('Special instructions or requirements')
        ]
    ],
    'amenities' => [
        'title' => 'Amenities & Features',
        'fields' => [
            Field::make('checkbox', 'pool', 'Pool')
                ->set_help_text('🏊 Swimming pool access'),
            Field::make('checkbox', 'ocean_view', 'Ocean View')
                ->set_help_text('🌊 Ocean views from property'),
            Field::make('checkbox', 'pet_friendly', 'Pet Friendly')
                ->set_help_text('🐶 Pets allowed'),
            Field::make('checkbox', 'hot_tub', 'Hot Tub')
                ->set_help_text('🛁 Hot tub/spa available'),
            Field::make('checkbox', 'wifi', 'WiFi')
                ->set_help_text('📶 Wireless internet'),
            Field::make('checkbox', 'parking', 'Parking')
                ->set_help_text('🚗 Parking available'),
            Field::make('checkbox', 'kitchen', 'Full Kitchen')
                ->set_help_text('🍳 Complete kitchen facilities'),
            Field::make('checkbox', 'beach_access', 'Beach Access')
                ->set_help_text('🏖️ Direct beach access'),
            Field::make('set', 'additional_amenities', 'Additional Amenities')
                ->set_options([
                    'concierge' => 'Concierge Service',
                    'cleaning' => 'Housekeeping',
                    'laundry' => 'Laundry Service',
                    'security' => '24/7 Security',
                    'transport' => 'Airport Transfer',
                    'balcony' => 'Private Balcony',
                    'bbq' => 'BBQ/Grill',
                    'fireplace' => 'Fireplace'
                ])
        ]
    ]
]);