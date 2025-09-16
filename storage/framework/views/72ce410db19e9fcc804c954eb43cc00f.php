<?php if($paginator->hasPages()): ?>
    <style>
        .pagination-wrapper .pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .pagination-wrapper .pagination .page-link {
            color: #6c757d;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
            border-radius: 0.375rem;
        }

        .pagination-wrapper .pagination .page-link:hover {
            color: #0d6efd;
            background-color: #f8f9fa;
            border-color: #adb5bd;
            text-decoration: none;
        }

        .pagination-wrapper .pagination .page-item.active .page-link {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
        }

        .pagination-wrapper .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-wrapper .pagination .page-link:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            z-index: 3;
        }

        /* Адаптивность */
        @media (max-width: 576px) {
            .pagination-wrapper .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="pagination-wrapper">
        <!-- Информация о результатах -->
        <div class="pagination-info text-center mb-3">
            <small class="text-muted">
                Показано <?php echo e($paginator->firstItem()); ?> - <?php echo e($paginator->lastItem()); ?> 
                из <?php echo e($paginator->total()); ?> 
                <?php echo e(trans_choice('статьи|статей|статей', $paginator->total())); ?>

            </small>
        </div>

        <nav aria-label="Пагинация">
            <ul class="pagination justify-content-center">
                
                <?php if($paginator->onFirstPage()): ?>
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" title="Предыдущая страница">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline ms-1">Назад</span>
                        </span>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" title="Предыдущая страница">
                            <i class="bi bi-chevron-left"></i>
                            <span class="d-none d-sm-inline ms-1">Назад</span>
                        </a>
                    </li>
                <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active" aria-current="page">
                                <span class="page-link"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" title="Следующая страница">
                        <span class="d-none d-sm-inline me-1">Вперёд</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" title="Следующая страница">
                        <span class="d-none d-sm-inline me-1">Вперёд</span>
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    </div>

   
<?php endif; ?>
<?php /**PATH C:\OSPanel\domains\link\resources\views/pagination/custom.blade.php ENDPATH**/ ?>