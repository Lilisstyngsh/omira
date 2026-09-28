

<?php $__env->startSection('header', 'History Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .page-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
    }

    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-head h2 {
        margin: 0;
        font-size: 17px;
        color: #111827;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        background: #f9fafb;
        text-align: left;
        padding: 12px;
        font-size: 11px;
        color: #6b7280;
    }

    .orders-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
        color: #374151;
    }

    .orders-table tbody tr {
        cursor: pointer;
        transition: .18s ease;
    }

    .orders-table tbody tr:hover {
        background: #f8fafc;
    }

    .orders-table tbody tr:active {
        background: #f1f5f9;
    }

    .order-number {
        font-weight: 700;
        color: #111827;
    }

    .status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-confirmed {
        background: #dcfce7;
        color: #166534;
    }

    @media (max-width: 900px) {
        .table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            min-width: 900px;
        }
    }
</style>

<div class="page-card">

    <div class="page-head">
        <h2>History Order Repair Box</h2>
    </div>

    <div class="table-wrap">

        <table class="orders-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Order</th>
                    <th>Jenis Order</th>
                    <th>Line</th>
                    <th>Qty</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr
                        onclick="window.location='<?php echo e(route('user.orders.show', $order)); ?>'"
                        title="Klik untuk melihat detail order">

                        <td>
                            <?php echo e($orders->firstItem() + $index); ?>

                        </td>

                        <td>
                            <?php echo e($order->created_at->format('d-m-Y H:i')); ?>

                        </td>

                        <td class="order-number">
                            <?php echo e($order->order_number); ?>

                        </td>

                        <td>
                            Repair Box
                        </td>

                        <td>
                            <?php echo e($order->line?->name ?? '-'); ?>

                        </td>

                        <td>
                            <?php echo e($order->quantity); ?>

                        </td>

                        <td>
                            <span class="status status-confirmed">
                                Selesai
                            </span>
                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#9ca3af;">
                            Belum ada history Order Repair Box.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <div style="margin-top:20px;">
        <?php echo e($orders->links()); ?>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/history.blade.php ENDPATH**/ ?>