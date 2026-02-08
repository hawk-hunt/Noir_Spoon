<?php
require __DIR__ . '/includes/db.php';

if ($pdo) {
    $stmt = $pdo->query('SELECT * FROM menu_categories ORDER BY "order" ASC, name ASC');
    $categories = $stmt->fetchAll();
} else {
    $categories = get_mock_menu_categories();
}

include __DIR__ . '/includes/header.php';
?>

<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="mb-12 space-y-4 text-center animate-fade-in-up">
            <h1 class="text-base-content text-3xl md:text-4xl lg:text-5xl font-bold">Our Menu</h1>
            <p class="text-base-content/80 text-lg">Artfully crafted dishes prepared with the finest ingredients</p>
        </div>

        <?php 
        $catIndex = 0;
        foreach ($categories as $cat): 
            $catDelay = $catIndex * 0.1 + 0.1;
            $catIndex++;
        ?>
            <section class="mb-16 animate-fade-in-up" style="animation-delay: <?php echo $catDelay; ?>s;">
                <h2 class="text-base-content text-2xl md:text-3xl font-semibold mb-8 text-center border-b-2 border-primary pb-4 hover:border-primary/50 transition-colors duration-300"><?php echo e($cat['name']); ?></h2>
                <?php
                if ($pdo) {
                    $stmt = $pdo->prepare('SELECT * FROM menu_items WHERE category_id = :cid ORDER BY "order" ASC, name ASC');
                    $stmt->execute(['cid' => $cat['id']]);
                    $items = $stmt->fetchAll();
                } else {
                    $items = array_filter(get_mock_menu_items(), function($item) use ($cat) {
                        return $item['category_id'] == $cat['id'];
                    });
                }
                ?>
                <div class="space-y-4">
                    <?php 
                    $itemIndex = 0;
                    foreach ($items as $it): 
                        $itemDelay = ($itemIndex * 0.05) + 0.05;
                        $itemIndex++;
                    ?>
                        <div class="card card-border shadow-none hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-102 transform animate-fade-in-up" style="animation-delay: <?php echo $itemDelay + $catDelay; ?>s;">
                            <div class="card-body">
                                <?php
                                    $imgSrc = !empty($it['image']) ? 'assets/img/' . $it['image'] : 'assets/img/pizza.png';
                                ?>
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-28 h-20 flex-shrink-0 overflow-hidden rounded-md bg-base-200 border">
                                            <img src="<?php echo e($imgSrc); ?>" alt="<?php echo e($it['name']); ?>" class="w-full h-full object-cover" />
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="card-title text-lg mb-2 hover:text-primary transition-colors duration-300"><?php echo e($it['name']); ?></h3>
                                            <p class="text-base-content/80 hover:text-base-content transition-colors duration-300"><?php echo e($it['description']); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-semibold text-primary whitespace-nowrap hover:scale-110 transition-transform duration-300">$<?php echo number_format($it['price'],2); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <div class="text-center mt-12">
            <p class="text-base-content/80 mb-6">Ready to experience our cuisine?</p>
            <a href="reservation.php" class="btn btn-primary btn-gradient btn-lg">
                Make a Reservation
                <span class="icon-[tabler--arrow-right] size-5"></span>
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
