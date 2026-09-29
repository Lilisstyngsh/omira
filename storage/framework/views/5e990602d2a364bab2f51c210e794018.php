<?php $__env->startSection('title', 'History Order Repair Box'); ?>
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

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px 14px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            white-space: nowrap;
            border-bottom: 1px solid #e9eef4;
        }

        .orders-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #374151;
            vertical-align: middle;
        }

        .orders-table tbody tr {
            cursor: pointer;
            transition: .18s ease;
        }

        .orders-table tbody tr:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .orders-table tbody tr:active {
            background: #f1f5f9;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 700;
            text-align: center;
        }

        .order-number {
            font-weight: 800;
            color: #111827;
            white-space: nowrap;
        }

        .order-user {
            color: #475569;
            white-space: nowrap;
        }

        .order-line-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-type-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #f5f3ff;
            color: #6659df;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-qty {
            font-size: 13px !important;
            font-weight: 800;
            color: #172033 !important;
            text-align: center;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        /* =========================================================
               DETAIL REPAIR BOX
            ========================================================== */

        .repair-detail {
            min-width: 280px;
            max-width: 390px;
        }

        .repair-detail-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eef2f6;
        }

        .repair-detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .repair-detail-item:first-child {
            padding-top: 0;
        }

        .repair-detail-main {
            min-width: 0;
        }

        .repair-model {
            color: #172033;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.4;
        }

        .repair-product {
            margin-top: 2px;
            color: #64748b;
            font-size: 9px;
            line-height: 1.4;
        }

        .repair-ng {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: center;
            min-width: 27px;
            height: 23px;
            padding: 0 7px;
            border-radius: 7px;
            background: #f5f3ff;
            color: #6557dc;
            font-size: 9px;
            font-weight: 800;
        }

        .repair-more {
            display: inline-block;
            margin-top: 7px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 700;
        }

        .repair-empty {
            color: #94a3b8;
            font-size: 10px;
        }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 900px) {
            .orders-table-wrap {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 1250px;
            }
        }
    </style>

    <div class="page-card">

        <div class="page-head">
            <h2>
                History Order Repair Box
            </h2>
        </div>

        <div class="orders-table-wrap">

            <table class="orders-table">

                <thead>

                    <tr>

                        <th style="width:55px;">
                            No
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            No Order
                        </th>

                        <th>
                            User
                        </th>

                        <th>
                            Jenis Order
                        </th>

                        <th>
                            Line
                        </th>

                        <th>
                            Detail Repair Box
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="window.location='<?php echo e(route('omd.orders.history.show', $order)); ?>'"
                            title="Klik untuk melihat detail riwayat order">

                            <td class="order-no">
                                <?php echo e($orders->firstItem() + $index); ?>

                            </td>

                            <td>
                                <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : '-'); ?>

                            </td>

                            <td class="order-number">
                                <?php echo e($order->order_number); ?>

                            </td>

                            <td class="order-user">
                                <?php echo e($order->user?->name ?? '-'); ?>

                            </td>

                            <td>
                                <span class="order-type-badge">
                                    Repair Box
                                </span>
                            </td>

                            <td>
                                <span class="order-line-badge">
                                    <?php echo e($order->line?->name ?? ($order->area?->name ?? '-')); ?>

                                </span>
                            </td>

                            <td class="order-qty">
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

                            <td colspan="9" class="order-empty">

                                <div class="order-empty-text">
                                    Belum ada history Order Repair Box.
                                </div>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/history.blade.php ENDPATH**/ ?>