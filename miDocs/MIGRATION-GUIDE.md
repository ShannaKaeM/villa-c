# HomeHero Block Migration Guide

## What Changed
The HomeHero block has been updated from property-specific "villa-cards" to generic "hero-cards" for maximum flexibility.

## Required Updates

### 1. Update Home Page Content
**Location:** WordPress Admin > Pages > Edit Home Page

**Steps:**
1. Find the HomeHero block in the page editor
2. In block settings, change "Right Content Type" from "Villa Cards" to "Hero Cards"
3. Re-add your content using the new hero card fields:

**Old Fields → New Fields:**
- `image` → `image` (same)
- `title` → `title` (same) 
- `location` → `subtitle` (more flexible)
- `price` → `badge` (can be price or any label)
- `bedrooms/bathrooms/guests` → `description` (combined into description text)
- `url` → `url` (same)
- New: `overlay_text` (text over image)
- New: `button_text` (custom button text)

### 2. Sample Hero Cards Configuration

Use this data structure for your hero cards:

```json
{
  "image": "your-image.jpg",
  "title": "Card Title",
  "subtitle": "Location or Category", 
  "description": "Brief description of the content",
  "badge": "$500/night or any label",
  "overlay_text": "Optional text over image",
  "url": "/link-destination",
  "button_text": "Custom Button Text"
}
```

### 3. Benefits of New System

**More Flexible:**
- Can showcase properties, services, features, or any content
- Not limited to property-specific fields
- Easier to customize for different use cases

**Better Design:**
- Cleaner card layout optimized for hero sections
- Better hover effects and animations
- Improved mobile responsiveness

**Future-Proof:**
- Generic structure allows for any type of content
- Easy to extend with new fields later
- Consistent with modern design patterns

## Example Use Cases

### Property Cards
```
Title: "Villa Paradise"
Subtitle: "Malibu, California" 
Badge: "$850/night"
Description: "Stunning oceanfront villa with panoramic views"
```

### Service Cards
```
Title: "Luxury Concierge"
Subtitle: "24/7 Premium Service"
Badge: "Premium"
Description: "Personal assistance for all your needs"
```

### Feature Cards
```
Title: "Ocean Views"
Subtitle: "Panoramic Vistas"
Badge: "Signature"
Description: "Wake up to breathtaking sunrise views"
```

## Need Help?

If you encounter any issues during migration, the new hero card system is designed to be more intuitive and flexible than the previous version. All the same content can be displayed, just organized in a more generic and reusable way.
