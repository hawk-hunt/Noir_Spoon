<?php
require __DIR__ . '/includes/db.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $guests = (int)($_POST['guests'] ?? 1);

    if ($name === '') $errors[] = 'Name is required';
    if ($phone === '') $errors[] = 'Phone is required';
    if ($date === '' || $time === '') $errors[] = 'Date and time are required';
    if ($guests < 1) $errors[] = 'Guests must be at least 1';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO reservations (name, phone, res_date, res_time, guests, created_at) VALUES (:name, :phone, :date, :time, :guests, now())');
        $stmt->execute(['name'=>$name,'phone'=>$phone,'date'=>$date,'time'=>$time,'guests'=>$guests]);
        $_SESSION['flash'] = 'Reservation submitted — we look forward to hosting you.';
        header('Location: reservation.php'); exit;
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
                    <h2 class="text-base-content mb-6 text-3xl font-semibold">Reserve a Table</h2>
                    
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
                        <div class="flex gap-6 max-md:flex-col">
                            <div class="w-full transform transition-all duration-300 hover:scale-102">
                                <label class="label-text mb-2 block" for="name">Your Name</label>
                                <div class="input input-lg hover:border-primary transition-colors duration-300">
                                    <input type="text" class="grow" placeholder="Enter your name" id="name" name="name" required />
                                    <span class="icon-[tabler--user] text-base-content/80 size-5.5 shrink-0"></span>
                                </div>
                            </div>
                            <div class="w-full transform transition-all duration-300 hover:scale-102">
                                <label class="label-text mb-2 block" for="phone">Phone Number</label>
                                <div class="input input-lg hover:border-primary transition-colors duration-300">
                                    <input type="tel" class="grow" placeholder="+1 (555) 123-4567" id="phone" name="phone" required />
                                    <span class="icon-[tabler--phone] text-base-content/80 size-5.5 shrink-0"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-6 max-md:flex-col">
                            <div class="w-full transform transition-all duration-300 hover:scale-102">
                                <label class="label-text mb-2 block" for="date">Date</label>
                                <div class="input input-lg hover:border-primary transition-colors duration-300">
                                    <input type="date" class="grow" id="date" name="date" required />
                                    <span class="icon-[tabler--calendar-event] text-base-content/80 size-5.5 shrink-0"></span>
                                </div>
                            </div>
                            <div class="w-full transform transition-all duration-300 hover:scale-102">
                                <label class="label-text mb-2 block" for="time">Time</label>
                                <div class="input input-lg hover:border-primary transition-colors duration-300">
                                    <input type="time" class="grow" id="time" name="time" required />
                                    <span class="icon-[tabler--clock] text-base-content/80 size-5.5 shrink-0"></span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full transform transition-all duration-300 hover:scale-102">
                            <label class="label-text mb-2 block" for="guests">Number of Guests</label>
                            <div class="input input-lg hover:border-primary transition-colors duration-300">
                                <input type="number" class="grow" min="1" max="20" value="2" id="guests" name="guests" required />
                                <span class="icon-[tabler--users] text-base-content/80 size-5.5 shrink-0"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-gradient w-full hover:shadow-lg transition-all duration-300 active:scale-95">Confirm Reservation</button>
                    </form>
                </div>
                
                <!-- Contact Info Section -->
                <div class="space-y-6 lg:col-span-3 animate-fade-in-right">
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in">
                        <h3 class="text-base-content mb-4 text-xl font-semibold">Phone</h3>
                        <p class="text-base-content/80">+1 (555) 123-4567</p>
                    </div>
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in" style="animation-delay: 0.1s;">
                        <h3 class="text-base-content mb-4 text-xl font-semibold">Location</h3>
                        <p class="text-base-content/80">123 Fine Dining Ave</p>
                        <p class="text-base-content/80">New York, NY 10001</p>
                    </div>
                    <div class="border-base-content/20 rounded-box border p-6 text-center hover:border-primary hover:shadow-lg transition-all duration-500 hover:scale-105 transform cursor-pointer animate-scale-in" style="animation-delay: 0.2s;">
                        <h3 class="text-base-content mb-2 text-xl font-semibold">Hours</h3>
                        <p class="text-base-content/80 font-medium">
                            <span class="text-primary">5:00 PM - 11:00 PM</span>
                        </p>
                        <p class="text-base-content/80 text-sm">Closed Mondays</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
