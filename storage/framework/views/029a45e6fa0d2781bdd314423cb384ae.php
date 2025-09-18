
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['section' => null, 'defaultTitle' => '', 'defaultSubtitle' => '', 'headingLevel' => 'h2']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['section' => null, 'defaultTitle' => '', 'defaultSubtitle' => '', 'headingLevel' => 'h2']); ?>
<?php foreach (array_filter((['section' => null, 'defaultTitle' => '', 'defaultSubtitle' => '', 'headingLevel' => 'h2']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php if((isset($section) && (!empty(trim($section->title ?? '')) || !empty(trim($section->subtitle ?? '')))) || (!isset($section) && ($defaultTitle || $defaultSubtitle))): ?>
    <header class="section-header mb-4">
        <?php if(isset($section)): ?>
            <?php if(!empty(trim($section->title ?? ''))): ?>
                <<?php echo e($headingLevel); ?>><?php echo e($section->title); ?></<?php echo e($headingLevel); ?>>
            <?php elseif($defaultTitle): ?>
                <<?php echo e($headingLevel); ?>><?php echo e($defaultTitle); ?></<?php echo e($headingLevel); ?>>
            <?php endif; ?>
            
            <?php if(!empty(trim($section->subtitle ?? ''))): ?>
                <p class="text-muted"><?php echo e($section->subtitle); ?></p>
            <?php elseif($defaultSubtitle): ?>
                <p class="text-muted"><?php echo e($defaultSubtitle); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <?php if($defaultTitle): ?>
                <<?php echo e($headingLevel); ?>><?php echo e($defaultTitle); ?></<?php echo e($headingLevel); ?>>
            <?php endif; ?>
            <?php if($defaultSubtitle): ?>
                <p class="text-muted"><?php echo e($defaultSubtitle); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </header>
<?php endif; ?><?php /**PATH C:\OSPanel\domains\link\resources\views\components\section-header.blade.php ENDPATH**/ ?>