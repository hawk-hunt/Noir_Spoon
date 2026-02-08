<?php
// Database connection using PDO for PostgreSQL
// Configure via environment variables or edit the defaults below for shared hosting
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_PORT = getenv('DB_PORT') ?: '5432';
$DB_NAME = getenv('DB_NAME') ?: 'noir_spoon';
$DB_USER = getenv('DB_USER') ?: 'dbuser';
$DB_PASS = getenv('DB_PASS') ?: 'dbpass';

$pdo = null;
$db_error = null;

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Store error but don't die - use mock data instead
    $db_error = htmlspecialchars($e->getMessage());
}

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Mock data for demo when database is unavailable
function get_mock_settings() {
    return [
        'hero_title' => 'Noir Spoon — A Modern Fine Dining Experience',
        'hero_subtitle' => 'Welcome to a dining experience where flavor, freshness, and hospitality come together.',
        'hero_cta' => 'Reserve a Table',
        'about_short' => 'Noir Spoon merges fine dining with modern refinement, offering a culinary experience crafted for the discerning palate.',
        'about_long' => 'Noir Spoon is dedicated to delivering an unforgettable fine dining experience. Every dish is crafted with precision, using only the finest ingredients and time-honored culinary techniques. Our team is passionate about creating moments that matter, one plate at a time.'
    ];
}

function get_mock_featured_dishes() {
    return [
        [
            'id' => 1,
            'name' => 'Pan-Seared Salmon',
            'description' => 'Atlantic salmon fillet with lemon beurre blanc and seasonal vegetables',
            'price' => 28.00,
            'category' => 'Mains',
            'image' => null,
            'featured' => true
        ],
        [
            'id' => 2,
            'name' => 'Truffle Risotto',
            'description' => 'Creamy Arborio risotto infused with black truffle and aged Parmesan',
            'price' => 25.00,
            'category' => 'Mains',
            'image' => null,
            'featured' => true
        ],
        [
            'id' => 3,
            'name' => 'Oyster Trio',
            'description' => 'Selection of fresh oysters with mignonette and lemon',
            'price' => 18.00,
            'category' => 'Starters',
            'image' => null,
            'featured' => true
        ],
        [
            'id' => 4,
            'name' => 'Wagyu Beef Short Rib',
            'description' => 'Japanese Wagyu prepared with miso glaze and daikon radish',
            'price' => 42.00,
            'category' => 'Mains',
            'image' => null,
            'featured' => true
        ],
        [
            'id' => 5,
            'name' => 'Chocolate Soufflé',
            'description' => 'Warm dark chocolate soufflé with vanilla ice cream',
            'price' => 12.00,
            'category' => 'Desserts',
            'image' => null,
            'featured' => true
        ],
        [
            'id' => 6,
            'name' => 'Lobster Tail',
            'description' => 'Succulent Maine lobster tail with garlic butter and asparagus',
            'price' => 38.00,
            'category' => 'Mains',
            'image' => null,
            'featured' => true
        ]
    ];
}

function get_mock_menu_categories() {
    return [
        ['id' => 1, 'name' => 'Starters', 'order' => 1, 'description' => 'Begin your meal with our curated selection of appetizers'],
        ['id' => 2, 'name' => 'Mexican', 'order' => 2, 'description' => 'Authentic Mexican cuisine with fresh ingredients'],
        ['id' => 3, 'name' => 'Breakfast', 'order' => 3, 'description' => 'Hearty breakfast and brunch items'],
        ['id' => 4, 'name' => 'Italian', 'order' => 4, 'description' => 'Traditional Italian pasta and dishes'],
        ['id' => 5, 'name' => 'Mains', 'order' => 5, 'description' => 'Our signature main course selections'],
        ['id' => 6, 'name' => 'Desserts', 'order' => 6, 'description' => 'Sweet treats and delicious endings'],
        ['id' => 7, 'name' => 'Drinks', 'order' => 7, 'description' => 'Beverages and cocktails']
    ];
}

function get_mock_menu_items() {
    return [
        // Starters
        ['id' => 1, 'name' => 'Oyster Trio', 'category_id' => 1, 'category' => 'Starters', 'price' => 18.00, 'description' => 'Selection of fresh oysters with mignonette and lemon', 'order' => 1, 'image' => 'starters/oysters.jpg', 'featured' => true],
        ['id' => 2, 'name' => 'Foie Gras', 'category_id' => 1, 'category' => 'Starters', 'price' => 32.00, 'description' => 'Pan-seared foie gras with brioche and fig jam', 'order' => 2, 'image' => 'starters/foie-gras.jpg', 'featured' => true],
        
        // Mexican
        ['id' => 3, 'name' => 'Tacos Al Pastor', 'category_id' => 2, 'category' => 'Mexican', 'price' => 14.00, 'description' => 'Marinated pork tacos with pineapple, onion, and cilantro', 'order' => 1, 'image' => 'mexican/tacos-al-pastor.jpg', 'featured' => true],
        ['id' => 4, 'name' => 'Chile Relleno', 'category_id' => 2, 'category' => 'Mexican', 'price' => 15.00, 'description' => 'Roasted poblano pepper stuffed with cheese and topped with ranchero sauce', 'order' => 2, 'image' => 'mexican/chile-relleno.jpg', 'featured' => true],
        ['id' => 5, 'name' => 'Enchiladas Verdes', 'category_id' => 2, 'category' => 'Mexican', 'price' => 16.00, 'description' => 'Rolled tortillas in green salsa with chicken and topped with sour cream', 'order' => 3, 'image' => 'mexican/enchiladas-verdes.jpg', 'featured' => true],
        ['id' => 6, 'name' => 'Burrito Colorado', 'category_id' => 2, 'category' => 'Mexican', 'price' => 13.50, 'description' => 'Large flour tortilla filled with beef, beans, and red chile sauce', 'order' => 4, 'image' => 'mexican/burrito-colorado.jpg', 'featured' => false],
        
        // Breakfast
        ['id' => 7, 'name' => 'Fluffy Pancakes', 'category_id' => 3, 'category' => 'Breakfast', 'price' => 12.00, 'description' => 'Buttermilk pancakes with maple syrup and butter', 'order' => 1, 'image' => 'breakfast/pancakes.jpg', 'featured' => true],
        ['id' => 8, 'name' => 'Classic Omelet', 'category_id' => 3, 'category' => 'Breakfast', 'price' => 11.00, 'description' => 'Eggs with cheese, ham, and sautéed vegetables', 'order' => 2, 'image' => 'breakfast/omelet.jpg', 'featured' => true],
        ['id' => 9, 'name' => 'Bacon Breakfast Plate', 'category_id' => 3, 'category' => 'Breakfast', 'price' => 10.50, 'description' => 'Crispy bacon, eggs, toast, and home fries', 'order' => 3, 'image' => 'breakfast/bacon-plate.jpg', 'featured' => true],
        ['id' => 10, 'name' => 'French Toast', 'category_id' => 3, 'category' => 'Breakfast', 'price' => 11.50, 'description' => 'Golden-brown French toast with cinnamon and powdered sugar', 'order' => 4, 'image' => 'breakfast/french-toast.jpg', 'featured' => false],
        
        // Italian
        ['id' => 11, 'name' => 'Classic Lasagna', 'category_id' => 4, 'category' => 'Italian', 'price' => 18.00, 'description' => 'Layers of pasta, meat sauce, and melted cheese', 'order' => 1, 'image' => 'italian/lasagna.jpg', 'featured' => true],
        ['id' => 12, 'name' => 'Fettuccine Alfredo', 'category_id' => 4, 'category' => 'Italian', 'price' => 14.50, 'description' => 'Creamy Parmesan sauce with fresh fettuccine noodles', 'order' => 2, 'image' => 'italian/fettuccine-alfredo.jpg', 'featured' => true],
        ['id' => 13, 'name' => 'Spaghetti Carbonara', 'category_id' => 4, 'category' => 'Italian', 'price' => 15.00, 'description' => 'Traditional carbonara with pancetta, eggs, and Pecorino Romano', 'order' => 3, 'image' => 'italian/spaghetti-carbonara.jpg', 'featured' => true],
        
        // Mains
        ['id' => 14, 'name' => 'Pan-Seared Salmon', 'category_id' => 5, 'category' => 'Mains', 'price' => 28.00, 'description' => 'Atlantic salmon fillet with lemon beurre blanc and seasonal vegetables', 'order' => 1, 'image' => 'mains/salmon.jpg', 'featured' => true],
        ['id' => 15, 'name' => 'Wagyu Beef Short Rib', 'category_id' => 5, 'category' => 'Mains', 'price' => 42.00, 'description' => 'Japanese Wagyu prepared with miso glaze and daikon radish', 'order' => 2, 'image' => 'mains/wagyu-rib.jpg', 'featured' => true],
        ['id' => 16, 'name' => 'Lobster Tail', 'category_id' => 5, 'category' => 'Mains', 'price' => 38.00, 'description' => 'Succulent Maine lobster tail with garlic butter and asparagus', 'order' => 3, 'image' => 'mains/lobster.jpg', 'featured' => true],
        
        // Desserts
        ['id' => 17, 'name' => 'Chocolate Soufflé', 'category_id' => 6, 'category' => 'Desserts', 'price' => 12.00, 'description' => 'Warm dark chocolate soufflé with vanilla ice cream', 'order' => 1, 'image' => 'desserts/chocolate-souffle.jpg', 'featured' => true],
        ['id' => 18, 'name' => 'Panna Cotta', 'category_id' => 6, 'category' => 'Desserts', 'price' => 10.00, 'description' => 'Silky Italian panna cotta with fresh berries', 'order' => 2, 'image' => 'desserts/panna-cotta.jpg', 'featured' => true],
        ['id' => 19, 'name' => 'Tiramisu', 'category_id' => 6, 'category' => 'Desserts', 'price' => 11.00, 'description' => 'Classic Italian dessert with mascarpone and espresso', 'order' => 3, 'image' => 'desserts/tiramisu.jpg', 'featured' => false],
        
        // Drinks
        ['id' => 20, 'name' => 'Château Margaux 2015', 'category_id' => 7, 'category' => 'Drinks', 'price' => 250.00, 'description' => 'Premium Bordeaux wine', 'order' => 1, 'image' => null, 'featured' => true],
        ['id' => 21, 'name' => 'Espresso Martini', 'category_id' => 7, 'category' => 'Drinks', 'price' => 14.00, 'description' => 'Vodka, Kahlúa, and fresh espresso', 'order' => 2, 'image' => 'drinks/espresso-martini.jpg', 'featured' => true]
    ];
}
