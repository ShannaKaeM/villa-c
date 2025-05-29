# HeroBook System Documentation
*Villa Community Design System - Section Level Management*

## Overview

The HeroBook system is a comprehensive hero section management tool for the Villa Community design system. It provides centralized control over hero layouts, styling, and components while integrating seamlessly with the Carbon Blocks Framework.

**Key Features:**
- Centralized hero template management
- Responsive layout system with multiple options
- Component-based architecture
- Design system integration (ColorBook, TextBook, LayoutBook)
- Live preview functionality
- Security with nonce verification
- **UiBook-independent styling** (as of latest update)

## System Architecture

### File Structure
```
inc/
├── herobook.php                    # Admin interface & core functionality
├── design-system-menu.php          # Hierarchical menu structure
└── [other design system files]

src/
├── helpers/
│   └── herobook-helper.php         # Template helper functions
└── blocks/
    └── Sections/
        └── home-hero/
            ├── block.php            # Block registration & context
            ├── block.twig           # Template with inline styling
            └── block.css            # Base component styles

miDocs/
└── HeroBook-System-Documentation.md # This documentation
```

### Design System Integration

The HeroBook integrates with three core design systems:

1. **ColorBook** - Color palette and branding
2. **TextBook** - Typography and text styling  
3. **LayoutBook** - Grid systems and spacing
4. **~~UiBook~~** - *(Removed)* Previously handled UI components, now uses direct CSS values

## Admin Interface

### Menu Structure
```
WordPress Admin → Design System → Sections → HeroBook
```

The HeroBook admin page provides:

- **Layout Management**: Choose from asymmetric 40/60, 50/50 split, or centered layouts
- **Content Alignment**: Left, center, or right alignment options
- **Right Side Layouts**: Grid configurations for product showcases
- **Section Heights**: Viewport-based height controls
- **Background Styles**: Color and styling options
- **Component Toggles**: Enable/disable specific components
- **Live Preview**: Real-time preview of changes

### Settings Available

#### Layout Types
- `asymmetric_40_60`: 40% content, 60% showcase
- `split_50_50`: Equal split layout
- `centered`: Centered content layout

#### Content Alignment
- `left`: Left-aligned content (default)
- `center`: Centered content
- `right`: Right-aligned content

#### Right Side Layouts
- `product_grid_2x2`: 2x2 product grid
- `product_grid_1x3`: 1x3 vertical grid
- `featured_large`: Large featured product

#### Section Heights
- `viewport_full`: 100vh height
- `viewport_75`: 75vh height
- `content_fit`: Auto-fit to content

#### Component Toggles
- Avatar Groups
- CTA Buttons
- Product Cards
- Promo Cards

## Helper Functions

### Core Functions

#### `hb_get_template_setting($setting, $template = 'default')`
Retrieves specific hero template settings.

```php
$layout = hb_get_template_setting('layout_type');
$alignment = hb_get_template_setting('content_alignment');
```

#### `hb_get_layout_classes($template = 'default')`
Generates CSS classes for the hero layout.

```php
$classes = hb_get_layout_classes(); 
// Returns: "hero-asymmetric-40-60 hero-content-left hero-right-product-grid-2x2"
```

#### `hb_get_css_properties($template = 'default')`
Returns CSS custom properties for styling.

```php
$css_props = hb_get_css_properties();
// Returns array of CSS variables for heights, spacing, etc.
```

#### `hb_show_component($component, $template = 'default')`
Checks if a component should be displayed.

```php
if (hb_show_component('avatar_group')) {
    // Show avatar group
}
```

#### `hb_get_context($template = 'default')`
Returns complete context array for Timber templates.

```php
$context = hb_get_context();
// Includes layout, styling, and component data
```

## Block Implementation

### Block Registration (`block.php`)

The home-hero block uses the Carbon Blocks auto-discovery system:

```php
// Auto-detected block name: home-hero
// Auto-detected category: Sections
// Template: block.twig
// Styles: block.css
```

Key features:
- **Design System Integration**: Loads ColorBook, TextBook, and LayoutBook contexts
- **HeroBook Integration**: Uses helper functions for layout and styling
- **Component Management**: Handles avatar groups, CTA buttons, and product cards
- **Security**: Includes nonce verification for admin settings

### Template Structure (`block.twig`)

The template uses **inline styling** with design system values instead of UiBook dependencies:

```twig
{# Direct CSS values instead of UiBook #}
<a href="{{ content.cta_url }}" 
   style="
       background: {{ colors.primary }};
       color: {{ colors.white }};
       padding: 0.75rem 1.5rem;        {# Direct value #}
       border-radius: 8px;             {# Direct value #}
       font-family: {{ typography.body_font }};
   ">
```

**Key Template Features:**
- **Responsive Design**: Mobile-first approach with CSS Grid
- **Component Integration**: Avatar groups, CTA buttons, product cards
- **Hover Effects**: Interactive elements with CSS transitions
- **Image Handling**: Timber image processing with fallbacks
- **Design System Values**: Uses ColorBook and TextBook variables

### Styling Approach

**Updated Styling Strategy (Post-UiBook Removal):**

1. **Direct CSS Values**: Common UI elements use hardcoded, consistent values
   - Button padding: `0.75rem 1.5rem`
   - Border radius: `8px` (buttons), `12px` (cards), `6px` (tags)
   - Box shadows: `0 4px 6px rgba(0, 0, 0, 0.1)`

2. **Design System Integration**: Dynamic values from other Books
   - Colors: `{{ colors.primary }}`, `{{ colors.white }}`
   - Typography: `{{ typography.body_font }}`, `{{ typography.h1_size }}`
   - Layout: `{{ layout.container_max_width }}`

3. **Responsive Behavior**: CSS Grid with automatic breakpoint handling
   - Mobile: Single column, stacked layout
   - Tablet: Adjusted grid with sidebar
   - Desktop: Full grid with product showcase

## Layout System

### Asymmetric 40/60 Layout
- **Left**: 40% width for content
- **Right**: 60% width for product showcase
- **Best for**: Product-focused hero sections

### Split 50/50 Layout  
- **Left**: 50% width for content
- **Right**: 50% width for showcase
- **Best for**: Balanced content and visuals

### Centered Layout
- **Full width**: Centered content
- **No sidebar**: Focus on main message
- **Best for**: Simple, message-focused heroes

## Component System

### Avatar Groups
Displays user avatars with accompanying text.

```twig
{% if avatar_group.show %}
<div class="carbon-block--home-hero__avatar-group">
    <div class="carbon-block--home-hero__avatars">
        <div class="carbon-block--home-hero__avatar">👤</div>
        <!-- More avatars -->
    </div>
    <span>{{ avatar_group.text }}</span>
</div>
{% endif %}
```

### CTA Buttons
Primary call-to-action buttons with hover effects.

```twig
<a href="{{ content.cta_url }}" 
   class="carbon-block--home-hero__cta-button"
   style="background: {{ colors.primary }}; padding: 0.75rem 1.5rem;">
    {{ content.cta_text }}
</a>
```

### Product Cards
Showcase product cards with images, pricing, and links.

```twig
<div class="carbon-block--home-hero__product-card">
    <img src="{{ Image(card.product_image).src }}" alt="{{ card.product_name }}">
    <div class="carbon-block--home-hero__price-tag">
        {{ card.product_name }}<br>
        <strong>{{ card.product_price }}</strong>
    </div>
</div>
```

### Promo Cards
Special promotional cards with dark backgrounds.

```twig
<div class="carbon-block--home-hero__promo-card"
     style="background: {{ colors.text_dark }}; color: {{ colors.white }};">
    <h3>{{ promo_card.title }}</h3>
    <p>{{ promo_card.description }}</p>
    <a href="{{ promo_card.button_url }}">{{ promo_card.button_text }}</a>
</div>
```

## Usage Examples

### Basic Hero Implementation

```php
// In your block or template
require_once get_stylesheet_directory() . '/src/helpers/herobook-helper.php';

// Get hero context
$hero_context = hb_get_context('default');

// Add to Timber context
$context['hero'] = $hero_context;
$context['content'] = array(
    'title' => 'Welcome to Villa Community',
    'description' => 'Discover your perfect vacation rental',
    'cta_text' => 'Browse Villas',
    'cta_url' => '/villas'
);
```

### Custom Template Usage

```twig
{# In your Twig template #}
<section class="carbon-block--home-hero {{ hero.classes }}" {{ hero.style_attribute|raw }}>
    <div class="carbon-block--home-hero__container">
        <div class="carbon-block--home-hero__content">
            <h1 style="color: {{ colors.text_dark }};">{{ content.title }}</h1>
            <p style="color: {{ colors.text_medium }};">{{ content.description }}</p>
            
            {% if hero.components.cta_button %}
            <a href="{{ content.cta_url }}" 
               style="background: {{ colors.primary }}; padding: 0.75rem 1.5rem; border-radius: 8px;">
                {{ content.cta_text }}
            </a>
            {% endif %}
        </div>
    </div>
</section>
```

## Security Features

- **Nonce Verification**: All form submissions include WordPress nonces
- **Capability Checks**: Requires `manage_options` capability
- **Data Sanitization**: All inputs are sanitized before storage
- **Escaping**: All outputs are properly escaped

## Recent Updates

### Version 2.0 - UiBook Independence
*Updated: May 28, 2025*

**Major Changes:**
- **Removed UiBook Dependencies**: All UI styling now uses direct CSS values
- **Enhanced Template System**: Improved inline styling approach
- **Simplified Maintenance**: Reduced complexity by eliminating UiBook references
- **Consistent Styling**: Standardized button, card, and component styles

**Migration Notes:**
- Existing hero sections automatically use new direct CSS values
- No breaking changes to existing implementations
- Improved performance with fewer design system dependencies

### Benefits of UiBook Removal:
1. **Simplified Codebase**: Fewer dependencies to manage
2. **Consistent Styling**: Hardcoded values ensure consistency
3. **Better Performance**: Reduced system calls and processing
4. **Easier Maintenance**: Direct CSS is easier to debug and modify
5. **Future-Proof**: Less dependent on external systems

## Best Practices

### Template Development
1. **Use Helper Functions**: Always use HeroBook helpers for consistency
2. **Design System Integration**: Leverage ColorBook and TextBook values
3. **Responsive Design**: Test layouts across all breakpoints
4. **Component Modularity**: Keep components reusable and independent
5. **Direct Styling**: Use inline styles with design system variables for UI elements

### Performance Optimization
1. **Lazy Loading**: Implement for product images
2. **CSS Optimization**: Minimize inline styles where possible
3. **Image Optimization**: Use appropriate image sizes
4. **Caching**: Leverage WordPress caching for settings

### Accessibility
1. **Semantic HTML**: Use proper heading hierarchy
2. **Alt Text**: Include descriptive alt text for images
3. **Keyboard Navigation**: Ensure all interactive elements are accessible
4. **Color Contrast**: Verify sufficient contrast ratios

## Troubleshooting

### Common Issues

**Hero not displaying correctly:**
- Check if HeroBook settings are saved
- Verify design system integration
- Ensure helper functions are loaded

**Styling issues:**
- Confirm ColorBook and TextBook are active
- Check for CSS conflicts
- Verify direct CSS values are properly applied

**Component not showing:**
- Check component toggle settings in HeroBook admin
- Verify component data is provided
- Ensure template conditions are met

### Debug Mode
Enable WordPress debug mode to see detailed error messages:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Future Enhancements

### Planned Features
- **Animation System**: CSS and JavaScript animations
- **A/B Testing**: Built-in testing capabilities
- **Advanced Layouts**: Additional layout options
- **Component Library**: Expanded component system
- **Performance Metrics**: Built-in performance monitoring

### Integration Opportunities
- **WooCommerce**: Product integration
- **Custom Post Types**: Villa listings integration
- **SEO Optimization**: Schema markup
- **Analytics**: Conversion tracking

---

*This documentation is maintained as part of the Villa Community design system. For questions or contributions, please refer to the project repository.*
