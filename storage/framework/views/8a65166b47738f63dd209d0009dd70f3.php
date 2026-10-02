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


        /* =================================================
                           REPAIR TABLE
                        ================================================== */

        .repair-table-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #000;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 1100px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 1100px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 1100px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
        }

        .repair-table th {
            padding: 10px 8px;
            background: #fafbfc;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            color: #475569;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .repair-table td {
            padding: 4px 8px;
            /* REVISI: sebelumnya 11px 8px */
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
            background: #fff;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: none;
        }

        .repair-table tbody tr:hover td {
            background: #fafbfc;
        }

        .repair-table td.no-cell,
        .repair-table td.model-cell {
            border-right: 1px solid #000;
        }

        .repair-table .model-cell,
        .repair-table .no-cell {
            font-weight: 800;
            color: #172033;
            vertical-align: middle !important;
        }

        .repair-table .model-cell {
            text-align: left;
        }

        .repair-table .no-cell {
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
            min-height: 22px;
            /* REVISI: sebelumnya 34px */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #000;
        }

        .repair-result-match-cell {
            background: #dcfce7 !important;
        }

        .repair-result-mismatch-cell {
            background: #fee2e2 !important;
        }

        .repair-result-match-cell .ng-value,
        .repair-result-mismatch-cell .ng-value {
            color: #000;
        }

        .empty-ng {
            color: #cbd5e1;
            font-size: 12px;
        }


        /* =================================================
                           KETERANGAN
                        ================================================== */

        .repair-table .keterangan-head {
            width: 180px !important;
            min-width: 180px !important;
            text-align: center !important;
        }

        .repair-table .keterangan-cell {
            width: 180px !important;
            min-width: 180px !important;
            padding: 2px !important;
            text-align: left !important;
            vertical-align: middle !important;
        }

        .repair-table .keterangan-text {
            width: 100%;
            padding: 3px 8px;
            /* REVISI: sebelumnya 8px 10px */
            text-align: left !important;
            color: #475569;
            font-size: 11px;
            line-height: 1.4;
            /* REVISI: sebelumnya 1.5 */
            white-space: pre-wrap;
            word-break: break-word;
            box-sizing: border-box;
        }


        /* =================================================
                           COMPLETED
                        ================================================== */

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


        /* =================================================
                           TIMELINE
                        ================================================== */

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


        /* =================================================
                           BUTTON
                        ================================================== */

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


        /* =================================================
                           RESPONSIVE
                        ================================================== */

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

        </div>


        <div class="repair-head-actions">

            <a href="<?php echo e(route('omd.orders.history')); ?>" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>

    
    <div class="repair-card">

        <h3 class="repair-card-title">
            Progress Order
        </h3>

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


                        <?php if($order->user?->name): ?>
                            <br>
                            <?php echo e($order->user->name); ?>

                        <?php endif; ?>

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


                            <?php if($order->result?->processedBy?->name): ?>
                                <br>
                                <?php echo e($order->result->processedBy->name); ?>

                            <?php endif; ?>
                        <?php elseif($order->repair_started_at): ?>
                            <?php echo e($order->repair_started_at->format('d-m-Y H:i')); ?>


                            <?php if($order->result?->processedBy?->name): ?>
                                <br>
                                <?php echo e($order->result->processedBy->name); ?>

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
                        Serah Terima
                    </strong>

                    <span>

                        <?php if($order->confirmation?->confirmed_at): ?>

                            <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>


                            <?php if($order->confirmation->user?->name): ?>
                                <br>
                                <?php echo e($order->confirmation->user->name); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>

                    </span>

                </div>


            </div>

        </div>

    </div>

    

    <div class="repair-card">

        <h3 class="repair-card-title">
            Informasi Order
        </h3>


        <div class="info-grid">

            <div class="info-item">
                <span>No Order</span>
                <strong><?php echo e($order->order_number); ?></strong>
            </div>

            <div class="info-item">
                <span>Tanggal</span>
                <strong><?php echo e($order->created_at?->format('d-m-Y H:i') ?? '-'); ?></strong>
            </div>

            <div class="info-item">
                <span>Nama</span>
                <strong><?php echo e($order->user?->name ?? '-'); ?></strong>
            </div>

            <div class="info-item">
                <span>Plant</span>
                <strong><?php echo e($order->line?->plant?->name ?? '-'); ?></strong>
            </div>

            <div class="info-item">
                <span>Line</span>
                <strong><?php echo e($order->line?->name ?? '-'); ?></strong>
            </div>

            <div class="info-item">
                <span>Jenis Order</span>
                <strong>Repair Box</strong>
            </div>

            <div class="info-item">
                <span>Total Qty</span>
                <strong><?php echo e($order->quantity); ?></strong>
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

                            <col style="width:180px;">

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

                                <th rowspan="3" class="keterangan-head">
                                    Keterangan
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

                            <col style="width:180px;">

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

                                        $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))
                                            ?->mismatch_note;

                                        $hasFinalResult = in_array($order->status, ['completed', 'confirmed'], true);

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

                                            <td>

                                                <?php if($ngItem): ?>
                                                    <div class="ng-value">

                                                        <?php echo e($ngItem->before_qty > 0 ? $ngItem->before_qty : ''); ?>


                                                    </div>
                                                <?php else: ?>
                                                    <div class="ng-value empty-ng">

                                                    </div>
                                                <?php endif; ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        

                                        <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $ngItem = $ngItems->get($code);
                                                $beforeQty = (int) ($ngItem?->before_qty ?? 0);
                                                $afterQty = (int) ($ngItem?->after_qty ?? 0);

                                                $isNgMatch = $hasFinalResult && $beforeQty === $afterQty;
                                                $isNgMismatch = $hasFinalResult && $beforeQty !== $afterQty;

                                                $resultCellClass = '';

                                                if ($hasFinalResult && $afterQty > 0) {
                                                    $resultCellClass = $isNgMatch
                                                        ? 'repair-result-match-cell'
                                                        : ($isNgMismatch
                                                            ? 'repair-result-mismatch-cell'
                                                            : '');
                                                }
                                            ?>

                                            <td class="<?php echo e($resultCellClass); ?>">
                                                <div class="ng-value">
                                                    <?php echo e($afterQty > 0 ? $afterQty : ''); ?>

                                                </div>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        

                                        <td class="keterangan-cell">
                                            <div class="keterangan-text"><?php echo e($productNote ?: '-'); ?></div>
                                        </td>

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
                Ringkasan Hasil Repair
            </h3>

            <p class="repair-card-desc">
                Ringkasan hasil repair untuk order ini.
            </p>


            <div class="info-grid">

                <div class="info-item">
                    <span>OK</span>
                    <strong><?php echo e($order->result->ok_qty); ?></strong>
                </div>

                <div class="info-item">
                    <span>Scrap</span>
                    <strong><?php echo e($order->result->scrap_qty); ?></strong>
                </div>

                <div class="info-item">
                    <span>NG</span>
                    <strong><?php echo e($order->result->ng_qty); ?></strong>
                </div>

                <div class="info-item">
                    <span>Diproses Oleh</span>
                    <strong><?php echo e($order->result->processedBy?->name ?? '-'); ?></strong>
                </div>

            </div>


            <div class="description-box" style="margin-top:14px;">
                <?php echo e($order->result->notes ?: 'Tidak ada catatan.'); ?>

            </div>

        </div>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/history-show.blade.php ENDPATH**/ ?>