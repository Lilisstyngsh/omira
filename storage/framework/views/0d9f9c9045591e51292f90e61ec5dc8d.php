<?php $__env->startSection('title', 'Order Repair Box'); ?>
<?php $__env->startSection('header', 'Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .order-page-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .order-page-title {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .order-page-desc {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .order-table-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .order-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 20px 22px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(135deg, #f8f7ff 0%, #fff 75%);
        }

        .order-table-header h3 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .order-table-header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .order-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            padding: 0 12px;
            border-radius: 10px;
            background: #f1efff;
            color: #6557dc;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-table-wrap {
            overflow-x: auto;
        }

        .order-table {
            width: 100%;
            min-width: 1180px;
            border-collapse: collapse;
        }

        .order-table th {
            padding: 12px 14px;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .order-table thead tr:first-child th {
            background: #f6f7fb;
            border-bottom: 1px solid #e9eef4;
            text-align: center;
        }

        .order-table thead tr:first-child th[rowspan="2"] {
            text-align: left;
            vertical-align: middle;
        }

        .order-table thead tr:nth-child(2) th {
            text-align: center;
            font-size: 9px;
            padding: 11px 10px;
        }

        .order-table td {
            padding: 15px 14px;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 12px;
            vertical-align: middle;
        }

        .order-table tbody tr {
            transition: .18s ease;
            cursor: pointer;
        }

        .order-table tbody tr:hover {
            background: #fafaff;
            transform: translateY(-1px);
        }

        .order-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 700;
            text-align: center;
        }

        .order-number {
            color: #172033;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-user {
            color: #475569;
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
            font-size: 13px;
            font-weight: 800;
            color: #172033;
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

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #f5f3ff;
            color: #7c3aed;
            font-size: 19px;
            font-weight: 800;
        }

        .order-empty-title {
            margin-bottom: 3px;
            color: #475569;
            font-weight: 750;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .order-page-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-table-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        .order-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            background: #f0fdf4;
        }

        .order-alert-icon {
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

        .order-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .order-alert span {
            color: #4d7a5c;
            font-size: 10px;
        }
    </style>

    <?php if($completedCount > 0): ?>
        <div class="order-alert">
            <div class="order-alert-icon">
                ✓
            </div>

            <div>
                <strong>
                    Order Repair Box Selesai
                </strong>

                <span>
                    <?php echo e($completedCount); ?> order selesai diproses dan menunggu konfirmasi dari User.
                </span>
            </div>
        </div>
    <?php endif; ?>

    <div class="order-table-card">

        <div class="order-table-header">

            <div>

                <h3>
                    Daftar Order Repair Box
                </h3>

            </div>
        </div>

        <div class="order-table-wrap">

            <table class="order-table">

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

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="window.location='<?php echo e(route('omd.orders.show', $order)); ?>'"
                            title="Klik untuk melihat detail order">

                            <td class="order-no">
                                <?php echo e(($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration); ?>

                            </td>

                            <td>
                                <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : '-'); ?>

                            </td>

                            <td class="order-number">
                                <?php echo e($order->order_number); ?>

                            </td>

                            <td>
                                <?php echo e($order->line?->name ?? ($order->area ? $order->area->name : '-')); ?>

                            </td>

                            <td>
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

    </div>

    <div style="margin-top:18px;">
        <?php echo e($orders->links()); ?>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/index.blade.php ENDPATH**/ ?>