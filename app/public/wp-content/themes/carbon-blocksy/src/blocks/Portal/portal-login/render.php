<?php
/**
 * Portal Login Block Render
 * Now integrates with FluentBoards authentication system
 */

if (! defined('ABSPATH')) {
    exit;
}

// Check if user is already logged in
if (is_user_logged_in()) {
    // Show logged-in message
    echo '<div class="portal-login-success">';
    echo '<h3>Welcome back!</h3>';
    echo '<p>You are already logged in. <a href="' . wp_logout_url() . '">Logout</a></p>';
    echo '<p><a href="/portal">Go to Portal Dashboard</a></p>';
    echo '</div>';
    return;
}

// Display login message for non-authenticated users
echo '<div class="portal-login-container">';
echo '<h2>' . esc_html($portal_title) . '</h2>';
echo '<div class="portal-login-content">';

// Show welcome message
if (!empty($welcome_message)) {
    echo '<p class="welcome-message">' . esc_html($welcome_message) . '</p>';
}

// FluentBoards integration notice
echo '<div class="login-instructions">';
echo '<h3>Access Your Community Portal</h3>';
echo '<p>Please log in to access your villa community dashboard, maintenance requests, and board communications.</p>';
echo '<p><strong>Note:</strong> This portal integrates with FluentBoards for enhanced project management and community collaboration.</p>';
echo '</div>';

// Login form will be handled by FluentBoards frontend portal
echo '<div class="fluent-boards-login">';
echo '<p>Login functionality will be provided by FluentBoards frontend portal.</p>';
echo '<p><em>FluentBoards integration pending setup...</em></p>';
echo '</div>';

echo '</div>'; // .portal-login-content
echo '</div>'; // .portal-login-container
?>
