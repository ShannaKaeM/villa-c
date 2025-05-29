<?php
/**
 * Custom User Roles for Villa Community Portal
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom user roles for the portal system
 */
function carbon_register_portal_user_roles() {
    // Villa Owner Role
    add_role('villa_owner', 'Villa Owner', [
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'upload_files' => true,
        'view_portal' => true,
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
        'view_portal' => true,
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
        'view_portal' => true,
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
        'view_portal' => true,
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
        'view_portal' => true,
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
 * Add custom capabilities to existing roles
 */
function carbon_add_portal_capabilities() {
    // Add portal capabilities to Administrator
    $admin_role = get_role('administrator');
    if ($admin_role) {
        $admin_role->add_cap('view_portal');
        $admin_role->add_cap('manage_own_villa');
        $admin_role->add_cap('submit_tickets');
        $admin_role->add_cap('view_announcements');
        $admin_role->add_cap('access_documents');
        $admin_role->add_cap('manage_committees');
        $admin_role->add_cap('view_all_tickets');
        $admin_role->add_cap('manage_all_tickets');
        $admin_role->add_cap('create_announcements');
        $admin_role->add_cap('manage_roadmap');
        $admin_role->add_cap('view_financials');
        $admin_role->add_cap('manage_properties');
        $admin_role->add_cap('view_owner_profiles');
        $admin_role->add_cap('manage_maintenance');
        $admin_role->add_cap('manage_users');
    }
}

/**
 * Remove custom roles (for deactivation)
 */
function carbon_remove_portal_user_roles() {
    remove_role('villa_owner');
    remove_role('bod_member');
    remove_role('committee_member');
    remove_role('staff_member');
    remove_role('dvo_member');
}

// Hook to register roles on theme activation
add_action('after_switch_theme', 'carbon_register_portal_user_roles');
add_action('after_switch_theme', 'carbon_add_portal_capabilities');

// Register roles if they don't exist
add_action('init', function() {
    if (!get_role('villa_owner')) {
        carbon_register_portal_user_roles();
        carbon_add_portal_capabilities();
    }
});
