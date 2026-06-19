<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between flex-wrap">
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">❤</div>
            <div>
                <h1 class="font-bold text-xl text-slate-900">AAROGYA</h1>
                <p class="text-xs text-slate-500"><?php echo e(__('messages.footer_tagline')); ?></p>
            </div>
        </div>

        
        <div class="hidden md:flex gap-8 font-medium">
            <a href="<?php echo e(url('/')); ?>" class="hover:text-cyan-600 <?php echo e(request()->is('/') ? 'text-cyan-600 border-b-2 border-cyan-600' : ''); ?>">
                <?php echo e(__('messages.home')); ?>

            </a>
            <a href="<?php echo e(route('symptom.checker')); ?>" class="hover:text-cyan-600 <?php echo e(request()->routeIs('symptom.checker') ? 'text-cyan-600 border-b-2 border-cyan-600' : ''); ?>">
                <?php echo e(__('messages.symptom_checker')); ?>

            </a>
            <a href="<?php echo e(route('doctors')); ?>" class="hover:text-cyan-600 <?php echo e(request()->routeIs('doctors') ? 'text-cyan-600 border-b-2 border-cyan-600' : ''); ?>">
                <?php echo e(__('messages.doctors')); ?>

            </a>
            <a href="<?php echo e(route('services')); ?>" class="hover:text-cyan-600 <?php echo e(request()->routeIs('services') ? 'text-cyan-600 border-b-2 border-cyan-600' : ''); ?>">
                <?php echo e(__('messages.services')); ?>

            </a>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-cyan-600 <?php echo e(request()->routeIs('dashboard') ? 'text-cyan-600 border-b-2 border-cyan-600' : ''); ?>">
                    <?php echo e(__('messages.dashboard')); ?>

                </a>
            <?php endif; ?>
        </div>

        
        <div class="flex items-center gap-4">
            
            <div class="flex items-center gap-2 text-sm">
                <form method="POST" action="<?php echo e(route('language.switch')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="locale" value="ne">
                    <button type="submit" 
                        class="px-2 py-1 rounded hover:bg-gray-100 transition <?php echo e(app()->getLocale() == 'ne' ? 'font-bold text-cyan-600' : 'text-slate-600'); ?>">
                        🇳🇵 <?php echo e(__('messages.nepali')); ?>

                    </button>
                </form>
                <span class="text-gray-300">|</span>
                <form method="POST" action="<?php echo e(route('language.switch')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" 
                        class="px-2 py-1 rounded hover:bg-gray-100 transition <?php echo e(app()->getLocale() == 'en' ? 'font-bold text-cyan-600' : 'text-slate-600'); ?>">
                        🇬🇧 <?php echo e(__('messages.english')); ?>

                    </button>
                </form>
            </div>

            
            <?php if(auth()->guard()->check()): ?>
                <span class="text-slate-700 text-sm hidden md:inline"><?php echo e(Auth::user()->name); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                        <?php echo e(__('messages.logout')); ?>

                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="bg-cyan-600 text-white px-5 py-3 rounded-xl hover:bg-cyan-700 transition">
                    <?php echo e(__('messages.start')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>
</nav><?php /**PATH C:\laragon\www\aarogya\resources\views/partials/navbar.blade.php ENDPATH**/ ?>