---
description: Access Villa Community site data and content files
---

# Site Data Access Workflow

This workflow provides quick access to all Villa Community site data, CSV files, and content documentation.

## Site Data Directory

All site data is located in:
```
app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/
```

## Available Data Files

### 1. Review All Site Data Files

```bash
# List all available data files
ls -la "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/"
```

### 2. Property Data

```bash
# View property/villa data
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Properties.csv"
```

### 3. Business Data

```bash
# View business listings data
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Businesses_Data__Trimmed_Final_.csv"
```

### 4. Articles Data

```bash
# View articles and content data
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Articles_Data__Final_Trim_.csv"
```

### 5. User Data

```bash
# View user data
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Users_Data__No_id_2_.csv"
```

### 6. Categories Data

```bash
# View categories structure
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Categories.csv"
```

### 7. Site Content Documentation

```bash
# View site content overview
cat "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/sitecontent.md"
```

## Quick Data Analysis

### Count Records in Each File

```bash
# Count properties
wc -l "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Properties.csv"

# Count businesses  
wc -l "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Businesses_Data__Trimmed_Final_.csv"

# Count articles
wc -l "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Articles_Data__Final_Trim_.csv"
```

### View CSV Headers

```bash
# View property data structure
head -1 "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Properties.csv"

# View business data structure  
head -1 "app/public/wp-content/themes/carbon-blocksy/miDocs/SITE DATA/Businesses_Data__Trimmed_Final_.csv"
```

## Usage Notes

- Use this data for creating mock content in blocks
- Reference property and business data for realistic listings
- Check categories for proper taxonomy structure
- Use articles data for content examples
- All data is CSV format for easy parsing

## Related Resources

- **Images**: `app/public/wp-content/themes/carbon-blocksy/miDocs/Images/`
- **Color System**: `app/public/wp-content/themes/carbon-blocksy/miDocs/villa-color-system-setup.md`
- **Database Export**: `app/public/wp-content/themes/carbon-blocksy/miDocs/villa-community20-local-export (1).dat`
