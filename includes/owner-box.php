<?php
// Owner highlight box include
// Usage: include __DIR__ . '/includes/owner-box.php'; or include_once
// Depends on `includes/db.php` exposing $pdo (PDO) as in this project.

include_once __DIR__ . '/db.php';

$default_image = '/assets/img/owner-default.jpg';
$default_name  = 'The Owner';
$default_goals = 'My goal is to present Noir Spoon\'s refined dining experiences online — highlighting our curated tasting menus, private events, and an effortless reservation experience.';

$image = $default_image;
$name  = $default_name;
$goals = $default_goals;

// If the user has placed the provided image in the assets folder with spaces in the filename,
// prefer that file when present (path from your message):
$candidate = __DIR__ . '/../assets/img/WhatsApp Image 2026-02-07 at 07.32.37.jpeg';
if (file_exists($candidate)) {
    // Use web-friendly path and encode spaces when output
    $image = '/assets/img/WhatsApp Image 2026-02-07 at 07.32.37.jpeg';
}

// Try to load from DB if PDO is available
if (isset($pdo) && $pdo) {
    try {
        $stmt = $pdo->query("SELECT owner_image, owner_name, owner_goals FROM site_owner LIMIT 1");
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
        if ($row) {
            if (!empty($row['owner_image'])) {
                $raw = trim($row['owner_image']);
                // If absolute or URL, keep; otherwise prefix to safe folder
                if (preg_match('#^(https?://|/)#i', $raw)) {
                    $image = htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
                } else {
                    $image = '/assets/img/owners/' . basename($raw);
                }
            }
            if (!empty($row['owner_name'])) {
                $name = htmlspecialchars($row['owner_name'], ENT_QUOTES, 'UTF-8');
            }
            if (!empty($row['owner_goals'])) {
                $goals = htmlspecialchars($row['owner_goals'], ENT_QUOTES, 'UTF-8');
            }
        }
    } catch (Exception $e) {
        // silently fall back to defaults
    }
}

?>
<div class="owner-box">
    <div class="owner-media">
        <?php
        // Encode spaces for URL safety when echoing
        $img_src = str_replace(' ', '%20', $image);
        ?>
        <img src="<?php echo htmlspecialchars($img_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" />
    </div>
  <div class="owner-text">
    <h3 class="owner-name"><?php echo $name; ?></h3>
    <p class="owner-goals"><?php echo $goals; ?></p>
  </div>
</div>
