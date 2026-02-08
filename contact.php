<?php
require __DIR__ . '/includes/db.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $email === '' || $message === '') $errors[] = 'All fields are required.';
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message, created_at) VALUES (:name,:email,:message,now())');
        $stmt->execute(['name'=>$name,'email'=>$email,'message'=>$message]);
        $_SESSION['flash'] = 'Message sent. We will reply shortly.';
        header('Location: contact.php'); exit;
    }
}
include __DIR__ . '/includes/header.php';
?>

<section class="bg-base-200 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="card shadow-lg hover:shadow-xl transition-shadow duration-500 animate-fade-in">
            <div class="card-body grid gap-10 lg:grid-cols-7">
                <!-- Form Section -->
                <div class="lg:col-span-4 animate-fade-in-left">
                    <h2 class="text-base-content mb-2 text-3xl font-semibold">Contact Us</h2>
                    <p class="text-base-content/80 mb-6">Have questions? We'd love to hear from you.</p>
                    
                    <?php if (!empty($_SESSION['flash'])): ?>
                        <div class="alert alert-success mb-4 animate-fade-in-up">
                            <svg class="h-6 w-6 shrink-0 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span><?php echo e($_SESSION['flash']); unset($_SESSION['flash']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($errors): ?>
                        <div class="alert alert-error mb-4 animate-fade-in-up">
                            <svg class="h-6 w-6 shrink-0 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div><ul><?php foreach ($errors as $er) echo '<li>'.e($er).'</li>'; ?></ul></div>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="space-y-6">
                        <div class="w-full transform transition-all duration-300 hover:scale-102">
                            <label class="label-text mb-2 block" for="name">Full Name</label>
                            <div class="input input-lg hover:border-primary transition-colors duration-300">
                                <input type="text" class="grow" placeholder="Your name" id="name" name="name" required />
                                <span class="icon-[tabler--user] text-base-content/80 size-5.5 shrink-0"></span>
                            </div>
                        </div>
                        <div class="w-full transform transition-all duration-300 hover:scale-102">
                            <label class="label-text mb-2 block" for="email">Email Address</label>
                            <div class="input input-lg hover:border-primary transition-colors duration-300">
                                <input type="email" class="grow" placeholder="your@email.com" id="email" name="email" required />
                                <span class="icon-[tabler--mail] text-base-content/80 size-5.5 shrink-0"></span>
                            </div>
                        </div>
                        <div class="w-full transform transition-all duration-300 hover:scale-102">
                            <label class="label-text mb-2 block" for="message">Message</label>
                            <div class="textarea hover:border-primary transition-colors duration-300">
                                <textarea class="grow resize-none" aria-label="Message" placeholder="Your message here..." id="message" name="message" required></textarea>
                                <span class="icon-[tabler--message-circle-2] text-base-content/80 mx-4 mt-2 size-6 shrink-0"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-gradient w-full hover:shadow-lg transition-all duration-300 active:scale-95">Send Message</button>
                    </form>
                </div>
                
                <!-- Contact Info Section -->
                <div class="space-y-6 lg:col-span-3 animate-fade-in-right">
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in">
                        <h3 class="text-base-content mb-4 text-xl font-semibold">Email</h3>
                        <p class="text-base-content/80"><a href="mailto:info@noirspoon.com" class="link link-primary hover:link-hover transition-all duration-300">info@noirspoon.com</a></p>
                    </div>
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in" style="animation-delay: 0.1s;">
                        <h3 class="text-base-content mb-4 text-xl font-semibold">Phone</h3>
                        <p class="text-base-content/80"><a href="tel:+15551234567" class="link link-primary hover:link-hover transition-all duration-300">+1 (555) 123-4567</a></p>
                    </div>
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in" style="animation-delay: 0.2s;">
                        <h3 class="text-base-content mb-4 text-xl font-semibold">Location</h3>
                        <p class="text-base-content/80">123 Fine Dining Ave</p>
                        <p class="text-base-content/80">New York, NY 10001</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
