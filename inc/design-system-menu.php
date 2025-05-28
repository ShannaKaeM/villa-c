<?php
/**
 * Design System Main Menu
 * Creates hierarchical admin structure for all Books
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Create main Design System menu
add_action('admin_menu', 'design_system_main_menu', 5);
function design_system_main_menu() {
    add_menu_page(
        'Design System',
        '📚 Design System',
        'manage_options',
        'design-system',
        'design_system_dashboard',
        'dashicons-admin-customizer',
        25
    );
    
    // Add dashboard as first submenu item
    add_submenu_page(
        'design-system',
        'Design System Dashboard',
        '🏠 Dashboard',
        'manage_options',
        'design-system',
        'design_system_dashboard'
    );
}

// Design System Dashboard
function design_system_dashboard() {
    ?>
    <div class="wrap design-system-dashboard">
        <div class="dashboard-header">
            <h1>📚 Design System Dashboard</h1>
            <p>Centralized control for Villa Community's design system</p>
        </div>

        <div class="dashboard-grid">
            <!-- Foundation Books -->
            <div class="book-category">
                <h2>🎨 Foundation Books</h2>
                <div class="book-cards">
                    <a href="<?php echo admin_url('admin.php?page=colorbook'); ?>" class="book-card colorbook">
                        <div class="book-icon">🎨</div>
                        <h3>ColorBook</h3>
                        <p>Manage color palette, OKLCH values, and CSS variables</p>
                        <div class="book-status">
                            <?php 
                            $colors = colorbook_get_current_colors();
                            echo count($colors) . ' colors defined';
                            ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=textbook'); ?>" class="book-card textbook">
                        <div class="book-icon">📝</div>
                        <h3>TextBook</h3>
                        <p>Typography settings, fonts, and text styling</p>
                        <div class="book-status">
                            <?php 
                            $typography = get_option('textbook_settings', array());
                            echo !empty($typography) ? 'Configured' : 'Default settings';
                            ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=layoutbook'); ?>" class="book-card layoutbook">
                        <div class="book-icon">📐</div>
                        <h3>LayoutBook</h3>
                        <p>Grid systems, spacing, and layout presets</p>
                        <div class="book-status">
                            <?php 
                            $layouts = get_option('layoutbook_settings', array());
                            echo !empty($layouts) ? 'Configured' : 'Default settings';
                            ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=uibook'); ?>" class="book-card uibook">
                        <div class="book-icon">🎛️</div>
                        <h3>UiBook</h3>
                        <p>Component styling, padding, borders, shadows</p>
                        <div class="book-status">
                            <?php 
                            $ui = get_option('uibook_settings', array());
                            echo !empty($ui) ? 'Configured' : 'Default settings';
                            ?>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Element Books -->
            <div class="book-category">
                <h2>🧩 Element Books</h2>
                <div class="book-cards">
                    <div class="book-card coming-soon">
                        <div class="book-icon">🔘</div>
                        <h3>ButtonBook</h3>
                        <p>Button styles, states, and variations</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">🎯</div>
                        <h3>IconBook</h3>
                        <p>Icon library and styling</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">📝</div>
                        <h3>InputBook</h3>
                        <p>Form inputs and field styling</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                </div>
            </div>

            <!-- Component Books -->
            <div class="book-category">
                <h2>📦 Component Books</h2>
                <div class="book-cards">
                    <div class="book-card coming-soon">
                        <div class="book-icon">🃏</div>
                        <h3>CardBook</h3>
                        <p>Card components and layouts</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">📋</div>
                        <h3>FormBook</h3>
                        <p>Form layouts and validation</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">🧭</div>
                        <h3>NavigationBook</h3>
                        <p>Navigation patterns and menus</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                </div>
            </div>

            <!-- Section Books -->
            <div class="book-category">
                <h2>🏗️ Section Books</h2>
                <div class="book-cards">
                    <a href="<?php echo admin_url('admin.php?page=herobook'); ?>" class="book-card herobook">
                        <div class="book-icon">🏗️</div>
                        <h3>HeroBook</h3>
                        <p>Hero section templates and layouts</p>
                        <div class="book-status">
                            <?php 
                            $heroes = herobook_get_current_templates();
                            echo isset($heroes['active_template']) ? 'Active: ' . ucfirst(str_replace('-', ' ', $heroes['active_template'])) : 'Not configured';
                            ?>
                        </div>
                    </a>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">📞</div>
                        <h3>ContactBook</h3>
                        <p>Contact section layouts</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                    
                    <div class="book-card coming-soon">
                        <div class="book-icon">📋</div>
                        <h3>ListingBook</h3>
                        <p>Property listing layouts</p>
                        <div class="book-status">Coming Soon</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="action-buttons">
                <button class="action-btn" onclick="exportAllSettings()">
                    📤 Export All Settings
                </button>
                <button class="action-btn" onclick="importSettings()">
                    📥 Import Settings
                </button>
                <button class="action-btn" onclick="resetToDefaults()">
                    🔄 Reset to Defaults
                </button>
                <button class="action-btn" onclick="generateCSS()">
                    🎨 Regenerate CSS
                </button>
            </div>
        </div>
    </div>

    <style>
        .design-system-dashboard {
            max-width: none;
            margin: 0;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            margin: 0 -20px 2rem -20px;
        }
        
        .dashboard-header h1 {
            color: white;
            margin: 0 0 0.5rem 0;
        }
        
        .dashboard-grid {
            display: grid;
            gap: 2rem;
        }
        
        .book-category h2 {
            margin: 0 0 1rem 0;
            color: #374151;
            font-size: 1.5rem;
        }
        
        .book-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .book-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            display: block;
        }
        
        .book-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }
        
        .book-card.coming-soon {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .book-card.coming-soon:hover {
            transform: none;
            box-shadow: none;
        }
        
        .book-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .book-card h3 {
            margin: 0 0 0.5rem 0;
            color: #111827;
        }
        
        .book-card p {
            margin: 0 0 1rem 0;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .book-status {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            color: #374151;
            font-weight: 500;
        }
        
        .quick-actions {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .quick-actions h2 {
            margin: 0 0 1rem 0;
            color: #374151;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s ease;
        }
        
        .action-btn:hover {
            background: #2563eb;
        }
        
        /* Book-specific styling */
        .book-card.colorbook .book-status {
            background: #fef3c7;
            color: #92400e;
        }
        
        .book-card.textbook .book-status {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .book-card.layoutbook .book-status {
            background: #dcfce7;
            color: #166534;
        }
        
        .book-card.uibook .book-status {
            background: #fce7f3;
            color: #be185d;
        }
        
        .book-card.herobook .book-status {
            background: #e0e7ff;
            color: #3730a3;
        }
    </style>

    <script>
        function exportAllSettings() {
            // Implementation for exporting all settings
            alert('Export functionality coming soon!');
        }
        
        function importSettings() {
            // Implementation for importing settings
            alert('Import functionality coming soon!');
        }
        
        function resetToDefaults() {
            if (confirm('This will reset ALL design system settings to defaults. Are you sure?')) {
                alert('Reset functionality coming soon!');
            }
        }
        
        function generateCSS() {
            // Implementation for regenerating CSS
            alert('CSS regeneration functionality coming soon!');
        }
    </script>
    <?php
}

// Update existing Books to be submenus
add_action('admin_menu', 'update_books_as_submenus', 15);
function update_books_as_submenus() {
    // Remove existing main menu items and add as submenus
    remove_menu_page('colorbook');
    remove_menu_page('textbook');
    remove_menu_page('layoutbook');
    remove_menu_page('uibook');
    
    // Add Foundation Books as submenus
    add_submenu_page(
        'design-system',
        'ColorBook',
        '🎨 ColorBook',
        'manage_options',
        'colorbook',
        'colorbook_admin_page'
    );
    
    add_submenu_page(
        'design-system',
        'TextBook',
        '📝 TextBook',
        'manage_options',
        'textbook',
        'textbook_admin_page'
    );
    
    add_submenu_page(
        'design-system',
        'LayoutBook',
        '📐 LayoutBook',
        'manage_options',
        'layoutbook',
        'layoutbook_admin_page'
    );
    
    add_submenu_page(
        'design-system',
        'UiBook',
        '🎛️ UiBook',
        'manage_options',
        'uibook',
        'uibook_admin_page'
    );
}
