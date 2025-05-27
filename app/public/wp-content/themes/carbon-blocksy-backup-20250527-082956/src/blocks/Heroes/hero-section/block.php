<?php
/**
 * Dynamic Carbon Block
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Debug: Log that this file is being loaded
error_log('=== HERO SECTION BLOCK.PHP LOADED ===');

if (! defined('ABSPATH')) {
    exit;
}

// Get component name from parent directory
$component = basename(dirname(__FILE__));
error_log('Component detected: ' . $component);

// Get category from grandparent directory
$category = basename(dirname(dirname(__FILE__)));
error_log('Category detected: ' . $category);

/**
 * Register Gutenberg Block dynamically
 */
error_log('=== CREATING HERO SECTION BLOCK ===');
error_log('Block title will be: ' . ucwords(str_replace('-', ' ', $component)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields([
        // Content
        Field::make('text', 'title', __('Title')),
        Field::make('text', 'subtitle', __('Subtitle')),
        Field::make('image', 'background_image', __('Background Image')),
        
        // Layout
        Field::make('select', 'text_alignment', __('Text Alignment'))
            ->set_options([
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ])
            ->set_default_value('center'),
        Field::make('select', 'width_type', __('Width Type'))
            ->set_options([
                'full' => 'Full Width',
                'contained' => 'Contained',
            ])
            ->set_default_value('full'),
        Field::make('text', 'height', __('Height (px)'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 200)
            ->set_attribute('max', 800)
            ->set_default_value(350),
            
        // Title Typography
        Field::make('separator', 'title_separator', __('Title Typography')),
        Field::make('select', 'title_color', __('Title Color'))
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
        Field::make('text', 'title_font_size', __('Title Font Size (rem)'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 1)
            ->set_attribute('max', 6)
            ->set_attribute('step', 0.1)
            ->set_default_value('3'),
        Field::make('select', 'title_font_weight', __('Title Font Weight'))
            ->set_options([
                '300' => 'Light (300)',
                '400' => 'Normal (400)',
                '500' => 'Medium (500)',
                '600' => 'Semi Bold (600)',
                '700' => 'Bold (700)',
                '800' => 'Extra Bold (800)',
                '900' => 'Black (900)',
            ])
            ->set_default_value('700'),
            
        // Subtitle Typography
        Field::make('separator', 'subtitle_separator', __('Subtitle Typography')),
        Field::make('select', 'subtitle_color', __('Subtitle Color'))
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
            ->set_default_value('base-dark'),
        Field::make('text', 'subtitle_font_size', __('Subtitle Font Size (rem)'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 0.5)
            ->set_attribute('max', 3)
            ->set_attribute('step', 0.1)
            ->set_default_value('1.125'),
        Field::make('select', 'subtitle_font_weight', __('Subtitle Font Weight'))
            ->set_options([
                '300' => 'Light (300)',
                '400' => 'Normal (400)',
                '500' => 'Medium (500)',
                '600' => 'Semi Bold (600)',
                '700' => 'Bold (700)',
            ])
            ->set_default_value('400'),
            
        // Background & Overlay
        Field::make('separator', 'background_separator', __('Background & Overlay')),
        Field::make('select', 'overlay_color', __('Overlay Color'))
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
            ->set_help_text('Optional overlay color'),
        Field::make('text', 'overlay_opacity', __('Overlay Opacity (%)'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 0)
            ->set_attribute('max', 100)
            ->set_default_value(50)
            ->set_help_text('Overlay transparency (0 = transparent, 100 = opaque)'),
    ])
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        // TEMPORARY DEBUG: Return simple test content
        return '<div style="background: red; color: white; padding: 20px; font-size: 24px;">🚨 HERO SECTION BLOCK IS WORKING! 🚨<br>Component: ' . $component . '<br>Category: ' . $category . '<br>Title: ' . ($fields['title'] ?? 'No title') . '</div>';
    });
