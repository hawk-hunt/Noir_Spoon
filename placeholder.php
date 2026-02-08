<?php
/**
 * Food Image Placeholder Generator
 * Generates attractive placeholder images for menu items
 * Access: placeholder.php?name=ItemName&category=mexican&width=400&height=300
 */

// Configuration
define('CACHE_DIR', __DIR__ . '/assets/img/cache/');
define('MAX_SIZE', 2000);

// Create cache directory if it doesn't exist
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}

// Get parameters
$name = $_GET['name'] ?? 'Menu Item';
$category = $_GET['category'] ?? 'mains';
$width = min(intval($_GET['width'] ?? 400), MAX_SIZE);
$height = min(intval($_GET['height'] ?? 300), MAX_SIZE);
$format = $_GET['format'] ?? 'jpg';

// Sanitize inputs
$width = max(100, $width);
$height = max(100, $height);
$name = htmlspecialchars(substr($name, 0, 50));

// Cache filename
$cache_file = CACHE_DIR . md5("$name-$category-$width-$height") . ".jpg";

// Return cached image if available
if (file_exists($cache_file)) {
    header('Content-Type: image/jpeg');
    header('Cache-Control: public, max-age=2592000'); // 30 days
    readfile($cache_file);
    exit;
}

// Color schemes by category
$colors = [
    'mexican' => [
        'bg' => [230, 126, 34],      // Orange
        'accent' => [46, 204, 113]   // Green
    ],
    'breakfast' => [
        'bg' => [243, 156, 18],      // Golden
        'accent' => [155, 89, 182]   // Purple
    ],
    'italian' => [
        'bg' => [192, 57, 43],       // Red
        'accent' => [236, 240, 241]  // White
    ],
    'starters' => [
        'bg' => [52, 152, 219],      // Blue
        'accent' => [241, 196, 15]   // Yellow
    ],
    'desserts' => [
        'bg' => [230, 126, 34],      // Orange
        'accent' => [255, 192, 203]  // Pink
    ],
    'drinks' => [
        'bg' => [26, 188, 156],      // Teal
        'accent' => [255, 213, 0]    // Gold
    ],
    'mains' => [
        'bg' => [155, 89, 182],      // Purple
        'accent' => [243, 156, 18]   // Orange
    ],
];

$color_set = $colors[$category] ?? $colors['mains'];
$bg_color = $color_set['bg'];
$accent_color = $color_set['accent'];

// Create image
$image = imagecreatetruecolor($width, $height);

// Fill background with gradient
$color_rgb = imagecolorallocate($image, $bg_color[0], $bg_color[1], $bg_color[2]);
imagefill($image, 0, 0, $color_rgb);

// Add gradient effect
for ($i = 0; $i < $height; $i++) {
    $ratio = $i / $height;
    $r = intval($bg_color[0] + ($accent_color[0] - $bg_color[0]) * $ratio * 0.3);
    $g = intval($bg_color[1] + ($accent_color[1] - $bg_color[1]) * $ratio * 0.3);
    $b = intval($bg_color[2] + ($accent_color[2] - $bg_color[2]) * $ratio * 0.3);
    
    $line_color = imagecolorallocate($image, $r, $g, $b);
    imageline($image, 0, $i, $width, $i, $line_color);
}

// Add decorative circles
$circle_color = imagecolorallocate(
    $image,
    min(255, $accent_color[0] + 50),
    min(255, $accent_color[1] + 50),
    min(255, $accent_color[2] + 50)
);

imagearc($image, $width / 2, $height / 2, $width * 0.8, $height * 0.8, 0, 360, $circle_color);
imagearc($image, $width / 2, $height / 2, $width * 0.6, $height * 0.6, 0, 360, $circle_color);

// Add emoji-like symbol
$white = imagecolorallocate($image, 255, 255, 255);
$emoji = get_category_emoji($category);

// Use TrueType font if available
$font_size = $width / 6;

// Add icon/symbol in center
$symbol_map = [
    'mexican' => '🌮',
    'breakfast' => '🥞',
    'italian' => '🍝',
    'starters' => '🥂',
    'desserts' => '🍰',
    'drinks' => '🍹',
    'mains' => '🍽️'
];

// Text parameters
$text = strtoupper($name);
$text_y = intval($height * 0.7);

// Try to add text (requires GD with FreeType support)
try {
    // Use built-in fonts instead of TrueType
    $text_width = strlen($text) * 7;
    $text_x = max(10, ($width - $text_width) / 2);
    
    imagestring($image, 5, $text_x, $text_y, $text, $white);
} catch (Exception $e) {
    // Continue without text if fonts not available
}

// Add category label at bottom
$category_label = ucfirst($category);
$label_width = strlen($category_label) * 5;
$label_x = ($width - $label_width) / 2;
imagestring($image, 3, $label_x, $height - 25, $category_label, $white);

// Compress and output
header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=2592000');

// Save to cache
ob_start();
imagejpeg($image, null, 85);
$image_data = ob_get_clean();
file_put_contents($cache_file, $image_data);

echo $image_data;

imagedestroy($image);

function get_category_emoji($category) {
    $emojis = [
        'mexican' => '🌮',
        'breakfast' => '🥞',
        'italian' => '🍝',
        'starters' => '🥂',
        'desserts' => '🍰',
        'drinks' => '🍹',
        'mains' => '🍽️'
    ];
    return $emojis[$category] ?? '🍳';
}
?>
