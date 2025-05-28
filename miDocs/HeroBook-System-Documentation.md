# HeroBook System Documentation
**Villa Community Design System - Section Management**

*Created: May 28, 2025*  
*Version: 1.0.0*

---

## Table of Contents

1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [File Structure](#file-structure)
4. [Admin Interface](#admin-interface)
5. [Helper Functions](#helper-functions)
6. [Block Implementation](#block-implementation)
7. [Usage Examples](#usage-examples)
8. [Customization Guide](#customization-guide)
9. [Integration with Design System](#integration-with-design-system)
10. [Troubleshooting](#troubleshooting)

---

## Overview

The HeroBook system is a comprehensive section management tool within the Villa Community design system. It provides centralized control over hero section layouts, styling, and component visibility across the entire website.

### Key Features

- **Centralized Layout Management**: Control hero section layouts from a single admin interface
- **Component Integration**: Manage avatar groups, CTA buttons, and product showcases
- **Responsive Design**: Mobile-first approach with automatic grid adaptations
- **Design System Integration**: Seamlessly uses ColorBook, TextBook, UiBook, and LayoutBook values
- **Live Preview**: Real-time preview of changes in the admin interface
- **Security**: Built-in nonce verification for all form submissions
- **Flexibility**: Block-level overrides when needed

### Benefits

1. **Consistency**: Ensures uniform hero section styling across the site
2. **Efficiency**: Update multiple hero sections from one location
3. **Maintainability**: Centralized configuration reduces code duplication
4. **User Experience**: Intuitive admin interface for non-technical users
5. **Performance**: Optimized CSS generation and caching

---

## System Architecture

The HeroBook system follows the established Villa Community design system pattern:

```
HeroBook System
├── Admin Interface (inc/herobook.php)
├── Helper Functions (src/helpers/herobook-helper.php)
├── Design System Menu (inc/design-system-menu.php)
└── Block Implementation (src/blocks/Sections/home-hero/)
```

### Core Components

1. **Admin Interface**: WordPress admin page for configuration
2. **Helper Functions**: PHP functions for accessing settings
3. **Block System**: Carbon Blocks integration
4. **CSS Framework**: Responsive styling system

---

## File Structure

```
carbon-blocksy/
├── inc/
│   ├── design-system-menu.php      # Main design system menu
│   └── herobook.php                # HeroBook admin interface
├── src/
│   ├── helpers/
│   │   └── herobook-helper.php     # Helper functions
│   └── blocks/
│       └── Sections/
│           └── home-hero/
│               ├── block.php       # Block configuration
│               ├── block.twig      # Template file
│               └── block.css       # Styling
└── functions.php                   # Integration point
```

### File Descriptions

| File | Purpose | Dependencies |
|------|---------|--------------|
| `inc/herobook.php` | Admin interface and settings management | WordPress admin functions |
| `src/helpers/herobook-helper.php` | Helper functions for accessing settings | HeroBook admin functions |
| `inc/design-system-menu.php` | Hierarchical admin menu structure | WordPress menu functions |
| `src/blocks/Sections/home-hero/block.php` | Block configuration and context | Carbon Fields, HeroBook helpers |
| `src/blocks/Sections/home-hero/block.twig` | Template rendering | Timber, Design system values |
| `src/blocks/Sections/home-hero/block.css` | Responsive styling | CSS Grid, Design system variables |

---

## Admin Interface

### Navigation Path
**WordPress Admin → Design System → Sections → HeroBook**

### Configuration Options

#### Layout Settings
- **Layout Type**: 
  - Asymmetric 40/60 (default)
  - Asymmetric 50/50
  - Centered
- **Content Alignment**: Left, Center, Right
- **Section Height**: Viewport 80%, Viewport 100%, Auto

#### Right Side Layout
- **Product Grid**: 2-column product showcase
- **Feature Cards**: Feature highlight cards
- **Single Image**: Large hero image
- **Gallery**: Image gallery grid

#### Background Styles
- **Solid**: Plain background color
- **Gradient**: Linear gradient background
- **Pattern**: Dotted pattern overlay

#### Component Integration
- **Show Avatar Group**: Toggle avatar group display
- **Show CTA Button**: Toggle call-to-action button
- **Show Product Cards**: Toggle product showcase

#### Security Features
- **Nonce Verification**: All form submissions are secured
- **Capability Checks**: Requires `manage_options` capability
- **Data Sanitization**: All inputs are sanitized before saving

### Live Preview
The admin interface includes a live preview section that updates in real-time as settings are changed, showing:
- Layout structure
- Component visibility
- Background styles
- Content alignment

---

## Helper Functions

### Core Functions

#### `hb_get_template_setting($template, $setting, $format = 'value')`
Retrieves specific hero template settings.

**Parameters:**
- `$template` (string): Template name (e.g., 'home-hero')
- `$setting` (string): Setting key
- `$format` (string): Return format ('value', 'css-var', 'class')

**Example:**
```php
$layout = hb_get_template_setting('home-hero', 'layout_type');
$css_var = hb_get_template_setting('home-hero', 'layout_type', 'css-var');
```

#### `hb_get_layout_classes($template = null)`
Generates CSS classes for hero layouts.

**Returns:** String of CSS classes
**Example:**
```php
$classes = hb_get_layout_classes('home-hero');
// Returns: "hero-template--home-hero hero-layout--asymmetric-40-60 hero-content--left"
```

#### `hb_get_css_properties($template = null)`
Gets CSS custom properties for styling.

**Returns:** Array of CSS properties
**Example:**
```php
$properties = hb_get_css_properties('home-hero');
// Returns: ['--hero-left-width' => '40%', '--hero-right-width' => '60%']
```

#### `hb_show_component($component, $template = null)`
Checks if a component should be displayed.

**Parameters:**
- `$component` (string): Component name ('avatar_group', 'cta_button', 'product_cards')
- `$template` (string): Template name

**Example:**
```php
if (hb_show_component('avatar_group', 'home-hero')) {
    // Render avatar group
}
```

#### `hb_get_context($template = null)`
Gets complete context for Timber templates.

**Returns:** Array with template data, settings, classes, and component visibility

### Utility Functions

#### `hb_get_template_options()`
Returns available template options for select fields.

#### `hb_get_layout_options()`
Returns layout type options.

#### `hb_get_right_layout_options()`
Returns right side layout options.

#### `hb_generate_grid_css($template = null)`
Generates dynamic CSS for grid layouts.

---

## Block Implementation

### Home Hero Block Structure

The Home Hero block (`src/blocks/Sections/home-hero/`) demonstrates the complete implementation pattern:

#### Block Configuration (block.php)
```php
// Carbon Fields configuration
Container::make('post_meta', 'Home Hero Settings')
    ->where('post_template', '=', 'page-templates/home.php')
    ->add_fields(array(
        // Content fields
        Field::make('text', 'hero_title', 'Hero Title'),
        Field::make('textarea', 'hero_description', 'Hero Description'),
        // Component fields
        Field::make('checkbox', 'show_avatar_group', 'Show Avatar Group'),
        // Layout override fields
        Field::make('checkbox', 'use_custom_layout', 'Use Custom Layout')
    ));
```

#### Template Integration (block.twig)
```twig
<section class="carbon-block--home-hero {{ hero.classes }}" {{ hero.style_attribute|raw }}>
    <div class="carbon-block--home-hero__container">
        <div class="carbon-block--home-hero__content hero-left">
            <!-- Content area -->
        </div>
        <div class="carbon-block--home-hero__showcase hero-right">
            <!-- Product showcase -->
        </div>
    </div>
</section>
```

#### Responsive Styling (block.css)
```css
.carbon-block--home-hero__container {
    display: grid;
    gap: 2rem;
    align-items: center;
}

.hero-layout--asymmetric-40-60 .carbon-block--home-hero__container {
    grid-template-columns: 40% 60%;
}

@media (max-width: 768px) {
    .carbon-block--home-hero__container {
        grid-template-columns: 1fr !important;
    }
}
```

### Context Function
The block includes a context function that:
1. Retrieves HeroBook settings
2. Applies custom overrides if specified
3. Integrates with other design system components
4. Provides complete context to the Twig template

---

## Usage Examples

### Basic Implementation

#### 1. Using Default HeroBook Settings
```php
// In your template or block
$hero_context = hb_get_context('home-hero');
echo '<div class="' . $hero_context['classes'] . '">';
```

#### 2. Custom Layout Override
```php
// Check for custom layout
if ($fields['use_custom_layout']) {
    $layout_classes = 'hero-layout--' . $fields['custom_layout_type'];
} else {
    $layout_classes = hb_get_layout_classes('home-hero');
}
```

#### 3. Component Visibility
```php
// Show avatar group only if enabled in HeroBook
if (hb_show_component('avatar_group', 'home-hero')) {
    // Render avatar group component
    include get_template_directory() . '/components/avatar-group.php';
}
```

### Advanced Implementation

#### 1. Dynamic CSS Generation
```php
// Generate CSS based on HeroBook settings
$css = hb_generate_grid_css('home-hero');
wp_add_inline_style('theme-style', $css);
```

#### 2. Multiple Template Support
```php
// Support different hero templates
$templates = ['home-hero', 'page-hero', 'product-hero'];
foreach ($templates as $template) {
    $context = hb_get_context($template);
    // Process each template
}
```

#### 3. Custom Component Integration
```php
// Add custom components to HeroBook
function custom_hero_components($components, $template) {
    $components['custom_banner'] = hb_get_template_setting($template, 'show_custom_banner');
    return $components;
}
add_filter('herobook_components', 'custom_hero_components', 10, 2);
```

---

## Customization Guide

### Adding New Templates

#### 1. Update Admin Interface
```php
// In inc/herobook.php, add new template option
$templates = array(
    'home_hero' => 'Home Hero',
    'page_hero' => 'Page Hero',
    'custom_hero' => 'Custom Hero' // New template
);
```

#### 2. Create Helper Functions
```php
// Add helper function for new template
function hb_get_custom_hero($setting = null, $format = 'value') {
    return hb_get_template_setting('custom_hero', $setting, $format);
}
```

#### 3. Implement Block
Create new block directory: `src/blocks/Sections/custom-hero/`

### Adding New Components

#### 1. Update Admin Interface
```php
// Add new component checkbox
Field::make('checkbox', 'show_testimonial', 'Show Testimonial')
    ->set_default_value(false)
```

#### 2. Update Helper Functions
```php
// Add component check
function hb_show_testimonial($template = null) {
    return hb_show_component('testimonial', $template);
}
```

#### 3. Update Templates
```twig
{% if hero.components.testimonial %}
    <!-- Testimonial component -->
{% endif %}
```

### Custom Styling

#### 1. Override CSS Variables
```css
:root {
    --hero-custom-spacing: 3rem;
    --hero-custom-radius: 12px;
}
```

#### 2. Add Custom Layout Types
```php
// In helper functions
function hb_get_custom_layout_options() {
    return array(
        'asymmetric-30-70' => 'Asymmetric 30/70',
        'triple-column' => 'Triple Column'
    );
}
```

---

## Integration with Design System

### ColorBook Integration
```twig
<h1 style="color: {{ colors.text_dark }};">{{ content.title }}</h1>
<p style="color: {{ colors.text_medium }};">{{ content.description }}</p>
```

### TextBook Integration
```twig
<h1 style="
    font-family: {{ typography.heading_font }};
    font-size: {{ typography.h1_size }};
    font-weight: {{ typography.heading_weight }};
">{{ content.title }}</h1>
```

### UiBook Integration
```twig
<a href="#" style="
    padding: {{ ui.button_padding_y }} {{ ui.button_padding_x }};
    border-radius: {{ ui.button_radius }};
">{{ content.cta_text }}</a>
```

### LayoutBook Integration
```css
.carbon-block--home-hero__container {
    max-width: var(--layout-max-width, 1200px);
    margin: 0 auto;
}
```

---

## Troubleshooting

### Common Issues

#### 1. Settings Not Saving
**Problem**: HeroBook settings don't persist after saving.
**Solution**: 
- Check nonce verification
- Verify user capabilities
- Ensure proper sanitization

```php
// Debug settings save
if (isset($_POST['herobook_nonce']) && wp_verify_nonce($_POST['herobook_nonce'], 'herobook_save')) {
    error_log('Nonce verified successfully');
} else {
    error_log('Nonce verification failed');
}
```

#### 2. Styles Not Applying
**Problem**: HeroBook CSS classes not working.
**Solution**:
- Check CSS file enqueuing
- Verify class generation
- Inspect CSS specificity

```php
// Debug class generation
$classes = hb_get_layout_classes('home-hero');
error_log('Generated classes: ' . $classes);
```

#### 3. Helper Functions Not Found
**Problem**: `hb_` functions not available.
**Solution**:
- Ensure helper file is included in functions.php
- Check file path
- Verify function names

```php
// Check if helper functions are loaded
if (function_exists('hb_get_context')) {
    error_log('HeroBook helpers loaded successfully');
} else {
    error_log('HeroBook helpers not loaded');
}
```

#### 4. Block Not Rendering
**Problem**: Home Hero block doesn't display correctly.
**Solution**:
- Check block registration
- Verify template path
- Inspect context data

```php
// Debug block context
function debug_hero_context($context, $block, $fields) {
    error_log('Hero context: ' . print_r($context, true));
    return $context;
}
add_filter('carbon_blocks_context_home-hero', 'debug_hero_context', 10, 3);
```

### Performance Optimization

#### 1. CSS Caching
```php
// Cache generated CSS
function cache_hero_css($template) {
    $cache_key = 'hero_css_' . $template;
    $css = wp_cache_get($cache_key);
    
    if (false === $css) {
        $css = hb_generate_grid_css($template);
        wp_cache_set($cache_key, $css, '', 3600); // Cache for 1 hour
    }
    
    return $css;
}
```

#### 2. Settings Optimization
```php
// Optimize settings retrieval
function get_cached_hero_settings() {
    static $settings = null;
    
    if (null === $settings) {
        $settings = get_option('herobook_settings', array());
    }
    
    return $settings;
}
```

### Debug Mode

Enable debug mode for troubleshooting:

```php
// Add to wp-config.php
define('HEROBOOK_DEBUG', true);

// Use in helper functions
if (defined('HEROBOOK_DEBUG') && HEROBOOK_DEBUG) {
    error_log('HeroBook Debug: ' . $message);
}
```

---

## Future Enhancements

### Planned Features

1. **Template Library**: Pre-built hero templates
2. **A/B Testing**: Built-in testing capabilities
3. **Animation Controls**: CSS animation management
4. **Advanced Layouts**: More complex grid systems
5. **Component Library**: Expanded component options
6. **Export/Import**: Settings backup and restore
7. **Multi-site Support**: Network-wide settings
8. **Performance Analytics**: Usage tracking and optimization

### Extension Points

The HeroBook system includes several hooks for extensions:

```php
// Filter hero settings before save
apply_filters('herobook_settings_before_save', $settings);

// Filter hero context before rendering
apply_filters('herobook_context', $context, $template);

// Action after settings save
do_action('herobook_settings_saved', $settings);

// Filter available templates
apply_filters('herobook_available_templates', $templates);
```

---

## Conclusion

The HeroBook system provides a robust, scalable solution for managing hero sections within the Villa Community design system. Its integration with the existing ColorBook, TextBook, UiBook, and LayoutBook systems ensures consistency and maintainability across the entire website.

For additional support or questions, refer to the main Carbon Blocks Framework documentation or contact the development team.

---

*This documentation is part of the Villa Community Design System. Last updated: May 28, 2025*
