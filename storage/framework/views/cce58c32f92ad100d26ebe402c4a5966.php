<?php $__env->startSection('title', 'TPS Tool OMD'); ?>
<?php $__env->startSection('header', 'TPS Tool OMD'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-head">
        <div>
            <h2>Order TPS Tool</h2>

            <div class="muted">
                Monitoring workflow TPS Tool.
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Tool</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php echo e($order->order_number); ?>

                        </td>

                        <td>
                            <?php echo e($order->user->name); ?>

                        </td>

                        <td>
                            <?php echo e($order->tool_name); ?>

                        </td>

                        <td>
                            <?php echo e($order->reported_date->format('d/m/Y')); ?>

                        </td>

                        <td>
                            <span class="badge badge-<?php echo e($order->status); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>

                        <td>
                            <a
                                class="btn btn-secondary"
                                href="<?php echo e(route('omd.tps.show', $order)); ?>"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="empty">
                            Belum ada order TPS Tool.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($orders->links()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/tps/index.blade.php ENDPATH**/ ?>