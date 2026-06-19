<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - <?php echo e(__('messages.auth_login_title')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; } .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }</style>
</head>
<body class="bg-slate-50">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="hero-bg overflow-hidden">
        <div class="max-w-md mx-auto px-6 py-16">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-slate-100">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-3">🔐</div>
                    <h2 class="text-3xl font-bold text-slate-900"><?php echo e(__('messages.auth_login_title')); ?></h2>
                </div>

                
                <?php if($errors->any()): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                
                <?php if(session('error')): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-slate-700 font-medium mb-1"><?php echo e(__('messages.auth_email')); ?></label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required autofocus>
                        </div>
                        <div>
                            <label class="block text-slate-700 font-medium mb-1"><?php echo e(__('messages.auth_password')); ?></label>
                            <input type="password" name="password" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 text-white py-3 rounded-xl hover:bg-cyan-700 transition font-semibold">
                            <?php echo e(__('messages.auth_login_button')); ?>

                        </button>
                    </div>
                </form>
                <div class="mt-6 text-center">
                    <a href="<?php echo e(route('register')); ?>" class="text-cyan-600 hover:underline">
                        <?php echo e(__('messages.auth_no_account')); ?> <?php echo e(__('messages.auth_register')); ?>

                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH C:\laragon\www\aarogya\resources\views/auth/login.blade.php ENDPATH**/ ?>