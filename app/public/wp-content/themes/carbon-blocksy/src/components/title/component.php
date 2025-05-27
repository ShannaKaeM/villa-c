<?php
/**
 * Title Component
 * 
 * Reusable title component with preset styles
 * Variants: hero, card
 */

use Carbon_Fields\Field;

// Register component styles for compilation
add_action('init', function() {
    // Register component styles with the theme's style compilation system
    if (function_exists('carbon_blocks_register_component_styles')) {
        carbon_blocks_register_component_styles('title', __DIR__ . '/styles');
    }
});

/**
 * Get title component configuration
 * 
 * @return array Component field configuration
 */
function get_title_component_fields($prefix = '') {
    $prefix = $prefix ? $prefix . '_' : '';
    
    return [
        Field::make('text', $prefix . 'title_text', __('Title Text')),
        Field::make('select', $prefix . 'title_variant', __('Title Style'))
            ->set_options([
                'hero' => 'Hero (Large)',
                'card' => 'Card (Medium)',
            ])
            ->set_default_value('hero')
            ->set_help_text('Choose the size and style variant for the title'),
        Field::make('select', $prefix . 'title_color', __('Title Color'))
            ->set_options([
                'primary-light'     => 'Primary Light',
                'primary'           => 'Primary',
                'primary-dark'      => 'Primary Dark',
                'secondary-light'   => 'Secondary Light',
                'secondary'         => 'Secondary',
                'secondary-dark'    => 'Secondary Dark',
                'neutral-light'     => 'Neutral Light',
                'neutral'           => 'Neutral',
                'neutral-dark'      => 'Neutral Dark',
                'base-lightest'     => 'Base Lightest',
                'base-light'        => 'Base Light',
                'base'              => 'Base',
                'base-dark'         => 'Base Dark',
                'base-darkest'      => 'Base Darkest',
                'base-white'        => 'Base White',
                'base-black'        => 'Base Black',
            ])
            ->set_default_value('base-black'),
        Field::make('select', $prefix . 'title_tag', __('HTML Tag'))
            ->set_options([
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
            ])
            ->set_default_value('h1')
            ->set_help_text('Choose the semantic HTML tag for SEO and accessibility'),
    ];
}
