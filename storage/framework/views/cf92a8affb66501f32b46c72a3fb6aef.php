<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - <?php echo e(__('messages.doctor_dashboard')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex gap-6">
            <?php echo $__env->make('partials.doctor-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="flex-1 min-w-0">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\laragon\www\aarogya\resources\views/layouts/doctor.blade.php ENDPATH**/ ?>