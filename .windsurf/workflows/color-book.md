---
description: Villa Community ColorBook system documentation and color palette reference
---

# Villa 16-Color System Setup Documentation

## Overview
The Villa theme uses a comprehensive 16-color system that integrates with both WordPress's theme.json and Blocksy's customizer. This document outlines the complete setup and configuration.

## Color System Structure

### The 16 Colors
The system consists of 16 carefully chosen colors organized into 4 groups:

1. **Primary Colors (3)** - Teal/Turquoise tones
   - `primary-light`: #759e9d
   - `primary`: #5a7f80 (base)
   - `primary-dark`: #425a5b

2. **Secondary Colors (3)** - Terracotta/Warm tones
   - `secondary-light`: #d1a896
   - `secondary`: #a36b57 (base)
   - `secondary-dark`: #744d3e

3. **Neutral Colors (3)** - Warm Gray tones
   - `neutral-light`: #e2ded2
   - `neutral`: #cec7b5 (base)
   - `neutral-dark`: #b6ad95

4. **Base Colors (7)** - True Gray scale
   - `base-white`: #ffffff
   - `base-lightest`: #e5e5e5
   - `base-light`: #bfbfbf
   - `base`: #9c9c9c (base)
   - `base-dark`: #737373
   - `base-darkest`: #4d4d4d
   - `base-black`: #000000

## File Structure

### 1. theme.json
Located at: `/wp-content/themes/carbonblocks/theme.json`

Contains the full color palette definition with all 16 colors. Each color is defined with:
- `slug`: The color identifier (e.g., "primary-light")
- `color`: The hex color value
- `name`: The display name

### 2. theme-integration.css
Located at: `/wp-content/themes/carbonblocks/assets/css/theme-integration.css`

Maps Blocksy's CSS variables to our theme colors:
```css
:root {
  /* Primary Colors (1-3) */
  --theme-palette-color-1: var(--wp--preset--color--primary-light);
  --theme-palette-color-2: var(--wp--preset--color--primary);
  --theme-palette-color-3: var(--wp--preset--color--primary-dark);
  
  /* Secondary Colors (4-6) */
  --theme-palette-color-4: var(--wp--preset--color--secondary-light);
  --theme-palette-color-5: var(--wp--preset--color--secondary);
  --theme-palette-color-6: var(--wp--preset--color--secondary-dark);
  
  /* Neutral Colors (7-8) */
  --theme-palette-color-7: var(--wp--preset--color--neutral-light);
  --theme-palette-color-8: var(--wp--preset--color--neutral);
  
  /* Additional Blocksy palette mappings */
  --paletteColor1: var(--wp--preset--color--primary-light);
  --paletteColor2: var(--wp--preset--color--primary);
  /* ... etc ... */
}
```

### 3. Villa Stylebook Admin
Located at: `/wp-content/themes/carbonblocks/inc/villa-stylebook.php`

Provides a visual admin interface for managing colors with:
- OKLCH color editing with sliders
- Real-time preview
- Sync to Blocksy functionality
- Import/Export capabilities

## Blocksy Integration

The Villa color system is designed to work seamlessly with Blocksy's customizer:

### Color Mapping
Villa colors are mapped to Blocksy's 16-color palette system:

```
Blocksy Palette → Villa Color System
color1  → Primary Light (#759e9d)
color2  → Primary (#5a7f80)
color3  → Primary Dark (#425a5b)
color4  → Secondary Light (#d1a896)
color5  → Secondary (#a36b57)
color6  → Secondary Dark (#744d3e)
color7  → Neutral Light (#e2ded2)
color8  → Neutral (#cec7b5)
color9  → Neutral Dark (#b6ad95)
color10 → Base White (#ffffff)
color11 → Base Lightest (#e5e5e5)
color12 → Base Light (#bfbfbf)
color13 → Base (#9c9c9c)
color14 → Base Dark (#737373)
color15 → Base Darkest (#4d4d4d)
color16 → Base Black (#000000)
```

### Sync Process
1. Colors are managed in the Villa Stylebook
2. When "Sync to Blocksy" is checked, colors automatically update in Blocksy's customizer
3. CSS variables are available as both `--paletteColor1` through `--paletteColor16` and semantic names like `--villa-primary`

### Export/Import
- **Export**: Use the "Export Colors" button in Villa Stylebook to create a Blocksy-compatible JSON file
- **Import**: In Blocksy Customizer → Import/Export → Import the JSON file
- The export includes proper formatting with `color1` through `color16` keys and descriptive titles

## Import/Export

### Exporting Colors from Villa
1. Click "Export Colors" in Villa Stylebook
2. This creates a `villa-colors-blocksy.json` file
3. The export is formatted for Blocksy's customizer import

### Importing to Blocksy
1. Go to **Blocksy Customizer → Import/Export**
2. Click **Import** and select the JSON file
3. Your 16 Villa colors will populate Blocksy's palette

### Blocksy Export Format (.dat files)
Blocksy exports use a serialized PHP format with this structure:
```
a:6:{
  s:8:"template";s:13:"blocksy-child";  // or "carbonblocks"
  s:4:"mods";a:18:{
    s:12:"colorPalette";a:16:{
      s:6:"color1";a:2:{
        s:5:"color";s:7:"#759e9d";     // hex color value
        s:5:"title";s:13:"Primary Light";  // optional title
      }
      // ... color2 through color16
    }
  }
}
```

**Important**: The colorPalette uses keys `color1` through `color16` (not `paletteColor1`)

### Syncing Process
1. **Initial Setup**: 
   - Go to Villa Stylebook
   - Check "Sync to Blocksy"
   - Click "Save Colors"
   - This updates Blocksy's theme mods directly

2. **Verification**:
   - Check **Customizer → Colors → Global Color Palette**
   - You should see all 16 Villa colors
   - Export from Blocksy should now contain Villa colors

3. **CSS Variables**:
   - Blocksy generates `--paletteColor1` through `--paletteColor16`
   - Villa also provides semantic variables like `--villa-primary`

## Related Files and Exports

### Current Color Files
- **theme.json**: Current WordPress color palette definitions
- **inc/colorbook.php**: ColorBook system functionality
- **assets/css/theme-integration.css**: CSS variable mappings

### Blocksy Integration Files
- Use Villa Stylebook export feature to generate Blocksy-compatible JSON files
- Import these files via Blocksy Customizer → Import/Export for theme setup

## Troubleshooting

### Colors Not Showing in Blocksy
1. Ensure "Sync to Blocksy" is checked when saving
2. Clear any caching plugins
3. Check that theme-integration.css is loading
4. Verify the sync ran by checking the colorPalette in theme mods

### Export Shows Wrong Colors
1. The sync must be run at least once
2. Check "Sync to Blocksy" and save in Villa Stylebook
3. Then export again - it should show Villa colors

### Villa Stylebook Not Visible
1. Verify CarbonBlocks theme is active
2. Check user has 'manage_options' capability
3. Look for admin notices about file loading

### Color Changes Not Applying
1. Clear browser cache
2. Check for CSS specificity issues
3. Ensure theme.json is writable

## Usage in Blocks

### CSS Variables
Use the WordPress preset variables in your block styles:
```css
.my-block {
  color: var(--wp--preset--color--primary);
  background: var(--wp--preset--color--base-lightest);
}
```

### Blocksy Variables
When Blocksy is active, you can also use:
```css
.my-element {
  color: var(--theme-palette-color-1);
  /* or */
  color: var(--paletteColor1);
}
```

## Admin Interface

### Accessing Villa Stylebook
1. Log into WordPress admin
2. Look for "Villa Stylebook" in the main menu (with art icon)
3. If not visible, ensure CarbonBlocks theme is active

### Editing Colors
1. Click on any color swatch to open the editor
2. Use OKLCH sliders to adjust:
   - **L** (Lightness): 0-100
   - **C** (Chroma): 0-0.4 (saturation)
   - **H** (Hue): 0-360 (color wheel position)
3. Preview changes in real-time
4. Click "Save Color" to apply

## Best Practices

1. **Consistency**: Use the predefined 16 colors throughout your site
2. **Accessibility**: Ensure sufficient contrast ratios (WCAG AA/AAA)
3. **Naming**: Stick to the established naming convention
4. **Testing**: Always test color changes across different blocks
5. **Backup**: Export your color palette before major changes

## Future Enhancements

- Full OKLCH color scales (50-950 variants)
- Dark mode support
- Color harmony suggestions
- Accessibility contrast checker
- Block-specific color presets

---

*Last updated: May 2024*
*CarbonBlocks Theme - Villa 16-Color System*
