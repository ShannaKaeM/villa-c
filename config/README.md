# Configuration Directory

This directory contains the core configuration files for the Carbon Blocks WordPress theme system.

## Overview

This theme is built around a **Carbon Blocks** architecture that combines:
- **Carbon Fields** for custom field management
- **Timber** for templating with Twig
- **Responsive CSS compilation** with breakpoint-based stylesheets

## File Structure

### Core Files

- **`setup.php`** - Main theme initialization file that bootstraps Carbon Fields and Timber dependencies
- **`breakpoints.php`** - (Empty) Intended for responsive breakpoint configuration  
- **`compile_helpers.php`** - Helper functions for compiling inline styles and scripts for Carbon Blocks

### Subdirectories

- **`timber/`** - Timber-specific configuration
  - **`paths.php`** - Configures Timber template paths to include Carbon Blocks templates

- **`cli/`** - (Empty) Intended for command-line interface tools

## How It Works

### 1. Theme Bootstrap (`setup.php`)

The theme initializes through `functions.php` which loads `config/setup.php`. This file:

- Safely loads Carbon Fields from `vendor/htmlburger/carbon-fields/`
- Initializes Timber from `vendor/timber/timber/` 
- Sets up basic Timber view directories (`views`, `templates`)
- Uses WordPress `after_setup_theme` hook with priority 1 for early initialization

### 2. Carbon Blocks Architecture

The theme uses a **block-based component system**:

```
carbon-blocks/
└── block-name/           # Individual block directory
    ├── block.php         # Block logic/registration
    ├── block.twig        # Twig template
    ├── components/       # Reusable components
    ├── scripts/          # Block-specific JavaScript
    └── styles/           # Responsive CSS files
        ├── XS.css        # Extra small screens
        ├── SM.css        # Small screens  
        ├── MD.css        # Medium screens
        ├── LG.css        # Large screens (base)
        └── 2XL.css       # Extra large screens
```

### 3. Responsive CSS System

Each block has **breakpoint-specific CSS files**:
- **LG.css** serves as the base stylesheet (no media query wrapper)
- Other breakpoints (XS, SM, MD, 2XL) are wrapped in media queries
- Styles are compiled inline via `compile_helpers.php`

### 4. Template System

**Timber Integration:**
- Custom template paths added for Carbon Blocks via `timber/paths.php`
- Blocks can be rendered using Twig templates
- Filter `carbon_blocks_paths` allows extending template locations

## Dependencies

Managed via Composer (`composer.json`):
- **`timber/timber: ^2.3`** - Twig templating for WordPress
- **`htmlburger/carbon-fields: ^3.6`** - Advanced custom fields

## Usage Pattern

1. Create a new block directory in `carbon-blocks/`
2. Add responsive CSS files for each breakpoint
3. Create Twig template for the block layout
4. Register the block in `block.php` using Carbon Fields
5. Styles and scripts are automatically compiled and inlined

This architecture enables component-based development with responsive design built into the workflow.