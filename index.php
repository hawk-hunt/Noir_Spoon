<?php
require __DIR__ . '/includes/db.php';

// Fetch site settings
if ($pdo) {
    $stmt = $pdo->query("SELECT key, value FROM site_settings");
    $settings = [];
    while ($row = $stmt->fetch()) $settings[$row['key']] = $row['value'];
} else {
    $settings = get_mock_settings();
}

// Featured dishes
if ($pdo) {
    $stmt = $pdo->prepare("SELECT mi.*, mc.name AS category FROM menu_items mi JOIN menu_categories mc ON mi.category_id = mc.id WHERE mi.featured = TRUE ORDER BY mi.id DESC LIMIT 6");
    $stmt->execute();
    $featured = $stmt->fetchAll();
} else {
    $featured = get_mock_featured_dishes();
}

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-[url('assets/img/free-layer-blur.png')] bg-cover bg-center pt-40 sm:pt-48 lg:pt-56 pb-0 md:py-32 lg:py-40">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-6 text-center">
            <div class="bg-base-200 border-base-content/20 w-fit rounded-full border px-3 py-1 animate-fade-in">
                <span>Serving Fine Cuisine Since 2016 ❤️</span>
            </div>
            <h1 class="text-base-content text-4xl md:text-5xl lg:text-6xl font-bold leading-tight max-w-3xl animate-fade-in-up">
                <?php echo e($settings['hero_title'] ?? 'Savor Every Bite. Savor Every Moment.'); ?>
            </h1>
            <p class="text-base-content/80 text-lg md:text-xl max-w-2xl animate-fade-in-up" style="animation-delay: 0.1s;">
                <?php echo e($settings['hero_subtitle'] ?? 'Welcome to a dining experience where flavor, freshness, and hospitality come together.'); ?>
            </p>
            <a href="reservation.php" class="btn btn-primary btn-gradient btn-lg animate-fade-in-up" style="animation-delay: 0.2s;">
                <?php echo e($settings['hero_cta'] ?? 'Reserve a Table'); ?>
                <span class="icon-[tabler--arrow-right] size-5"></span>
            </a>
        </div>
        <img src="assets/img/dishes-hero.png" alt="Dishes" class="min-h-67 w-full object-cover mt-8 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-500 animate-fade-in-up" style="animation-delay: 0.3s;" />
    </div>
</section>

<!-- Featured Dishes Section -->
<?php if ($featured): ?>
<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="mb-12 space-y-4 text-center sm:mb-16 lg:mb-24">
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Featured Dishes</h2>
            <p class="text-base-content/80 text-xl">Handpicked selections from our exquisite menu</p>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php 
            $index = 0;
            foreach ($featured as $dish): 
                $delay = ($index * 0.1) + 0.1;
                $index++;
            ?>
                <div class="card card-border shadow-none hover:border-primary transition-all duration-500 hover:shadow-lg animate-fade-in-up" style="animation-delay: <?php echo $delay; ?>s;">
                    <?php if (!empty($dish['image']) && file_exists(__DIR__ . '/assets/img/' . $dish['image'])): ?>
                        <figure class="overflow-hidden">
                            <img src="assets/img/<?php echo e($dish['image']); ?>" alt="<?php echo e($dish['name']); ?>" class="h-64 w-full object-cover hover:scale-110 transition-transform duration-500" />
                        </figure>
                    <?php else: ?>
                        <figure class="bg-base-200 h-64 flex items-center justify-center">
                            <span class="text-4xl">🍽️</span>
                        </figure>
                    <?php endif; ?>
                    <div class="card-body gap-3">
                        <h3 class="card-title text-xl"><?php echo e($dish['name']); ?></h3>
                        <p class="text-base-content/60 text-sm"><?php echo e($dish['category']); ?></p>
                        <p class="text-base-content/80"><?php echo e($dish['description']); ?></p>
                        <div class="card-actions justify-between items-center">
                            <span class="text-lg font-semibold text-primary">$<?php echo number_format($dish['price'], 2); ?></span>
                            <a href="menu.php" class="btn btn-sm btn-primary btn-ghost hover:scale-105 transition-transform duration-300">View Menu</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- About Short Section -->
<section class="bg-base-200 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="space-y-6 text-center">
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">About Noir Spoon</h2>
            <p class="text-base-content/80 text-lg max-w-2xl mx-auto">
                <?php echo e($settings['about_short'] ?? 'Noir Spoon merges fine dining with modern refinement, offering a culinary experience crafted for the discerning palate.'); ?>
            </p>
            <a href="about.php" class="btn btn-primary btn-gradient">
                Learn More
                <span class="icon-[tabler--arrow-right] size-5"></span>
            </a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="mb-12 space-y-4 text-center sm:mb-16 lg:mb-24">
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Our Services</h2>
            <p class="text-base-content/80 text-xl">Dine in, take out, or delivery — always premium quality</p>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="card card-border shadow-none hover:shadow-lg transition-all duration-500 hover:border-primary hover:-translate-y-2 animate-fade-in-up">
                <figure class="overflow-hidden">
                    <img src="assets/img/free-blog-1.png" alt="Dine In" class="h-48 w-full object-cover hover:scale-110 transition-transform duration-500" />
                </figure>
                <div class="card-body gap-3">
                    <h5 class="card-title text-xl">Dine In</h5>
                    <p class="mb-5">Experience our elegant ambiance and exceptional service in our refined dining room.</p>
                    <div class="card-actions">
                        <a href="reservation.php" class="btn btn-primary btn-gradient btn-sm hover:shadow-lg transition-all duration-300">
                            Reserve Now
                            <span class="icon-[tabler--arrow-right] size-4"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card card-border shadow-none hover:shadow-lg transition-all duration-500 hover:border-primary hover:-translate-y-2 animate-fade-in-up" style="animation-delay: 0.1s;">
                <figure class="overflow-hidden">
                    <img src="assets/img/free-blog-2.png" alt="Takeout" class="h-48 w-full object-cover hover:scale-110 transition-transform duration-500" />
                </figure>
                <div class="card-body gap-3">
                    <h5 class="card-title text-xl">Takeout</h5>
                    <p class="mb-5">Enjoy our signature dishes carefully packaged for at-home enjoyment.</p>
                    <div class="card-actions">
                        <a href="menu.php" class="btn btn-primary btn-gradient btn-sm hover:shadow-lg transition-all duration-300">
                            Order Now
                            <span class="icon-[tabler--arrow-right] size-4"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card card-border shadow-none hover:shadow-lg transition-all duration-500 hover:border-primary hover:-translate-y-2 animate-fade-in-up" style="animation-delay: 0.2s;">
                <figure class="overflow-hidden">
                    <img src="assets/img/free-blog-3.png" alt="Delivery" class="h-48 w-full object-cover hover:scale-110 transition-transform duration-500" />
                </figure>
                <div class="card-body gap-3">
                    <h5 class="card-title text-xl">Delivery</h5>
                    <p class="mb-5">Fresh, hot meals delivered to your door whenever you crave them.</p>
                    <div class="card-actions">
                        <a href="menu.php" class="btn btn-primary btn-gradient btn-sm hover:shadow-lg transition-all duration-300">
                            Browse Menu
                            <span class="icon-[tabler--arrow-right] size-4"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-base-200 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="from-primary/30 to-error/30 p-0.25 overflow-hidden rounded-3xl bg-gradient-to-r">
            <div class="bg-base-100 rounded-3xl p-8 lg:p-16">
                <div class="flex justify-between gap-8 max-md:flex-col md:items-center">
                    <div class="max-w-lg space-y-4">
                        <h2 class="text-base-content text-2xl md:text-4xl font-bold">Ready to Dine?</h2>
                        <p class="text-base-content/80">Make a reservation or check out our menu — your next memorable meal awaits.</p>
                        <a class="btn btn-gradient btn-primary" href="reservation.php">
                            Book Your Table
                            <span class="icon-[tabler--arrow-right]"></span>
                        </a>
                    </div>
                    <img src="assets/img/pizza.png" alt="Pizza" class="rounded-lg max-w-sm hidden lg:block" />
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
