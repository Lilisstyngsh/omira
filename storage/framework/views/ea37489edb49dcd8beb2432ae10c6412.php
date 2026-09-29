<?php $__env->startSection('title', 'Detail Order Repair Box'); ?>
<?php $__env->startSection('header', 'Detail Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .repair-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .repair-head h2 {
            margin: 0 0 7px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .repair-head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .repair-head-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .repair-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            padding: 22px;
        }

        .repair-card+.repair-card {
            margin-top: 18px;
        }

        .repair-card-title {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .repair-card-desc {
            margin: 0 0 18px;
            font-size: 11px;
            color: #64748b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-submitted {
            background: #f1efff;
            color: #6557dc;
        }

        .status-in_repair {
            background: #fff7e8;
            color: #b77906;
        }

        .status-completed {
            background: #ecfdf3;
            color: #15803d;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-draft {
            background: #f1f5f9;
            color: #64748b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .info-item {
            padding: 13px;
            border-radius: 11px;
            background: #f8fafc;
            border: 1px solid #edf1f5;
        }

        .info-item span {
            display: block;
            margin-bottom: 5px;
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .info-item strong {
            font-size: 12px;
            color: #334155;
        }

        .description-box {
            padding: 13px;
            border-radius: 11px;
            background: #fafbfc;
            border: 1px solid #edf1f5;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .repair-table-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #edf1f5;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 920px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 920px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 920px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }

        .repair-table th {
            padding: 10px 8px;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: center;
            white-space: nowrap;
        }

        .repair-table td {
            padding: 11px 8px;
            border-bottom: 1px solid #eef2f6;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
            background: #fff;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: none;
        }

        .repair-table tbody tr:hover td {
            background: #fcfcff;
        }

        .model-cell,
        .no-cell {
            font-weight: 800;
            color: #172033;
            vertical-align: middle !important;
        }

        .model-cell {
            text-align: left;
        }

        .no-cell {
            text-align: center;
        }

        .model-text {
            font-weight: 800;
            color: #172033;
        }

        .product-text {
            font-weight: 650;
            color: #475569;
        }

        .ng-value {
            min-height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #334155;
        }

        .empty-ng {
            color: #cbd5e1;
            font-size: 12px;
        }

        .confirm-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .confirm-box-info strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .confirm-box-info span {
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
        }

        .confirmed-box {
            padding: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .confirmed-box strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .confirmed-box span {
            color: #4d7a5c;
            font-size: 10px;
            line-height: 1.5;
        }

        .btn {
            height: 36px;
            padding: 0 14px;
            border: none;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .18s ease;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
            box-shadow: 0 6px 15px rgba(22, 163, 74, .16);
        }

        .btn-success:hover {
            background: #15803d;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .timeline-wrap {
            overflow-x: auto;
            padding: 8px 2px 5px;
        }

        .timeline {
            min-width: 620px;
            display: grid;
            grid-template-columns: repeat(4, minmax(140px, 1fr));
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 12.5%;
            right: 12.5%;
            top: 18px;
            height: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            text-align: center;
            padding: 0 8px;
            z-index: 1;
        }

        .timeline-icon {
            width: 28px;
            height: 28px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 15px;
            font-weight: 800;
            background: #fee2e2;
            color: #dc2626;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #fecaca;
        }

        .timeline-item.active .timeline-icon {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 0 0 2px #bbf7d0;
        }

        .timeline-item strong {
            display: block;
            margin-bottom: 5px;
            color: #334155;
            font-size: 11px;
            font-weight: 800;
        }

        .timeline-item span {
            display: block;
            font-size: 9px;
            line-height: 1.5;
            color: #dc2626;
        }

        .timeline-item.active span {
            color: #64748b;
        }

        .error-list {
            margin: 0 0 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff1f2;
            border: 1px solid #ffd8dd;
            color: #b42318;
            font-size: 11px;
        }

        @media (max-width: 1000px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {

            .repair-head {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .confirm-box {
                align-items: flex-start;
                flex-direction: column;
            }

            .confirm-box .btn {
                width: 100%;
            }
        }
    </style>


    <div class="repair-head">

        <div>

            <h2>
                <?php echo e($order->order_number); ?>

            </h2>

            <p>
                Order Repair Box
            </p>

        </div>


        <div class="repair-head-actions">

            <span class="status-badge status-<?php echo e($order->status); ?>">
                <?php echo e($order->status_label); ?>

            </span>


            <a href="<?php echo e(route('user.orders.index')); ?>" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>


    <?php if($errors->any()): ?>

        <div class="error-list">

            <ul style="margin:0;padding-left:16px;">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <?php echo e($error); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

        </div>

    <?php endif; ?>


    

    <div class="repair-card">

        <h3 class="repair-card-title">
            Informasi Order
        </h3>


        <div class="info-grid">

            <div class="info-item">

                <span>
                    No Order
                </span>

                <strong>
                    <?php echo e($order->order_number); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Tanggal
                </span>

                <strong>
                    <?php echo e($order->created_at?->format('d-m-Y H:i') ?? '-'); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Nama
                </span>

                <strong>
                    <?php echo e($order->user?->name ?? '-'); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Plant
                </span>

                <strong>
                    <?php echo e($order->line?->plant?->name ?? '-'); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Line
                </span>

                <strong>
                    <?php echo e($order->line?->name ?? '-'); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Jenis Order
                </span>

                <strong>
                    Repair Box
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Total Qty
                </span>

                <strong>
                    <?php echo e($order->quantity); ?>

                </strong>

            </div>


            <div class="info-item">

                <span>
                    Status
                </span>

                <strong>
                    <?php echo e($order->status_label); ?>

                </strong>

            </div>

        </div>


        <div style="margin-top:18px;">

            <div style="margin-bottom:7px;font-size:11px;font-weight:800;color:#334155;">
                Keterangan
            </div>

            <div class="description-box">
                <?php echo e($order->description ?: '-'); ?>

            </div>

        </div>

    </div>


    

    <?php if($order->items->isNotEmpty()): ?>

        <?php

            $modelGroups = $order->items->groupBy('master_model_id');

            $ngCodes = ['P', 'H', 'C', 'S'];

        ?>


        <div class="repair-card">

            <h3 class="repair-card-title">
                Detail Repair Box
            </h3>


            <p class="repair-card-desc">
                Perbandingan quantity NG sebelum dan sesudah repair.
            </p>


            <div class="repair-table-scroll">


                
                <div class="repair-table-header">

                    <table class="repair-table">

                        <colgroup>

                            <col style="width:55px;">
                            <col style="width:150px;">
                            <col style="width:170px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                        </colgroup>


                        <thead>

                            <tr>

                                <th rowspan="3">
                                    No
                                </th>

                                <th rowspan="3" style="text-align:left;">
                                    Model
                                </th>

                                <th rowspan="3" style="text-align:left;">
                                    Produk
                                </th>

                                <th colspan="8">
                                    Jenis &amp; Qty NG
                                </th>

                            </tr>


                            <tr>

                                <th colspan="4">
                                    Sebelum
                                </th>

                                <th colspan="4">
                                    Sesudah
                                </th>

                            </tr>


                            <tr>

                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th>
                                        <?php echo e($code); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th>
                                        <?php echo e($code); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>

                        </thead>

                    </table>

                </div>


                
                <div class="repair-table-body">

                    <table class="repair-table">

                        <colgroup>

                            <col style="width:55px;">
                            <col style="width:150px;">
                            <col style="width:170px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                        </colgroup>


                        <tbody>

                            <?php $__currentLoopData = $modelGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php

                                    $productGroups = $modelItems->groupBy('product_id');

                                    $modelRowspan = $productGroups->count();

                                ?>


                                <?php $__currentLoopData = $productGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php

                                        $ngItems = $productItems->keyBy(function ($item) {
                                            return strtoupper($item->ngType?->code ?? '');
                                        });

                                    ?>


                                    <tr>

                                        <?php if($loop->first): ?>
                                            <td rowspan="<?php echo e($modelRowspan); ?>" class="no-cell">
                                                <?php echo e($loop->parent->iteration); ?>

                                            </td>


                                            <td rowspan="<?php echo e($modelRowspan); ?>" class="model-cell">

                                                <span class="model-text">
                                                    <?php echo e($modelItems->first()->masterModel?->model ?? '-'); ?>

                                                </span>

                                            </td>
                                        <?php endif; ?>


                                        <td>

                                            <span class="product-text">
                                                <?php echo e($productItems->first()->product?->name ?? '-'); ?>

                                            </span>

                                        </td>


                                        
                                        <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $ngItem = $ngItems->get($code);
                                            ?>


                                            <td style="text-align:center;">

                                                <?php if($ngItem): ?>
                                                    <div class="ng-value">
                                                        <?php echo e($ngItem->before_qty); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div class="ng-value empty-ng">
                                                        -
                                                    </div>
                                                <?php endif; ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        
                                        <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $ngItem = $ngItems->get($code);
                                            ?>


                                            <td style="text-align:center;">

                                                <?php if($ngItem): ?>
                                                    <div class="ng-value">

                                                        <?php if($ngItem->after_qty !== null): ?>
                                                            <?php echo e($ngItem->after_qty); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div class="ng-value empty-ng">
                                                        -
                                                    </div>
                                                <?php endif; ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    <?php elseif($order->result): ?>
        

        <div class="repair-card">

            <h3 class="repair-card-title">
                Hasil Repair
            </h3>


            <div class="info-grid">

                <div class="info-item">

                    <span>
                        OK
                    </span>

                    <strong>
                        <?php echo e($order->result->ok_qty); ?>

                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        SCRAP
                    </span>

                    <strong>
                        <?php echo e($order->result->scrap_qty); ?>

                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        NG
                    </span>

                    <strong>
                        <?php echo e($order->result->ng_qty); ?>

                    </strong>

                </div>

            </div>


            <div class="description-box" style="margin-top:14px;">
                <?php echo e($order->result->notes ?: 'Tidak ada catatan.'); ?>

            </div>

        </div>

    <?php endif; ?>


    

    <?php if($order->status === 'completed'): ?>

        <div class="repair-card">

            <div class="confirm-box">

                <div class="confirm-box-info">

                    <strong>
                        Order Repair Box Selesai
                    </strong>

                    <span>
                        OMD telah menyelesaikan proses repair.
                        Silakan periksa hasil repair dan lakukan konfirmasi penerimaan.
                    </span>

                </div>


                <form method="POST" action="<?php echo e(route('user.orders.confirm', $order)); ?>">

                    <?php echo csrf_field(); ?>

                    <button type="submit" class="btn btn-success">
                        Konfirmasi Penerimaan
                    </button>

                </form>

            </div>

        </div>
    <?php elseif($order->status === 'confirmed'): ?>
        <div class="repair-card">

            <div class="confirmed-box">

                <strong>
                    Order Telah Dikonfirmasi
                </strong>

                <span>
                    Order Repair Box telah dikonfirmasi dan proses telah selesai.
                </span>


                <?php if($order->confirmation?->confirmed_at): ?>
                    <div style="margin-top:8px;font-size:10px;color:#4d7a5c;">

                        Dikonfirmasi pada

                        <strong style="display:inline;font-size:10px;">
                            <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>

                        </strong>

                    </div>
                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>


    

    <div class="repair-card">

        <h3 class="repair-card-title">
            Progress Order
        </h3>


        <p class="repair-card-desc">
            Riwayat tahapan Order Repair Box.
        </p>


        <div class="timeline-wrap">

            <div class="timeline">


                
                <div class="timeline-item active">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        User Submit
                    </strong>

                    <span>
                        <?php echo e($order->created_at?->format('d-m-Y H:i') ?? '-'); ?>

                    </span>

                </div>


                
                <div
                    class="timeline-item
                <?php echo e(in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? 'active' : ''); ?>">

                    <div class="timeline-icon">

                        <?php echo e(in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? '✓' : '✕'); ?>


                    </div>


                    <strong>
                        Verified OMD
                    </strong>


                    <span>

                        <?php if($order->verified_at): ?>
                            <?php echo e($order->verified_at->format('d-m-Y H:i')); ?>

                        <?php else: ?>
                            Belum dilakukan
                        <?php endif; ?>

                    </span>

                </div>


                
                <div
                    class="timeline-item
                <?php echo e(in_array($order->status, ['completed', 'confirmed']) ? 'active' : ''); ?>">

                    <div class="timeline-icon">

                        <?php if(in_array($order->status, ['completed', 'confirmed'])): ?>
                            ✓
                        <?php elseif($order->status === 'in_repair'): ?>
                            △
                        <?php else: ?>
                            ✕
                        <?php endif; ?>

                    </div>


                    <strong>
                        Repair OMD
                    </strong>


                    <span>

                        <?php if($order->repair_completed_at): ?>
                            <?php echo e($order->repair_completed_at->format('d-m-Y H:i')); ?>

                        <?php elseif($order->repair_started_at): ?>
                            Sedang diproses
                        <?php else: ?>
                            Belum dilakukan
                        <?php endif; ?>

                    </span>

                </div>


                
                <div
                    class="timeline-item
                <?php echo e($order->status === 'confirmed' ? 'active' : ''); ?>">

                    <div class="timeline-icon">

                        <?php if($order->status === 'confirmed'): ?>
                            ✓
                        <?php elseif($order->status === 'completed'): ?>
                            △
                        <?php else: ?>
                            ✕
                        <?php endif; ?>

                    </div>


                    <strong>
                        Serah Terima
                    </strong>


                    <span>

                        <?php if($order->status === 'confirmed' && $order->confirmation?->confirmed_at): ?>
                            <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>

                        <?php elseif($order->status === 'completed'): ?>
                            Menunggu konfirmasi User
                        <?php else: ?>
                            Belum dilakukan
                        <?php endif; ?>

                    </span>

                </div>

            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/show.blade.php ENDPATH**/ ?>