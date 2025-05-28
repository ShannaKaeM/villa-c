---
description: Villa Community Complete Theme JSON Configuration
---

# Villa Community Complete Theme JSON

This is the comprehensive theme.json configuration for the Villa Community project, providing complete styling control through WordPress's theme.json system.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "settings": {
    "color": {
      "palette": [
        {
          "slug": "primary-light",
          "color": "#8dabac",
          "name": "Primary Light"
        },
        {
          "slug": "primary",
          "color": "#5a7f80",
          "name": "Primary"
        },
        {
          "slug": "primary-dark",
          "color": "#425a5b",
          "name": "Primary Dark"
        },
        {
          "slug": "secondary-light",
          "color": "#d1a896",
          "name": "Secondary Light"
        },
        {
          "slug": "secondary",
          "color": "#a36b57",
          "name": "Secondary"
        },
        {
          "slug": "secondary-dark",
          "color": "#744d3e",
          "name": "Secondary Dark"
        },
        {
          "slug": "neutral-light",
          "color": "#cdbfad",
          "name": "Neutral Light"
        },
        {
          "slug": "neutral",
          "color": "#9b8974",
          "name": "Neutral"
        },
        {
          "slug": "neutral-dark",
          "color": "#6d6152",
          "name": "Neutral Dark"
        },
        {
          "slug": "base-lightest",
          "color": "#e5e5e5",
          "name": "Base Lightest"
        },
        {
          "slug": "base-light",
          "color": "#d4d4d4",
          "name": "Base Light"
        },
        {
          "slug": "base",
          "color": "#9c9c9c",
          "name": "Base"
        },
        {
          "slug": "base-dark",
          "color": "#6e6e6e",
          "name": "Base Dark"
        },
        {
          "slug": "base-darkest",
          "color": "#424242",
          "name": "Base Darkest"
        },
        {
          "slug": "white",
          "color": "#ffffff",
          "name": "White"
        },
        {
          "slug": "black",
          "color": "#000000",
          "name": "Black"
        }
      ],
      "gradients": [
        {
          "slug": "primary-to-secondary",
          "gradient": "linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%)",
          "name": "Primary to Secondary"
        },
        {
          "slug": "dark-overlay",
          "gradient": "linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%)",
          "name": "Dark Overlay"
        }
      ],
      "duotone": [
        {
          "colors": ["#2c3e50", "#ecf0f1"],
          "slug": "primary-to-light",
          "name": "Primary to Light"
        }
      ]
    },
    "typography": {
      "fontFamilies": [
        {
          "fontFamily": "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif",
          "name": "System Font",
          "slug": "system-font"
        },
        {
          "fontFamily": "'Inter', sans-serif",
          "name": "Inter",
          "slug": "inter",
          "fontFace": [
            {
              "fontFamily": "Inter",
              "fontWeight": "300 900",
              "fontStyle": "normal",
              "fontStretch": "normal",
              "src": ["file:./assets/fonts/inter-variable.woff2"]
            }
          ]
        }
      ],
      "fontSizes": [
        {
          "slug": "small",
          "size": "0.875rem",
          "name": "Small"
        },
        {
          "slug": "medium",
          "size": "1rem",
          "name": "Medium"
        },
        {
          "slug": "large",
          "size": "1.25rem",
          "name": "Large"
        },
        {
          "slug": "x-large",
          "size": "1.5rem",
          "name": "Extra Large"
        },
        {
          "slug": "xx-large",
          "size": "2.5rem",
          "name": "2X Large"
        },
        {
          "slug": "huge",
          "size": "3rem",
          "name": "Huge"
        }
      ]
    },
    "spacing": {
      "units": ["px", "em", "rem", "%", "vh", "vw"],
      "spacingSizes": [
        {
          "slug": "10",
          "size": "0.5rem",
          "name": "10"
        },
        {
          "slug": "20",
          "size": "1rem",
          "name": "20"
        },
        {
          "slug": "30",
          "size": "1.5rem",
          "name": "30"
        },
        {
          "slug": "40",
          "size": "2rem",
          "name": "40"
        },
        {
          "slug": "50",
          "size": "2.5rem",
          "name": "50"
        },
        {
          "slug": "60",
          "size": "3rem",
          "name": "60"
        },
        {
          "slug": "70",
          "size": "4rem",
          "name": "70"
        },
        {
          "slug": "80",
          "size": "5rem",
          "name": "80"
        }
      ]
    },
    "layout": {
      "contentSize": "1200px",
      "wideSize": "1450px"
    },
    "custom": {
      "spacing": {
        "small": "max(1.25rem, 5vw)",
        "medium": "clamp(2rem, 8vw, calc(4 * var(--wp--style--block-gap)))",
        "large": "clamp(4rem, 10vw, 8rem)",
        "outer": "var(--wp--custom--spacing--small, 1.25rem)"
      },
      "typography": {
        "font-size": {
          "heading": "clamp(2.5rem, 5vw, 3.5rem)",
          "subheading": "clamp(1.5rem, 3vw, 2rem)"
        },
        "line-height": {
          "tiny": 1.15,
          "small": 1.2,
          "medium": 1.4,
          "normal": 1.6
        }
      }
    }
  },
  "styles": {
    "color": {
      "background": "var(--wp--preset--color--white)",
      "text": "var(--wp--preset--color--neutral-dark)"
    },
    "typography": {
      "fontFamily": "var(--wp--preset--font-family--inter)",
      "lineHeight": "1.6",
      "fontSize": "1rem"
    },
    "spacing": {
      "blockGap": "1.5rem",
      "padding": {
        "top": "0",
        "right": "var(--wp--preset--spacing--30)",
        "bottom": "0",
        "left": "var(--wp--preset--spacing--30)"
      }
    },
    "elements": {
      "link": {
        "color": {
          "text": "var(--wp--preset--color--primary)"
        },
        ":hover": {
          "color": {
            "text": "var(--wp--preset--color--secondary)"
          }
        }
      },
      "heading": {
        "typography": {
          "fontWeight": "600",
          "lineHeight": "1.2"
        },
        "color": {
          "text": "var(--wp--preset--color--neutral-dark)"
        }
      },
      "h1": {
        "typography": {
          "fontSize": "var(--wp--preset--font-size--xx-large)"
        }
      },
      "h2": {
        "typography": {
          "fontSize": "var(--wp--preset--font-size--x-large)"
        }
      },
      "h3": {
        "typography": {
          "fontSize": "var(--wp--preset--font-size--large)"
        }
      },
      "button": {
        "border": {
          "radius": "8px"
        },
        "color": {
          "background": "var(--wp--preset--color--primary)",
          "text": "var(--wp--preset--color--white)"
        },
        "typography": {
          "fontSize": "var(--wp--preset--font-size--medium)",
          "fontWeight": "600"
        },
        ":hover": {
          "color": {
            "background": "var(--wp--preset--color--secondary)"
          }
        }
      }
    },
    "blocks": {
      "core/navigation": {
        "elements": {
          "link": {
            ":hover": {
              "typography": {
                "textDecoration": "underline"
              }
            }
          }
        }
      }
    }
  }
}
```

## Usage in Carbon Blocks

This theme.json provides comprehensive styling through CSS custom properties that can be used in your Carbon Blocks:

### Colors
```css
/* Use predefined colors */
background: var(--wp--preset--color--primary);
color: var(--wp--preset--color--white);

/* Use gradients */
background: var(--wp--preset--gradient--primary-to-secondary);
```

### Typography
```css
/* Use font families */
font-family: var(--wp--preset--font-family--inter);

/* Use font sizes */
font-size: var(--wp--preset--font-size--large);
```

### Spacing
```css
/* Use spacing presets */
margin: var(--wp--preset--spacing--40);
padding: var(--wp--preset--spacing--30);

/* Use custom spacing */
gap: var(--wp--custom--spacing--medium);
```

### Layout
```css
/* Use layout constraints */
max-width: var(--wp--style--global--content-size);
max-width: var(--wp--style--global--wide-size);
```

## Integration with ColorBook

The ColorBook system should sync with these theme.json colors to maintain consistency across Blocksy customizer and WordPress editor.
