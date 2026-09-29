

<?php $__env->startSection('title', 'Detail History Order Repair Box'); ?>
<?php $__env->startSection('header', 'Detail History Order Repair Box'); ?>

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

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
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

        .order-table-wrap {
            width: 100%;
            max-height: 430px;
            overflow: auto;
            border-radius: 12px;
            border: 1px solid #edf1f5;
            background: #fff;
        }

        .repair-table {
            width: 100%;
            min-width: 980px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .repair-table th {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 10px 12px;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .repair-table thead tr:first-child th {
            border-bottom: 0;
        }

        .repair-table .sub-head {
            padding-top: 8px;
            padding-bottom: 10px;
            text-align: center;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4 !important;
        }

        .repair-table td {
            padding: 11px 12px;
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

        .repair-table .model-cell,
        .repair-table .no-cell {
            font-weight: 800;
            color: #172033;
            vertical-align: middle;
            text-align: center;
        }

        .repair-table .model-cell {
            text-align: left;
        }

        .model-text {
            font-weight: 800;
            color: #172033;
        }

        .product-text {
            font-weight: 650;
            color: #475569;
        }

        .ng-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #f8fafc;
            color: #cbd5e1;
            font-size: 12px;
            font-weight: 900;
            border: 1px solid #edf1f5;
        }

        .ng-check.active {
            background: #f5f3ff;
            color: #6557dc;
            border-color: #e9e3ff;
        }

        .qty-readonly {
            text-align: center;
            font-weight: 800;
            color: #334155;
        }

        .result-total {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            height: 28px;
            padding: 0 9px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 11px;
            font-weight: 800;
        }

        .completed-box {
            padding: 15px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .completed-box strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
        }

        .completed-box span {
            display: block;
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
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
            background: #bbf7d0;
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
            background: #dcfce7;
            color: #16a34a;
            border: 3px solid #fff;
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
            color: #64748b;
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

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
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
        }
    </style>


    

    <div class="repair-head">

        <div>

            <h2>
                <?php echo e($order->order_number); ?>

            </h2>

            <p>
                Detail History Order Repair Box
            </p>

        </div>


        <div class="repair-head-actions">

            <span class="status-badge status-confirmed">
                Selesai
            </span>

            <a href="<?php echo e(route('omd.orders.history')); ?>" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>


    

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
            $groupedItems = $order->items->groupBy('master_model_id');
        ?>


        <div class="repair-card">

            <h3 class="repair-card-title">
                Detail Repair Box
            </h3>

            <p class="repair-card-desc">
                Detail model, produk, jenis NG, dan hasil repair.
            </p>


            <div class="order-table-wrap">

                <table class="repair-table">

                    <thead>

                        <tr>

                            <th rowspan="2" style="width:55px;text-align:center;">
                                No
                            </th>

                            <th rowspan="2" style="width:170px;">
                                Model
                            </th>

                            <th rowspan="2" style="width:170px;">
                                Produk
                            </th>

                            <th colspan="4" style="text-align:center;">
                                Jenis NG
                            </th>

                            <th rowspan="2" style="width:95px;text-align:center;">
                                Qty Sebelum
                            </th>

                            <th rowspan="2" style="width:95px;text-align:center;">
                                Qty Sesudah
                            </th>

                            <th rowspan="2" style="min-width:220px;">
                                Keterangan
                            </th>

                        </tr>


                        <tr>

                            <th class="sub-head" style="width:48px;">
                                P
                            </th>

                            <th class="sub-head" style="width:48px;">
                                H
                            </th>

                            <th class="sub-head" style="width:48px;">
                                C
                            </th>

                            <th class="sub-head" style="width:48px;">
                                S
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php $__currentLoopData = $groupedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $modelNo = $loop->iteration;
                                $rowspan = $modelItems->count();
                            ?>


                            <?php $__currentLoopData = $modelItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $ngCode = strtoupper($item->ngType?->code ?? '');
                                ?>


                                <tr>

                                    <?php if($loop->first): ?>
                                        <td rowspan="<?php echo e($rowspan); ?>" class="no-cell">
                                            <?php echo e($modelNo); ?>

                                        </td>


                                        <td rowspan="<?php echo e($rowspan); ?>" class="model-cell">

                                            <span class="model-text">
                                                <?php echo e($item->masterModel?->model ?? '-'); ?>

                                            </span>

                                        </td>
                                    <?php endif; ?>


                                    <td>

                                        <span class="product-text">
                                            <?php echo e($item->product?->name ?? '-'); ?>

                                        </span>

                                    </td>


                                    
                                    <td style="text-align:center;">

                                        <span class="ng-check <?php echo e($ngCode === 'P' ? 'active' : ''); ?>">
                                            <?php echo e($ngCode === 'P' ? '✓' : ''); ?>

                                        </span>

                                    </td>


                                    
                                    <td style="text-align:center;">

                                        <span class="ng-check <?php echo e($ngCode === 'H' ? 'active' : ''); ?>">
                                            <?php echo e($ngCode === 'H' ? '✓' : ''); ?>

                                        </span>

                                    </td>


                                    
                                    <td style="text-align:center;">

                                        <span class="ng-check <?php echo e($ngCode === 'C' ? 'active' : ''); ?>">
                                            <?php echo e($ngCode === 'C' ? '✓' : ''); ?>

                                        </span>

                                    </td>


                                    
                                    <td style="text-align:center;">

                                        <span class="ng-check <?php echo e($ngCode === 'S' ? 'active' : ''); ?>">
                                            <?php echo e($ngCode === 'S' ? '✓' : ''); ?>

                                        </span>

                                    </td>


                                    
                                    <td class="qty-readonly">
                                        <?php echo e($item->before_qty); ?>

                                    </td>


                                    
                                    <td class="qty-readonly">
                                        <?php echo e($item->after_qty ?? '-'); ?>

                                    </td>


                                    
                                    <td>
                                        <?php echo e($item->mismatch_note ?: '-'); ?>

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>


    

    <?php if($order->result): ?>
        <div class="repair-card">

            <h3 class="repair-card-title">
                Ringkasan Hasil Repair
            </h3>

            <p class="repair-card-desc">
                Ringkasan hasil repair untuk order ini.
            </p>


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
                        Scrap
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


                <div class="info-item">

                    <span>
                        Diproses Oleh
                    </span>

                    <strong>
                        <?php echo e($order->result->processedBy?->name ?? '-'); ?>

                    </strong>

                </div>

            </div>


            <div class="description-box" style="margin-top:14px;">
                <?php echo e($order->result->notes ?: 'Tidak ada catatan.'); ?>

            </div>

        </div>
    <?php endif; ?>


    

    <div class="repair-card">

        <div class="completed-box">

            <strong>
                Order Repair Box Selesai
            </strong>

            <span>
                Order telah dikonfirmasi oleh User dan dipindahkan ke History Order Repair Box.
            </span>

            <?php if($order->confirmation?->confirmed_at): ?>
                <span style="margin-top:7px;">

                    Dikonfirmasi pada
                    <strong style="display:inline;font-size:10px;">
                        <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>

                    </strong>

                </span>
            <?php endif; ?>

        </div>

    </div>


    

    <div class="repair-card">

        <h3 class="repair-card-title">
            Progress Order
        </h3>

        <p class="repair-card-desc">
            Riwayat tahapan Order Repair Box.
        </p>


        <div class="timeline-wrap">

            <div class="timeline">


                
                <div class="timeline-item">

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


                
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Verified OMD
                    </strong>

                    <span>

                        <?php if($order->verified_at): ?>

                            <?php echo e($order->verified_at->format('d-m-Y H:i')); ?>


                            <?php if($order->omdVerifier?->name): ?>
                                <br>
                                <?php echo e($order->omdVerifier->name); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            -

                        <?php endif; ?>

                    </span>

                </div>


                
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Repair OMD
                    </strong>

                    <span>

                        <?php if($order->repair_completed_at): ?>
                            <?php echo e($order->repair_completed_at->format('d-m-Y H:i')); ?>

                        <?php elseif($order->repair_started_at): ?>
                            <?php echo e($order->repair_started_at->format('d-m-Y H:i')); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>

                    </span>

                </div>


                
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Serah Terima
                    </strong>

                    <span>

                        <?php if($order->confirmation?->confirmed_at): ?>
                            <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>

                    </span>

                </div>


            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/history-show.blade.php ENDPATH**/ ?>