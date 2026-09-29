<?php $__env->startSection('title', 'Order Repair Box'); ?>
<?php $__env->startSection('header', 'Order Repair Box'); ?>

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

        .btn-create {
            text-decoration: none;
            background: #4f46e5;
            color: #fff;
            padding: 10px 15px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
            transition: .18s ease;
        }

        .btn-create:hover {
            background: #4338ca;
            color: #fff;
        }

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            min-width: 1180px;
            border-collapse: collapse;
        }

        .orders-table th {
            padding: 12px 14px;
            text-align: left;
            background: #f9fafb;
            border-bottom: 1px solid #e9eef4;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            white-space: nowrap;
        }

        .orders-table thead tr:first-child th {
            background: #f6f7fb;
            text-align: center;
        }

        .orders-table thead tr:first-child th[rowspan="2"] {
            text-align: left;
            vertical-align: middle;
        }

        .orders-table thead tr:nth-child(2) th {
            text-align: center;
            font-size: 9px;
            padding: 11px 10px;
        }

        .orders-table td {
            padding: 14px;
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
            text-align: center;
            color: #94a3b8;
            font-weight: 700;
        }

        .order-number {
            font-weight: 800;
            color: #111827;
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

        .process-cell {
            width: 115px;
            text-align: center !important;
            padding: 12px 8px !important;
        }

        .process-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 900;
            line-height: 1;
        }

        .process-done {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: inset 0 0 0 1px #bbf7d0;
        }

        .process-progress {
            background: #fef3c7;
            color: #d97706;
            box-shadow: inset 0 0 0 1px #fde68a;
        }

        .process-pending {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: inset 0 0 0 1px #fecaca;
        }

        .order-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #fde68a;
            border-radius: 14px;
            background: #fffbeb;
        }

        .order-alert-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fef3c7;
            color: #d97706;
            font-size: 16px;
            font-weight: 800;
        }

        .order-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #92400e;
            font-size: 12px;
            font-weight: 800;
        }

        .order-alert span {
            color: #a16207;
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

            .page-head {
                align-items: flex-start;
                gap: 12px;
            }
        }

        .success-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            background: #f0fdf4;
        }

        .success-alert-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #dcfce7;
            color: #16a34a;
            font-size: 16px;
            font-weight: 800;
        }

        .success-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .success-alert span {
            color: #4d7a5c;
            font-size: 10px;
        }
    </style>

    <?php
        $completedOrders = $orders->where('status', 'completed')->count();
    ?>

    <?php if($completedOrders > 0): ?>
        <div class="order-alert">
            <div class="order-alert-icon">
                !
            </div>

            <div>
                <strong>
                    Order Repair Box Selesai
                </strong>

                <span>
                    <?php echo e($completedOrders); ?> order sudah selesai diperbaiki dan menunggu konfirmasi User.
                </span>
            </div>
        </div>
    <?php endif; ?>

    <div class="page-card">

        <div class="page-head">
            <h2>Daftar Order Repair Box</h2>

            <a href="<?php echo e(route('user.orders.create')); ?>" class="btn-create">
                + Buat Order
            </a>
        </div>

        <div class="orders-table-wrap">

            <table class="orders-table">

                <thead>

                    <tr>

                        <th rowspan="2" style="width:55px;">
                            No
                        </th>

                        <th rowspan="2">
                            Tanggal
                        </th>

                        <th rowspan="2">
                            No Order
                        </th>

                        <th rowspan="2">
                            Jenis Order
                        </th>

                        <th rowspan="2">
                            Line
                        </th>

                        <th rowspan="2">
                            Qty
                        </th>

                        <th colspan="4" style="text-align:center;">
                            Status
                        </th>

                    </tr>

                    <tr>

                        <th class="process-cell">
                            User Submit
                        </th>

                        <th class="process-cell">
                            Verified OMD
                        </th>

                        <th class="process-cell">
                            Repair OMD
                        </th>

                        <th class="process-cell">
                            Serah Terima
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="window.location='<?php echo e(route('user.orders.show', $order)); ?>'"
                            title="Klik untuk melihat detail order">

                            <td class="order-no">
                                <?php echo e($orders->firstItem() + $index); ?>

                            </td>

                            <td>
                                <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : '-'); ?>

                            </td>

                            <td class="order-number">
                                <?php echo e($order->order_number); ?>

                            </td>

                            <td>
                                <span class="order-type-badge">
                                    Repair Box
                                </span>
                            </td>

                            <td>
                                <span class="order-line-badge">
                                    <?php echo e($order->line?->name ?? '-'); ?>

                                </span>
                            </td>

                            <td class="order-qty">
                                <?php echo e($order->quantity); ?>

                            </td>

                            
                            <td class="process-cell">

                                <span class="process-icon process-done" title="User sudah submit order">
                                    ✓
                                </span>

                            </td>

                            
                            <td class="process-cell">

                                <?php if(in_array($order->status, ['in_repair', 'completed', 'confirmed'])): ?>
                                    <span class="process-icon process-done" title="Order sudah diverifikasi OMD">
                                        ✓
                                    </span>
                                <?php else: ?>
                                    <span class="process-icon process-pending" title="Belum diverifikasi OMD">
                                        ✕
                                    </span>
                                <?php endif; ?>

                            </td>

                            
                            <td class="process-cell">

                                <?php if(in_array($order->status, ['completed', 'confirmed'])): ?>
                                    <span class="process-icon process-done" title="Repair OMD sudah selesai">
                                        ✓
                                    </span>
                                <?php elseif($order->status === 'in_repair'): ?>
                                    <span class="process-icon process-progress" title="Repair OMD sedang diproses">
                                        △
                                    </span>
                                <?php else: ?>
                                    <span class="process-icon process-pending" title="Repair OMD belum dimulai">
                                        ✕
                                    </span>
                                <?php endif; ?>

                            </td>

                            
                            <td class="process-cell">

                                <?php if($order->status === 'confirmed'): ?>
                                    <span class="process-icon process-done" title="Serah terima sudah selesai">
                                        ✓
                                    </span>
                                <?php elseif($order->status === 'completed'): ?>
                                    <span class="process-icon process-progress"
                                        title="Menunggu serah terima / konfirmasi User">
                                        △
                                    </span>
                                <?php else: ?>
                                    <span class="process-icon process-pending" title="Belum masuk tahap serah terima">
                                        ✕
                                    </span>
                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="10" class="order-empty">
                                <div class="order-empty-text">
                                    Belum ada Order Repair Box
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/index.blade.php ENDPATH**/ ?>