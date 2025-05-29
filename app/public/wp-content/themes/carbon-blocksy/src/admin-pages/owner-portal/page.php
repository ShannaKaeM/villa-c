<?php
/**
 * Owner Portal Dashboard Admin Page
 * Auto-discovered by Carbon Blocks Framework
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

use Carbon_Fields\Field;

// Debug: Log that this file is being loaded
error_log('Owner Portal admin page loaded successfully');

// Get page slug from directory name (auto-discovery pattern)
$page_slug = basename(dirname(__FILE__));

carbon_create_admin_page([
    'title' => 'Owner Portal Dashboard',
    'menu_title' => 'Owner Portal',
    'slug' => $page_slug,
    'icon' => 'dashicons-admin-home',
    'position' => 24,
    'capability' => 'read',
    'fields' => [
        Field::make('html', 'portal_dashboard_content')
            ->set_html(carbon_render_portal_dashboard()),
    ]
]);

/**
 * Render the portal dashboard content
 */
function carbon_render_portal_dashboard() {
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    
    // Check if user has portal access
    $portal_roles = ['villa_owner', 'bod_member', 'committee_member', 'staff_member', 'dvo_member'];
    $has_portal_access = array_intersect($user_roles, $portal_roles);
    
    if (!$has_portal_access && !current_user_can('administrator')) {
        return '<div class="notice notice-error"><p>You do not have access to the Owner Portal.</p></div>';
    }
    
    // Get user's owner profile
    $owner_profile = carbon_get_user_owner_profile($current_user->ID);
    
    ob_start();
    ?>
    <div class="owner-portal-dashboard">
        <div class="portal-header">
            <h2>Welcome to Villa Capriani Owner Portal</h2>
            <p>Hello, <?php echo esc_html($current_user->display_name); ?>!</p>
        </div>
        
        <div class="portal-stats">
            <div class="stat-grid">
                <div class="stat-card">
                    <h3>My Profile</h3>
                    <?php if ($owner_profile): ?>
                        <?php 
                        $avatar_url = carbon_get_post_meta($owner_profile->ID, 'profile_avatar');
                        if ($avatar_url): ?>
                            <div class="profile-avatar" style="margin-bottom: 15px;">
                                <img src="<?php echo esc_url($avatar_url); ?>" alt="Profile Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;">
                            </div>
                        <?php endif; ?>
                        <p><strong>Status:</strong> <?php echo esc_html(carbon_get_post_meta($owner_profile->ID, 'owner_status')); ?></p>
                        <p><strong>Owned Villas:</strong> <?php echo count(carbon_get_post_meta($owner_profile->ID, 'owned_villas')); ?></p>
                        <a href="<?php echo admin_url('post.php?post=' . $owner_profile->ID . '&action=edit'); ?>" class="button">Edit Profile</a>
                    <?php else: ?>
                        <p>No owner profile found.</p>
                        <a href="<?php echo admin_url('post-new.php?post_type=owner-profiles'); ?>" class="button">Create Profile</a>
                    <?php endif; ?>
                </div>
                
                <div class="stat-card">
                    <h3>Quick Actions</h3>
                    <ul>
                        <li><a href="<?php echo admin_url('edit.php?post_type=owner-profiles'); ?>">View All Profiles</a></li>
                        <li><a href="<?php echo admin_url('edit.php?post_type=villas'); ?>">Manage Villas</a></li>
                        <?php if (in_array('bod_member', $user_roles) || current_user_can('administrator')): ?>
                            <li><a href="<?php echo admin_url('tools.php?page=portal-sample-data'); ?>">Generate Sample Data</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="stat-card">
                    <h3>System Status</h3>
                    <p><strong>Total Owners:</strong> <?php echo wp_count_posts('owner-profiles')->publish; ?></p>
                    <p><strong>Total Villas:</strong> <?php echo wp_count_posts('villas')->publish; ?></p>
                    <p><strong>Your Role:</strong> <?php echo esc_html(implode(', ', array_map('ucwords', str_replace('_', ' ', $user_roles)))); ?></p>
                </div>
            </div>
        </div>
        
        <div class="portal-sections">
            <div class="section-grid">
                <?php if (in_array('villa_owner', $user_roles) || current_user_can('administrator')): ?>
                <div class="portal-section">
                    <h3>Owner Resources</h3>
                    <ul>
                        <li>Property Documents</li>
                        <li>Maintenance Requests</li>
                        <li>Community Calendar</li>
                        <li>Financial Statements</li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('bod_member', $user_roles) || current_user_can('administrator')): ?>
                <div class="portal-section">
                    <h3>Board Resources</h3>
                    <ul>
                        <li>Meeting Minutes</li>
                        <li>Financial Reports</li>
                        <li>Policy Documents</li>
                        <li>Vendor Management</li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('committee_member', $user_roles) || current_user_can('administrator')): ?>
                <div class="portal-section">
                    <h3>Committee Tools</h3>
                    <ul>
                        <li>Project Management</li>
                        <li>Committee Documents</li>
                        <li>Collaboration Tools</li>
                        <li>Task Assignments</li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if (in_array('staff_member', $user_roles) || in_array('dvo_member', $user_roles) || current_user_can('administrator')): ?>
                <div class="portal-section">
                    <h3>Management Tools</h3>
                    <ul>
                        <li>Owner Directory</li>
                        <li>Maintenance Tracking</li>
                        <li>Communication Center</li>
                        <li>Reporting Dashboard</li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="portal-footer">
            <p><em>Villa Capriani Owner Portal - Powered by Carbon Blocks Framework</em></p>
        </div>
    </div>
    
    <style>
    .owner-portal-dashboard {
        max-width: 1200px;
        margin: 20px 0;
    }
    
    .portal-header {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #007cba;
    }
    
    .stat-grid, .section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .stat-card, .portal-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #e1e1e1;
    }
    
    .stat-card h3, .portal-section h3 {
        margin-top: 0;
        color: #1d2327;
        border-bottom: 2px solid #007cba;
        padding-bottom: 10px;
    }
    
    .stat-card ul, .portal-section ul {
        list-style: none;
        padding: 0;
    }
    
    .stat-card li, .portal-section li {
        padding: 5px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .stat-card li:last-child, .portal-section li:last-child {
        border-bottom: none;
    }
    
    .portal-footer {
        text-align: center;
        padding: 20px;
        color: #666;
        border-top: 1px solid #e1e1e1;
        margin-top: 20px;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Get owner profile for a WordPress user
 */
function carbon_get_user_owner_profile($user_id) {
    $profiles = get_posts([
        'post_type' => 'owner-profiles',
        'meta_query' => [
            [
                'key' => '_linked_user',
                'value' => serialize(strval($user_id)),
                'compare' => 'LIKE'
            ]
        ],
        'posts_per_page' => 1
    ]);
    
    return !empty($profiles) ? $profiles[0] : null;
}
