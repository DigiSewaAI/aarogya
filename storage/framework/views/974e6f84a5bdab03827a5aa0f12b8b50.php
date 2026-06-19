<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - <?php echo e($doctor->name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
    </style>
</head>
<body class="bg-slate-50">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <section class="hero-bg overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 py-12">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-8 text-white">
                    <div class="flex items-center gap-6">
                        <?php if($doctor->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . $doctor->profile_photo)); ?>" 
                                 alt="<?php echo e($doctor->name); ?>" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        <?php else: ?>
                            <div class="w-32 h-32 rounded-full bg-white flex items-center justify-center text-6xl shadow-lg">
                                👨‍⚕️
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="text-3xl font-bold"><?php echo e($doctor->name); ?></h1>
                            <p class="text-cyan-100 text-lg"><?php echo e($doctor->specialization); ?></p>
                            <p class="text-sm text-cyan-200 mt-1">
                                ⭐ 4.9 (120 <?php echo e(__('messages.reviews')); ?>) • 
                                <?php echo e($doctor->experience ?? 0); ?> <?php echo e(__('messages.experience_years')); ?>

                            </p>
                            <?php if($doctor->verification_status == 'approved'): ?>
                                <span class="inline-block mt-2 bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                                    ✅ <?php echo e(__('messages.verified')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-slate-500"><?php echo e(__('messages.qualification')); ?></p>
                            <p class="font-medium text-slate-800"><?php echo e($doctor->qualification ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500"><?php echo e(__('messages.nmc_registration')); ?></p>
                            <p class="font-medium text-slate-800"><?php echo e($doctor->nmc_registration ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500"><?php echo e(__('messages.consultation_fee')); ?></p>
                            <p class="font-medium text-cyan-600 text-xl"><?php echo e(__('messages.currency')); ?> <?php echo e(number_format($doctor->consultation_fee ?? 0, 2)); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500"><?php echo e(__('messages.clinic_name')); ?></p>
                            <p class="font-medium text-slate-800"><?php echo e($doctor->clinic_name ?? 'N/A'); ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-slate-500"><?php echo e(__('messages.bio')); ?></p>
                            <p class="text-slate-700 mt-1 leading-relaxed"><?php echo e($doctor->bio ?? 'No bio available.'); ?></p>
                        </div>
                    </div>

                    <!-- Schedule / Availability -->
                    <?php if(isset($doctor->schedules) && $doctor->schedules->count() > 0): ?>
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <h3 class="font-semibold text-slate-800"><?php echo e(__('messages.available_slots')); ?></h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                <?php $__currentLoopData = $doctor->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($schedule->is_active): ?>
                                        <div class="bg-cyan-50 p-2 rounded-xl text-center text-sm">
                                            <span class="font-medium"><?php echo e($schedule->day_name ?? $schedule->day_of_week); ?></span>
                                            <p class="text-xs text-slate-500"><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Buttons -->
                    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap gap-4">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::user()->isPatient()): ?>
                                
                                <a href="<?php echo e(route('appointment.create', $doctor->id)); ?>" 
                                   class="bg-cyan-600 text-white px-8 py-3 rounded-xl hover:bg-cyan-700 transition shadow-lg flex items-center gap-2">
                                    📅 <?php echo e(__('messages.book_appointment')); ?>

                                </a>
                            <?php else: ?>
                                <span class="bg-slate-200 text-slate-500 px-8 py-3 rounded-xl cursor-not-allowed">
                                    <?php echo e(__('messages.book_appointment')); ?>

                                </span>
                                <p class="text-sm text-slate-400 mt-2"><?php echo e(__('messages.patient_only_booking')); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" 
                               class="bg-cyan-600 text-white px-8 py-3 rounded-xl hover:bg-cyan-700 transition shadow-lg flex items-center gap-2">
                                🔐 <?php echo e(__('messages.login_to_book')); ?>

                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('doctors')); ?>" 
                           class="border border-slate-300 px-8 py-3 rounded-xl hover:bg-slate-50 transition text-slate-700">
                            ← <?php echo e(__('messages.back_to_doctors')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\laragon\www\aarogya\resources\views/doctor-profile.blade.php ENDPATH**/ ?>