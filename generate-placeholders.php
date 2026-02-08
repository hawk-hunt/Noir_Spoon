<?php
/**
 * Placeholder Food Image Generator
 * Creates attractive professional-looking placeholder images for all menu items
 * Usage: php generate-placeholders.php
 */

define('IMAGES_DIR', __DIR__ . '/assets/img/menu');

// Menu items with category-specific styling
$menu_items = [
    'mexican' => [
        'tacos-al-pastor' => ['Tacos Al Pastor', '🌮', [230, 126, 34], [46, 204, 113]],
        'chile-relleno' => ['Chile Relleno', '🌶️', [192, 57, 43], [52, 152, 219]],
        'enchiladas-verdes' => ['Enchiladas', '🟢', [46, 204, 113], [230, 126, 34]],
        'burrito-colorado' => ['Burrito', '🌯', [192, 57, 43], [243, 156, 18]]
    ],
    'breakfast' => [
        'pancakes' => ['Fluffy Pancakes', '🥞', [243, 156, 18], [155, 89, 182]],
        'omelet' => ['Classic Omelet', '🍳', [255, 193, 7], [76, 175, 80]],
        'bacon-plate' => ['Bacon Breakfast', '🥓', [192, 57, 43], [255, 152, 0]],
        'french-toast' => ['French Toast', '🍞', [193, 156, 0], [211, 84, 0]]
    ],
    'italian' => [
        'lasagna' => ['Classic Lasagna', '🍝', [192, 57, 43], [236, 240, 241]],
        'fettuccine-alfredo' => ['Fettuccine', '🍴', [244, 208, 63], [255, 152, 0]],
        'spaghetti-carbonara' => ['Spaghetti', '🍝', [255, 193, 7], [244, 67, 54]]
    ],
    'starters' => [
        'oysters' => ['Oyster Trio', '🦪', [52, 152, 219], [241, 196, 15]],
        'foie-gras' => ['Foie Gras', '✨', [33, 33, 33], [255, 193, 7]]
    ],
    'mains' => [
        'salmon' => ['Pan-Seared Salmon', '🐟', [255, 152, 0], [52, 168, 224]],
        'wagyu-rib' => ['Wagyu Beef', '🥩', [139, 69, 19], [255, 152, 0]],
        'lobster' => ['Lobster Tail', '🦞', [255, 87, 34], [255, 193, 7]]
    ],
    'desserts' => [
        'chocolate-souffle' => ['Chocolate Soufflé', '🍫', [101, 67, 33], [255, 193, 7]],
        'panna-cotta' => ['Panna Cotta', '🍮', [244, 67, 54], [233, 30, 99]],
        'tiramisu' => ['Tiramisu', '☕', [101, 67, 33], [230, 124, 115]]
    ],
    'drinks' => [
        'espresso-martini' => ['Espresso Martini', '🍹', [63, 81, 181], [255, 193, 7]],
        'cocktail' => ['Cocktail', '🍸', [233, 30, 99], [103, 58, 183]]
    ]
];

// Helper function to create gradient image
function create_food_image($width, $height, $title, $emoji, $color1, $color2, $filename) {
    $image = imagecreatetruecolor($width, $height);
    
    // Create gradient background
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = intval($color1[0] + ($color2[0] - $color1[0]) * $ratio);
        $g = intval($color1[1] + ($color2[1] - $color1[1]) * $ratio);
        $b = intval($color1[2] + ($color2[2] - $color1[2]) * $ratio);
        
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }
    
    // Add decorative elements
    $white = imagecolorallocate($image, 255, 255, 255);
    $highlight = imagecolorallocate($image, 255, 255, 255);
    
    // Add circles for decoration
    imagesetthickness($image, 3);
    imagearc($image, (int)($width / 2), (int)($height / 2), (int)($width * 0.75), (int)($height * 0.75), 0, 360, $white);
    imagearc($image, (int)($width / 2), (int)($height / 2), (int)($width * 0.55), (int)($height * 0.55), 0, 360, $white);
    
    // Add text
    $text = strtoupper($title);
    
    // Position text in image
    $text_x = 20;
    $text_y = $height - 50;
    
    imagestring($image, 5, $text_x, $text_y, $text, $white);
    
    // Save image as JPEG
    if (!imagejpeg($image, $filename, 85)) {
        imagedestroy($image);
        return false;
    }
    
    imagedestroy($image);
    return true;
}

// Generate all images
function generate_all_placeholders() {
    global $menu_items;
    
    echo "🍽️  Food Image Placeholder Generator\n";
    echo "====================================\n\n";
    
    $total = 0;
    $success = 0;
    
    foreach ($menu_items as $category => $items) {
        $dir = IMAGES_DIR . '/' . $category;
        
        // Create directory
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0755, true)) {
                echo "❌ Cannot create directory: $dir\n";
                continue;
            }
            echo "📁 Created: $category/\n";
        }
        
        // Generate images for this category
        foreach ($items as $filename => $data) {
            $total++;
            list($title, $emoji, $color1, $color2) = $data;
            
            $filepath = $dir . '/' . $filename . '.jpg';
            
            echo "  ⏳ $filename... ";
            
            if (create_food_image(800, 600, $title, $emoji, $color1, $color2, $filepath)) {
                echo "✓ Generated\n";
                $success++;
            } else {
                echo "✗ Failed\n";
            }
        }
    }
    
    echo "\n📊 Summary\n";
    echo "==========\n";
    echo "Generated: $success / $total images\n";
    
    if ($success === $total) {
        echo "\n✅ All placeholder images created successfully!\n";
        echo "   Visit: menu-display.php to see your menu\n";
    }
}

// Run generator
echo "\n";
generate_all_placeholders();
echo "\n";
?>
