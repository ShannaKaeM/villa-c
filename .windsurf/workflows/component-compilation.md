---
description: DesignBook Component Compilation Workflow
---

# DesignBook Component Compilation Workflow

This workflow demonstrates how to manually compile a section using the DesignBook system's layered architecture.

## Architecture Overview

```
Foundation Layer (TextBook Semantic Elements)
    ↓
Element Layer (TextBlockBook Components)  
    ↓
Component Layer (Hero Layout Elements)
    ↓
Section Layer (Split Screen Hero Block)
    ↓
Page Implementation
```

## Step 1: Foundation Layer - Semantic Text Elements

### Create Semantic Elements in TextBook
- **Location**: `inc/textbook-semantic.php`
- **Purpose**: Define base styling for semantic text elements
- **Elements**: Pretitle, Title, Description, Body, etc.

```php
// Get semantic element
$pretitle_styles = tb_get_semantic_element('pretitle', 'base');
```

### Key Features:
- Font family, size, weight controls
- Color and spacing settings  
- Text transform and letter spacing
- Live preview in admin

## Step 2: Element Layer - Text Block Components

### Compile Text Blocks in TextBlockBook
- **Location**: `inc/textblockbook.php`
- **Purpose**: Combine semantic elements into reusable text blocks
- **Admin**: Design System → Elements → TextBlockBook

```php
// Get compiled text block
$hero_text_block = tbb_get_text_block('hero-text-block', 'hero');
```

### Composition Features:
- Element slot system (pretitle, title, description)
- Variant creation (hero, card, feature)
- Layout controls (alignment, spacing, max-width)
- Live preview and code export

## Step 3: Component Layer - Layout Elements

### Create Layout Variants in Hero Layouts
- **Location**: `inc/hero-layouts.php`
- **Purpose**: Define layout patterns for hero sections
- **Admin**: Design System → Components → Hero Layouts

```php
// Get hero layout
$split_layout = hlb_get_hero_layout('split-screen', 'split-40-60');
```

### Layout Options:
- Split Screen (40/60, 50/50, 60/40)
- Full Width with overlay
- Bento Grid layouts
- Content positioning and alignment

## Step 4: Section Layer - WordPress Block

### Create the Final Block
- **Location**: `src/blocks/Heroes/split-screen-hero/`
- **Files**: `block.php`, `template.twig`, `styles/LG.css`

```php
// In block.php render callback
$context['text_block'] = tbb_get_text_block('hero-text-block', $fields['text_block_variant']);
$context['hero_layout'] = hlb_get_hero_layout('split-screen', $fields['layout_variant']);
```

### Block Structure:
```
split-screen-hero/
├── block.php              # WordPress block configuration
├── template.twig           # Main template
├── components/
│   ├── hero-text-block.twig    # Compiled text component
│   └── hero-image.twig         # Image component
└── styles/
    └── LG.css              # DesignBook system styles
```

## Step 5: Component Templates

### Hero Text Block Component
**File**: `components/hero-text-block.twig`

```twig
<div class="hero-text-block hero-text-block--{{ fields.text_block_variant }}">
    <span class="pretitle pretitle--{{ fields.text_block_variant }}">{{ fields.pretitle }}</span>
    <h1 class="title title--{{ fields.text_block_variant }}">{{ fields.title }}</h1>
    <p class="description description--{{ fields.text_block_variant }}">{{ fields.description }}</p>
</div>
```

### CSS Using DesignBook Variables
```css
.semantic-element--title .title {
    font-family: var(--typography-font-family-heading, inherit);
    font-size: var(--typography-font-size-display, 3rem);
    color: var(--color-foreground, #000000);
}
```

## Variant System

### Creating Variants
1. **Text Element Variants**: Hero Title, Card Title, Feature Title
2. **Text Block Variants**: Hero Block, Feature Block, Card Block  
3. **Layout Variants**: Split Screen, Full Width, Bento Grid
4. **Section Variants**: Home Hero, Page Hero, Feature Hero

### Variant Inheritance
```
Base Element → Element Variant → Block Variant → Section Variant
```

## Benefits of This System

### 1. **Consistency**
- All components use the same DesignBook variables
- Semantic elements ensure consistent typography
- Layout patterns are reusable across sections

### 2. **Flexibility** 
- Mix and match components easily
- Create variants without duplicating code
- Override specific properties when needed

### 3. **Maintainability**
- Changes to base elements cascade through system
- Clear separation of concerns
- Easy to update design tokens globally

### 4. **Scalability**
- New components build on existing foundation
- Variant system allows for unlimited customization
- Admin interfaces make it accessible to non-developers

## Usage in WordPress

### Admin Experience
1. Go to **Pages → Edit Page**
2. Add **Split Screen Hero** block
3. Configure content in **Content** tab
4. Choose layout in **Layout** tab  
5. Select text block variant in **Text Block** tab
6. Add components in **Components** tab
7. Apply styling in **Styling** tab

### Default Content
- Includes starter image from villa collection
- Pre-filled with Villa Community content
- Ready-to-use CTA buttons and styling

## File Locations

```
inc/
├── textbook-semantic.php       # Foundation layer
├── textblockbook.php          # Element layer  
├── hero-layouts.php           # Component layer
└── design-system-menu.php     # Admin organization

src/blocks/Heroes/split-screen-hero/
├── block.php                  # Section layer
├── template.twig              # Main template
├── components/                # Compiled components
└── styles/                    # DesignBook CSS
```

This workflow demonstrates the power of systematic component compilation using the DesignBook architecture.
