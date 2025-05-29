<?php
/**
 * User Role Switcher for Portal Testing
 * Allows administrators to temporarily switch roles for testing
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add role switcher to admin bar
 */
function carbon_add_role_switcher_to_admin_bar($wp_admin_bar) {
    if (!current_user_can('administrator')) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $current_role = carbon_get_current_test_role();
    
    // Main switcher menu
    $wp_admin_bar->add_menu([
        'id' => 'portal-role-switcher',
        'title' => '🎭 Role: ' . ($current_role ? ucwords(str_replace('_', ' ', $current_role)) : 'Administrator'),
        'href' => '#',
    ]);
    
    // Portal roles submenu
    $portal_roles = [
        'administrator' => 'Administrator (Default)',
        'villa_owner' => 'Villa Owner',
        'bod_member' => 'Board of Directors',
        'committee_member' => 'Committee Member',
        'staff_member' => 'Staff Member',
        'dvo_member' => 'Director of Villa Operations'
    ];
    
    foreach ($portal_roles as $role_key => $role_label) {
        $is_current = ($current_role === $role_key || (!$current_role && $role_key === 'administrator'));
        
        $wp_admin_bar->add_menu([
            'parent' => 'portal-role-switcher',
            'id' => 'switch-to-' . $role_key,
            'title' => ($is_current ? '✓ ' : '') . $role_label,
            'href' => wp_nonce_url(
                admin_url('admin.php?action=carbon_switch_role&role=' . $role_key),
                'carbon_switch_role'
            ),
        ]);
    }
}
add_action('admin_bar_menu', 'carbon_add_role_switcher_to_admin_bar', 100);

/**
 * Handle role switching
 */
function carbon_handle_role_switch() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'carbon_switch_role') {
        return;
    }
    
    if (!current_user_can('administrator')) {
        wp_die('You do not have permission to switch roles.');
    }
    
    if (!wp_verify_nonce($_GET['_wpnonce'], 'carbon_switch_role')) {
        wp_die('Security check failed.');
    }
    
    $role = sanitize_text_field($_GET['role']);
    $valid_roles = ['administrator', 'villa_owner', 'bod_member', 'committee_member', 'staff_member', 'dvo_member'];
    
    if (!in_array($role, $valid_roles)) {
        wp_die('Invalid role specified.');
    }
    
    // Store the test role in session/transient
    if ($role === 'administrator') {
        delete_user_meta(get_current_user_id(), '_carbon_test_role');
        delete_user_meta(get_current_user_id(), '_carbon_test_profile');
    } else {
        update_user_meta(get_current_user_id(), '_carbon_test_role', $role);
        
        // Create or find a test profile for this role
        $test_profile = carbon_get_or_create_test_profile($role);
        if ($test_profile) {
            update_user_meta(get_current_user_id(), '_carbon_test_profile', $test_profile);
        }
    }
    
    // Redirect back to the referring page or dashboard
    $redirect_url = wp_get_referer() ?: admin_url();
    wp_redirect($redirect_url);
    exit;
}
add_action('admin_init', 'carbon_handle_role_switch');

/**
 * Get current test role
 */
function carbon_get_current_test_role() {
    if (!current_user_can('administrator')) {
        return false;
    }
    
    return get_user_meta(get_current_user_id(), '_carbon_test_role', true);
}

/**
 * Get current test profile
 */
function carbon_get_current_test_profile() {
    if (!current_user_can('administrator')) {
        return false;
    }
    
    return get_user_meta(get_current_user_id(), '_carbon_test_profile', true);
}

/**
 * Override user roles for portal access when testing
 */
function carbon_override_user_roles_for_testing($roles, $user_id) {
    if ($user_id != get_current_user_id() || !current_user_can('administrator')) {
        return $roles;
    }
    
    $test_role = carbon_get_current_test_role();
    if ($test_role && $test_role !== 'administrator') {
        return [$test_role];
    }
    
    return $roles;
}
add_filter('carbon_get_user_roles', 'carbon_override_user_roles_for_testing', 10, 2);

/**
 * Override owner profile lookup when testing
 */
function carbon_override_owner_profile_for_testing($profile, $user_id) {
    if ($user_id != get_current_user_id() || !current_user_can('administrator')) {
        return $profile;
    }
    
    $test_profile_id = carbon_get_current_test_profile();
    if ($test_profile_id) {
        return get_post($test_profile_id);
    }
    
    return $profile;
}
add_filter('carbon_get_user_owner_profile', 'carbon_override_owner_profile_for_testing', 10, 2);

/**
 * Create or find a test profile for the role
 */
function carbon_get_or_create_test_profile($role) {
    // Look for existing test profiles
    $existing_profiles = get_posts([
        'post_type' => 'owner-profiles',
        'meta_query' => [
            [
                'key' => '_carbon_test_profile_role',
                'value' => $role,
                'compare' => '='
            ]
        ],
        'posts_per_page' => 1
    ]);
    
    if (!empty($existing_profiles)) {
        return $existing_profiles[0]->ID;
    }
    
    // Create a new test profile
    $role_names = [
        'villa_owner' => 'Test Villa Owner',
        'bod_member' => 'Test BOD Member',
        'committee_member' => 'Test Committee Member',
        'staff_member' => 'Test Staff Member',
        'dvo_member' => 'Test DVO Member'
    ];
    
    $profile_id = wp_insert_post([
        'post_type' => 'owner-profiles',
        'post_title' => $role_names[$role] ?? 'Test User',
        'post_status' => 'publish',
        'post_author' => get_current_user_id()
    ]);
    
    if ($profile_id && !is_wp_error($profile_id)) {
        // Mark as test profile
        update_post_meta($profile_id, '_carbon_test_profile_role', $role);
        
        // Add basic test data
        carbon_set_post_meta($profile_id, 'first_name', 'Test');
        carbon_set_post_meta($profile_id, 'last_name', ucwords(str_replace('_', ' ', $role)));
        carbon_set_post_meta($profile_id, 'email_address', 'test@villacommunity.local');
        carbon_set_post_meta($profile_id, 'owner_status', 'active');
        
        // Add avatar
        $avatar_url = get_stylesheet_directory_uri() . '/miDocs/SITE DATA/Images/Branding/Avatars/avatar-secondary.png';
        carbon_set_post_meta($profile_id, 'profile_avatar', $avatar_url);
        
        // Role-specific data
        switch ($role) {
            case 'villa_owner':
                // Assign a random villa
                $villas = get_posts(['post_type' => 'villas', 'posts_per_page' => 1]);
                if (!empty($villas)) {
                    carbon_set_post_meta($profile_id, 'owned_villas', [$villas[0]->ID]);
                }
                break;
            case 'bod_member':
                carbon_set_post_meta($profile_id, 'board_position', 'Test Board Member');
                break;
            case 'committee_member':
                carbon_set_post_meta($profile_id, 'committees', ['landscaping']);
                break;
        }
        
        return $profile_id;
    }
    
    return false;
}

/**
 * Add CSS for role switcher
 */
function carbon_role_switcher_styles() {
    if (!current_user_can('administrator')) {
        return;
    }
    ?>
    <style>
    #wp-admin-bar-portal-role-switcher .ab-item {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }
    #wp-admin-bar-portal-role-switcher .ab-item:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    }
    #wp-admin-bar-portal-role-switcher .ab-submenu .ab-item {
        background: #f8f9fa !important;
        color: #333 !important;
    }
    #wp-admin-bar-portal-role-switcher .ab-submenu .ab-item:hover {
        background: #e9ecef !important;
    }
    </style>
    <?php
}
add_action('admin_head', 'carbon_role_switcher_styles');
add_action('wp_head', 'carbon_role_switcher_styles');
