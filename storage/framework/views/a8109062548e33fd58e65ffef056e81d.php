<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    
    <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-8 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white"><?php echo e(__('messages.profile')); ?></h2>
                <p class="text-cyan-100 text-sm">Your professional information</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs font-medium">
                    <?php echo e($doctor->verification_status ?? 'pending'); ?>

                </span>
                <a href="<?php echo e(route('doctor.profile.edit')); ?>" 
                   class="bg-white text-cyan-600 px-4 py-2 rounded-xl hover:bg-cyan-50 transition text-sm font-medium">
                    ✏️ <?php echo e(__('messages.edit_profile')); ?>

                </a>
            </div>
        </div>
    </div>

    <div class="p-8">
        
        <?php if(session('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        
        <div class="flex items-center gap-6 pb-6 border-b border-slate-200">
            <div>
                <?php if($doctor->profile_photo): ?>
                    <img src="<?php echo e(asset('storage/' . $doctor->profile_photo)); ?>" 
                         alt="Profile Photo" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-cyan-100">
                <?php else: ?>
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center text-white text-3xl font-bold">
                        <?php echo e(substr($user->name, 0, 1)); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div>
                <p class="font-medium text-slate-800 text-xl"><?php echo e($user->name); ?></p>
                <p class="text-sm text-slate-500"><?php echo e(__('messages.doctor')); ?></p>
                <p class="text-xs text-slate-400 mt-1"><?php echo e($doctor->specialization ?? 'Not specified'); ?></p>
            </div>
        </div>

        
        <div class="grid md:grid-cols-2 gap-6 mt-6">
            
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">👤</span> Personal Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.auth_name')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($user->name); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.specialization')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->specialization ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.qualification')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->qualification ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.nmc_registration')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->nmc_registration ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">💼</span> Professional Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.experience')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->experience ?? '0'); ?> <?php echo e(__('messages.experience_years')); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.consultation_fee')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e(__('messages.currency')); ?> <?php echo e(number_format($doctor->consultation_fee ?? 0, 2)); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">🏥</span> Clinic Information
                </h3>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.clinic_name')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->clinic_name ?? '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500"><?php echo e(__('messages.clinic_address')); ?></p>
                        <p class="font-medium text-slate-800"><?php echo e($doctor->clinic_address ?? '-'); ?></p>
                    </div>
                </div>
            </div>

            
            <div class="col-span-2">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-cyan-600">📝</span> About
                </h3>
                <div class="bg-slate-50 p-4 rounded-2xl">
                    <p class="text-slate-700"><?php echo e($doctor->bio ?? 'No bio provided yet.'); ?></p>
                </div>
            </div>
        </div>

        
        <div class="mt-8 pt-4 border-t border-slate-200 text-right">
            <a href="<?php echo e(route('doctor.profile.edit')); ?>" 
               class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-8 py-3 rounded-xl transition inline-flex items-center gap-2">
                ✏️ <?php echo e(__('messages.edit_profile')); ?>

            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\aarogya\resources\views/doctor/profile.blade.php ENDPATH**/ ?>