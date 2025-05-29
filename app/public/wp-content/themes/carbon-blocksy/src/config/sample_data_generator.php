<?php
/**
 * Sample Data Generator for Villa Community Portal
 * Creates sample users and owner profiles for testing
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

// Debug: Log that this file is being loaded
error_log('Sample Data Generator loaded successfully');

/**
 * Generate sample users and owner profiles
 */
function carbon_generate_sample_portal_data() {
    // Sample data arrays
    $first_names = [
        'John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa',
        'William', 'Jennifer', 'James', 'Mary', 'Christopher', 'Patricia', 'Daniel',
        'Linda', 'Matthew', 'Elizabeth', 'Anthony', 'Barbara'
    ];
    
    $last_names = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
        'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'
    ];
    
    $roles_distribution = [
        'villa_owner' => 12,      // 12 regular owners
        'bod_member' => 3,        // 3 board members
        'committee_member' => 3,  // 3 committee members
        'staff_member' => 1,      // 1 staff member
        'dvo_member' => 1         // 1 DVO
    ];
    
    $committees = ['Technology & Marketing', 'Legal & Governance', 'Grounds & Appearance', 'Budget & Revenue', 'Operations & Maintenance'];
    $board_positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Member at Large'];
    
    $created_users = [];
    $user_count = 0;
    
    foreach ($roles_distribution as $role => $count) {
        for ($i = 0; $i < $count; $i++) {
            $user_count++;
            
            // Generate user data
            $first_name = $first_names[array_rand($first_names)];
            $last_name = $last_names[array_rand($last_names)];
            $username = strtolower($first_name . '.' . $last_name . $user_count);
            $email = $username . '@villacapriani.com';
            
            // Create WordPress user
            $user_id = wp_create_user($username, 'TempPass123!', $email);
            
            if (!is_wp_error($user_id)) {
                // Set user role
                $user = new WP_User($user_id);
                $user->set_role($role);
                
                // Update user meta
                wp_update_user([
                    'ID' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'display_name' => $first_name . ' ' . $last_name
                ]);
                
                // Create Owner Profile
                $profile_title = $first_name . ' ' . $last_name . ' - Owner Profile';
                $profile_id = wp_insert_post([
                    'post_title' => $profile_title,
                    'post_type' => 'owner-profiles',
                    'post_status' => 'publish',
                    'post_content' => 'Sample owner profile for ' . $first_name . ' ' . $last_name
                ]);
                
                if ($profile_id && !is_wp_error($profile_id)) {
                    // Link user to profile
                    carbon_set_post_meta($profile_id, 'linked_user', [$user_id]);
                    
                    // Profile avatar - use the secondary avatar as default
                    $avatar_url = get_stylesheet_directory_uri() . '/miDocs/SITE DATA/Images/Branding/Avatars/avatar-secondary.png';
                    carbon_set_post_meta($profile_id, 'profile_avatar', $avatar_url);
                    
                    // Basic info
                    carbon_set_post_meta($profile_id, 'first_name', $first_name);
                    carbon_set_post_meta($profile_id, 'last_name', $last_name);
                    carbon_set_post_meta($profile_id, 'email_address', $email);
                    carbon_set_post_meta($profile_id, 'phone_primary', '910-' . rand(200, 999) . '-' . rand(1000, 9999));
                    carbon_set_post_meta($profile_id, 'move_in_date', date('Y-m-d', strtotime('-' . rand(1, 60) . ' months')));
                    carbon_set_post_meta($profile_id, 'owner_status', 'active');
                    
                    // Contact info
                    carbon_set_post_meta($profile_id, 'mailing_address', rand(100, 999) . ' Ocean View Dr, North Topsail Beach, NC 28460');
                    carbon_set_post_meta($profile_id, 'emergency_contact_name', $first_names[array_rand($first_names)] . ' ' . $last_names[array_rand($last_names)]);
                    carbon_set_post_meta($profile_id, 'emergency_contact_phone', '919-' . rand(200, 999) . '-' . rand(1000, 9999));
                    carbon_set_post_meta($profile_id, 'preferred_contact_method', ['email', 'phone', 'portal'][array_rand(['email', 'phone', 'portal'])]);
                    carbon_set_post_meta($profile_id, 'receive_newsletters', true);
                    carbon_set_post_meta($profile_id, 'receive_maintenance_alerts', true);
                    
                    // Villa assignments (assign random villa units)
                    $villa_posts = get_posts([
                        'post_type' => 'villas',
                        'posts_per_page' => 5,
                        'orderby' => 'rand'
                    ]);
                    
                    if (!empty($villa_posts)) {
                        $assigned_villa = $villa_posts[0]->ID;
                        carbon_set_post_meta($profile_id, 'owned_villas', [$assigned_villa]);
                        carbon_set_post_meta($profile_id, 'primary_residence', rand(0, 1) ? 'yes' : 'no');
                        carbon_set_post_meta($profile_id, 'rental_management', rand(0, 1));
                    }
                    
                    // Role-specific data
                    carbon_set_post_meta($profile_id, 'portal_roles', [$role]);
                    
                    switch ($role) {
                        case 'bod_member':
                            carbon_set_post_meta($profile_id, 'board_position', $board_positions[array_rand($board_positions)]);
                            carbon_set_post_meta($profile_id, 'term_start_date', date('Y-m-d', strtotime('-' . rand(6, 24) . ' months')));
                            carbon_set_post_meta($profile_id, 'term_end_date', date('Y-m-d', strtotime('+' . rand(6, 18) . ' months')));
                            carbon_set_post_meta($profile_id, 'bio', 'Experienced board member dedicated to improving our community.');
                            break;
                            
                        case 'committee_member':
                            // Will assign to committees after Groups CPT is created
                            carbon_set_post_meta($profile_id, 'bio', 'Active committee member working to enhance community life.');
                            break;
                            
                        case 'staff_member':
                            carbon_set_post_meta($profile_id, 'bio', 'Dedicated staff member serving the Villa Capriani community.');
                            break;
                            
                        case 'dvo_member':
                            carbon_set_post_meta($profile_id, 'board_position', 'Director of Villa Operations');
                            carbon_set_post_meta($profile_id, 'bio', 'Director of Villa Operations overseeing daily community management.');
                            break;
                    }
                    
                    // Portal preferences
                    carbon_set_post_meta($profile_id, 'portal_access_enabled', true);
                    carbon_set_post_meta($profile_id, 'dashboard_layout', 'standard');
                    carbon_set_post_meta($profile_id, 'notification_preferences', ['announcements', 'maintenance', 'tickets']);
                    
                    $created_users[] = [
                        'user_id' => $user_id,
                        'profile_id' => $profile_id,
                        'name' => $first_name . ' ' . $last_name,
                        'role' => $role,
                        'email' => $email,
                        'username' => $username
                    ];
                }
            }
        }
    }
    
    return $created_users;
}

/**
 * Display sample data generation results
 */
function carbon_display_sample_data_results($users) {
    echo '<div class="notice notice-success"><p><strong>Sample Portal Data Generated Successfully!</strong></p>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Profile ID</th></tr></thead>';
    echo '<tbody>';
    
    foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . esc_html($user['name']) . '</td>';
        echo '<td>' . esc_html($user['username']) . '</td>';
        echo '<td>' . esc_html($user['email']) . '</td>';
        echo '<td>' . esc_html(str_replace('_', ' ', ucwords($user['role'], '_'))) . '</td>';
        echo '<td>' . esc_html($user['profile_id']) . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    echo '<p><em>Default password for all users: TempPass123!</em></p>';
    echo '<p><em>Users should change their passwords on first login.</em></p></div>';
}

/**
 * Admin page to generate sample data
 */
function carbon_sample_data_admin_page() {
    if (isset($_POST['generate_sample_data']) && wp_verify_nonce($_POST['_wpnonce'], 'generate_sample_data')) {
        $users = carbon_generate_sample_portal_data();
        carbon_display_sample_data_results($users);
    }
    
    ?>
    <div class="wrap">
        <h1>Generate Sample Portal Data</h1>
        <p>This will create 20 sample users with various roles and corresponding owner profiles for testing the portal system.</p>
        
        <form method="post">
            <?php wp_nonce_field('generate_sample_data'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Sample Data Distribution</th>
                    <td>
                        <ul>
                            <li><strong>12 Villa Owners</strong> - Regular property owners</li>
                            <li><strong>3 BOD Members</strong> - Board of Directors</li>
                            <li><strong>3 Committee Members</strong> - Various committees</li>
                            <li><strong>1 Staff Member</strong> - Property management</li>
                            <li><strong>1 DVO</strong> - Director of Villa Operations</li>
                        </ul>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="generate_sample_data" class="button-primary" value="Generate Sample Data" />
            </p>
        </form>
    </div>
    <?php
}

/**
 * Add admin menu for sample data generation
 */
function carbon_add_sample_data_menu() {
    add_management_page(
        'Generate Sample Portal Data',
        'Portal Sample Data',
        'manage_options',
        'portal-sample-data',
        'carbon_sample_data_admin_page'
    );
}

// Add menu for administrators (always available)
add_action('admin_menu', 'carbon_add_sample_data_menu');
