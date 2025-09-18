

<?php $__env->startSection('title', 'Управление профилем - ' . config('app.name')); ?>
<?php $__env->startSection('description', 'Редактирование персональной информации и настроек профиля'); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
          
            <div class="card-body">
                <!-- Bootstrap Nav Tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo e((!isset($tab) || $tab === 'basic') ? 'active' : ''); ?>" 
                                id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" 
                                type="button" role="tab" aria-controls="basic" aria-selected="<?php echo e((!isset($tab) || $tab === 'basic') ? 'true' : 'false'); ?>">
                            <i class="bi bi-person"></i>
                            <span class="d-none d-md-inline ms-2">Основная информация</span>
                            <span class="d-md-none">Инфо</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo e((isset($tab) && $tab === 'social') ? 'active' : ''); ?>" 
                                id="social-tab" data-bs-toggle="tab" data-bs-target="#social" 
                                type="button" role="tab" aria-controls="social" aria-selected="<?php echo e((isset($tab) && $tab === 'social') ? 'true' : 'false'); ?>">
                            <i class="bi bi-share"></i>
                            <span class="d-none d-md-inline ms-2">Социальные сети</span>
                            <span class="d-md-none">Соцсети</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo e((isset($tab) && $tab === 'security') ? 'active' : ''); ?>" 
                                id="security-tab" data-bs-toggle="tab" data-bs-target="#security" 
                                type="button" role="tab" aria-controls="security" aria-selected="<?php echo e((isset($tab) && $tab === 'security') ? 'true' : 'false'); ?>">
                            <i class="bi bi-shield-lock"></i>
                            <span class="d-none d-md-inline ms-2">Безопасность</span>
                            <span class="d-md-none">Пароль</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo e((isset($tab) && $tab === 'sections') ? 'active' : ''); ?>" 
                                id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" 
                                type="button" role="tab" aria-controls="sections" aria-selected="<?php echo e((isset($tab) && $tab === 'sections') ? 'true' : 'false'); ?>">
                            <i class="bi bi-layout-text-window"></i>
                            <span class="d-none d-md-inline ms-2">Управление разделами</span>
                            <span class="d-md-none">Разделы</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabContent">
                    <!-- Основная информация -->
                    <div class="tab-pane fade <?php echo e((!isset($tab) || $tab === 'basic') ? 'show active' : ''); ?>" 
                         id="basic" role="tabpanel" aria-labelledby="basic-tab">
                        <?php echo $__env->make('admin.profile.basic', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>



                    <!-- Социальные сети -->
                    <div class="tab-pane fade <?php echo e((isset($tab) && $tab === 'social') ? 'show active' : ''); ?>" 
                         id="social" role="tabpanel" aria-labelledby="social-tab">
                        <?php echo $__env->make('admin.profile.social', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <!-- Безопасность -->
                    <div class="tab-pane fade <?php echo e((isset($tab) && $tab === 'security') ? 'show active' : ''); ?>" 
                         id="security" role="tabpanel" aria-labelledby="security-tab">
                        <?php echo $__env->make('admin.profile.security', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <!-- Управление разделами -->
                    <div class="tab-pane fade <?php echo e((isset($tab) && $tab === 'sections') ? 'show active' : ''); ?>" 
                         id="sections" role="tabpanel" aria-labelledby="sections-tab">
                        <?php echo $__env->make('admin.profile.sections', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('admin.profile.social-modals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('admin.profile.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\link\resources\views\admin\profile.blade.php ENDPATH**/ ?>