<?php
    $user = auth()->user();
    $initial = strtoupper(substr($user->name, 0, 1));
    $roleName = match($user->role) {
        'doctor' => __('messages.doctor'),
        'clinic' => __('messages.clinic'),
        'admin' => __('messages.admin'),
        default => __('messages.patient'),
    };
?>

<div class="bg-white rounded-3xl shadow-xl p-6 sticky top-20">
    
    <div class="flex items-center gap-3 pb-6 border-b border-slate-200">
        <div class="w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">
            <?php echo e($initial); ?>

        </div>
        <div>
            <p class="font-bold text-slate-800"><?php echo e($user->name); ?></p>
            <p class="text-xs text-slate-500"><?php echo e($roleName); ?></p>
        </div>
    </div>

    
    <nav class="mt-6 space-y-2">
        
        <a href="<?php echo e(route('doctor.dashboard')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.dashboard') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>📊</span> <?php echo e(__('messages.dashboard')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.appointments')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.appointments') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>📋</span> <?php echo e(__('messages.appointments')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.patients')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.patients') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>👨‍⚕️</span> <?php echo e(__('messages.patients')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.schedule')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.schedule') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>📅</span> <?php echo e(__('messages.schedule')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.qr.index')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.qr.*') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>📱</span> <?php echo e(__('messages.qr_code')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.profile')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.profile') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>👤</span> <?php echo e(__('messages.profile')); ?>

        </a>

        
        <a href="<?php echo e(route('doctor.profile.edit')); ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-cyan-50 transition <?php echo e(request()->routeIs('doctor.profile.edit') ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-600'); ?>">
            <span>⚙️</span> <?php echo e(__('messages.settings')); ?>

        </a>
    </nav>
</div><?php /**PATH C:\laragon\www\aarogya\resources\views/partials/doctor-sidebar.blade.php ENDPATH**/ ?>