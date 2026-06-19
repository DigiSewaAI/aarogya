<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - <?php echo e(__('messages.doctors_title')); ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .glass { backdrop-filter: blur(16px); background: rgba(255,255,255,.75); border: 1px solid rgba(255,255,255,.2); }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
        .doctor-card:hover .doctor-image {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="hero-bg overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 pt-8 pb-16">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium mb-4">
                    <?php echo e(__('messages.doctors_badge')); ?>

                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900">
                    <?php echo __('messages.doctors_heading'); ?>

                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto">
                    <?php echo e(__('messages.doctors_subtitle')); ?>

                </p>
            </div>

            <?php if(isset($doctors) && count($doctors) > 0): ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="doctor-card bg-white rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100">
                            
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 h-32 flex items-center justify-center relative overflow-hidden">
                                <?php if($doctor->profile_photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $doctor->profile_photo)); ?>" 
                                         alt="<?php echo e($doctor->name); ?>" 
                                         class="doctor-image w-full h-full object-cover transition-transform duration-300">
                                <?php else: ?>
                                    <div class="bg-white rounded-full p-3 w-24 h-24 flex items-center justify-center shadow-lg">
                                        <span class="text-5xl"><?php echo e(substr($doctor->name, 0, 1)); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($doctor->verification_status == 'approved'): ?>
                                    <span class="absolute top-3 right-3 bg-green-500 text-white text-xs px-2 py-1 rounded-full shadow">
                                        ✅ Verified
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-slate-800"><?php echo e($doctor->name); ?></h3>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="bg-cyan-100 text-cyan-700 text-xs px-2 py-1 rounded-full">
                                        <?php echo e($doctor->specialization ?? __('messages.doctor_specialization')); ?>

                                    </span>
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">
                                        <?php echo e($doctor->experience ?? 0); ?> <?php echo e(__('messages.doctor_experience_years')); ?>

                                    </span>
                                </div>

                                <div class="mt-4 space-y-2 text-slate-600">
                                    <p class="flex items-center gap-2">
                                        💰 <span class="font-semibold"><?php echo e(__('messages.currency')); ?> <?php echo e(number_format($doctor->consultation_fee ?? 0, 2)); ?></span> / <?php echo e(__('messages.doctor_fee')); ?>

                                    </p>
                                    <p class="flex items-center gap-2">📅 <span><?php echo e(__('messages.doctor_available_today')); ?></span></p>
                                    <p class="flex items-center gap-2">
                                        ⭐ <?php echo e(__('messages.doctor_rating')); ?> (<?php echo e(__('messages.doctor_reviews_count', ['count' => 120])); ?>)
                                    </p>
                                </div>

                                <div class="mt-6 flex gap-3">
                                    
                                    <?php if(auth()->guard()->check()): ?>
                                        <a href="<?php echo e(route('appointment.create', $doctor->id)); ?>" 
                                           class="flex-1 bg-cyan-600 text-white text-center py-2 rounded-xl hover:bg-cyan-700 transition">
                                            <?php echo e(__('messages.doctor_book')); ?>

                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('login')); ?>" 
                                           class="flex-1 bg-cyan-600 text-white text-center py-2 rounded-xl hover:bg-cyan-700 transition">
                                            <?php echo e(__('messages.doctor_book')); ?>

                                        </a>
                                    <?php endif; ?>

                                    
                                    <a href="<?php echo e(route('doctor.show', $doctor->id)); ?>" 
                                       class="px-4 py-2 border border-slate-300 rounded-xl hover:bg-slate-50 transition text-slate-700">
                                        <?php echo e(__('messages.doctor_profile')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <?php if(method_exists($doctors, 'links')): ?>
                    <div class="mt-8">
                        <?php echo e($doctors->links()); ?>

                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="bg-white/60 backdrop-blur-sm rounded-3xl shadow-xl p-12 text-center border border-slate-100">
                    <div class="text-7xl mb-4">👨‍⚕️🚫</div>
                    <h2 class="text-2xl font-bold text-slate-700"><?php echo e(__('messages.doctors_no_doctors')); ?></h2>
                    <p class="text-slate-500 mt-2"><?php echo e(__('messages.doctors_no_doctors_sub')); ?></p>
                    <a href="<?php echo e(url('/')); ?>" class="inline-block mt-6 bg-cyan-600 text-white px-6 py-2 rounded-xl hover:bg-cyan-700 transition">
                        <?php echo e(__('messages.doctors_go_home')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold"><?php echo e(__('messages.doctors_features_heading')); ?></h2>
            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">✅</div>
                    <h3 class="font-bold"><?php echo e(__('messages.doctors_feature1_title')); ?></h3>
                    <p class="text-slate-500"><?php echo e(__('messages.doctors_feature1_desc')); ?></p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">💬</div>
                    <h3 class="font-bold"><?php echo e(__('messages.doctors_feature2_title')); ?></h3>
                    <p class="text-slate-500"><?php echo e(__('messages.doctors_feature2_desc')); ?></p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <div class="text-4xl mb-3">📋</div>
                    <h3 class="font-bold"><?php echo e(__('messages.doctors_feature3_title')); ?></h3>
                    <p class="text-slate-500"><?php echo e(__('messages.doctors_feature3_desc')); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\laragon\www\aarogya\resources\views/doctors.blade.php ENDPATH**/ ?>