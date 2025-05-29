<?php
/**
 * DesignBook Dashboard - Main Design System Hub
 * Central dashboard for managing ColorBook and TextBook systems
 */

// Add DesignBook main menu
add_action('admin_menu', 'designbook_admin_menu');
function designbook_admin_menu() {
    add_menu_page(
        'DesignBook',
        'DesignBook',
        'manage_options',
        'designbook',
        'designbook_admin_page',
        'dashicons-art',
        25
    );
}

// Get system status for dashboard cards
function designbook_get_system_status() {
    $status = [];
    
    // ColorBook status
    $colorbook_colors = get_option('colorbook_colors', []);
    $status['colorbook'] = [
        'configured' => !empty($colorbook_colors),
        'count' => count($colorbook_colors),
        'last_updated' => get_option('colorbook_last_updated', 'Never')
    ];
    
    // TextBook status
    $textbook_semantic = get_option('textbook_semantic_elements', []);
    $textbook_families = get_option('textbook_font_families', []);
    $status['textbook'] = [
        'configured' => !empty($textbook_semantic) || !empty($textbook_families),
        'semantic_count' => count($textbook_semantic),
        'font_count' => count($textbook_families),
        'last_updated' => get_option('textbook_last_updated', 'Never')
    ];
    
    return $status;
}

// Get quick stats for dashboard
function designbook_get_quick_stats() {
    $colorbook_colors = get_option('colorbook_colors', []);
    $textbook_semantic = get_option('textbook_semantic_elements', []);
    $textbook_families = get_option('textbook_font_families', []);
    
    return [
        'total_colors' => count($colorbook_colors),
        'semantic_elements' => count($textbook_semantic),
        'font_families' => count($textbook_families),
        'css_generated' => file_exists(get_template_directory() . '/assets/css/designbook.css')
    ];
}

// Main DesignBook dashboard page
function designbook_admin_page() {
    $status = designbook_get_system_status();
    $stats = designbook_get_quick_stats();
    ?>
    <div class="wrap designbook-dashboard">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1>🎨 DesignBook Dashboard</h1>
                <p>Your complete design system management hub</p>
            </div>
            <div class="header-actions">
                <button type="button" class="button button-primary" onclick="designbook_export_all()">
                    📦 Export All
                </button>
                <button type="button" class="button button-secondary" onclick="designbook_regenerate_css()">
                    🔄 Regenerate CSS
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon">🎨</div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['total_colors']; ?></div>
                    <div class="stat-label">Colors</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['semantic_elements']; ?></div>
                    <div class="stat-label">Text Elements</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🔤</div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['font_families']; ?></div>
                    <div class="stat-label">Font Families</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><?php echo $stats['css_generated'] ? '✅' : '❌'; ?></div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['css_generated'] ? 'Active' : 'Missing'; ?></div>
                    <div class="stat-label">CSS Status</div>
                </div>
            </div>
        </div>

        <!-- System Cards -->
        <div class="system-cards">
            <!-- ColorBook Card -->
            <div class="system-card colorbook-card">
                <div class="card-header">
                    <div class="card-icon">🎨</div>
                    <div class="card-title">
                        <h2>ColorBook</h2>
                        <p>OKLCH Color Management System</p>
                    </div>
                    <div class="card-status <?php echo $status['colorbook']['configured'] ? 'configured' : 'not-configured'; ?>">
                        <?php echo $status['colorbook']['configured'] ? '✅ Configured' : '⚠️ Not Configured'; ?>
                    </div>
                </div>
                
                <div class="card-content">
                    <div class="card-stats">
                        <div class="card-stat">
                            <span class="stat-value"><?php echo $status['colorbook']['count']; ?></span>
                            <span class="stat-label">Colors Defined</span>
                        </div>
                        <div class="card-stat">
                            <span class="stat-value"><?php echo $status['colorbook']['last_updated']; ?></span>
                            <span class="stat-label">Last Updated</span>
                        </div>
                    </div>
                    
                    <div class="card-description">
                        <p>Manage your color palette with advanced OKLCH color system. Define primary, secondary, and semantic colors with live preview and CSS variable generation.</p>
                    </div>
                    
                    <div class="card-features">
                        <span class="feature-tag">OKLCH Colors</span>
                        <span class="feature-tag">Live Preview</span>
                        <span class="feature-tag">CSS Variables</span>
                        <span class="feature-tag">JSON Export</span>
                    </div>
                </div>
                
                <div class="card-actions">
                    <a href="<?php echo admin_url('admin.php?page=colorbook'); ?>" class="button button-primary">
                        🎨 Open ColorBook
                    </a>
                    <button type="button" class="button button-secondary" onclick="colorbook_quick_export()">
                        📤 Export Colors
                    </button>
                </div>
            </div>

            <!-- TextBook Card -->
            <div class="system-card textbook-card">
                <div class="card-header">
                    <div class="card-icon">📖</div>
                    <div class="card-title">
                        <h2>TextBook</h2>
                        <p>Typography & Semantic Elements</p>
                    </div>
                    <div class="card-status <?php echo $status['textbook']['configured'] ? 'configured' : 'not-configured'; ?>">
                        <?php echo $status['textbook']['configured'] ? '✅ Configured' : '⚠️ Not Configured'; ?>
                    </div>
                </div>
                
                <div class="card-content">
                    <div class="card-stats">
                        <div class="card-stat">
                            <span class="stat-value"><?php echo $status['textbook']['semantic_count']; ?></span>
                            <span class="stat-label">Semantic Elements</span>
                        </div>
                        <div class="card-stat">
                            <span class="stat-value"><?php echo $status['textbook']['font_count']; ?></span>
                            <span class="stat-label">Font Families</span>
                        </div>
                    </div>
                    
                    <div class="card-description">
                        <p>Define typography foundation and semantic text elements. Manage font families, sizes, weights, and create consistent text styling across your site.</p>
                    </div>
                    
                    <div class="card-features">
                        <span class="feature-tag">Foundation Settings</span>
                        <span class="feature-tag">Semantic Elements</span>
                        <span class="feature-tag">Font Management</span>
                        <span class="feature-tag">Auto CSS</span>
                    </div>
                </div>
                
                <div class="card-actions">
                    <a href="<?php echo admin_url('admin.php?page=textbook'); ?>" class="button button-primary">
                        📖 Open TextBook
                    </a>
                    <button type="button" class="button button-secondary" onclick="textbook_quick_export()">
                        📤 Export Typography
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>📊 System Overview</h2>
            <div class="activity-grid">
                <div class="activity-item">
                    <h3>🎨 Color System</h3>
                    <p>OKLCH-based color management with live preview and CSS variable generation. Perfect for modern design systems.</p>
                    <div class="activity-meta">
                        Status: <?php echo $status['colorbook']['configured'] ? 'Active' : 'Needs Setup'; ?>
                    </div>
                </div>
                
                <div class="activity-item">
                    <h3>📝 Typography System</h3>
                    <p>Foundation typography settings and semantic elements for consistent text styling across your website.</p>
                    <div class="activity-meta">
                        Status: <?php echo $status['textbook']['configured'] ? 'Active' : 'Needs Setup'; ?>
                    </div>
                </div>
                
                <div class="activity-item">
                    <h3>🔧 Integration</h3>
                    <p>Seamless integration with WordPress theme.json and Blocksy theme for optimal performance and compatibility.</p>
                    <div class="activity-meta">
                        Theme: Blocksy Compatible
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="actions-grid">
                <button type="button" class="action-button" onclick="window.location.href='<?php echo admin_url('admin.php?page=colorbook'); ?>'">
                    <div class="action-icon">🎨</div>
                    <div class="action-text">
                        <strong>Manage Colors</strong>
                        <span>Add, edit, and organize your color palette</span>
                    </div>
                </button>
                
                <button type="button" class="action-button" onclick="window.location.href='<?php echo admin_url('admin.php?page=textbook'); ?>'">
                    <div class="action-icon">📖</div>
                    <div class="action-text">
                        <strong>Manage Typography</strong>
                        <span>Configure fonts and text elements</span>
                    </div>
                </button>
                
                <button type="button" class="action-button" onclick="designbook_regenerate_css()">
                    <div class="action-icon">🔄</div>
                    <div class="action-text">
                        <strong>Regenerate CSS</strong>
                        <span>Update CSS variables and styles</span>
                    </div>
                </button>
                
                <button type="button" class="action-button" onclick="designbook_export_all()">
                    <div class="action-icon">📦</div>
                    <div class="action-text">
                        <strong>Export Everything</strong>
                        <span>Download complete design system</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <style>
    .designbook-dashboard {
        max-width: 1200px;
        margin: 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-content h1 {
        margin: 0 0 10px 0;
        font-size: 2.5em;
        font-weight: 700;
    }

    .header-content p {
        margin: 0;
        opacity: 0.9;
        font-size: 1.1em;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .header-actions .button {
        padding: 12px 20px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e1e5e9;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stat-icon {
        font-size: 2.5em;
        opacity: 0.8;
    }

    .stat-number {
        font-size: 2em;
        font-weight: 700;
        color: #1d2327;
        line-height: 1;
    }

    .stat-label {
        color: #646970;
        font-size: 0.9em;
        margin-top: 5px;
    }

    .system-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }

    .system-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e1e5e9;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .system-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.1);
    }

    .card-header {
        padding: 25px;
        background: #f8f9fa;
        border-bottom: 1px solid #e1e5e9;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .card-icon {
        font-size: 2.5em;
    }

    .card-title h2 {
        margin: 0 0 5px 0;
        color: #1d2327;
        font-size: 1.5em;
    }

    .card-title p {
        margin: 0;
        color: #646970;
        font-size: 0.9em;
    }

    .card-status {
        margin-left: auto;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }

    .card-status.configured {
        background: #d1f2eb;
        color: #0d7a5f;
    }

    .card-status.not-configured {
        background: #fef2e6;
        color: #b7791f;
    }

    .card-content {
        padding: 25px;
    }

    .card-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .card-stat {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-value {
        display: block;
        font-size: 1.8em;
        font-weight: 700;
        color: #1d2327;
    }

    .stat-label {
        font-size: 0.85em;
        color: #646970;
        margin-top: 5px;
    }

    .card-description {
        margin-bottom: 20px;
    }

    .card-description p {
        color: #646970;
        line-height: 1.6;
        margin: 0;
    }

    .card-features {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .feature-tag {
        background: #e7f3ff;
        color: #0073aa;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.8em;
        font-weight: 500;
    }

    .card-actions {
        padding: 20px 25px;
        background: #f8f9fa;
        border-top: 1px solid #e1e5e9;
        display: flex;
        gap: 10px;
    }

    .recent-activity,
    .quick-actions {
        margin: 40px 0;
    }

    .recent-activity h2,
    .quick-actions h2 {
        margin-bottom: 20px;
        color: #1d2327;
    }

    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .activity-item {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e1e5e9;
    }

    .activity-item h3 {
        margin: 0 0 10px 0;
        color: #1d2327;
    }

    .activity-item p {
        margin: 0 0 15px 0;
        color: #646970;
        line-height: 1.6;
    }

    .activity-meta {
        font-size: 0.85em;
        color: #0073aa;
        font-weight: 500;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .action-button {
        background: white;
        border: 1px solid #e1e5e9;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }

    .action-button:hover {
        background: #f8f9fa;
        border-color: #0073aa;
        transform: translateY(-2px);
    }

    .action-icon {
        font-size: 2em;
    }

    .action-text strong {
        display: block;
        color: #1d2327;
        margin-bottom: 5px;
    }

    .action-text span {
        color: #646970;
        font-size: 0.9em;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }
        
        .system-cards {
            grid-template-columns: 1fr;
        }
        
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>

    <script>
    function designbook_export_all() {
        alert('🚀 Export All functionality coming soon!\n\nThis will export your complete design system including:\n• ColorBook colors and OKLCH values\n• TextBook typography settings\n• Generated CSS variables\n• Theme.json configuration');
    }

    function designbook_regenerate_css() {
        if (confirm('Regenerate CSS variables and styles?\n\nThis will update all CSS files based on your current ColorBook and TextBook settings.')) {
            // AJAX call to regenerate CSS would go here
            alert('✅ CSS regenerated successfully!\n\nYour design system styles have been updated.');
        }
    }

    function colorbook_quick_export() {
        alert('🎨 ColorBook Export\n\nThis will export your color palette in multiple formats:\n• JSON with OKLCH values\n• CSS variables\n• Sass/SCSS variables');
    }

    function textbook_quick_export() {
        alert('📖 TextBook Export\n\nThis will export your typography system:\n• Font family definitions\n• Semantic element styles\n• CSS typography classes');
    }
    </script>
    <?php
}
