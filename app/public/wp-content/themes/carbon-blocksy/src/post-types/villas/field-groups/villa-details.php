<?php
/**
 * Villa Details Field Group - Community Management
 * Auto-discovered by Carbon Blocks Framework
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

use Carbon_Fields\Field;

// Get post type slug from parent directory (auto-discovery pattern)
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta_with_tabs($post_type_slug, 'Villa Details', [
    'basic_info' => [
        'label' => 'Basic Information',
        'fields' => [
            Field::make('text', 'villa_name', 'Villa Name')
                ->set_width(50)
                ->set_help_text('Display name for the property'),
            Field::make('text', 'unit_number', 'Unit Number')
                ->set_width(50)
                ->set_required(true)
                ->set_help_text('Unit identifier (e.g., 209A, 301B)'),
            Field::make('text', 'building', 'Building')
                ->set_width(50)
                ->set_help_text('Building name or number'),
            Field::make('text', 'floor', 'Floor')
                ->set_width(50)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'bedrooms', 'Bedrooms')
                ->set_width(33)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'bathrooms', 'Bathrooms')
                ->set_width(33)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'square_feet', 'Square Feet')
                ->set_width(34)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('select', 'property_type', 'Property Type')
                ->set_width(50)
                ->set_options([
                    'condo' => 'Condominium',
                    'townhouse' => 'Townhouse',
                    'villa' => 'Villa',
                    'penthouse' => 'Penthouse'
                ])
                ->set_default_value('condo'),
            Field::make('select', 'status', 'Property Status')
                ->set_width(50)
                ->set_options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'maintenance' => 'Under Maintenance',
                    'renovation' => 'Under Renovation',
                    'for_sale' => 'For Sale'
                ])
                ->set_default_value('active'),
            Field::make('checkbox', 'is_featured', 'Featured Property')
                ->set_help_text('Mark as featured property on website'),
        ]
    ],
    'ownership' => [
        'label' => 'Ownership & Management',
        'fields' => [
            Field::make('association', 'primary_owners', 'Primary Owners')
                ->set_types([
                    [
                        'type' => 'post',
                        'post_type' => 'owner-profiles',
                    ]
                ])
                ->set_max(2)
                ->set_help_text('Select 1-2 primary owners for this property'),
            Field::make('association', 'secondary_owners', 'Secondary Owners')
                ->set_types([
                    [
                        'type' => 'post',
                        'post_type' => 'owner-profiles',
                    ]
                ])
                ->set_help_text('Additional owners or family members with access'),
            Field::make('date', 'purchase_date', 'Purchase Date')
                ->set_width(50),
            Field::make('text', 'purchase_price', 'Purchase Price')
                ->set_width(50)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 0)
                ->set_help_text('Original purchase price (for HOA records)'),
            Field::make('select', 'ownership_type', 'Ownership Type')
                ->set_width(50)
                ->set_options([
                    'individual' => 'Individual',
                    'joint' => 'Joint Ownership',
                    'trust' => 'Trust',
                    'llc' => 'LLC',
                    'corporation' => 'Corporation'
                ])
                ->set_default_value('individual'),
            Field::make('text', 'ownership_percentage', 'Ownership Percentage')
                ->set_width(50)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 0)
                ->set_attribute('max', 100)
                ->set_default_value(100)
                ->set_help_text('Percentage of ownership (for shared properties)'),
        ]
    ],
    'location' => [
        'label' => 'Location & Address',
        'fields' => [
            Field::make('text', 'address', 'Street Address')
                ->set_help_text('Full street address'),
            Field::make('text', 'city', 'City')
                ->set_width(40)
                ->set_default_value('North Topsail Beach'),
            Field::make('text', 'state', 'State')
                ->set_width(30)
                ->set_default_value('NC'),
            Field::make('text', 'zip_code', 'ZIP Code')
                ->set_width(30),
            Field::make('text', 'latitude', 'Latitude')
                ->set_width(50)
                ->set_help_text('GPS coordinate for mapping'),
            Field::make('text', 'longitude', 'Longitude')
                ->set_width(50)
                ->set_help_text('GPS coordinate for mapping'),
            Field::make('textarea', 'location_notes', 'Location Notes')
                ->set_rows(3)
                ->set_help_text('Special location details, parking instructions, etc.'),
        ]
    ],
    'rental_management' => [
        'label' => 'Rental & Property Management',
        'fields' => [
            Field::make('checkbox', 'available_for_rental', 'Available for Rental')
                ->set_help_text('Property is available for short-term rental'),
            Field::make('text', 'rental_manager_name', 'Rental Manager Name')
                ->set_width(50)
                ->set_conditional_logic([
                    [
                        'field' => 'available_for_rental',
                        'value' => true,
                    ]
                ]),
            Field::make('text', 'rental_manager_phone', 'Rental Manager Phone')
                ->set_width(50)
                ->set_conditional_logic([
                    [
                        'field' => 'available_for_rental',
                        'value' => true,
                    ]
                ]),
            Field::make('text', 'rental_manager_email', 'Rental Manager Email')
                ->set_conditional_logic([
                    [
                        'field' => 'available_for_rental',
                        'value' => true,
                    ]
                ])
                ->set_attribute('type', 'email'),
            Field::make('text', 'booking_url', 'Booking URL')
                ->set_conditional_logic([
                    [
                        'field' => 'available_for_rental',
                        'value' => true,
                    ]
                ])
                ->set_help_text('Direct booking link (VRBO, Airbnb, etc.)'),
            Field::make('text', 'ical_url', 'iCal URL')
                ->set_conditional_logic([
                    [
                        'field' => 'available_for_rental',
                        'value' => true,
                    ]
                ])
                ->set_help_text('Calendar sync URL for availability'),
            Field::make('checkbox', 'owner_occupied', 'Owner Occupied')
                ->set_help_text('Property is owner\'s primary residence'),
            Field::make('select', 'occupancy_status', 'Current Occupancy')
                ->set_options([
                    'owner' => 'Owner Occupied',
                    'rental' => 'Short-term Rental',
                    'vacant' => 'Vacant',
                    'maintenance' => 'Maintenance/Renovation'
                ])
                ->set_default_value('owner'),
        ]
    ],
    'maintenance_tickets' => [
        'label' => 'Maintenance & Tickets',
        'fields' => [
            Field::make('textarea', 'maintenance_notes', 'Maintenance Notes')
                ->set_rows(4)
                ->set_help_text('Ongoing maintenance issues or special requirements'),
            Field::make('date', 'last_inspection', 'Last Inspection Date')
                ->set_width(50),
            Field::make('date', 'next_inspection', 'Next Inspection Due')
                ->set_width(50),
            Field::make('complex', 'maintenance_contacts', 'Maintenance Contacts')
                ->add_fields([
                    Field::make('text', 'contact_name', 'Contact Name'),
                    Field::make('text', 'contact_phone', 'Phone'),
                    Field::make('text', 'contact_email', 'Email')
                        ->set_attribute('type', 'email'),
                    Field::make('text', 'contact_type', 'Contact Type')
                        ->set_help_text('e.g., HVAC, Plumbing, Electrical, General')
                ])
                ->set_help_text('Preferred maintenance contacts for this property'),
            Field::make('checkbox', 'key_access_required', 'Key Access Required')
                ->set_help_text('Property requires key access for maintenance'),
            Field::make('textarea', 'access_instructions', 'Access Instructions')
                ->set_rows(3)
                ->set_conditional_logic([
                    [
                        'field' => 'key_access_required',
                        'value' => true,
                    ]
                ])
                ->set_help_text('Instructions for accessing the property'),
        ]
    ],
    'hoa_financial' => [
        'label' => 'HOA & Financial',
        'fields' => [
            Field::make('text', 'monthly_hoa_fee', 'Monthly HOA Fee')
                ->set_width(50)
                ->set_attribute('type', 'number')
                ->set_attribute('min', 0)
                ->set_help_text('Current monthly HOA fee amount'),
            Field::make('select', 'payment_status', 'Payment Status')
                ->set_width(50)
                ->set_options([
                    'current' => 'Current',
                    'late' => 'Late Payment',
                    'delinquent' => 'Delinquent',
                    'payment_plan' => 'Payment Plan'
                ])
                ->set_default_value('current'),
            Field::make('date', 'last_payment_date', 'Last Payment Date')
                ->set_width(50),
            Field::make('text', 'account_balance', 'Account Balance')
                ->set_width(50)
                ->set_attribute('type', 'number')
                ->set_help_text('Current account balance (negative = owed)'),
            Field::make('checkbox', 'special_assessment_pending', 'Special Assessment Pending')
                ->set_help_text('Property has pending special assessments'),
            Field::make('textarea', 'financial_notes', 'Financial Notes')
                ->set_rows(3)
                ->set_help_text('Payment arrangements, special circumstances, etc.'),
        ]
    ]
]);