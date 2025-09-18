
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'src' => '',
    'alt' => '',
    'width' => '300',
    'height' => '200',
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => null,
    'class' => '',
    'fallback' => '/hero.png'
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'src' => '',
    'alt' => '',
    'width' => '300',
    'height' => '200',
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => null,
    'class' => '',
    'fallback' => '/hero.png'
]); ?>
<?php foreach (array_filter(([
    'src' => '',
    'alt' => '',
    'width' => '300',
    'height' => '200',
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => null,
    'class' => '',
    'fallback' => '/hero.png'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<img src="<?php echo e($src ?: $fallback); ?>" 
     alt="<?php echo e($alt); ?>" 
     loading="<?php echo e($loading); ?>"
     width="<?php echo e($width); ?>"
     height="<?php echo e($height); ?>"
     decoding="<?php echo e($decoding); ?>"
     <?php if($fetchpriority): ?> fetchpriority="<?php echo e($fetchpriority); ?>" <?php endif; ?>
     <?php if($class): ?> class="<?php echo e($class); ?>" <?php endif; ?>
     <?php echo e($attributes); ?>><?php /**PATH C:\OSPanel\domains\link\resources\views\components\optimized-image.blade.php ENDPATH**/ ?>