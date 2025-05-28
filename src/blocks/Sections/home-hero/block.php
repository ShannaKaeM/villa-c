<?php
/**
 * Home Hero Block
 * Uses HeroBook system for layout and styling
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Block configuration
Container::make('post_meta', 'Home Hero Settings')
    ->where('post_template', '=', 'page-templates/home.php')
    ->or_where('post_type', '=', 'page')
    ->add_fields(array(
        
        // Content Section
        Field::make('separator', 'content_section', 'Content Settings'),
        
        Field::make('text', 'hero_title', 'Hero Title')
            ->set_default_value('Exquisite design combined with functionalities')
            ->set_width(100),
            
        Field::make('textarea', 'hero_description', 'Hero Description')
            ->set_default_value('Pellentesque ullamcorper dignissim condimentum volutpat consectetur mauris nunc lacinia quis.')
            ->set_rows(3)
            ->set_width(100),
            
        Field::make('text', 'cta_text', 'CTA Button Text')
            ->set_default_value('Shop Now')
            ->set_width(50),
            
        Field::make('text', 'cta_url', 'CTA Button URL')
            ->set_default_value('#')
            ->set_width(50),
            
        // Avatar Group Section
        Field::make('separator', 'avatar_section', 'Avatar Group'),
        
        Field::make('checkbox', 'show_avatar_group', 'Show Avatar Group')
            ->set_default_value(true),
            
        Field::make('text', 'avatar_text', 'Avatar Group Text')
            ->set_default_value('Contact with our expert')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_avatar_group',
                    'value' => true,
                )
            )),
            
        // Product Cards Section
        Field::make('separator', 'products_section', 'Product Showcase'),
        
        Field::make('checkbox', 'show_product_cards', 'Show Product Cards')
            ->set_default_value(true),
            
        Field::make('complex', 'product_cards', 'Product Cards')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_product_cards',
                    'value' => true,
                )
            ))
            ->add_fields(array(
                Field::make('text', 'product_name', 'Product Name')
                    ->set_width(50),
                Field::make('text', 'product_price', 'Product Price')
                    ->set_width(50),
                Field::make('image', 'product_image', 'Product Image')
                    ->set_width(50),
                Field::make('select', 'card_size', 'Card Size')
                    ->set_options(array(
                        'normal' => 'Normal',
                        'large' => 'Large (spans 2 rows)'
                    ))
                    ->set_default_value('normal')
                    ->set_width(50),
                Field::make('text', 'product_url', 'Product URL')
                    ->set_width(100)
            ))
            ->set_default_value(array(
                array(
                    'product_name' => 'Wooden Chair',
                    'product_price' => '$199',
                    'card_size' => 'large'
                ),
                array(
                    'product_name' => 'Premium Elite',
                    'product_price' => '$130',
                    'card_size' => 'normal'
                )
            )),
            
        // Promo Card
        Field::make('separator', 'promo_section', 'Promo Card'),
        
        Field::make('checkbox', 'show_promo_card', 'Show Promo Card')
            ->set_default_value(true),
            
        Field::make('text', 'promo_title', 'Promo Title')
            ->set_default_value('25% OFF')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_promo_card',
                    'value' => true,
                )
            ))
            ->set_width(50),
            
        Field::make('text', 'promo_description', 'Promo Description')
            ->set_default_value('Done ac odio tempor dapibus')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_promo_card',
                    'value' => true,
                )
            ))
            ->set_width(50),
            
        Field::make('text', 'promo_button_text', 'Promo Button Text')
            ->set_default_value('Explore Now')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_promo_card',
                    'value' => true,
                )
            ))
            ->set_width(50),
            
        Field::make('text', 'promo_button_url', 'Promo Button URL')
            ->set_default_value('#')
            ->set_conditional_logic(array(
                array(
                    'field' => 'show_promo_card',
                    'value' => true,
                )
            ))
            ->set_width(50),
            
        // Layout Override Section
        Field::make('separator', 'layout_section', 'Layout Override'),
        
        Field::make('checkbox', 'use_custom_layout', 'Use Custom Layout (Override HeroBook)')
            ->set_default_value(false),
            
        Field::make('select', 'custom_layout_type', 'Custom Layout Type')
            ->set_options(array(
                'asymmetric-40-60' => 'Asymmetric 40/60',
                'asymmetric-50-50' => 'Split 50/50',
                'centered' => 'Centered'
            ))
            ->set_conditional_logic(array(
                array(
                    'field' => 'use_custom_layout',
                    'value' => true,
                )
            ))
            ->set_width(50),
            
        Field::make('select', 'custom_content_alignment', 'Custom Content Alignment')
            ->set_options(array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right'
            ))
            ->set_conditional_logic(array(
                array(
                    'field' => 'use_custom_layout',
                    'value' => true,
                )
            ))
            ->set_width(50)
    ));

// Block context function
function home_hero_block_context($context, $block, $fields) {
    // Include HeroBook helper
    require_once get_stylesheet_directory() . '/src/helpers/herobook-helper.php';
    
    // Get HeroBook context
    $hero_context = hb_get_context('home-hero');
    
    // Override with custom layout if specified
    if ($fields['use_custom_layout']) {
        $hero_context['settings']['layout_type'] = $fields['custom_layout_type'];
        $hero_context['settings']['content_alignment'] = $fields['custom_content_alignment'];
        $hero_context['classes'] = hb_get_layout_classes('home-hero');
        $hero_context['css_properties'] = hb_get_css_properties('home-hero');
        $hero_context['style_attribute'] = hb_get_style_attribute('home-hero');
    }
    
    // Add block-specific content
    $context['hero'] = $hero_context;
    $context['content'] = array(
        'title' => $fields['hero_title'],
        'description' => $fields['hero_description'],
        'cta_text' => $fields['cta_text'],
        'cta_url' => $fields['cta_url']
    );
    
    // Avatar group
    $context['avatar_group'] = array(
        'show' => $fields['show_avatar_group'] && $hero_context['components']['avatar_group'],
        'text' => $fields['avatar_text']
    );
    
    // Product cards
    $context['product_showcase'] = array(
        'show' => $fields['show_product_cards'] && $hero_context['components']['product_cards'],
        'cards' => $fields['product_cards'] ?? array()
    );
    
    // Promo card
    $context['promo_card'] = array(
        'show' => $fields['show_promo_card'],
        'title' => $fields['promo_title'],
        'description' => $fields['promo_description'],
        'button_text' => $fields['promo_button_text'],
        'button_url' => $fields['promo_button_url']
    );
    
    // Include design system context
    $context['colors'] = cb_get_all_colors();
    $context['typography'] = tb_get_context();
    $context['layout'] = lb_get_context();
    
    return $context;
}

// Register context filter
add_filter('carbon_blocks_context_home-hero', 'home_hero_block_context', 10, 3);
