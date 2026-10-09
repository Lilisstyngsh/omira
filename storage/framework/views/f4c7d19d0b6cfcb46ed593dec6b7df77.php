<?php $__env->startSection('title', 'Dashboard User'); ?>
<?php $__env->startSection('header', 'Dashboard User'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .user-dashboard {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .dashboard-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .dashboard-welcome h2 {
        margin: 0 0 4px;
        color: #172033;
        font-size: 21px;
        font-weight: 800;
    }

    .dashboard-welcome p {
        margin: 0;
        color: #64748b;
        font-size: 11px;
    }

    .dashboard-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .dashboard-kpi {
        position: relative;
        min-height: 102px;
        padding: 17px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .045);
    }

    .dashboard-kpi-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .dashboard-kpi-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .035em;
        text-transform: uppercase;
    }

    .dashboard-kpi-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f5f3ff;
        color: #7c3aed;
        font-size: 13px;
    }

    .dashboard-kpi.attention {
        border-color: #ddd6fe;
        box-shadow: 0 8px 26px rgba(124, 58, 237, .08);
    }

    .dashboard-kpi.attention .dashboard-kpi-icon {
        background: #ede9fe;
        color: #6d28d9;
    }

    .dashboard-kpi-value {
        margin-top: 10px;
        color: #172033;
        font-size: 27px;
        font-weight: 850;
        line-height: 1;
    }

    .dashboard-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 17px;
        box-shadow: 0 9px 28px rgba(15, 23, 42, .045);
    }

    .dashboard-panel-head {
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 18px;
        border-bottom: 1px solid #edf1f5;
    }

    .dashboard-panel-title {
        margin: 0;
        color: #172033;
        font-size: 14px;
        font-weight: 800;
    }

    .action-list {
        display: flex;
        flex-direction: column;
    }

    .action-order {
        display: grid;
        grid-template-columns: minmax(150px, 1fr) minmax(170px, 1.4fr) auto;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border-bottom: 1px solid #eef2f6;
    }

    .action-order:last-child {
        border-bottom: 0;
    }

    .action-order-number {
        color: #172033;
        font-size: 11px;
        font-weight: 800;
    }

    .action-order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 5px 10px;
        margin-top: 4px;
        color: #64748b;
        font-size: 9.5px;
    }

    .action-order-message {
        color: #475569;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .action-order-message strong {
        display: block;
        margin-bottom: 2px;
        color: #6d28d9;
        font-size: 10.5px;
    }

    .dashboard-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 58px;
        padding: 14px 18px;
        color: #64748b;
        font-size: 10.5px;
    }

    .dashboard-empty i {
        color: #16a34a;
    }

    .recent-table-wrap {
        overflow-x: auto;
    }

    .recent-table {
        width: 100%;
        min-width: 820px;
        border-collapse: collapse;
    }

    .recent-table th {
        padding: 10px 13px;
        background: #fafbfc;
        border-bottom: 1px solid #e8edf4;
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .025em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .recent-table td {
        padding: 12px 13px;
        border-bottom: 1px solid #eef2f6;
        color: #334155;
        font-size: 10.5px;
        vertical-align: middle;
    }

    .recent-table tbody tr {
        cursor: pointer;
        transition: background .16s ease;
    }

    .recent-table tbody tr:hover {
        background: #fafaff;
    }

    .recent-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .order-number {
        color: #172033;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-model-product {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .order-model-product strong {
        color: #172033;
        font-size: 10.5px;
    }

    .order-model-product span {
        color: #64748b;
        font-size: 9.5px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-height: 25px;
        padding: 0 9px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-pill.submitted {
        background: #eff6ff;
        color: #2563eb;
    }

    .status-pill.in-repair {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-pill.completed {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .status-pill.revision {
        background: #fff1f2;
        color: #be123c;
    }

    .status-pill.confirmed {
        background: #ecfdf3;
        color: #15803d;
    }

    .row-action {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
        color: #475569;
        text-decoration: none;
    }

    .row-action:hover {
        border-color: #ddd6fe;
        background: #f5f3ff;
        color: #6d28d9;
    }

    @media (max-width: 1100px) {
        .dashboard-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 760px) {
        .dashboard-welcome,
        .dashboard-panel-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .action-order {
            grid-template-columns: 1fr auto;
        }

        .action-order-message {
            grid-column: 1 / -1;
            grid-row: 2;
        }
    }

    @media (max-width: 520px) {
        .dashboard-kpis {
            grid-template-columns: 1fr;
        }

        .action-order {
            grid-template-columns: 1fr;
        }

        .action-order-message {
            grid-column: auto;
            grid-row: auto;
        }
    }
</style>

<?php
    $lineName = auth()->user()->line?->name ?? 'Line belum ditentukan';

    $statusMap = [
        'submitted' => ['label' => 'Menunggu Verifikasi', 'class' => 'submitted', 'icon' => 'fa-clock'],
        'in_repair' => ['label' => 'Sedang Repair', 'class' => 'in-repair', 'icon' => 'fa-screwdriver-wrench'],
        'completed' => ['label' => 'Perlu Konfirmasi', 'class' => 'completed', 'icon' => 'fa-circle-check'],
        'revision_requested' => ['label' => 'Dalam Koreksi', 'class' => 'revision', 'icon' => 'fa-triangle-exclamation'],
        'confirmed' => ['label' => 'Selesai', 'class' => 'confirmed', 'icon' => 'fa-check-double'],
    ];
?>

<div class="user-dashboard">
    <div class="dashboard-welcome">
        <div>
            <h2>Selamat datang, <?php echo e(auth()->user()->name); ?></h2>
            <p><?php echo e($lineName); ?></p>
        </div>

        <a class="btn btn-primary" href="<?php echo e(route('user.orders.create')); ?>">
            <i class="fa-solid fa-plus"></i>
            Buat Order Repair
        </a>
    </div>

    <div class="dashboard-kpis">
        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Order Aktif</div>
                <span class="dashboard-kpi-icon"><i class="fa-solid fa-box-open"></i></span>
            </div>
            <div class="dashboard-kpi-value"><?php echo e($orderActive); ?></div>
        </div>

        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Diproses OMD</div>
                <span class="dashboard-kpi-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
            </div>
            <div class="dashboard-kpi-value"><?php echo e($processedOmd); ?></div>
        </div>

        <div class="dashboard-kpi <?php echo e($needConfirmation > 0 ? 'attention' : ''); ?>">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Perlu Konfirmasi</div>
                <span class="dashboard-kpi-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
            </div>
            <div class="dashboard-kpi-value"><?php echo e($needConfirmation); ?></div>
        </div>

        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Selesai Bulan Ini</div>
                <span class="dashboard-kpi-icon"><i class="fa-solid fa-check-double"></i></span>
            </div>
            <div class="dashboard-kpi-value"><?php echo e($completedThisMonth); ?></div>
        </div>
    </div>

    <section class="dashboard-panel">
        <div class="dashboard-panel-head">
            <div>
                <h3 class="dashboard-panel-title">Perlu Tindakan</h3>
            </div>
        </div>

        <?php if($actionOrders->isNotEmpty()): ?>
            <div class="action-list">
                <?php $__currentLoopData = $actionOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $firstItem = $order->items->first();
                        $modelName = $firstItem?->masterModel?->model ?? $order->model ?? '-';
                        $qty = $order->after_qty_sum !== null
                            ? (int) $order->after_qty_sum
                            : (int) ($order->quantity ?? 0);
                        $isCorrection = (int) $order->feedbacks_count > 0;
                    ?>

                    <div class="action-order">
                        <div>
                            <div class="action-order-number"><?php echo e($order->order_number); ?></div>
                            <div class="action-order-meta">
                                <span><?php echo e($order->line?->name ?? '-'); ?></span>
                                <span>Model <?php echo e($modelName); ?></span>
                                <span><?php echo e($qty); ?> NG</span>
                            </div>
                        </div>

                        <div class="action-order-message">
                            <strong><?php echo e($isCorrection ? 'Hasil koreksi siap diperiksa' : 'Hasil repair siap diperiksa'); ?></strong>
                        </div>

                        <a href="<?php echo e(route('user.orders.show', $order)); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-arrow-right"></i>
                            Cek Order
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="dashboard-empty">
                <i class="fa-solid fa-circle-check"></i>
                Tidak ada order yang perlu dikonfirmasi.
            </div>
        <?php endif; ?>
    </section>

    <section class="dashboard-panel">
        <div class="dashboard-panel-head">
            <div>
                <h3 class="dashboard-panel-title">Order Terbaru</h3>
            </div>

            <a class="btn btn-secondary" href="<?php echo e(route('user.orders.index')); ?>">
                Lihat Semua Order
            </a>
        </div>

        <div class="recent-table-wrap">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Line</th>
                        <th>Model / Produk</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th style="width:58px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $firstItem = $order->items->first();
                            $modelName = $firstItem?->masterModel?->model ?? $order->model ?? '-';
                            $productName = $firstItem?->product?->name ?? $order->product?->name ?? '-';
                            $productCount = $order->items->pluck('product_id')->filter()->unique()->count();
                            $showAfterQty = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
                            $qty = $showAfterQty
                                ? ($order->after_qty_sum !== null
                                    ? (int) $order->after_qty_sum
                                    : (int) ($order->quantity ?? 0))
                                : ($order->before_qty_sum !== null
                                    ? (int) $order->before_qty_sum
                                    : (int) ($order->quantity ?? 0));
                            $status = $statusMap[$order->status] ?? [
                                'label' => $order->status_label,
                                'class' => 'submitted',
                                'icon' => 'fa-circle',
                            ];
                        ?>

                        <tr onclick="window.location='<?php echo e(route('user.orders.show', $order)); ?>'" title="Lihat detail order">
                            <td class="order-number"><?php echo e($order->order_number); ?></td>
                            <td><?php echo e($order->created_at?->format('d-m-Y H:i') ?? '-'); ?></td>
                            <td><?php echo e($order->line?->name ?? '-'); ?></td>
                            <td>
                                <div class="order-model-product">
                                    <strong><?php echo e($modelName); ?></strong>
                                    <span>
                                        <?php echo e($productName); ?>

                                        <?php if($productCount > 1): ?>
                                            +<?php echo e($productCount - 1); ?> produk
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </td>
                            <td><?php echo e($qty); ?> NG</td>
                            <td>
                                <span class="status-pill <?php echo e($status['class']); ?>">
                                    <i class="fa-solid <?php echo e($status['icon']); ?>"></i>
                                    <?php echo e($status['label']); ?>

                                </span>
                            </td>
                            <td style="text-align:center;">
                                <a href="<?php echo e(route('user.orders.show', $order)); ?>"
                                   class="row-action"
                                   title="Lihat detail order"
                                   aria-label="Lihat detail order"
                                   onclick="event.stopPropagation();">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <div class="dashboard-empty">
                                    <i class="fa-solid fa-box-open"></i>
                                    Belum ada Order Repair Box.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/dashboard.blade.php ENDPATH**/ ?>