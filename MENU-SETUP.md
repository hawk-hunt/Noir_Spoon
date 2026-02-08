# Menu Display System Setup Guide

## Overview

This menu system provides a professional, responsive menu display for your restaurant with support for multiple food categories including Mexican, Breakfast, Italian, and more.

## Features

✅ **Categories Included:**
- Starters / Appetizers
- Mexican Cuisine (Tacos, Burritos, Enchiladas)
- Breakfast (Pancakes, Omelets, Bacon, Toast)
- Italian (Lasagna, Pasta)
- Mains
- Desserts
- Drinks

✅ **Functionality:**
- Beautiful, responsive menu display
- Image support for each menu item
- Price display with formatted currency
- Featured items highlighting
- Category navigation with smooth scrolling
- Admin interface for managing items
- Image upload with validation
- Mobile-optimized layout

## Directory Structure

```
assets/
├── img/
│   ├── menu/
│   │   ├── starters/
│   │   ├── mexican/
│   │   ├── breakfast/
│   │   ├── italian/
│   │   ├── mains/
│   │   ├── desserts/
│   │   └── drinks/
```

## Database Setup

### Option 1: Using PostgreSQL (Recommended)

1. Run the migration SQL file:
```sql
psql -U your_user -d noir_spoon < database-schema.sql
```

Or paste the contents of `database-schema.sql` into your PostgreSQL client.

### Option 2: Using Mock Data (No Database)

The system includes mock data that works without a database for demo purposes. No setup required!

## Files Created

### Public Pages
- **`menu-display.php`** - Main menu display page with all categories and items
  - Access at: `https://yoursite.com/menu-display.php`
  - Features sticky category navigation
  - Responsive grid layout
  - Image fallback with emoji

### Admin Pages
- **`admin/menu-manager.php`** - Menu item management interface
  - Add new menu items
  - Upload images with validation
  - Set prices and descriptions
  - Mark items as featured
  - Delete items

### Database Files
- **`database-schema.sql`** - Complete database schema with sample data
- **`includes/db.php`** - Updated with new categories and items

## How to Use

### 1. Access the Menu Display

Navigate to `menu-display.php` on your website. You'll see:
- Hero section with welcome message
- Sticky navigation for quick category access
- Grid layout of menu items by category
- Prices displayed in formatted currency
- Featured items marked with a star

### 2. Add Menu Items (Admin)

1. Go to `admin/menu-manager.php`
2. Fill in the item details:
   - **Item Name**: Name of the dish
   - **Category**: Select from dropdown
   - **Description**: Brief description (1-2 sentences)
   - **Price**: In dollars
   - **Order**: Display order (lower numbers appear first)
   - **Image**: Upload JPG, PNG, or WebP (up to 5MB)
   - **Featured**: Check to highlight as featured
3. Click "Add Item"
4. Item appears immediately in the menu

### 3. Add Food Images

#### Option A: Use Free Stock Photos
Services with no attribution required:
- **Unsplash** (unsplash.com) - Professional food photography
- **Pexels** (pexels.com) - High-quality images
- **Pixabay** (pixabay.com) - Diverse food images

#### Option B: Use AI Image Generation
- **DALL-E 3** - Generate custom food images
- **Midjourney** - Professional food photography style
- **Stable Diffusion** - Open-source option

#### Option C: Professional Photography
- Hire a food photographer
- Use your own professional photos

### 4. Image Naming Convention

When uploading images, they're automatically organized:
```
assets/img/menu/[category]/[item-name-timestamp].[ext]
```

Example uploads:
- `assets/img/menu/mexican/tacos-al-pastor-1707396000.jpg`
- `assets/img/menu/breakfast/pancakes-1707396100.png`

## AI Image Generation Prompts

Use these prompts with DALL-E 3, Midjourney, or Stable Diffusion:

### Mexican Food
```
Professional food photography, high-quality photo of authentic Mexican tacos al pastor 
with marinade, pineapple, and cilantro, on a wooden plate, bright natural lighting, 
studio photography, restaurant quality, appetizing, fresh ingredients, 8k resolution
```

### Breakfast
```
Professional food photography, fluffy buttermilk pancakes with maple syrup drizzle, 
crispy bacon strips, and fresh berries, on a white ceramic plate, bright morning light, 
studio photography, hotel breakfast quality, high resolution, appetizing presentation
```

### Italian
```
Professional food photography, homemade lasagna with meat sauce and melted mozzarella 
cheese, cross-section showing layers, fresh basil garnish, on a ceramic plate, warm 
lighting, restaurant quality photography, 8k resolution, appetizing
```

## Customization

### Change Colors
Edit in `menu-display.php` and `admin/menu-manager.php`:
```php
// Change from primary/secondary gradient
class="bg-gradient-to-r from-primary to-secondary"
```

### Change Item Limit
Edit in `menu-display.php` to limit featured items:
```php
LIMIT 6  // Change this number
```

### Add Custom Fields
Extend the database schema in `database-schema.sql`:
```sql
ALTER TABLE menu_items ADD COLUMN your_field VARCHAR(255);
```

## Image Upload Tips

**Best Practices:**
- Use 16:9 aspect ratio for consistency
- Minimum 600px width for clarity
- Compress images (keep under 5MB)
- Use descriptive filenames
- Test on mobile devices

**Recommended Sizes:**
- Width: 800-1000px
- Height: 600-750px
- Format: JPG (web optimization) or PNG (transparency)

## Mobile Responsiveness

The menu system is fully responsive:
- Desktop: 3-4 item grid
- Tablet: 2-3 item grid
- Mobile: Single column with swipe-friendly navigation

## Troubleshooting

### Images not displaying?
1. Check file permissions: `assets/img/menu/` (need 755)
2. Verify file format is JPG/PNG/WebP
3. Check browser console for 404 errors
4. Ensure database has correct image paths

### Database errors?
1. Run `database-schema.sql` to create tables
2. Check PostgreSQL credentials in `includes/db.php`
3. Verify database user has correct permissions

### Upload fails?
1. Check `php.ini` file upload limits:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
2. Verify folder exists and is writable
3. Check file size (limit 5MB)

## Next Steps

1. **Setup Database**: Run `database-schema.sql`
2. **Access Menu Display**: Visit `menu-display.php`
3. **Add Menu Items**: Use `admin/menu-manager.php`
4. **Upload Images**: Add food photos via admin interface
5. **Customize**: Adjust colors, spacing, and layout as needed
6. **Launch**: Make menu-display.php your main menu page

## Support

For detailed setup instructions or modifications:
- Check error logs in browser developer console (F12)
- Review database error messages in admin interface
- Ensure all files are in correct directories
- Test with sample text before uploading images

---

**Version**: 1.0
**Last Updated**: February 2026
