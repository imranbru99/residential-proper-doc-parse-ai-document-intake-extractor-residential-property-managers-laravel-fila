<?php $__env->startSection('title', 'Features & Modules — ResidentialProperDocParse'); ?>

<?php $__env->startSection('content'); ?>
<section class="wrap" style="padding-top:3rem">
    <div class="section-head">
        <div class="eyebrow">Complete feature catalog</div>
        <h2>Everything ResidentialProperDocParse can run</h2>
        <p class="lead">Toggle modules from the Filament admin. Core document operations remain available for the MVP workflow.</p>
    </div>
    <div class="grid grid-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="module">
                <span class="badge <?php echo e(($module['enabled'] ?? false) ? 'on' : 'off'); ?>"><?php echo e(($module['enabled'] ?? false) ? 'ENABLED' : 'DISABLED'); ?></span>
                <h3><?php echo e($module['name']); ?></h3>
                <p><?php echo e($module['description']); ?></p>
                <p style="margin-top:0.75rem;color:var(--sand);font-size:0.85rem">Key: <?php echo e($key); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($module['core'])): ?> · CORE <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/public/features.blade.php ENDPATH**/ ?>