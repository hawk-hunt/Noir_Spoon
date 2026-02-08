<?php
/**
 * Food Image Downloader
 * Downloads images from Unsplash API and saves to menu folders
 * Usage: php download-images.php
 * Or access via browser: download-images.php
 */

// Configuration
define('UNSPLASH_API_URL', 'https://api.unsplash.com/search/photos');
define('IMAGES_DIR', __DIR__ . '/assets/img/menu');
define('MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('PERMISSION_MODE', 0755);

// Search queries mapped to categories and items
$download_list = [
    // Mexican
    'mexican' => [
        'tacos-al-pastor' => 'Mexican tacos al pastor marinated pork',
        'chile-relleno' => 'Chilean relleno poblano pepper cheese',
        'enchiladas-verdes' => 'Enchiladas verdes Mexican food',
        'burrito-colorado' => 'Burrito Colorado red chile sauce'
    ],
    // Breakfast
    'breakfast' => [
        'pancakes' => 'Fluffy buttermilk pancakes breakfast',
        'omelet' => 'Classic omelet eggs cheese',
        'bacon-plate' => 'Bacon breakfast plate eggs toast',
        'french-toast' => 'French toast cinnamon breakfast'
    ],
    // Italian
    'italian' => [
        'lasagna' => 'Homemade lasagna layers pasta meat sauce',
        'fettuccine-alfredo' => 'Fettuccine Alfredo creamy sauce',
        'spaghetti-carbonara' => 'Spaghetti carbonara pancetta eggs'
    ],
    // Starters
    'starters' => [
        'oysters' => 'Fresh oysters plate restaurant',
        'foie-gras' => 'Foie gras fine dining'
    ],
    // Mains
    'mains' => [
        'salmon' => 'Pan seared salmon fish lemon',
        'wagyu-rib' => 'Wagyu beef short rib meat',
        'lobster' => 'Lobster tail Maine seafood'
    ],
    // Desserts
    'desserts' => [
        'chocolate-souffle' => 'Chocolate souffle warm dessert',
        'panna-cotta' => 'Panna cotta Italian dessert berries',
        'tiramisu' => 'Tiramisu classic Italian dessert'
    ],
    // Drinks
    'drinks' => [
        'espresso-martini' => 'Espresso martini cocktail coffee',
        'cocktail' => 'Cocktail bar drink mixed'
    ]
];

// CLI or Web mode
$is_cli = php_sapi_name() === 'cli';
$mode = $_GET['mode'] ?? ($_POST['mode'] ?? 'list');

if (!$is_cli) {
    header('Content-Type: text/html; charset=UTF-8');
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Image Downloader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .status-item {
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border-left: 4px solid;
        }
        .status-success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        .status-error {
            background: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
        .status-pending {
            background: #dbeafe;
            border-color: #3b82f6;
            color: #1e3a8a;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold mb-2">🍽️ Food Image Downloader</h1>
            <p class="text-gray-600 mb-6">Download high-quality food images from Unsplash and save to your menu folders</p>

            <?php
            if ($mode === 'download') {
                echo render_download_page();
            } else {
                echo render_selection_page();
            }
            ?>
        </div>
    </div>
</body>
</html>
    <?php
} else {
    // CLI mode
    echo "\n🍽️  Food Image Downloader - CLI Mode\n";
    echo "====================================\n\n";
    
    if ($argc > 1 && $argv[1] === 'all') {
        download_all_images();
    } else {
        show_cli_menu();
    }
}

function render_selection_page() {
    global $download_list;
    
    $html = '<form method="POST" class="space-y-6">';
    $html .= '<input type="hidden" name="mode" value="download">';
    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
    
    foreach ($download_list as $category => $items) {
        $html .= '<div class="border rounded-lg p-4">';
        $html .= '<h3 class="font-bold text-lg mb-3 capitalize">' . ucfirst($category) . '</h3>';
        $html .= '<div class="space-y-2">';
        
        foreach ($items as $filename => $query) {
            $checkbox_id = "$category-$filename";
            $html .= '<label class="flex items-center p-2 rounded hover:bg-gray-100 cursor-pointer">';
            $html .= '<input type="checkbox" name="items[]" value="' . htmlspecialchars("$category/$filename") . '" class="mr-3">';
            $html .= '<span class="capitalize">' . str_replace('-', ' ', $filename) . '</span>';
            $html .= '</label>';
        }
        
        $html .= '</div></div>';
    }
    
    $html .= '</div>';
    
    $html .= '<div class="flex gap-4 mt-8">';
    $html .= '<button type="button" onclick="selectAll()" class="btn btn-outline">Select All</button>';
    $html .= '<button type="button" onclick="deselectAll()" class="btn btn-outline">Deselect All</button>';
    
    // Add hidden checkboxes for category presets
    foreach (array_keys($download_list) as $category) {
        $html .= '<button type="button" onclick="selectCategory(\'' . $category . '\')" class="btn btn-sm capitalize">' . ucfirst($category) . '</button>';
    }
    
    $html .= '</div>';
    
    $html .= '<div class="mt-8 flex gap-4">';
    $html .= '<button type="submit" class="btn btn-primary btn-lg">Download Selected Images</button>';
    $html .= '<button type="button" onclick="downloadAll()" class="btn btn-secondary btn-lg">Download All</button>';
    $html .= '</div>';
    
    $html .= '</form>';
    
    $html .= <<<'JS'
<script>
function selectAll() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
}
function deselectAll() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
}
function selectCategory(cat) {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.checked = cb.value.startsWith(cat + '/');
    });
}
function downloadAll() {
    selectAll();
    document.querySelector('form').submit();
}
</script>
    JS;
    
    return $html;
}

function render_download_page() {
    global $download_list;
    
    $items = $_POST['items'] ?? [];
    
    if (empty($items)) {
        return '<div class="alert alert-warning">No items selected</div>';
    }
    
    $html = '<div class="space-y-4">';
    $html .= '<h2 class="text-2xl font-bold">Downloading Images...</h2>';
    $html .= '<div id="download-status" class="space-y-3"></div>';
    $html .= '</div>';
    
    $html .= '<script>';
    $html .= 'const items = ' . json_encode($items) . ';';
    $html .= 'const statusDiv = document.getElementById("download-status");';
    $html .= '
let completed = 0;
async function downloadImages() {
    for (const item of items) {
        const [category, filename] = item.split("/");
        const statusEl = document.createElement("div");
        statusEl.className = "status-item status-pending";
        statusEl.innerHTML = `<strong>${category}/${filename}</strong><div class="progress-bar mt-2"><div class="progress-fill" style="width: 0%"></div></div>`;
        statusDiv.appendChild(statusEl);
        
        try {
            const response = await fetch("download-images.php?action=download&category=" + category + "&filename=" + filename);
            const data = await response.json();
            
            if (data.success) {
                statusEl.className = "status-item status-success";
                statusEl.innerHTML = `✓ <strong>${category}/${filename}</strong> - Downloaded successfully`;
            } else {
                statusEl.className = "status-item status-error";
                statusEl.innerHTML = `✗ <strong>${category}/${filename}</strong> - ${data.error}`;
            }
        } catch (e) {
            statusEl.className = "status-item status-error";
            statusEl.innerHTML = `✗ <strong>${category}/${filename}</strong> - ${e.message}`;
        }
        
        completed++;
        const progress = (completed / items.length) * 100;
    }
    
    setTimeout(() => {
        const summary = document.createElement("div");
        summary.className = "alert alert-success mt-8";
        summary.innerHTML = `<strong>✓ Complete!</strong> ${completed}/${items.length} images downloaded. <a href="menu-display.php" class="link">View your menu</a>`;
        statusDiv.appendChild(summary);
    }, 500);
}
downloadImages();
';
    $html .= '</script>';
    
    return $html;
}

function download_image_from_unsplash($query, $category, $filename) {
    // Validate inputs
    if (!preg_match('/^[a-z0-9\-]+$/', $category)) {
        return ['success' => false, 'error' => 'Invalid category'];
    }
    if (!preg_match('/^[a-z0-9\-]+$/', $filename)) {
        return ['success' => false, 'error' => 'Invalid filename'];
    }
    
    // Create directory if needed
    $dir = IMAGES_DIR . '/' . $category;
    if (!is_dir($dir)) {
        if (!@mkdir($dir, PERMISSION_MODE, true)) {
            return ['success' => false, 'error' => 'Cannot create directory'];
        }
    }
    
    // Query Unsplash API
    $url = UNSPLASH_API_URL . '?query=' . urlencode($query) . '&per_page=1&orientation=landscape';
    
    $ctx = stream_context_create(['http' => ['timeout' => 10]]);
    $response = @file_get_contents($url, false, $ctx);
    
    if (!$response) {
        return ['success' => false, 'error' => 'API unavailable'];
    }
    
    $data = json_decode($response, true);
    
    if (!isset($data['results'][0]['urls']['regular'])) {
        return ['success' => false, 'error' => 'No results found'];
    }
    
    $image_url = $data['results'][0]['urls']['regular'];
    
    // Download image
    $image_data = @file_get_contents($image_url, false, $ctx);
    
    if (!$image_data) {
        return ['success' => false, 'error' => 'Download failed'];
    }
    
    if (strlen($image_data) > MAX_SIZE) {
        return ['success' => false, 'error' => 'File too large'];
    }
    
    // Save file
    $filepath = $dir . '/' . $filename . '.jpg';
    if (!@file_put_contents($filepath, $image_data)) {
        return ['success' => false, 'error' => 'Save failed'];
    }
    
    @chmod($filepath, 0644);
    
    return ['success' => true, 'file' => $filepath, 'size' => strlen($image_data)];
}

function download_all_images() {
    global $download_list;
    
    echo "Starting downloads...\n";
    $total = 0;
    $success = 0;
    
    foreach ($download_list as $category => $items) {
        echo "\n📁 Category: " . strtoupper($category) . "\n";
        
        foreach ($items as $filename => $query) {
            $total++;
            echo "  ⏳ $filename... ";
            
            $result = download_image_from_unsplash($query, $category, $filename);
            
            if ($result['success']) {
                echo "✓ (" . round($result['size'] / 1024, 1) . "KB)\n";
                $success++;
            } else {
                echo "✗ (" . $result['error'] . ")\n";
            }
            
            sleep(1); // Rate limiting for API
        }
    }
    
    echo "\n\n=== Summary ===\n";
    echo "Downloaded: $success / $total\n";
    echo "Success rate: " . round(($success / $total) * 100) . "%\n";
}

function show_cli_menu() {
    global $download_list;
    
    echo "Select category or action:\n";
    $i = 1;
    foreach (array_keys($download_list) as $cat) {
        echo "  $i) " . ucfirst($cat) . "\n";
        $i++;
    }
    echo "  $i) All categories\n";
    echo "  0) Exit\n\n";
    echo "Enter choice (0-$i): ";
    
    $choice = trim(fgets(STDIN));
    $categories = array_keys($download_list);
    
    if ($choice == 0) exit(0);
    if ($choice == count($categories) + 1) {
        download_all_images();
    } elseif (isset($categories[$choice - 1])) {
        $cat = $categories[$choice - 1];
        echo "\nDownloading $cat images...\n";
        foreach ($download_list[$cat] as $filename => $query) {
            echo "  $filename... ";
            $result = download_image_from_unsplash($query, $cat, $filename);
            echo $result['success'] ? "✓\n" : "✗ (" . $result['error'] . ")\n";
            sleep(1);
        }
    }
}

// Handle AJAX download requests
if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: application/json');
    
    $category = $_GET['category'] ?? '';
    $filename = $_GET['filename'] ?? '';
    
    // Find the query string
    global $download_list;
    if (!isset($download_list[$category][$filename])) {
        echo json_encode(['success' => false, 'error' => 'Item not found']);
        exit;
    }
    
    $query = $download_list[$category][$filename];
    $result = download_image_from_unsplash($query, $category, $filename);
    
    echo json_encode($result);
    exit;
}
?>
