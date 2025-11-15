

<?php $__env->startSection('title', 'Gestión de Espacios'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 fade-in">
    <div>
        <h1 class="h2 mb-1">🏛️ Gestión de Espacios Físicos</h1>
        <p class="text-muted mb-0">Administra las áreas disponibles para eventos (piscina, cancha, salón, etc.)</p>
    </div>
    <a href="<?php echo e(route('admin.espacios.create')); ?>" class="btn btn-primary">
        ➕ Nuevo Espacio
    </a>
</div>

<div class="card fade-in">
    <div class="card-body">
        <?php if($espacios->isEmpty()): ?>
            <div class="text-center py-5">
                <h2 class="mb-3">🏛️</h2>
                <h4 class="text-muted">No hay espacios registrados</h4>
                <p class="text-muted">Comienza agregando tu primer espacio</p>
                <a href="<?php echo e(route('admin.espacios.create')); ?>" class="btn btn-primary mt-3">
                    Crear Espacio
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Capacidad</th>
                            <th>Precio/Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $espacios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $espacio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($espacio->id); ?></strong></td>
                                <td><?php echo e($espacio->nombre); ?></td>
                                <td><?php echo e(Str::limit($espacio->descripcion, 40) ?? 'Sin descripción'); ?></td>
                                <td><span class="badge bg-info">👥 <?php echo e($espacio->capacidad); ?> personas</span></td>
                                <td><strong>$<?php echo e(number_format($espacio->precio_hora, 2)); ?></strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($espacio->disponible ? 'success' : 'danger'); ?>">
                                        <?php echo e($espacio->disponible ? '✓ Disponible' : '✗ No disponible'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e(route('admin.espacios.edit', $espacio)); ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="Editar">
                                            ✏️ Editar
                                        </a>
                                        <form action="<?php echo e(route('admin.espacios.destroy', $espacio)); ?>" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este espacio?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">
                    Mostrando <?php echo e($espacios->firstItem()); ?> - <?php echo e($espacios->lastItem()); ?> de <?php echo e($espacios->total()); ?> espacios
                </p>
                <?php echo e($espacios->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campestre\resources\views/admin/espacios/index.blade.php ENDPATH**/ ?>