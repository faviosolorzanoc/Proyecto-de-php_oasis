

<?php $__env->startSection('title', 'Gestión de Pedidos'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h1 class="h2">Gestión de Pedidos</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Mesa</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($pedido->id); ?></td>
                            <td><?php echo e($pedido->user->name); ?></td>
                            <td><?php echo e($pedido->mesa ? 'Mesa ' . $pedido->mesa->numero : 'Para llevar'); ?></td>
                            <td>$<?php echo e(number_format($pedido->total, 2)); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($pedido->estado == 'pendiente' ? 'warning' : 
                                    ($pedido->estado == 'en_preparacion' ? 'info' : 
                                    ($pedido->estado == 'listo' ? 'primary' : 
                                    ($pedido->estado == 'entregado' ? 'success' : 'danger')))); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $pedido->estado))); ?>

                                </span>
                            </td>
                            <td><?php echo e($pedido->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.pedidos.show', $pedido)); ?>" class="btn btn-sm btn-info">Ver Detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay pedidos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <?php echo e($pedidos->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\campestre\resources\views/admin/pedidos/index.blade.php ENDPATH**/ ?>