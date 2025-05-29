# DesignBook System

**Modern Design System for WordPress Themes**

The DesignBook system provides a comprehensive design system with WordPress admin integration, focusing on styling and design tokens rather than complex admin configurations.

## 📁 Directory Structure

```
designbook/
├── admin-pages/          # WordPress admin pages (moved out of src/)
│   ├── page.php         # Main DesignBook admin page
│   ├── colorbook/       # Color management sub-page
│   │   └── page.php
│   └── textbook/        # Typography management sub-page
│       └── page.php
├── helpers/             # PHP helper functions
│   ├── designbook-helper.php  # Data access functions
│   └── designbook-css.php     # CSS generation functions
├── styles/              # CSS design system
│   └── designbook.css   # Main design system CSS
└── README.md           # This documentation
```

## 🎨 Design System Features

### Color System
- **OKLCH color space** for perceptually uniform colors
- **Primary/Secondary** color palettes with light/dark variants
- **Neutral scale** (100-900) for consistent grays
- **Semantic colors** (success, warning, error, info)
- **Utility classes** for quick color application

### Typography System
- **Responsive font sizes** using clamp() for fluid scaling
- **Font family variables** for consistent typography
- **Line height and letter spacing** tokens
- **Typography utility classes** for rapid development

### Spacing System
- **Responsive spacing** using clamp() for fluid layouts
- **Consistent scale** from xs to 4xl
- **Padding and margin utilities** for layout control

### Component System
- **Button variants** (primary, secondary, outline)
- **Card components** with hover effects
- **Form elements** with focus states
- **Typography components** (headings, text)
- **Layout utilities** (grid, flex, container)

## 🔧 Usage

### CSS Variables
All design tokens are available as CSS custom properties:

```css
/* Colors */
color: var(--db-color-primary);
background-color: var(--db-color-neutral-100);

/* Typography */
font-size: var(--db-font-size-lg);
font-family: var(--db-font-primary-sans);

/* Spacing */
padding: var(--db-spacing-md);
margin: var(--db-spacing-lg);

/* Shadows */
box-shadow: var(--db-shadow-md);
```

### Utility Classes
Quick styling with predefined utility classes:

```html
<!-- Colors -->
<div class="db-bg-primary db-text-white">Primary background</div>
<p class="db-text-neutral-700">Neutral text</p>

<!-- Typography -->
<h1 class="db-heading db-heading--h1">Large heading</h1>
<p class="db-text db-text--large">Large text</p>

<!-- Spacing -->
<div class="db-p-lg db-m-md">Padded and margined</div>

<!-- Layout -->
<div class="db-container">
  <div class="db-grid db-grid--3">
    <div class="db-card">Card 1</div>
    <div class="db-card">Card 2</div>
    <div class="db-card">Card 3</div>
  </div>
</div>
```

### Component Classes
Ready-to-use component styles:

```html
<!-- Buttons -->
<button class="db-button db-button--primary">Primary Button</button>
<button class="db-button db-button--secondary">Secondary Button</button>
<button class="db-button db-button--outline">Outline Button</button>

<!-- Cards -->
<div class="db-card">
  <h3 class="db-heading db-heading--h3">Card Title</h3>
  <p class="db-text">Card content goes here.</p>
</div>

<!-- Forms -->
<input type="text" class="db-input" placeholder="Enter text">
```

## 🎯 Benefits

### For Developers
- **Consistent design tokens** across all components
- **Responsive by default** with fluid scaling
- **Modern CSS features** (custom properties, clamp, oklch)
- **Utility-first approach** for rapid development
- **Component library** for common UI patterns

### For Designers
- **Unified color system** with perceptual uniformity
- **Responsive typography** that scales beautifully
- **Consistent spacing** for harmonious layouts
- **Modern shadows and effects** for depth
- **Design tokens** that translate directly to code

### For Content Creators
- **WordPress admin integration** for easy customization
- **Live preview** of design changes
- **No code required** for color and typography updates
- **Consistent branding** across all content

## 🚀 Integration

The DesignBook system is automatically loaded in `functions.php`:

```php
// Load DesignBook System
require_once get_stylesheet_directory() . '/designbook/helpers/designbook-helper.php';
require_once get_stylesheet_directory() . '/designbook/helpers/designbook-css.php';

// Enqueue DesignBook CSS
wp_enqueue_style('designbook-styles', 
    get_stylesheet_directory_uri() . '/designbook/styles/designbook.css');

// Output dynamic CSS variables from admin
add_action('wp_head', function() {
    if (carbon_get_theme_option('designbook_enabled')) {
        echo "<style id='designbook-css'>\n";
        echo db_generate_color_css();
        echo db_generate_typography_css();
        echo "</style>\n";
    }
});
```

## 📋 Next Steps

1. **Test admin pages** - Verify ColorBook and TextBook appear in WordPress admin
2. **Add design tokens** - Use admin interface to define colors and typography
3. **Apply to blocks** - Use DesignBook classes in block templates
4. **Customize theme.json** - Sync design tokens to Gutenberg editor
5. **Create documentation** - Document custom design patterns

## 🔄 Migration from Old System

The DesignBook system replaces the old individual book systems:
- ✅ **ColorBook** → Integrated into DesignBook ColorBook sub-page
- ✅ **TextBook** → Integrated into DesignBook TextBook sub-page
- ✅ **JSON files** → WordPress theme options storage
- ✅ **Individual helpers** → Unified DesignBook helper system
- ✅ **Scattered CSS** → Centralized design system CSS

This provides a cleaner, more maintainable approach to design system management.
