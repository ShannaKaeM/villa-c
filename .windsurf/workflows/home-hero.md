---
description: HomeHero Block Workflow - DesignBook System Integration
---

# HomeHero Block Development Workflow

## Overview
The HomeHero block is a comprehensive hero section designed for the Villa Community homepage, integrating with the complete DesignBook system for consistent design management.

## DesignBook System Integration

### Core Systems Used
1. **ColorBook** - Color palette and theming
2. **TextBook** - Typography management  
3. **LayoutBook** - Grid and spacing systems
4. **HeroBook** - Hero-specific templates and configurations

### Block Structure
```
src/blocks/Heroes/home-hero/
├── block.php          # Block registration with DesignBook context loading
├── block.twig         # Main template with full DesignBook integration
├── components/        # Reusable components
│   ├── button.twig    # DesignBook-styled buttons
│   └── villa-cards.twig # Dynamic villa display
└── styles/            # Responsive CSS with Book system variables
    ├── LG.css         # Base styles (no media wrapper)
    ├── MD.css         # Tablet overrides
    └── SM.css         # Mobile overrides
```

## Block Features

### Content Fields
- **Main Title** - Primary hero heading
- **Subtitle** - Secondary heading text
- **Description** - Body content with rich text support
- **CTA Buttons** - Primary and secondary call-to-action buttons
- **Background Image** - Hero background with overlay support
- **Statistics Display** - Icon-based stats with numbers and labels
- **Avatar Group** - Customer testimonial avatars
- **Layout Override** - Custom layout options per instance

### Layout Options
- **Asymmetric 40/60** - Content left, media right (40/60 split)
- **Asymmetric 50/50** - Equal split layout
- **Centered** - Single column centered content

### Right Content Types
- **Villa Cards** - Featured property listings
- **Feature Grid** - Service/amenity highlights
- **Image Gallery** - Photo showcase
- **Stats Display** - Large metric displays

## Development Workflow

### 1. Block Registration (block.php)
```php
// DesignBook context loading
$context = Timber::context();
$context['colors'] = cb_get_context();      // ColorBook
$context['typography'] = tb_get_context();  // TextBook
$context['layout'] = lb_get_context();      // LayoutBook
$context['herobook'] = hb_get_context('home-hero'); // HeroBook
```

### 2. Template Integration (block.twig)
```twig
{# Layout determination from HeroBook #}
{% set layout_type = fields.layout_override ?: (context.herobook.settings.layout_type ?? 'asymmetric-40-60') %}
{% set content_alignment = context.herobook.settings.content_alignment ?? 'left' %}

{# DesignBook classes and styling #}
<div class="{{ block.css_class }} {{ context.herobook.classes ?? '' }} hero-content--{{ content_alignment }}">
```

### 3. Component Development
- **Button Component** - Uses ColorBook variables for consistent styling
- **Villa Cards** - Queries villa post type with featured meta
- **Dynamic Content** - Switches based on right_content_type field

### 4. Styling System
- **LG.css** - Base styles using DesignBook CSS variables
- **MD.css** - Tablet responsive overrides (auto-wrapped by framework)
- **SM.css** - Mobile responsive overrides (auto-wrapped by framework)

## CSS Variable Usage

### ColorBook Integration
```css
background-color: var(--wp--custom--color--primary);
color: var(--wp--custom--color--foreground);
border-color: var(--wp--custom--color--contrast);
```

### TextBook Integration
```css
font-family: var(--wp--custom--typography--font-family--heading);
font-size: var(--wp--custom--typography--font-size--huge);
font-weight: var(--wp--custom--typography--font-weight--heading);
```

### LayoutBook Integration
```css
max-width: var(--wp--custom--layout--content-size);
padding: var(--wp--custom--spacing--large);
gap: var(--wp--custom--spacing--medium);
```

## Villa Data Integration

### Query Structure
```twig
{% set villa_args = {
    'post_type': 'villas',
    'posts_per_page': count ?: 4,
    'meta_query': [
        {
            'key': 'is_featured',
            'value': '1',
            'compare': '='
        }
    ]
} %}
```

### Villa Card Features
- Featured image with price badge
- Bedroom/bathroom/guest count
- Location display (city, state)
- Hover effects and responsive design

## Responsive Design

### Breakpoint Strategy
- **Large (LG)** - Multi-column layouts, full feature display
- **Medium (MD)** - Single column, centered content, maintained villa grid
- **Small (SM)** - Stacked elements, single villa column, mobile-optimized

### Key Responsive Features
- Grid layouts collapse gracefully
- Typography scales appropriately
- Button layouts stack on mobile
- Villa cards maintain usability across devices

## Testing Checklist

### Functionality Tests
- [ ] Block appears in Heroes category
- [ ] All fields save and display correctly
- [ ] Villa cards query and display featured properties
- [ ] Buttons link to correct URLs
- [ ] Background images display properly

### DesignBook Integration Tests
- [ ] ColorBook variables apply correctly
- [ ] TextBook typography renders properly
- [ ] LayoutBook spacing and grids work
- [ ] HeroBook settings override correctly

### Responsive Tests
- [ ] Desktop layout displays correctly
- [ ] Tablet layout centers and adjusts
- [ ] Mobile layout stacks appropriately
- [ ] Villa cards responsive behavior works

### Performance Tests
- [ ] CSS compiles without errors
- [ ] JavaScript (if any) loads properly
- [ ] Images optimize and load efficiently
- [ ] No console errors in browser

## Troubleshooting

### Common Issues
1. **Missing DesignBook Context** - Ensure helper functions are loaded
2. **CSS Not Applying** - Check variable names and framework compilation
3. **Villa Cards Not Displaying** - Verify villa post type and featured meta
4. **Layout Issues** - Confirm HeroBook settings and layout overrides

### Debug Steps
1. Check WordPress admin for block registration
2. Verify DesignBook helper functions are available
3. Inspect CSS compilation and variable output
4. Test villa query in WordPress admin
5. Review browser console for JavaScript errors

## Future Enhancements

### Planned Features
- Animation and scroll effects
- Video background support
- Advanced villa filtering
- A/B testing integration
- Performance analytics

### DesignBook Expansions
- Animation Book for motion design
- MediaBook for video/audio management
- PerformanceBook for optimization settings
- AnalyticsBook for conversion tracking

This workflow ensures consistent development practices and seamless integration with the Villa Community DesignBook system.
