---
description: Review the Theme Overview
---

# Documentation Review Workflow

This workflow helps you review and understand the Carbon Blocks Framework documentation for the Villa Community project.

## 1. Review Main Theme Documentation

Review the main Carbon Blocks Framework README to understand the overall architecture:

```bash
# View the main theme documentation
cat app/public/wp-content/themes/carbon-blocksy/README.md
```

**Key concepts to understand:**
- Zero-configuration file-based routing
- Auto-discovery system for blocks, post types, and taxonomies
- Responsive CSS compilation with breakpoints
- Carbon Fields + Timber integration

## 2. Review Blocks System Documentation

Understand how the block system works:

```bash
# View the blocks documentation
cat app/public/wp-content/themes/carbon-blocksy/src/blocks/README.md
```

**Key concepts:**
- Auto-discovery and registration of blocks
- Responsive CSS compilation system
- BEM naming conventions
- Twig template system with block context
- Component architecture

## 3. Review Post Types Documentation

Understand the post types system:

```bash
# View the post types documentation
cat app/public/wp-content/themes/carbon-blocksy/src/post-types/README.md
```

**Key concepts:**
- File-based post type registration
- Carbon Fields meta boxes with tabs
- Auto-discovery system

## 4. Review Other System Documentation

Check other system documentation as needed:

```bash
# Admin pages system
cat app/public/wp-content/themes/carbon-blocksy/src/admin-pages/README.md

# Configuration system
cat app/public/wp-content/themes/carbon-blocksy/src/config/README.md

# Taxonomy system
cat app/public/wp-content/themes/carbon-blocksy/src/taxonomy/README.md
```

## 5. Verify Current Project Structure

Check that the current project follows the documented patterns:

```bash
# Check blocks structure
find app/public/wp-content/themes/carbon-blocksy/src/blocks -type f -name "*.php" | head -10

# Check post types structure
find app/public/wp-content/themes/carbon-blocksy/src/post-types -type f | head -10

# Check if hero section block exists
ls -la app/public/wp-content/themes/carbon-blocksy/src/blocks/Heroes/hero-section/
```

## 6. Review Current Block Implementation

Examine the hero section block that's currently being worked on:

```bash
# View hero section block registration
cat app/public/wp-content/themes/carbon-blocksy/src/blocks/Heroes/hero-section/block.php

# View hero section template
cat app/public/wp-content/themes/carbon-blocksy/src/blocks/Heroes/hero-section/block.twig

# Check responsive styles
ls -la app/public/wp-content/themes/carbon-blocksy/src/blocks/Heroes/hero-section/styles/
```

## Documentation Summary

After reviewing the documentation, you should understand:

1. **Framework Architecture**: Zero-config, file-based routing system
2. **Block Development**: How to create responsive, component-based blocks
3. **Naming Conventions**: BEM CSS classes and auto-generated names
4. **Compilation System**: How CSS and JS are compiled and injected
5. **Template System**: Timber/Twig integration with block context
6. **Post Types**: How custom post types and meta fields are registered
7. **Development Workflow**: Best practices for creating new components

This framework emphasizes developer experience with minimal configuration and maximum consistency.