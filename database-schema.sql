-- Menu Categories Table
CREATE TABLE IF NOT EXISTS menu_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    "order" INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL REFERENCES menu_categories(id) ON DELETE CASCADE,
    description TEXT,
    price DECIMAL(10, 2),
    image VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    allergies TEXT,
    spicy_level INT DEFAULT 0,
    "order" INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS idx_menu_items_category ON menu_items(category_id);
CREATE INDEX IF NOT EXISTS idx_menu_items_featured ON menu_items(featured);
CREATE INDEX IF NOT EXISTS idx_menu_items_active ON menu_items(active);

-- Insert Default Categories
INSERT INTO menu_categories (name, description, "order") VALUES
    ('Starters', 'Begin your meal with our curated selection of appetizers', 1),
    ('Mexican', 'Authentic Mexican cuisine with fresh ingredients', 2),
    ('Breakfast', 'Hearty breakfast and brunch items', 3),
    ('Italian', 'Traditional Italian pasta and dishes', 4),
    ('Mains', 'Our signature main course selections', 5),
    ('Desserts', 'Sweet treats and delicious endings', 6),
    ('Drinks', 'Beverages and cocktails', 7)
ON CONFLICT DO NOTHING;

-- Insert Sample Menu Items
INSERT INTO menu_items (name, category_id, description, price, image, featured, "order") VALUES
    -- Starters
    ('Oyster Trio', 1, 'Selection of fresh oysters with mignonette and lemon', 18.00, 'starters/oysters.jpg', TRUE, 1),
    ('Foie Gras', 1, 'Pan-seared foie gras with brioche and fig jam', 32.00, 'starters/foie-gras.jpg', TRUE, 2),
    
    -- Mexican
    ('Tacos Al Pastor', 2, 'Marinated pork tacos with pineapple, onion, and cilantro', 14.00, 'mexican/tacos-al-pastor.jpg', TRUE, 1),
    ('Chile Relleno', 2, 'Roasted poblano pepper stuffed with cheese and topped with ranchero sauce', 15.00, 'mexican/chile-relleno.jpg', TRUE, 2),
    ('Enchiladas Verdes', 2, 'Rolled tortillas in green salsa with chicken and topped with sour cream', 16.00, 'mexican/enchiladas-verdes.jpg', TRUE, 3),
    ('Burrito Colorado', 2, 'Large flour tortilla filled with beef, beans, and red chile sauce', 13.50, 'mexican/burrito-colorado.jpg', FALSE, 4),
    
    -- Breakfast
    ('Fluffy Pancakes', 3, 'Buttermilk pancakes with maple syrup and butter', 12.00, 'breakfast/pancakes.jpg', TRUE, 1),
    ('Classic Omelet', 3, 'Eggs with cheese, ham, and sautéed vegetables', 11.00, 'breakfast/omelet.jpg', TRUE, 2),
    ('Bacon Breakfast Plate', 3, 'Crispy bacon, eggs, toast, and home fries', 10.50, 'breakfast/bacon-plate.jpg', TRUE, 3),
    ('French Toast', 3, 'Golden-brown French toast with cinnamon and powdered sugar', 11.50, 'breakfast/french-toast.jpg', FALSE, 4),
    
    -- Italian
    ('Classic Lasagna', 4, 'Layers of pasta, meat sauce, and melted cheese', 18.00, 'italian/lasagna.jpg', TRUE, 1),
    ('Fettuccine Alfredo', 4, 'Creamy Parmesan sauce with fresh fettuccine noodles', 14.50, 'italian/fettuccine-alfredo.jpg', TRUE, 2),
    ('Spaghetti Carbonara', 4, 'Traditional carbonara with pancetta, eggs, and Pecorino Romano', 15.00, 'italian/spaghetti-carbonara.jpg', TRUE, 3),
    
    -- Mains
    ('Pan-Seared Salmon', 5, 'Atlantic salmon fillet with lemon beurre blanc and seasonal vegetables', 28.00, 'mains/salmon.jpg', TRUE, 1),
    ('Wagyu Beef Short Rib', 5, 'Japanese Wagyu prepared with miso glaze and daikon radish', 42.00, 'mains/wagyu-rib.jpg', TRUE, 2),
    ('Lobster Tail', 5, 'Succulent Maine lobster tail with garlic butter and asparagus', 38.00, 'mains/lobster.jpg', TRUE, 3),
    
    -- Desserts
    ('Chocolate Soufflé', 6, 'Warm dark chocolate soufflé with vanilla ice cream', 12.00, 'desserts/chocolate-souffle.jpg', TRUE, 1),
    ('Panna Cotta', 6, 'Silky Italian panna cotta with fresh berries', 10.00, 'desserts/panna-cotta.jpg', TRUE, 2),
    ('Tiramisu', 6, 'Classic Italian dessert with mascarpone and espresso', 11.00, 'desserts/tiramisu.jpg', FALSE, 3),
    
    -- Drinks
    ('Château Margaux 2015', 7, 'Premium Bordeaux wine', 250.00, NULL, TRUE, 1),
    ('Espresso Martini', 7, 'Vodka, Kahlúa, and fresh espresso', 14.00, 'drinks/espresso-martini.jpg', TRUE, 2)
ON CONFLICT DO NOTHING;
