<?php
/**
 * Owner Profile Details Field Group
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

carbon_create_post_meta_with_tabs($post_type_slug, 'Owner Profile Details', [
    'basic_info' => [
        'label' => 'Basic Information',
        'fields' => [
            Field::make('text', 'first_name', 'First Name')
                ->set_width(50)
                ->set_required(true),
            Field::make('text', 'last_name', 'Last Name')
                ->set_width(50)
                ->set_required(true),
            Field::make('email', 'email_address', 'Email Address')
                ->set_width(50)
                ->set_required(true),
            Field::make('text', 'phone_primary', 'Primary Phone')
                ->set_width(50)
                ->set_attribute('placeholder', '(910) 555-0123'),
            Field::make('text', 'phone_secondary', 'Secondary Phone')
                ->set_width(50)
                ->set_attribute('placeholder', '(910) 555-0123'),
            Field::make('date', 'move_in_date', 'Move-in Date')
                ->set_width(50),
            Field::make('select', 'owner_status', 'Owner Status')
                ->set_width(50)
                ->set_options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'pending' => 'Pending',
                    'former' => 'Former Owner'
                ])
                ->set_default_value('active'),
        ]
    ],
    'contact_info' => [
        'label' => 'Contact Information',
        'fields' => [
            Field::make('textarea', 'mailing_address', 'Mailing Address')
                ->set_rows(3)
                ->set_help_text('Full mailing address for correspondence'),
            Field::make('text', 'emergency_contact_name', 'Emergency Contact Name')
                ->set_width(50),
            Field::make('text', 'emergency_contact_phone', 'Emergency Contact Phone')
                ->set_width(50),
            Field::make('text', 'emergency_contact_relationship', 'Relationship')
                ->set_width(50),
            Field::make('select', 'preferred_contact_method', 'Preferred Contact Method')
                ->set_width(50)
                ->set_options([
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'portal' => 'Portal Messages',
                    'mail' => 'Physical Mail'
                ])
                ->set_default_value('email'),
            Field::make('checkbox', 'receive_newsletters', 'Receive Newsletters')
                ->set_option_value('yes'),
            Field::make('checkbox', 'receive_maintenance_alerts', 'Receive Maintenance Alerts')
                ->set_option_value('yes'),
            Field::make('checkbox', 'receive_emergency_notifications', 'Receive Emergency Notifications')
                ->set_option_value('yes')
                ->set_default_value(true),
        ]
    ],
    'villa_assignments' => [
        'label' => 'Villa Assignments',
        'fields' => [
            Field::make('association', 'owned_villas', 'Owned Villas')
                ->set_types([
                    [
                        'type' => 'post',
                        'post_type' => 'villas',
                    ]
                ])
                ->set_help_text('Select the villa units owned by this person'),
            Field::make('select', 'primary_residence', 'Primary Residence')
                ->set_options([
                    'yes' => 'Yes - This is their primary residence',
                    'no' => 'No - This is a secondary/vacation home'
                ])
                ->set_help_text('Is this their primary residence?'),
            Field::make('checkbox', 'rental_management', 'Rental Management')
                ->set_option_value('yes')
                ->set_help_text('Does this owner manage their unit as a rental?'),
            Field::make('text', 'rental_contact', 'Rental Management Contact')
                ->set_conditional_logic([
                    [
                        'field' => 'rental_management',
                        'value' => true,
                    ]
                ])
                ->set_help_text('Contact information for rental management'),
        ]
    ],
    'roles_committees' => [
        'label' => 'Roles & Committees',
        'fields' => [
            Field::make('association', 'linked_user', 'Linked WordPress User')
                ->set_types([
                    [
                        'type' => 'user',
                    ]
                ])
                ->set_max(1)
                ->set_help_text('Link this profile to a WordPress user account'),
            Field::make('multiselect', 'portal_roles', 'Portal Roles')
                ->set_options([
                    'villa_owner' => 'Villa Owner',
                    'bod_member' => 'Board of Directors',
                    'committee_member' => 'Committee Member',
                    'staff_member' => 'Staff Member',
                    'dvo_member' => 'Director of Villa Operations'
                ])
                ->set_help_text('Select all applicable roles for this person'),
            Field::make('text', 'board_position', 'Board Position')
                ->set_conditional_logic([
                    [
                        'field' => 'portal_roles',
                        'value' => 'bod_member',
                        'compare' => 'IN',
                    ]
                ])
                ->set_help_text('Specific position on the Board of Directors'),
            Field::make('date', 'term_start_date', 'Term Start Date')
                ->set_width(50)
                ->set_conditional_logic([
                    [
                        'field' => 'portal_roles',
                        'value' => 'bod_member',
                        'compare' => 'IN',
                    ]
                ]),
            Field::make('date', 'term_end_date', 'Term End Date')
                ->set_width(50)
                ->set_conditional_logic([
                    [
                        'field' => 'portal_roles',
                        'value' => 'bod_member',
                        'compare' => 'IN',
                    ]
                ]),
            Field::make('textarea', 'bio', 'Bio/Description')
                ->set_rows(4)
                ->set_help_text('Brief biography or description for board members and committee chairs'),
        ]
    ],
    'portal_preferences' => [
        'label' => 'Portal Preferences',
        'fields' => [
            Field::make('checkbox', 'portal_access_enabled', 'Portal Access Enabled')
                ->set_option_value('yes')
                ->set_default_value(true)
                ->set_help_text('Enable or disable portal access for this user'),
            Field::make('select', 'dashboard_layout', 'Dashboard Layout')
                ->set_options([
                    'standard' => 'Standard Layout',
                    'compact' => 'Compact Layout',
                    'detailed' => 'Detailed Layout'
                ])
                ->set_default_value('standard'),
            Field::make('set', 'notification_preferences', 'Notification Preferences')
                ->set_options([
                    'announcements' => 'Community Announcements',
                    'maintenance' => 'Maintenance Notifications',
                    'tickets' => 'Ticket Updates',
                    'events' => 'Community Events',
                    'financial' => 'Financial Updates',
                    'emergency' => 'Emergency Alerts'
                ])
                ->set_default_value(['announcements', 'maintenance', 'emergency'])
                ->set_help_text('Select which types of notifications to receive'),
            Field::make('textarea', 'notes', 'Internal Notes')
                ->set_rows(3)
                ->set_help_text('Internal notes for staff use only'),
        ]
    ]
]);
