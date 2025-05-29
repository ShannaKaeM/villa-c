<?php
/**
 * Owner Dashboard Block Render
 * Now integrates with FluentBoards authentication system
 */

if (! defined('ABSPATH')) {
    exit;
}

// Check if user is logged in and has portal access
if (!is_user_logged_in()) {
    echo '<div class="portal-access-denied">';
    echo '<h3>Access Restricted</h3>';
    echo '<p>Please log in to access the owner portal.</p>';
    echo '<p><a href="/portal-login">Login Here</a></p>';
    echo '</div>';
    return;
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
$portal_roles = ['villa_owner', 'bod_member', 'committee_member', 'staff_member', 'dvo_member'];
$has_portal_access = array_intersect($user_roles, $portal_roles) || current_user_can('administrator');

if (!$has_portal_access) {
    echo '<div class="portal-access-denied">';
    echo '<h3>Access Restricted</h3>';
    echo '<p>You do not have permission to access the owner portal.</p>';
    echo '<p>Please contact the administration if you believe this is an error.</p>';
    echo '</div>';
    return;
}

// Display portal header
echo '<div class="owner-portal-dashboard">';
echo '<header class="portal-header">';
echo '<h1>' . esc_html($portal_title) . '</h1>';
echo '<div class="user-info">';
echo '<span>Welcome, ' . esc_html($current_user->display_name) . '</span>';
echo '<a href="' . wp_logout_url() . '" class="logout-link">Logout</a>';
echo '</div>';
echo '</header>';

// Welcome message
if (!empty($welcome_message)) {
    echo '<div class="welcome-section">';
    echo '<p>' . esc_html($welcome_message) . '</p>';
    echo '</div>';
}

// FluentBoards integration placeholder
echo '<div class="fluent-boards-section">';
echo '<h2>Community Management</h2>';
echo '<p><em>FluentBoards integration will be added here for:</em></p>';
echo '<ul>';
echo '<li>Maintenance requests and tracking</li>';
echo '<li>BOD meeting agendas and minutes</li>';
echo '<li>Committee project management</li>';
echo '<li>Community announcements</li>';
echo '</ul>';
echo '</div>';

// Villa-specific information section
echo '<div class="villa-info-section">';
echo '<h2>Your Villa Information</h2>';

// Try to get owner profile for current user
$owner_profile = null;
$owner_profiles = get_posts([
    'post_type' => 'owner-profiles',
    'meta_query' => [
        [
            'key' => 'user_account',
            'value' => $current_user->ID,
            'compare' => '='
        ]
    ],
    'posts_per_page' => 1
]);

if (!empty($owner_profiles)) {
    $owner_profile = $owner_profiles[0];
    
    // Get linked villas
    $linked_villas = get_field('owned_villas', $owner_profile->ID);
    
    if ($linked_villas) {
        echo '<h3>Your Properties</h3>';
        echo '<div class="villa-list">';
        
        foreach ($linked_villas as $villa) {
            echo '<div class="villa-item">';
            echo '<h4>' . esc_html($villa->post_title) . '</h4>';
            
            // Get villa details
            $unit_number = get_field('unit_number', $villa->ID);
            $floor = get_field('floor', $villa->ID);
            $bedrooms = get_field('bedrooms', $villa->ID);
            $bathrooms = get_field('bathrooms', $villa->ID);
            
            echo '<div class="villa-details">';
            if ($unit_number) echo '<span>Unit: ' . esc_html($unit_number) . '</span>';
            if ($floor) echo '<span>Floor: ' . esc_html($floor) . '</span>';
            if ($bedrooms) echo '<span>Bedrooms: ' . esc_html($bedrooms) . '</span>';
            if ($bathrooms) echo '<span>Bathrooms: ' . esc_html($bathrooms) . '</span>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
    } else {
        echo '<p>No villa properties are currently linked to your profile.</p>';
    }
} else {
    echo '<p>No owner profile found. Please contact administration to set up your profile.</p>';
}

echo '</div>'; // .villa-info-section

// Recent announcements section
echo '<div class="announcements-section">';
echo '<h2>Recent Community Announcements</h2>';

$announcements = get_posts([
    'post_type' => 'post',
    'category_name' => 'announcements',
    'posts_per_page' => 3,
    'post_status' => 'publish'
]);

if ($announcements) {
    echo '<div class="announcements-list">';
    foreach ($announcements as $announcement) {
        echo '<div class="announcement-item">';
        echo '<h4>' . esc_html($announcement->post_title) . '</h4>';
        echo '<p class="announcement-date">' . get_the_date('F j, Y', $announcement) . '</p>';
        echo '<p>' . wp_trim_words($announcement->post_content, 30) . '</p>';
        echo '<a href="' . get_permalink($announcement) . '">Read More</a>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>No recent announcements.</p>';
}

echo '</div>'; // .announcements-section

echo '</div>'; // .owner-portal-dashboard
?>
