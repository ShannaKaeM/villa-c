<?php
/**
 * Dynamic Carbon Block
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

if (! defined('ABSPATH')) {
    exit;
}

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory
$category = basename(dirname(dirname(__FILE__)));

/**
 * Register Gutenberg Block dynamically
 */
Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields([
        Field::make('text', 'title', __('Title')),
        Field::make('select', 'title_variant', __('Title Style'))
            ->set_options([
                'hero' => 'Hero (Large)',
                'card' => 'Card (Medium)',
            ])
            ->set_default_value('hero')
            ->set_help_text('Choose the size and style variant for the title'),
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
        Field::make('select', 'title_tag', __('Title HTML Tag'))
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
        Field::make('text', 'subtitle', __('Subtitle')),
        Field::make('select', 'subtitle_variant', __('Subtitle Style'))
            ->set_options([
                'hero' => 'Hero (Large)',
                'card' => 'Card (Medium)',
                'caption' => 'Caption (Small)',
            ])
            ->set_default_value('hero')
            ->set_help_text('Choose the size and style variant for the subtitle'),
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
        Field::make('select', 'subtitle_tag', __('Subtitle HTML Tag'))
            ->set_options([
                'p' => 'Paragraph',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'span' => 'Span',
                'div' => 'Div',
            ])
            ->set_default_value('p')
            ->set_help_text('Choose the semantic HTML tag for SEO and accessibility'),
        Field::make('text', 'button_text', __('Button Text')),
        Field::make('text', 'button_url', __('Button URL')),
        Field::make('image', 'background_image', __('Background Image')),
        Field::make('select', 'text_alignment', __('Text Alignment'))
            ->set_options([
                'left'   => 'Left',
                'center' => 'Center',
                'right'  => 'Right',
            ])
            ->set_default_value('center'),
        Field::make('select', 'width_type', __('Width Type'))
            ->set_options([
                'full'    => 'Full Width',
                'content' => 'Content Width',
            ])
            ->set_default_value('full')
            ->set_help_text('Choose between full viewport width or constrained to content width'),
        Field::make('number', 'height', __('Height (pixels)'))
            ->set_attribute('min', 200)
            ->set_attribute('max', 1000)
            ->set_default_value(350)
            ->set_help_text('Set the height of the hero section in pixels'),
            
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
            ->set_default_value('base-white')
            ->set_help_text('Choose the color for the overlay that sits on top of the background image'),
        Field::make('text', 'overlay_opacity', __('Overlay Transparency (0-100)'))
            ->set_attribute('type', 'number')
            ->set_attribute('min', 0)
            ->set_attribute('max', 100)
            ->set_attribute('step', 5)
            ->set_default_value('80')
            ->set_help_text('Enter a number from 0 (transparent) to 100 (opaque)'),
    ])
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        $block_name = $category . '/' . $component;
        
        // Create base context
        $context = carbon_blocks_create_context($block_name, $fields);
        
        // Add component styles - include title component styles
        $title_component_styles = carbon_blocks_compile_component_styles('title');
        $context['block']['styles'] .= $title_component_styles;
        
        return Timber::compile('@blocks/' . $block_name . '/block.twig', $context);
    });
