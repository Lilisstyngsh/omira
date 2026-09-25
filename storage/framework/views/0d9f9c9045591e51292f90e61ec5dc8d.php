<?php $__env->startSection('title', 'Order Repair'); ?>
<?php $__env->startSection('header', 'Order Repair'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-head">
        <div>
            <h2>Order Masuk</h2>

            <div class="muted">
                Kelola order repair yang dikirim user.
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Area</th>
                    <th>Model</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <b><?php echo e($order->order_number); ?></b>
                        </td>

                        <td>
                            <?php echo e($order->user->name); ?>

                        </td>

                        <td>
                            <?php echo e($order->area->category); ?> - <?php echo e($order->area->name); ?>

                        </td>

                        <td>
                            <?php echo e($order->masterModel?->model ?? $order->model ?? '-'); ?>

                        </td>

                        <td>
                            <?php echo e($order->product->name); ?>

                        </td>

                        <td>
                            <?php echo e($order->quantity); ?>

                        </td>

                        <td>
                            <span class="badge badge-<?php echo e($order->status); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>

                        <td>
                            <a
                                class="btn btn-secondary"
                                href="<?php echo e(route('omd.orders.show', $order)); ?>"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="empty">
                            Belum ada order.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($orders->links()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/index.blade.php ENDPATH**/ ?>