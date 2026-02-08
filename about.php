<?php
require __DIR__ . '/includes/db.php';

if ($pdo) {
    $stmt = $pdo->prepare('SELECT value FROM site_settings WHERE key = :k LIMIT 1');
    $stmt->execute(['k'=>'about_long']);
    $about = $stmt->fetchColumn();
} else {
    $settings = get_mock_settings();
    $about = $settings['about_long'];
}

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-b from-base-200 to-base-100 py-12 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center animate-fade-in">
            <h1 class="text-base-content text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Generational Excellence in Every Bite</h1>
            <p class="text-base-content/70 text-lg md:text-xl mb-8 max-w-2xl mx-auto">
                From our family kitchen to your table — recipes passed down through generations, prepared with unwavering commitment to quality, freshness, and taste.
            </p>
        </div>
    </div>
</section>

<!-- Founder's Legacy Section -->
<section class="bg-base-100 py-12 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-base-content text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Our Founder's Legacy</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-transparent via-primary to-transparent mx-auto mb-6"></div>
                <p class="text-base-content/80 text-lg max-w-2xl mx-auto">Built on principles, preserved with pride, and carried forward with purpose.</p>
            </div>

            <!-- Legacy Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center mb-16">
                <!-- Owner Image - Left -->
                <div class="flex justify-center animate-fade-in-left order-2 md:order-1">
                    <div class="relative">
                        <!-- Heritage Frame -->
                        <div class="absolute inset-0 bg-primary/5 rounded-2xl transform -rotate-1 scale-105"></div>
                        <div class="relative w-80 h-80 md:w-96 md:h-96 rounded-2xl overflow-hidden shadow-2xl border-4 border-primary/20 hover:shadow-2xl transition-all duration-500 hover:border-primary/40">
                            <img src="assets/img/WhatsApp Image 2026-02-08 at 07.39.10.jpeg" alt="Current Owner, Keeper of Our Legacy" class="w-full h-full object-cover" />
                        </div>
                        <!-- Accent Corner -->
                        <div class="absolute -bottom-2 -right-2 w-16 h-16 border-r-4 border-b-4 border-primary/30 rounded-br-lg"></div>
                    </div>
                </div>
                
                <!-- Legacy Story - Right -->
                <div class="space-y-8 order-1 md:order-2 animate-fade-in-right">
                    <!-- The Foundation -->
                    <div class="space-y-3">
                        <h3 class="text-base-content text-2xl font-bold">The Foundation</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            Our founder understood that great food is more than technique—it's a reflection of values. In a modest home kitchen, they built a tradition on three unshakeable principles: use only the finest ingredients, prepare every dish with genuine care, and treat every customer like family.
                        </p>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            These weren't just business rules. They were a family creed, woven into every recipe, every practice, every interaction.
                        </p>
                    </div>

                    <!-- Recipes & Techniques -->
                    <div class="space-y-3">
                        <h3 class="text-base-content text-2xl font-bold">Recipes & Techniques Passed Down</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            For generations, our family kitchen has been both classroom and laboratory. Recipes weren't written down casually—they were taught with intention, refined with patience, and perfected through countless meals. Every technique, every flavor balance, every presentation detail carries the stamp of our heritage.
                        </p>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            Our founder believed that consistency comes from discipline—measuring precisely, sourcing carefully, never rushing. This disciplined approach remains at the heart of everything we do.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Visual Divider -->
            <div class="my-12 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>

            <!-- Current Leadership Section -->
            <div class="bg-base-200/50 rounded-xl p-8 md:p-12 border border-primary/10 animate-fade-in-up">
                <h3 class="text-base-content text-2xl font-bold mb-4">Honoring the Past. Leading the Future.</h3>
                
                <p class="text-base-content/80 text-lg leading-relaxed mb-6">
                    As the current owner, I stand as both student and steward of this legacy. I was raised understanding that our reputation isn't built in a single day—it's earned through consistency, earned through excellence, earned through respect for the traditions passed to me.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Consistency -->
                    <div class="space-y-3">
                        <h4 class="text-base-content font-semibold text-lg">Consistency</h4>
                        <p class="text-base-content/70 leading-relaxed">
                            Every dish prepared today meets the same standards our founder established. We don't vary our quality based on the size of an event or the budget. Excellence is our baseline.
                        </p>
                    </div>

                    <!-- Pride in Craft -->
                    <div class="space-y-3">
                        <h4 class="text-base-content font-semibold text-lg">Pride in Craft</h4>
                        <p class="text-base-content/70 leading-relaxed">
                            We take genuine pride in every detail—from ingredient selection to presentation. Our team understands they're not just cooking; they're continuing a family tradition of excellence.
                        </p>
                    </div>

                    <!-- Respect & Evolution -->
                    <div class="space-y-3">
                        <h4 class="text-base-content font-semibold text-lg">Respect & Evolution</h4>
                        <p class="text-base-content/70 leading-relaxed">
                            We respect our heritage while thoughtfully evolving. Modern techniques and contemporary tastes enhance—never replace—the foundation our founder built.
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-primary/20">
                    <p class="text-base-content/80 text-lg leading-relaxed italic">
                        "Our founder's vision wasn't about creating a restaurant or a brand. It was about creating trust—the trust that when you hire us to cater your event, you're receiving the benefit of generations of knowledge, care, and commitment to excellence. That trust is sacred to us, and we protect it every single day."
                    </p>
                </div>
            </div>

            <!-- Heritage Highlights -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3 animate-fade-in-up">
                    <h4 class="text-base-content font-semibold text-lg flex items-center gap-2">
                        <span class="text-primary font-bold">•</span> What We Preserve
                    </h4>
                    <ul class="space-y-2 text-base-content/80">
                        <li>Original family recipes unchanged in their essence</li>
                        <li>Hand-selected, premium ingredient sourcing</li>
                        <li>Personal attention to every client relationship</li>
                        <li>Meticulous preparation and attention to detail</li>
                        <li>Genuine hospitality in every interaction</li>
                    </ul>
                </div>

                <div class="space-y-3 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <h4 class="text-base-content font-semibold text-lg flex items-center gap-2">
                        <span class="text-primary font-bold">•</span> How We Evolve
                    </h4>
                    <ul class="space-y-2 text-base-content/80">
                        <li>Contemporary plating and presentation techniques</li>
                        <li>Dietary accommodations and modern preferences</li>
                        <li>Efficient logistics and event management</li>
                        <li>Sustainable sourcing and ethical practices</li>
                        <li>Professional team training and development</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Heritage Section -->
<section class="bg-base-200 py-12 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-base-content text-3xl md:text-4xl font-bold mb-12 text-center">A Legacy of Quality & Care</h2>
            
            <div class="space-y-8">
                <!-- Heritage Timeline -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-base-100 rounded-lg p-8 border border-primary/20 hover:border-primary/40 transition-all duration-300 animate-fade-in-up">
                        <h3 class="text-base-content text-2xl font-bold mb-4">Our Roots</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            What started as a passion for sharing delicious, home-cooked food has become a cornerstone of our community. Our founder believed that great food builds connections, and every meal is an opportunity to care for those we serve.
                        </p>
                    </div>
                    
                    <div class="bg-base-100 rounded-lg p-8 border border-primary/20 hover:border-primary/40 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <h3 class="text-base-content text-2xl font-bold mb-4">Passed Down With Pride</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            Recipes refined through generations. Techniques perfected in home kitchens. Memories created one event at a time. This isn't just a business—it's a family commitment to excellence that we carry forward today.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Vision & Goals Section -->
<section class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-base-content text-3xl md:text-4xl font-bold mb-4 text-center">Our Commitment to You</h2>
            <p class="text-base-content/80 text-lg text-center mb-16 max-w-2xl mx-auto">
                Every event we cater is a reflection of our dedication to delivering the same quality and care our founder promised decades ago.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Mission Card -->
                <div class="card card-border shadow-lg hover:shadow-xl transition-shadow animate-fade-in-up">
                    <div class="card-body gap-4">
                        <h3 class="card-title text-2xl">Our Mission</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            To provide exceptional catering that brings people together through delicious, authentic food prepared with care, integrity, and unwavering attention to detail. We honor tradition while meeting the needs of modern events.
                        </p>
                    </div>
                </div>
                
                <!-- Vision Card -->
                <div class="card card-border shadow-lg hover:shadow-xl transition-shadow animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card-body gap-4">
                        <h3 class="card-title text-2xl">Our Vision</h3>
                        <p class="text-base-content/80 text-lg leading-relaxed">
                            To remain a trusted, family-led catering business that grows thoughtfully while maintaining the quality, personalization, and genuine care that define us. We serve with pride and passion, every single time.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Business Goals -->
            <div class="bg-base-200 rounded-2xl p-8 md:p-12">
                <h3 class="text-base-content text-3xl font-bold mb-10 text-center">Our Core Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2 animate-fade-in-up">
                        <h4 class="text-base-content font-semibold text-xl">Tradition</h4>
                        <p class="text-base-content/80">Honoring the recipes, techniques, and values passed down through our family while respecting their timeless wisdom.</p>
                    </div>
                    <div class="space-y-2 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <h4 class="text-base-content font-semibold text-xl">Trust</h4>
                        <p class="text-base-content/80">Building relationships through consistent delivery, transparency, and unwavering reliability in every interaction.</p>
                    </div>
                    <div class="space-y-2 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <h4 class="text-base-content font-semibold text-xl">Quality</h4>
                        <p class="text-base-content/80">Never compromising on ingredients, preparation, or presentation. Excellence is our standard, not an exception.</p>
                    </div>
                    <div class="space-y-2 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <h4 class="text-base-content font-semibold text-xl">Care</h4>
                        <p class="text-base-content/80">Treating every client and every event as family. Personal attention and genuine concern define every aspect of our service.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA at end of page -->
<section class="bg-base-100 py-12">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-base-content text-3xl font-bold mb-4">Ready to Bring Our Excellence to Your Event?</h3>
            <p class="text-base-content/80 text-lg mb-8">Contact us today to discuss your catering needs and let us create something special for you.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="reservation.php" class="btn btn-primary btn-gradient btn-lg">
                    Get a Quote
                    <span class="icon-[tabler--arrow-right] size-5"></span>
                </a>
                <a href="contact.php" class="btn btn-outline btn-lg">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
