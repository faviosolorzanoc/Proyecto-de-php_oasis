

<?php $__env->startSection('title', 'Servicios'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="mb-4" style="color: var(--color-primary);">Nuestros Servicios</h1>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $servicios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow">
                <?php if($servicio->imagen): ?>
                    <img src="<?php echo e($servicio->imagen); ?>" class="card-img-top" alt="<?php echo e($servicio->nombre); ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-white fs-1">🎯</span>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--color-primary);"><?php echo e($servicio->nombre); ?></h5>
                    <p class="card-text"><?php echo e($servicio->descripcion ?? 'Sin descripción'); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0" style="color: var(--color-secondary);">$<?php echo e(number_format($servicio->precio, 2)); ?></span>
                        <span class="badge bg-success">Disponible</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info">No hay servicios disponibles en este momento.</div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campestre\resources\views/cliente/servicios.blade.php ENDPATH**/ ?>