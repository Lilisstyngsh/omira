

<?php $__env->startSection('title', 'History Order Repair Box'); ?>
<?php $__env->startSection('header', 'History Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .history-page {
            width: 100%;
        }

        .history-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
            padding: 22px 24px;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            background: linear-gradient(135deg, #f8f7ff 0%, #ffffff 68%);
            box-shadow: 0 10px 35px rgba(15, 23, 42, .05);
        }

        .history-hero-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .history-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #ede9fe;
            color: #6d5ce7;
        }

        .history-icon svg {
            width: 22px;
            height: 22px;
        }

        .history-title {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: 800;
            color: #172033;
        }

        .history-desc {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.6;
        }

        .history-total {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 10px;
            background: #f5f3ff;
            color: #6659df;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .history-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .history-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 19px 22px;
            border-bottom: 1px solid #edf1f5;
            background: #fff;
        }

        .history-card-header-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .history-header-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f8fafc;
            color: #64748b;
        }

        .history-header-icon svg {
            width: 17px;
            height: 17px;
        }

        .history-card-title {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            color: #172033;
        }

        .history-card-subtitle {
            margin: 3px 0 0;
            color: #94a3b8;
            font-size: 11px;
        }

        .history-table-wrap {
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
        }

        .history-table th {
            padding: 13px 16px;
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

        .history-table td {
            padding: 15px 16px;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 12px;
            vertical-align: middle;
        }

        .history-table tbody tr {
            transition: .18s ease;
        }

        .history-table tbody tr:hover {
            background: #fbfbfe;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }

        .history-no {
            width: 55px;
            text-align: center;
            color: #94a3b8 !important;
            font-weight: 700;
        }

        .date-wrap {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .date-main {
            color: #334155;
            font-weight: 700;
        }

        .date-time {
            color: #94a3b8;
            font-size: 10px;
        }

        .order-number-wrap {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-number {
            color: #172033;
            font-weight: 800;
            letter-spacing: .01em;
        }

        .order-label {
            width: fit-content;
            padding: 3px 7px;
            border-radius: 6px;
            background: #f8fafc;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 700;
        }

        .line-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 10px;
            font-weight: 800;
        }

        .user-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
        }

        .user-name {
            color: #334155;
            font-size: 11px;
            font-weight: 700;
        }

        .order-qty {
            color: #172033;
            font-size: 13px;
            font-weight: 800;
        }

        .qty-label {
            display: block;
            margin-top: 3px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 29px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-completed {
            background: #ecfdf3;
            color: #15803d;
        }

        .status-completed .status-dot {
            background: #22c55e;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-confirmed .status-dot {
            background: #16a34a;
        }

        .handover-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .handover-main {
            color: #475569;
            font-size: 10px;
            font-weight: 700;
        }

        .handover-time {
            color: #94a3b8;
            font-size: 9px;
        }

        .history-action {
            height: 32px;
            padding: 0 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: .18s ease;
        }

        .history-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #172033;
        }

        .history-action svg {
            width: 14px;
            height: 14px;
        }

        .history-empty {
            padding: 65px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .empty-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: #f5f3ff;
            color: #7c3aed;
        }

        .empty-icon svg {
            width: 22px;
            height: 22px;
        }

        .empty-title {
            margin-bottom: 4px;
            color: #475569;
            font-size: 13px;
            font-weight: 800;
        }

        .empty-text {
            color: #94a3b8;
            font-size: 11px;
        }

        .history-pagination {
            padding: 18px 22px;
            border-top: 1px solid #edf1f5;
            background: #fff;
        }

        @media (max-width: 768px) {
            .history-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .history-hero-left {
                align-items: flex-start;
            }

            .history-card-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    <div class="history-page">

        
        <div class="history-hero">

            <div class="history-hero-left">

                <div class="history-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7" />
                        <path d="M3 4v5h5" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                </div>

                <div>
                    <h2 class="history-title">
                        History Order Repair Box
                    </h2>

                    <p class="history-desc">
                        Riwayat order yang telah selesai diproses oleh OMD.
                    </p>
                </div>

            </div>

            <div class="history-total">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3h18v18H3z" />
                    <path d="M8 8h8" />
                    <path d="M8 12h8" />
                    <path d="M8 16h5" />
                </svg>

                <?php echo e($orders->total()); ?> Order
            </div>

        </div>


        
        <div class="history-card">

            <div class="history-card-header">

                <div class="history-card-header-left">

                    <div class="history-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 6h13" />
                            <path d="M8 12h13" />
                            <path d="M8 18h13" />
                            <path d="M3 6h.01" />
                            <path d="M3 12h.01" />
                            <path d="M3 18h.01" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="history-card-title">
                            Riwayat Order
                        </h3>

                        <p class="history-card-subtitle">
                            Daftar order Repair Box yang telah selesai.
                        </p>
                    </div>

                </div>

            </div>


            <div class="history-table-wrap">

                <table class="history-table">

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
                                Line
                            </th>

                            <th>
                                Diorder Oleh
                            </th>

                            <th>
                                Qty
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Serah Terima
                            </th>

                            <th style="width:100px;">
                                Aksi
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr>

                                
                                <td class="history-no">
                                    <?php echo e(($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration); ?>

                                </td>


                                
                                <td>

                                    <div class="date-wrap">

                                        <span class="date-main">
                                            <?php echo e($order->created_at ? $order->created_at->format('d-m-Y') : '-'); ?>

                                        </span>

                                        <span class="date-time">
                                            <?php echo e($order->created_at ? $order->created_at->format('H:i') : '-'); ?> WIB
                                        </span>

                                    </div>

                                </td>


                                
                                <td>

                                    <div class="order-number-wrap">

                                        <span class="order-number">
                                            <?php echo e($order->order_number); ?>

                                        </span>

                                        <span class="order-label">
                                            REPAIR BOX
                                        </span>

                                    </div>

                                </td>


                                
                                <td>

                                    <span class="line-badge">
                                        <?php echo e($order->line?->name ?? ($order->area ? $order->area->name : '-')); ?>

                                    </span>

                                </td>


                                
                                <td>

                                    <div class="user-wrap">

                                        <div class="user-avatar">
                                            <?php echo e(strtoupper(substr($order->user?->name ?? 'U', 0, 1))); ?>

                                        </div>

                                        <span class="user-name">
                                            <?php echo e($order->user?->name ?? '-'); ?>

                                        </span>

                                    </div>

                                </td>


                                
                                <td>

                                    <span class="order-qty">
                                        <?php echo e($order->quantity); ?>

                                    </span>

                                    <span class="qty-label">
                                        Total NG
                                    </span>

                                </td>


                                
                                <td>

                                    <span class="status-badge status-<?php echo e($order->status); ?>">

                                        <span class="status-dot"></span>

                                        <?php echo e($order->status_label); ?>


                                    </span>

                                </td>


                                
                                <td>

                                    <?php if($order->handed_over_at): ?>
                                        <div class="handover-info">

                                            <span class="handover-main">
                                                Sudah Diserahterimakan
                                            </span>

                                            <span class="handover-time">
                                                <?php echo e($order->handed_over_at->format('d-m-Y H:i')); ?>

                                            </span>

                                        </div>
                                    <?php elseif($order->status === 'confirmed'): ?>
                                        <div class="handover-info">

                                            <span class="handover-main">
                                                Sudah Dikonfirmasi
                                            </span>

                                            <?php if($order->confirmation?->confirmed_at): ?>
                                                <span class="handover-time">
                                                    <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>

                                                </span>
                                            <?php endif; ?>

                                        </div>
                                    <?php else: ?>
                                        <span style="color:#94a3b8;font-size:10px;">
                                            -
                                        </span>
                                    <?php endif; ?>

                                </td>


                                
                                <td>

                                    <a href="<?php echo e(route('omd.orders.show', $order)); ?>" class="history-action">
                                        Detail

                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="m13 6 6 6-6 6" />
                                        </svg>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td colspan="9" class="history-empty">

                                    <div class="empty-icon">

                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0" />
                                            <path d="M12 7v5l3 2" />
                                        </svg>

                                    </div>

                                    <div class="empty-title">
                                        Belum Ada History Order
                                    </div>

                                    <div class="empty-text">
                                        Order yang telah selesai diproses akan muncul di halaman ini.
                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>


            
            <?php if($orders->hasPages()): ?>
                <div class="history-pagination">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php endif; ?>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/history.blade.php ENDPATH**/ ?>