<?php
/**
 * Property Sidebar Integration with Blocksy
 * Registers custom sidebar for property pages and integrates with Blocksy's sidebar system
 */

// Register custom sidebar for property pages
function villa_register_property_sidebar() {
    $sidebar_title_tag = blocksy_get_theme_mod('widgets_title_wrapper', 'h3');
    
    register_sidebar(array(
        'name' => __('Property Sidebar', 'carbon-blocksy'),
        'id' => 'sidebar-property',
        'description' => __('Sidebar for property listing pages. Add property filter widgets here.', 'carbon-blocksy'),
        'before_widget' => '<div class="ct-widget is-layout-flow %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<' . $sidebar_title_tag . ' class="widget-title">',
        'after_title' => '</' . $sidebar_title_tag . '>',
    ));
}
add_action('widgets_init', 'villa_register_property_sidebar', 20);

// Hook into Blocksy's sidebar system to use our custom sidebar on property pages
function villa_use_property_sidebar($sidebar_id) {
    // Check if we're on a page with property listing blocks
    if (is_page() && has_block('carbon-blocks/property-listing')) {
        return 'sidebar-property';
    }
    
    // Check if we're on the search properties page
    if (is_page('search-properties')) {
        return 'sidebar-property';
    }
    
    return $sidebar_id;
}

// Hook into Blocksy's sidebar rendering system
add_filter('blocksy:sidebar:get_sidebar_to_render', 'villa_use_property_sidebar');

// Ensure property pages show sidebar by default
function villa_property_page_sidebar_position($position) {
    if (is_page() && has_block('carbon-blocks/property-listing')) {
        return 'right'; // Force sidebar to show on property pages
    }
    
    if (is_page('search-properties')) {
        return 'right';
    }
    
    return $position;
}
add_filter('blocksy:general:sidebar-position', 'villa_property_page_sidebar_position');

// Add custom CSS for property sidebar integration
function villa_property_sidebar_styles() {
    if (is_page() && (has_block('carbon-blocks/property-listing') || is_page('search-properties'))) {
        ?>
        <style>
        /* Property sidebar specific styles */
        #sidebar .villa-property-filter {
            background: var(--theme-background-color, #fff);
            border: 1px solid var(--theme-border-color, #e5e5e5);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Integrate with Blocksy's widget styling */
        #sidebar .villa-property-filter .villa-property-filter__select,
        #sidebar .villa-property-filter .villa-property-filter__checkbox {
            font-family: inherit;
        }
        
        /* Responsive adjustments */
        @media (max-width: 999px) {
            #sidebar .villa-property-filter {
                margin-bottom: 1rem;
            }
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'villa_property_sidebar_styles');

// Add helpful admin notice about property sidebar
function villa_property_sidebar_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'widgets') {
        ?>
        <div class="notice notice-info">
            <p><strong><?php _e('Villa Property Sidebar:', 'carbon-blocksy'); ?></strong> 
            <?php _e('Add the "Villa Property Filter" widget to the "Property Sidebar" to enable filtering on property listing pages.', 'carbon-blocksy'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'villa_property_sidebar_admin_notice');
