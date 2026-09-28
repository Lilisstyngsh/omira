<?php $__env->startSection('title', 'Dashboard User'); ?>

<?php $__env->startSection('header', 'Dashboard User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <h2>Selamat datang, <?php echo e(auth()->user()->name); ?></h2>

            <div class="muted">
                Pantau pengajuan order repair yang Anda buat.
            </div>
        </div>

        <a class="btn btn-primary" href="<?php echo e(route('user.orders.create')); ?>">
            + Buat Order Repair
        </a>
    </div>

    <div class="cards">
        <div class="card">
            <div class="stat-label">Total Order</div>
            <div class="stat-value"><?php echo e($total); ?></div>
        </div>

        <div class="card">
            <div class="stat-label">Menunggu Verifikasi OMD</div>
            <div class="stat-value"><?php echo e($submitted); ?></div>
        </div>

        <div class="card">
            <div class="stat-label">Sedang Repair</div>
            <div class="stat-value"><?php echo e($inRepair); ?></div>
        </div>

        <div class="card">
            <div class="stat-label">Selesai</div>
            <div class="stat-value"><?php echo e($completed); ?></div>
        </div>
    </div>

    <div class="card">
        <div class="page-head">
            <div>
                <h3>Order Terbaru</h3>

                <div class="muted">
                    Enam order terakhir.
                </div>
            </div>

            <a class="btn btn-secondary" href="<?php echo e(route('user.orders.index')); ?>">
                Lihat Semua
            </a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Line</th>
                        <th>Produk</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('user.orders.show', $order)); ?>">
                                    <b><?php echo e($order->order_number); ?></b>
                                </a>
                            </td>

                            <td>
                                <?php echo e($order->created_at->format('d/m/Y H:i')); ?>

                            </td>

                            <td>
                                <?php echo e($order->line?->name ?? '-'); ?>

                            </td>

                            <td>
                                <?php if($order->items->isNotEmpty()): ?>
                                    <?php echo e($order->items->first()->product?->name ?? '-'); ?>


                                    <?php if($order->items->count() > 1): ?>
                                        <span class="muted">
                                            +<?php echo e($order->items->count() - 1); ?> lainnya
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo e($order->product?->name ?? '-'); ?>

                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="badge badge-<?php echo e($order->status); ?>">
                                    <?php echo e($order->status_label); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="empty">
                                Belum ada order.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/dashboard.blade.php ENDPATH**/ ?>