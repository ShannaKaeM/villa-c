---
description: Villa Community Theme Integration - Working with Blocksy's theme system
---

# Villa Community Theme Integration

This workflow explains how the Villa Community project integrates with Blocksy's built-in theming system rather than overriding it with custom theme.json.

## Framework Architecture

The **Carbon Blocks Framework** is designed to work **with** Blocksy as the parent theme, not replace its theming system:

- **Blocksy handles**: Core theme styling, colors, typography, spacing, customizer options
- **Carbon Blocks handles**: Custom blocks, post types, taxonomies, and admin functionality
- **ColorBook system**: Syncs Villa colors with Blocksy's color palette

## Integration Approach

### 1. Let Blocksy Handle Theme Styling

Instead of creating a custom `theme.json`, we leverage Blocksy's existing systems:

```php
// ColorBook syncs with Blocksy's customizer
function colorbook_sync_with_blocksy($colors) {
    // Map Villa colors to Blocksy palette positions
    foreach ($colors as $index => $color) {
        set_theme_mod("palette_color_" . ($index + 1), $color['color']);
    }
}
```

### 2. Remove Hardcoded Styles from Blocks

Blocks should use minimal CSS and let Blocksy provide:
- Typography (font families, sizes, weights)
- Color palette (primary, secondary, accent colors)
- Spacing system (margins, padding)
- Layout constraints (container widths)

### 3. Use Blocksy's CSS Custom Properties

Blocksy automatically generates CSS custom properties that blocks can use:

```css
/* Instead of hardcoded colors */
.carbon-block--property-listing__button {
    background: var(--theme-palette-color-1); /* Blocksy's primary color */
    color: var(--theme-palette-color-8); /* Blocksy's text color */
}

/* Instead of hardcoded typography */
.carbon-block--property-listing__title {
    /* Let Blocksy handle font-family, font-size, line-height */
}
```

## Villa Community Implementation

### ColorBook Integration
The ColorBook system provides a bridge between Villa's design system and Blocksy:

1. **Admin Interface**: Manage Villa's 16-color palette
2. **Blocksy Sync**: Colors automatically sync to Blocksy customizer
3. **CSS Variables**: Available throughout the site via Blocksy's system

### Block Development Philosophy
- **Structure over Style**: Focus on layout and functionality
- **Theme Integration**: Let Blocksy handle visual styling
- **Responsive Design**: Use Blocksy's breakpoint system
- **Accessibility**: Leverage Blocksy's built-in accessibility features

## Development Workflow

### 1. Create Block Structure
```bash
# Focus on functionality, not styling
mkdir -p src/blocks/Category/block-name/{styles,scripts,components}
```

### 2. Minimal CSS Approach
```css
/* LG.css - Structure only */
.carbon-block--example {
    display: grid;
    gap: 1rem;
    /* Let Blocksy handle colors, typography, spacing */
}
```

### 3. Leverage Blocksy's System
```twig
{# block.twig - Clean markup #}
<section class="carbon-block--example">
    <h2>{{ fields.title }}</h2>
    <p>{{ fields.description }}</p>
</section>
```

### 4. Use ColorBook for Brand Colors
```php
// Access Villa colors through Blocksy's system
$primary_color = get_theme_mod('palette_color_1'); // Villa Primary
$secondary_color = get_theme_mod('palette_color_2'); // Villa Secondary
```

## Benefits of This Approach

1. **Consistency**: All styling comes from one source (Blocksy)
2. **Maintainability**: Changes in Blocksy customizer affect entire site
3. **Performance**: No duplicate CSS or conflicting styles
4. **User Experience**: Site admins use familiar Blocksy customizer
5. **Updates**: Blocksy updates improve entire site automatically

## Key Files

- **ColorBook System**: `/inc/colorbook.php` - Syncs Villa colors with Blocksy
- **Block Styles**: Minimal CSS focusing on layout only
- **Blocksy Customizer**: Primary interface for theme styling
- **Carbon Blocks**: Handle functionality, not visual styling

## Best Practices

1. **Never override** Blocksy's core styling with theme.json
2. **Always use** Blocksy's color and typography systems
3. **Focus blocks** on structure and functionality
4. **Leverage ColorBook** for brand color management
5. **Test integration** with different Blocksy theme options

This approach ensures Villa Community blocks integrate seamlessly with Blocksy while maintaining the brand's visual identity through the ColorBook system.
