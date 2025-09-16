
<div class="modal fade" id="imageEditorModal" tabindex="-1" aria-labelledby="imageEditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageEditorModalLabel">Редактирование изображения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body p-0">
                <div class="image-editor-container">
                    <!-- Область для загрузки файла -->
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="file-upload-content">
                            <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">Выберите изображение</h5>
                            <p class="text-muted mb-3">Поддерживаются форматы: JPG, PNG, WEBP</p>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('imageInput').click()">
                                <i class="bi bi-folder2-open me-2"></i>Выбрать файл
                            </button>
                            <input type="file" id="imageInput" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <!-- Область редактирования -->
                    <div class="image-editor-workspace" id="imageEditorWorkspace" style="display: none;">
                        <div class="canvas-container">
                            <canvas id="imageCanvas"></canvas>
                            <div class="crop-overlay" id="cropOverlay">
                                <div class="crop-area" id="cropArea">
                                    <div class="crop-handle crop-handle-nw"></div>
                                    <div class="crop-handle crop-handle-ne"></div>
                                    <div class="crop-handle crop-handle-sw"></div>
                                    <div class="crop-handle crop-handle-se"></div>
                                    <div class="crop-center" id="cropCenter"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex " style="justify-content: space-between;
    gap: 10px;
    display: flex
;
    padding: 5px;
    /* height: 100%; */
    flex-direction: row;
    flex-wrap: nowrap;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Отмена
                </button>
                <button type="button" class="btn btn-success" id="saveImageBtn" style="display: none;">
                    <i class="bi bi-check-lg me-2"></i>Сохранить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Скрытая форма для отправки обрезанного изображения -->
<form id="croppedImageForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="cropped_image" id="croppedImageData">
    <input type="hidden" name="image_type" id="imageType">
</form><?php /**PATH C:\OSPanel\domains\link\resources\views/components/image-editor.blade.php ENDPATH**/ ?>