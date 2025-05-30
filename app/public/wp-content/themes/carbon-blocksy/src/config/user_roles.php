<?php
/**
 * Custom User Roles for Villa Community - FluentBoards Integration
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Register custom user roles for FluentBoards integration
 */
function carbon_register_villa_user_roles() {
    // Villa Owner Role
    add_role('villa_owner', 'Villa Owner', [
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'upload_files' => true,
        'manage_own_villa' => true,
        'submit_tickets' => true,
        'view_announcements' => true,
        'access_documents' => true,
    ]);

    // Board of Directors Member
    add_role('bod_member', 'Board of Directors (BOD)', [
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
        'upload_files' => true,
        'manage_own_villa' => true,
        'submit_tickets' => true,
        'view_announcements' => true,
        'access_documents' => true,
        'manage_committees' => true,
        'view_all_tickets' => true,
        'create_announcements' => true,
        'manage_roadmap' => true,
        'view_financials' => true,
    ]);

    // Committee Member
    add_role('committee_member', 'Committee Member', [
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'upload_files' => true,
        'manage_own_villa' => true,
        'submit_tickets' => true,
        'view_announcements' => true,
        'access_documents' => true,
        'manage_committee_projects' => true,
        'respond_to_tickets' => true,
        'contribute_roadmap' => true,
    ]);

    // Staff Member (Property Management)
    add_role('staff_member', 'Staff Member', [
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,
        'upload_files' => true,
        'view_announcements' => true,
        'access_documents' => true,
        'manage_all_tickets' => true,
        'manage_properties' => true,
        'create_announcements' => true,
        'view_owner_profiles' => true,
        'manage_maintenance' => true,
    ]);

    // Director of Villa Operations (DVO)
    add_role('dvo_member', 'Director of Villa Operations (DVO)', [
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'upload_files' => true,
        'view_announcements' => true,
        'access_documents' => true,
        'manage_all_tickets' => true,
        'manage_properties' => true,
        'create_announcements' => true,
        'view_owner_profiles' => true,
        'manage_maintenance' => true,
        'manage_committees' => true,
        'manage_roadmap' => true,
        'view_financials' => true,
        'manage_users' => true,
    ]);
}

/**
 * Add villa capabilities to Administrator role
 */
function carbon_add_villa_capabilities() {
    // Add villa capabilities to Administrator
    $admin_role = get_role('administrator');
    if ($admin_role) {
        // Add all villa-specific capabilities to admin
        $villa_caps = [
            'manage_own_villa', 'submit_tickets', 'view_announcements', 'access_documents',
            'manage_committees', 'view_all_tickets', 'create_announcements', 'manage_roadmap',
            'view_financials', 'manage_committee_projects', 'respond_to_tickets', 'contribute_roadmap',
            'manage_all_tickets', 'manage_properties', 'view_owner_profiles', 'manage_maintenance',
            'manage_users'
        ];
        
        foreach ($villa_caps as $cap) {
            $admin_role->add_cap($cap);
        }
    }
}

/**
 * Remove villa user roles (for theme deactivation)
 */
function carbon_remove_villa_user_roles() {
    remove_role('villa_owner');
    remove_role('bod_member');
    remove_role('committee_member');
    remove_role('staff_member');
    remove_role('dvo_member');
}

// Hook into theme activation
add_action('after_switch_theme', 'carbon_register_villa_user_roles');
add_action('after_switch_theme', 'carbon_add_villa_capabilities');

// Hook into theme deactivation
register_deactivation_hook(__FILE__, 'carbon_remove_villa_user_roles');

// Ensure roles exist if they don't
add_action('init', function() {
    if (!get_role('villa_owner')) {
        carbon_register_villa_user_roles();
        carbon_add_villa_capabilities();
    }
});
