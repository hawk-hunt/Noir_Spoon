# Noir Spoon — Production-Ready Restaurant Website

## Setup Instructions

### 1. **Copy Images to noir-spoon**

Copy the following images from `/assets/img/` to `/noir-spoon/assets/img/`:

```
/assets/img/ → /noir-spoon/assets/img/

  - dishes-hero.png
  - free-blog-1.png (Dine In)
  - free-blog-2.png (Takeout)
  - free-blog-3.png (Delivery)
  - free-layer-blur.png (Hero background)
  - pizza.png (CTA section)
  - mint.png
  - restaurant-about-us.png
  - avatars/ (folder with all avatar images)
  - chef-1.png → chef-4.png
  - favicon/ (folder with favicon)
```

### 2. **Database Setup (PostgreSQL)**

Run the SQL schema to create all tables:

```sql
-- Create database
CREATE DATABASE noir_spoon;

-- Connect to the database and run this schema:

CREATE TABLE admins (
  id serial PRIMARY KEY,
  username varchar(100) UNIQUE NOT NULL,
  password_hash varchar(255) NOT NULL,
  created_at timestamp with time zone DEFAULT now()
);

CREATE TABLE menu_categories (
  id serial PRIMARY KEY,
  name varchar(150) NOT NULL,
  "order" integer DEFAULT 0
);

CREATE TABLE menu_items (
  id serial PRIMARY KEY,
  category_id integer REFERENCES menu_categories(id) ON DELETE SET NULL,
  name varchar(200) NOT NULL,
  description text,
  price numeric(8,2) NOT NULL DEFAULT 0,
  image varchar(255),
  featured boolean DEFAULT false,
  "order" integer DEFAULT 0
);

CREATE TABLE reservations (
  id serial PRIMARY KEY,
  name varchar(200) NOT NULL,
  phone varchar(100) NOT NULL,
  res_date date NOT NULL,
  res_time time NOT NULL,
  guests integer DEFAULT 2,
  created_at timestamp with time zone DEFAULT now()
);

CREATE TABLE contact_messages (
  id serial PRIMARY KEY,
  name varchar(200) NOT NULL,
  email varchar(255) NOT NULL,
  message text NOT NULL,
  created_at timestamp with time zone DEFAULT now()
);

CREATE TABLE site_settings (
  key varchar(100) PRIMARY KEY,
  value text
);

-- Insert initial settings
INSERT INTO site_settings(key, value) VALUES
('hero_title','Noir Spoon — A Modern Fine Dining Experience'),
('hero_subtitle','Welcome to a dining experience where flavor, freshness, and hospitality come together.'),
('hero_cta','Reserve a Table'),
('about_short','Noir Spoon merges fine dining with modern refinement, offering a culinary experience crafted for the discerning palate.'),
('about_long','Noir Spoon is dedicated to delivering an unforgettable fine dining experience. Every dish is crafted with precision, using only the finest ingredients and time-honored culinary techniques. Our team is passionate about creating moments that matter, one plate at a time.');

-- Create admin user (CHANGE THE PASSWORD HASH!)
-- Generate hash with: php -r "echo password_hash('adminpassword', PASSWORD_DEFAULT);"
INSERT INTO admins (username, password_hash) VALUES ('admin','$2y$10$your_hash_here');
```

### 3. **Configure Database Connection**

Edit `/noir-spoon/includes/db.php`:

**Option A: Environment Variables (Recommended for cPanel)**
```bash
export DB_HOST=localhost
export DB_PORT=5432
export DB_NAME=noir_spoon
export DB_USER=dbuser
export DB_PASS=securepassword
```

**Option B: Edit db.php Directly**
```php
$DB_HOST = 'localhost';
$DB_PORT = '5432';
$DB_NAME = 'noir_spoon';
$DB_USER = 'dbuser';
$DB_PASS = 'securepassword';
```

### 4. **Generate Admin Password Hash**

In terminal/command prompt:
```bash
php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);" 
```
Copy the output and replace `$2y$10$your_hash_here` in the admin INSERT query.

### 5. **Project Structure**

```
/noir-spoon/
├── index.php (Home page)
├── menu.php (Menu listing)
├── reservation.php (Reservation form)
├── about.php (About page)
├── contact.php (Contact form)
├── /includes/
│   ├── header.php (Navigation & head)
│   ├── footer.php (Footer & scripts)
│   └── db.php (Database connection)
├── /admin/
│   ├── login.php (Admin login)
│   ├── logout.php (Logout)
│   ├── dashboard.php (Admin dashboard)
│   ├── menu.php (Manage menu)
│   ├── reservations.php (View reservations)
│   └── messages.php (View contact messages)
├── /assets/
│   ├── css/ (FlyonUI CSS from main project)
│   ├── js/ (FlyonUI JS from main project)
│   ├── dist/ (FlyonUI compiled files)
│   ├── img/ (Restaurant images)
│   └── images/ (Menu item images)
└── /sql/
    └── schema.sql (Full schema)
```

### 6. **Access Admin Panel**

1. Navigate to `/noir-spoon/admin/login.php`
2. Username: `admin`
3. Password: (`yourpassword` from step 4)
4. Dashboard allows you to:
   - Add/edit/delete menu categories
   - Add/edit/delete menu items
   - View reservations
   - View contact messages
   - Edit homepage text (via database)

### 7. **Customize Homepage Text**

Update site settings in database:
```sql
UPDATE site_settings SET value='Your Text Here' WHERE key='hero_title';
UPDATE site_settings SET value='Your Text Here' WHERE key='hero_subtitle';
UPDATE site_settings SET value='Your Text Here' WHERE key='about_short';
```

### 8. **Menu Item Images**

Upload images to `/noir-spoon/assets/images/` via admin panel or manually. Images are referenced in the database.

### 9. **Shared Hosting Deployment**

1. Upload entire `/noir-spoon/` folder via cPanel File Manager or FTP
2. Create PostgreSQL database in cPanel
3. Run SQL schema via phpPgAdmin or terminal
4. Set environment variables in cPanel if using Option A
5. Test: `yoursite.com/noir-spoon/`

### 10. **Security Notes**

- Use HTTPS in production
- Restrict `/admin/` access via `.htaccess` (for Apache):
```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^admin/ - [F]
</IfModule>
```
- Change default admin password
- Use environment variables for DB credentials
- Validate all form inputs (already done with PDO prepared statements)

---

**Noir Spoon is now ready for production!** 🍽️

