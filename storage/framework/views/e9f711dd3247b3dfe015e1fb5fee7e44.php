<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-3xl shadow-xl p-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800"><?php echo e(__('messages.qr_code')); ?></h2>
        <span class="text-sm text-slate-500">Dr. <?php echo e($doctor->name); ?></span>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="mt-8 text-center">
        <!-- QR Code Display -->
        <div class="inline-block bg-white p-6 rounded-3xl shadow-lg border border-slate-200">
            <?php echo QrCode::size(300)->generate(route('doctor.show', $doctor->id)); ?>

        </div>

        <p class="mt-4 text-slate-600">
            <?php echo e(__('messages.qr_scan_message')); ?>

        </p>
        <p class="text-sm text-slate-400">
            <?php echo e(route('doctor.show', $doctor->id)); ?>

        </p>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="<?php echo e(route('doctor.qr.download')); ?>" 
               class="bg-cyan-600 text-white px-6 py-3 rounded-xl hover:bg-cyan-700 transition flex items-center gap-2">
                ⬇️ <?php echo e(__('messages.download_qr')); ?>

            </a>
            <a href="<?php echo e(route('doctor.qr.print')); ?>" target="_blank"
               class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition flex items-center gap-2">
                🖨️ <?php echo e(__('messages.print_qr')); ?>

            </a>
            <a href="<?php echo e(route('doctor.qr.share')); ?>" 
               class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition flex items-center gap-2">
                📤 <?php echo e(__('messages.share_qr')); ?>

            </a>
        </div>
    </div>

    <!-- QR Code Info -->
    <div class="mt-8 p-6 bg-slate-50 rounded-2xl">
        <h3 class="font-semibold text-slate-800"><?php echo e(__('messages.qr_info_title')); ?></h3>
        <ul class="mt-2 space-y-2 text-sm text-slate-600">
            <li>✅ <?php echo e(__('messages.qr_info_1')); ?></li>
            <li>✅ <?php echo e(__('messages.qr_info_2')); ?></li>
            <li>✅ <?php echo e(__('messages.qr_info_3')); ?></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.doctor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\aarogya\resources\views/doctor/qr-code.blade.php ENDPATH**/ ?>