<?php
require __DIR__ . '/includes/db.php';

// Get all categories
if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM menu_categories ORDER BY "order" ASC, name ASC');
    $categories = $stmt->fetchAll();
} else {
    $categories = get_mock_menu_categories();
}

include __DIR__ . '/includes/header.php';
?>

<style>
.menu-section {
    scroll-margin-top: 80px;
}

.menu-item-card {
    transition: all 0.3s ease;
}

.menu-item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.menu-item-image {
    width: 100%;
    height: 240px;
    object-fit: cover;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
}

.price-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
}

.category-header {
    position: relative;
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
}

.category-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #667eea, transparent);
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fbbf24;
    color: #000;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .menu-grid {
        grid-template-columns: 1fr;
    }
}

.menu-item-body {
    padding: 1.25rem;
}

.menu-item-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--base-content);
    margin-bottom: 0.5rem;
}

.menu-item-description {
    font-size: 0.875rem;
    color: var(--base-content, rgba(0, 0, 0, 0.6));
    margin-bottom: 1rem;
    line-height: 1.5;
}

.menu-item-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--base-300);
}

.category-description {
    font-size: 0.95rem;
    color: var(--base-content, rgba(0, 0, 0, 0.7));
    margin-top: 0.5rem;
    font-style: italic;
}

.no-image {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}
</style>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary py-12 md:py-20">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 animate-fade-in-up">
            <h1 class="text-white text-4xl md:text-5xl lg:text-6xl font-bold">Our Complete Menu</h1>
            <p class="text-secondary-content text-lg md:text-xl">Discover a world of exquisite flavors from around the globe</p>
        </div>
    </div>
</section>

<!-- Menu Categories Navigation -->
<section class="bg-base-100 sticky top-16 z-40 shadow-sm">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto py-4 gap-2 md:gap-4">
            <?php foreach ($categories as $index => $cat): ?>
                <a 
                    href="#category-<?php echo e($cat['id']); ?>" 
                    class="btn btn-sm md:btn-md btn-ghost whitespace-nowrap flex-shrink-0 <?php echo $index === 0 ? 'btn-primary' : ''; ?>"
                >
                    <?php echo e($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Menu Items By Category -->
<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <?php 
        $catIndex = 0;
        foreach ($categories as $cat): 
            $catDelay = $catIndex * 0.05 + 0.1;
            $catIndex++;
        ?>
            <section id="category-<?php echo e($cat['id']); ?>" class="menu-section mb-20 animate-fade-in-up" style="animation-delay: <?php echo $catDelay; ?>s;">
                <!-- Category Header -->
                <div class="category-header text-center mb-12">
                    <h2 class="text-base-content text-3xl md:text-4xl lg:text-5xl font-bold">
                        <?php echo e($cat['name']); ?>
                    </h2>
                    <?php if (!empty($cat['description'])): ?>
                        <p class="category-description text-lg">
                            <?php echo e($cat['description']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Menu Items Grid -->
                <?php
                if ($pdo) {
                    $stmt = $pdo->prepare('SELECT * FROM menu_items WHERE category_id = :cid AND active = TRUE ORDER BY "order" ASC, name ASC');
                    $stmt->execute(['cid' => $cat['id']]);
                    $items = $stmt->fetchAll();
                } else {
                    $items = array_filter(get_mock_menu_items(), function($item) use ($cat) {
                        return $item['category_id'] == $cat['id'];
                    });
                }
                ?>

                <?php if (!empty($items)): ?>
                    <div class="menu-grid">
                        <?php 
                        $itemIndex = 0;
                        foreach ($items as $item): 
                            $itemDelay = ($itemIndex * 0.05) + 0.05;
                            $itemIndex++;
                        ?>
                            <div class="card card-border shadow-md bg-base-100 menu-item-card animate-fade-in-up" style="animation-delay: <?php echo $itemDelay + $catDelay; ?>s;">
                                <!-- Item Image -->
                                <?php
                                    $imgSrc = null;
                                    if (!empty($item['image'])) {
                                        $imgSrc = 'assets/img/menu/' . e($item['image']);
                                    }
                                ?>
                                <figure class="relative bg-base-200 h-64 overflow-hidden">
                                    <?php if ($imgSrc): ?>
                                        <img 
                                            src="<?php echo $imgSrc; ?>" 
                                            alt="<?php echo e($item['name']); ?>"
                                            class="menu-item-image"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        />
                                        <div class="no-image" style="display: none;">🍽️</div>
                                    <?php else: ?>
                                        <div class="no-image">🍽️</div>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['featured']): ?>
                                        <div class="featured-badge">⭐ Featured</div>
                                    <?php endif; ?>
                                </figure>

                                <!-- Item Details -->
                                <div class="menu-item-body flex flex-col flex-grow">
                                    <h3 class="menu-item-name"><?php echo e($item['name']); ?></h3>
                                    <p class="menu-item-description flex-grow"><?php echo e($item['description']); ?></p>
                                    
                                    <div class="menu-item-footer">
                                        <span class="price-badge">$<?php echo number_format($item['price'], 2); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <span>No items available in this category.</span>
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-gradient-to-r from-primary to-secondary py-12 md:py-20">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-6 animate-fade-in-up">
            <h2 class="text-white text-3xl md:text-4xl font-bold">Ready to Experience Our Menu?</h2>
            <p class="text-secondary-content text-lg md:text-xl max-w-2xl mx-auto">
                Reserve a table and let our culinary team prepare an unforgettable dining experience for you.
            </p>
            <a href="reservation.php" class="btn btn-ghost text-white border-white hover:bg-white hover:text-primary btn-lg">
                Make a Reservation
                <span class="icon-[tabler--arrow-right] size-5"></span>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
