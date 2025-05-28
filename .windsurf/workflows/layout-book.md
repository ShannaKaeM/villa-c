---
description: LayoutBook system documentation and grid layout management
---

# LayoutBook - Layout & Grid Management System

LayoutBook provides a centralized system for managing grid presets, spacing systems, and layout configurations across your site.

## Access LayoutBook

1. Go to WordPress Admin → LayoutBook
2. Configure grid presets, spacing, and breakpoints
3. Use presets in Grid Components and blocks

## Grid Presets

### Available Presets
- **Cards Grid**: 280px-350px, 2rem gaps - Perfect for card layouts
- **Features Grid**: 250px-300px, 1.5rem gaps - Icon + text features  
- **Gallery Grid**: 200px-250px, 1rem gaps - Image galleries
- **Hero Layout**: 400px+, 3rem gaps - Large hero sections

### Using Presets in Components

```php
// In component fields
Field::make('select', 'layout_preset', 'Layout Preset')
    ->set_options([
        'cards' => 'Cards Grid',
        'features' => 'Features Grid',
        'gallery' => 'Gallery Grid',
        'hero' => 'Hero Layout',
        'custom' => 'Custom Settings'
    ])
```

```twig
{# In component template #}
{% set preset = component.layout_preset|default('cards') %}
{% if preset != 'custom' %}
    {% set layout_data = lb_grid_template(preset) %}
{% endif %}
```

## Helper Functions

### Get Layout Settings
```php
// Get grid preset
$preset = lb_get_grid_preset('cards');

// Get spacing value
$spacing = lb_get_spacing('lg'); // Returns 2rem

// Get breakpoint
$breakpoint = lb_get_breakpoint('md'); // Returns 768px

// Generate CSS properties
$css = lb_generate_css_props(['custom-prop' => 'value']);
```

### Grid CSS Generation
```php
// Auto-fit grid CSS
$grid_css = lb_get_grid_css('cards'); 
// Returns: repeat(auto-fit, minmax(280px, 350px))

// Custom grid CSS
$grid_css = lb_get_grid_css(null, '300px', 'none');
// Returns: repeat(auto-fit, minmax(300px, 1fr))
```

## Spacing System

LayoutBook provides consistent spacing values:
- **XS**: 0.5rem (8px)
- **SM**: 1rem (16px) 
- **MD**: 1.5rem (24px)
- **LG**: 2rem (32px)
- **XL**: 3rem (48px)
- **XXL**: 4rem (64px)

Use in CSS:
```css
.my-element {
    margin: var(--layout-spacing-lg);
    padding: var(--layout-spacing-md);
}
```

## Responsive Breakpoints

Standard breakpoints for responsive design:
- **SM**: 640px - Small devices
- **MD**: 768px - Tablets
- **LG**: 1024px - Laptops
- **XL**: 1280px - Desktops
- **XXL**: 1536px - Large screens

## Layout Templates

### Hero Section Template
```
┌─────────────────────────────────────────┐
│  Large Text/Image (span: full)          │
├─────────────┬─────────────┬─────────────┤
│   Button    │   Button    │   Button    │
└─────────────┴─────────────┴─────────────┘
```

### Feature Grid Template
```
┌─────────────┬─────────────┬─────────────┤
│ 🏠 Icon+Text│ 📍 Icon+Text│ 💰 Icon+Text│
├─────────────┼─────────────┼─────────────┤
│ 🛏️ Icon+Text│ 👥 Icon+Text│ ✨ Icon+Text│
└─────────────┴─────────────┴─────────────┘
```

### Mixed Content Template
```
┌─────────────────────────────┬───────────┐
│  Card with Image (span: 2)  │   Image   │
├─────────────┬─────────────┬─────────────┤
│    Text     │    Text     │   Button    │
└─────────────┴─────────────┴─────────────┘
```

## Best Practices

1. **Use Presets First**: Start with predefined presets before creating custom layouts
2. **Consistent Spacing**: Use the spacing system for margins and padding
3. **Responsive Design**: Test layouts across all breakpoints
4. **Performance**: Presets are optimized for performance and consistency
5. **Maintenance**: Update presets in LayoutBook rather than individual components

## Integration with Existing Components

### Grid Component
- Select preset from dropdown
- Automatically applies min/max widths and gaps
- Override with custom settings if needed

### Villa Grid Block
- Can use LayoutBook presets for consistent card sizing
- Inherits spacing system for gaps

### Custom Components
- Include LayoutBook helper in component.php
- Use helper functions in templates
- Apply CSS custom properties for dynamic styling

## File Structure

```
/inc/layoutbook.php              # Main LayoutBook system
/src/helpers/layoutbook-helper.php   # Helper functions
/miDocs/SITE DATA/layoutbook.json    # Saved settings
/miDocs/theme.json               # Theme integration
```

## Keyboard Shortcuts

- **Ctrl/Cmd + S**: Save layout settings
- **Tab Navigation**: Switch between preset tabs
- **Live Preview**: Updates automatically as you type

## Troubleshooting

### Presets Not Applying
1. Check if LayoutBook is loaded in functions.php
2. Verify helper functions are included
3. Clear any caching

### CSS Not Updating
1. Save LayoutBook settings to regenerate CSS variables
2. Check browser developer tools for CSS custom properties
3. Ensure grid template is being applied correctly

### Performance Issues
1. Use presets instead of custom CSS for each component
2. Minimize the number of different grid configurations
3. Leverage CSS custom properties for dynamic values
