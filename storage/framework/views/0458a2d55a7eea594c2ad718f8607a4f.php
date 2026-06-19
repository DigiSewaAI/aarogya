<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - <?php echo e(__('messages.dashboard')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-poppins">

    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex gap-6">
            <!-- Sidebar -->
            <div class="w-64 flex-shrink-0">
                <?php echo $__env->make('partials.doctor-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6 text-white">
                        <h2 class="text-3xl font-bold"><?php echo e(__('messages.dashboard_welcome', ['name' => auth()->user()->name])); ?></h2>
                        <p class="opacity-90"><?php echo e(__('messages.dashboard_subtitle')); ?></p>
                    </div>
                    <div class="p-8">
                        <!-- Widgets Grid -->
                        <div class="grid md:grid-cols-4 gap-6">
                            <div class="bg-cyan-50 p-6 rounded-2xl">
                                <div class="text-4xl mb-3">📅</div>
                                <h3 class="font-bold text-xl"><?php echo e(__('messages.todays_appointments') ?? 'Today\'s Appointments'); ?></h3>
                                <p class="text-3xl font-bold text-cyan-600">0</p>
                            </div>
                            <div class="bg-cyan-50 p-6 rounded-2xl">
                                <div class="text-4xl mb-3">📋</div>
                                <h3 class="font-bold text-xl"><?php echo e(__('messages.pending_requests') ?? 'Pending Requests'); ?></h3>
                                <p class="text-3xl font-bold text-cyan-600">0</p>
                            </div>
                            <div class="bg-cyan-50 p-6 rounded-2xl">
                                <div class="text-4xl mb-3">👨‍⚕️</div>
                                <h3 class="font-bold text-xl"><?php echo e(__('messages.total_patients') ?? 'Total Patients'); ?></h3>
                                <p class="text-3xl font-bold text-cyan-600">0</p>
                            </div>
                            <div class="bg-cyan-50 p-6 rounded-2xl">
                                <div class="text-4xl mb-3">✅</div>
                                <h3 class="font-bold text-xl"><?php echo e(__('messages.completed') ?? 'Completed'); ?></h3>
                                <p class="text-3xl font-bold text-cyan-600">0</p>
                            </div>
                        </div>

                        <!-- Recent Appointments Table -->
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-slate-800"><?php echo e(__('messages.recent_appointments') ?? 'Recent Appointments'); ?></h3>
                            <div class="mt-4 bg-slate-50 rounded-xl p-4 text-slate-500 italic">
                                <?php echo e(__('messages.no_appointments_yet') ?? 'No appointments yet.'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\laragon\www\aarogya\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>