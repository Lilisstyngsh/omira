<?php $__env->startSection('title', 'Detail Order Repair Box'); ?>
<?php $__env->startSection('header', 'Detail Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $hasOmdResult = in_array($order->status, ['completed', 'confirmed'], true);

        $totalQtyOmd = 0;

        if ($hasOmdResult) {
            $totalQtyOmd = $order->items->sum(fn($item) => (int) ($item->after_qty ?? 0));
        }
    ?>

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

        /* =================================================
                                   INFO
                                ================================================== */

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
                                   REPAIR TABLE (SAMA DENGAN HALAMAN USER)
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

        .ng-value {
            min-height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 800;
            color: #000;
        }

        .empty-ng {
            color: #cbd5e1;
            font-size: 12px;
        }

        .ng-cell {
            text-align: center;
            vertical-align: middle !important;
        }

        /* =================================================
                                   INPUT OMD (STATUS in_repair)
                                ================================================== */

        .repair-table .ng-input {
            display: block;
            width: 44px;
            max-width: 100%;
            height: 28px;
            box-sizing: border-box;
            border: 1px solid #64748b;
            border-radius: 7px;
            outline: none;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            background: #fff;
            padding: 0 4px;
            margin: 0 auto;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .repair-table .ng-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        .repair-table .ng-input::placeholder {
            color: #cbd5e1;
        }

        .repair-table .ng-input.user-focus {
            background: #fff7d6;
            border-color: #eab308;
            box-shadow: 0 0 0 2px rgba(234, 179, 8, .12);
        }

        .repair-table .ng-input.user-focus:focus {
            border-color: #ca8a04;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, .16);
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
            text-align: left !important;
            color: #475569;
            font-size: 11px;
            line-height: 1.4;
            white-space: pre-wrap;
            word-break: break-word;
            box-sizing: border-box;
        }

        .repair-table .keterangan-input {
            width: 100%;
            min-height: 32px;
            box-sizing: border-box;
            padding: 6px 8px;
            border: 1px solid #64748b;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            line-height: 1.4;
            resize: vertical;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .repair-table .keterangan-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        /* =================================================
                                   ACTION / COMPLETED
                                ================================================== */

        .action-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px;
            border-radius: 12px;
            background: #fafbfc;
            border: 1px solid #edf1f5;
        }

        .action-card-info strong {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            color: #334155;
        }

        .action-card-info span {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.5;
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
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
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

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            box-shadow: 0 6px 15px rgba(124, 58, 237, .18);
        }

        .btn-primary:hover {
            background: #6d28d9;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-back {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 6px 15px rgba(37, 99, 235, .18);
        }

        .btn-back:hover {
            background: #1d4ed8;
            color: #fff;
        }

        /* =================================================
                                   LEGACY
                                ================================================== */

        .legacy-result-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        /* =================================================
                                   ERROR
                                ================================================== */

        .error-list {
            margin: 0 0 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff1f2;
            border: 1px solid #ffd8dd;
            color: #b42318;
            font-size: 11px;
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
            background: #f1f5f9;
            color: #64748b;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #cbd5e1;
        }

        .timeline-icon.completed {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 0 0 2px #bbf7d0;
        }

        .timeline-icon.in-progress {
            background: #fef3c7;
            color: #d97706;
            box-shadow: 0 0 0 2px #fde68a;
        }

        .timeline-icon.pending {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: 0 0 0 2px #fecaca;
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

            .info-grid,
            .legacy-result-grid {
                grid-template-columns: 1fr;
            }

            .action-card {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        .info-item.total-qty-omd {
            background: #ecfdf5;
            border: 1px solid #86efac;
        }

        .info-item.total-qty-omd span {
            color: #15803d;
        }

        .info-item.total-qty-omd strong {
            color: #166534;
            font-size: 14px;
        }
    </style>


    

    <div class="repair-head">

        <div>
            <h2>
                <?php echo e($order->order_number); ?>

            </h2>
        </div>

        <div class="repair-head-actions">
            <a href="<?php echo e(route('omd.orders.index')); ?>" class="btn btn-back">
                ← Kembali
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
            Progress Order
        </h3>

        <p class="repair-card-desc">
            Riwayat tahapan Order Repair Box.
        </p>


        <div class="timeline-wrap">

            <div class="timeline">

                <?php
                    $verifiedDone = (bool) $order->verified_at;
                    $repairStarted = (bool) $order->repair_started_at;
                    $repairDone = (bool) $order->repair_completed_at;
                    $handoverDone = (bool) $order->confirmation?->confirmed_at;

                    $verifiedState = $verifiedDone ? 'completed' : 'pending';

                    $repairState = $repairDone ? 'completed' : ($repairStarted ? 'in-progress' : 'pending');

                    $handoverState = $handoverDone
                        ? 'completed'
                        : ($order->status === 'completed'
                            ? 'in-progress'
                            : 'pending');
                ?>


                
                <div class="timeline-item">

                    <div class="timeline-icon completed">
                        ✓
                    </div>

                    <strong>
                        User Submit
                    </strong>

                    <span>
                        <?php if($order->created_at): ?>
                            <?php echo e($order->created_at->format('d-m-Y H:i')); ?>


                            <?php if($order->user?->name): ?>
                                <br>
                                <?php echo e($order->user->name); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </span>

                </div>


                
                <div class="timeline-item">

                    <div class="timeline-icon <?php echo e($verifiedState); ?>">
                        <?php echo e($verifiedState === 'completed' ? '✓' : '×'); ?>

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

                    <div class="timeline-icon <?php echo e($repairState); ?>">
                        <?php if($repairState === 'completed'): ?>
                            ✓
                        <?php elseif($repairState === 'in-progress'): ?>
                            △
                        <?php else: ?>
                            ×
                        <?php endif; ?>
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

                    <div class="timeline-icon <?php echo e($handoverState); ?>">
                        <?php if($handoverState === 'completed'): ?>
                            ✓
                        <?php elseif($handoverState === 'in-progress'): ?>
                            △
                        <?php else: ?>
                            ×
                        <?php endif; ?>
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

            <?php if($hasOmdResult): ?>
                <div class="info-item total-qty-omd">

                    <span>
                        Qty OMD
                    </span>

                    <strong>
                        <?php echo e($totalQtyOmd); ?>

                    </strong>

                </div>
            <?php endif; ?>

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

            $isInRepair = $order->status === 'in_repair';

            // Kolom "Sesudah" tampil di semua status kecuali submitted
            $showAfter = $order->status !== 'submitted';

            $hasFinalResult = in_array($order->status, ['completed', 'confirmed'], true);
        ?>


        <div class="repair-card">

            <h3 class="repair-card-title">
                <?php echo e($isInRepair ? 'Diisi Oleh OMD Setelah Repair' : 'Hasil Repair'); ?>

            </h3>

            <p class="repair-card-desc">
                <?php if($isInRepair): ?>
                    Masukkan hasil repair untuk setiap Produk dan Jenis NG.
                <?php elseif($order->status === 'submitted'): ?>
                    Detail qty NG dari order User. Verifikasi order untuk memulai proses repair.
                <?php else: ?>
                    Hasil repair yang telah disimpan oleh OMD.
                <?php endif; ?>
            </p>


            <?php if($isInRepair): ?>
                <form method="POST" action="<?php echo e(route('omd.orders.complete', $order)); ?>">

                    <?php echo csrf_field(); ?>
            <?php endif; ?>


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

                            <?php if($showAfter): ?>
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                            <?php endif; ?>

                            <col style="width:180px;">

                        </colgroup>


                        <thead>

                            <tr>

                                <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>">
                                    No
                                </th>

                                <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>" style="text-align:left;">
                                    Model
                                </th>

                                <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>" style="text-align:left;">
                                    Produk
                                </th>

                                <th colspan="<?php echo e($showAfter ? 8 : 4); ?>">
                                    Jenis &amp; Qty NG
                                </th>

                                <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>" class="keterangan-head">
                                    Keterangan
                                </th>

                            </tr>


                            <?php if($showAfter): ?>
                                <tr>

                                    <th colspan="4">
                                        Sebelum
                                    </th>

                                    <th colspan="4">
                                        Sesudah
                                    </th>

                                </tr>
                            <?php endif; ?>


                            <tr>

                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th>
                                        <?php echo e($code); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                <?php if($showAfter): ?>
                                    <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th>
                                            <?php echo e($code); ?>

                                        </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

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

                            <?php if($showAfter): ?>
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                            <?php endif; ?>

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

                                        $firstItem = $productItems->first();
                                        $productId = $firstItem->product_id;

                                        $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))
                                            ?->mismatch_note;
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
                                                <?php echo e($firstItem->product?->name ?? '-'); ?>

                                            </span>

                                        </td>


                                        

                                        <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $ngItem = $ngItems->get($code);
                                            ?>

                                            <td class="ng-cell">

                                                <?php if($ngItem): ?>
                                                    <div class="ng-value">
                                                        <?php echo e($ngItem->before_qty > 0 ? $ngItem->before_qty : ''); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div class="ng-value empty-ng"></div>
                                                <?php endif; ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        

                                        <?php if($showAfter): ?>
                                            <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $ngItem = $ngItems->get($code);

                                                    $beforeQty = (int) ($ngItem?->before_qty ?? 0);
                                                    $afterQty = (int) ($ngItem?->after_qty ?? 0);

                                                    $hasBeforeQty = $beforeQty > 0;

                                                    $resultCellClass = '';

                                                    if ($hasFinalResult && ($beforeQty > 0 || $afterQty > 0)) {
                                                        $resultCellClass =
                                                            $beforeQty === $afterQty
                                                                ? 'repair-result-match-cell'
                                                                : 'repair-result-mismatch-cell';
                                                    }
                                                ?>

                                                <td class="ng-cell <?php echo e($resultCellClass); ?>">

                                                    <?php if($isInRepair): ?>
                                                        <?php if($ngItem): ?>
                                                            <input type="number"
                                                                name="items[<?php echo e($ngItem->id); ?>][after_qty]"
                                                                class="ng-input <?php echo e($hasBeforeQty ? 'user-focus' : ''); ?>"
                                                                min="0"
                                                                value="<?php echo e(old('items.' . $ngItem->id . '.after_qty', $ngItem->after_qty ?? '')); ?>"
                                                                placeholder="">
                                                        <?php else: ?>
                                                            <input type="number"
                                                                name="new_items[<?php echo e($productId); ?>][<?php echo e($code); ?>]"
                                                                class="ng-input" min="0"
                                                                value="<?php echo e(old('new_items.' . $productId . '.' . $code, '')); ?>"
                                                                placeholder="">
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="ng-value">
                                                            <?php echo e($afterQty > 0 ? $afterQty : ''); ?>

                                                        </div>
                                                    <?php endif; ?>

                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>


                                        

                                        <td class="keterangan-cell">

                                            <?php if($isInRepair): ?>
                                                <textarea name="product_notes[<?php echo e($productId); ?>]" class="keterangan-input" placeholder="Keterangan..."><?php echo e(old('product_notes.' . $productId, '')); ?></textarea>
                                            <?php elseif($order->status === 'submitted'): ?>
                                                <div class="keterangan-text">
                                                    <?php echo e($productNote ?: $order->description ?: '-'); ?></div>
                                            <?php else: ?>
                                                <div class="keterangan-text"><?php echo e($productNote ?: '-'); ?></div>
                                            <?php endif; ?>

                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>


            </div>


            <?php if($isInRepair): ?>
                <div style="display:flex;justify-content:flex-end;margin-top:12px;">

                    <button type="submit" class="btn btn-primary">
                        Simpan dan Serah Terima Hasil Repair
                    </button>

                </div>

                </form>
            <?php endif; ?>

        </div>

    <?php endif; ?>


    

    <?php if($order->items->isEmpty() && $order->result): ?>
        <div class="repair-card">

            <h3 class="repair-card-title">
                Hasil Repair
            </h3>

            <p class="repair-card-desc">
                Data hasil repair dari format order lama.
            </p>


            <div class="legacy-result-grid">

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

            </div>


            <div class="description-box" style="margin-top:14px;">
                <?php echo e($order->result->notes ?: 'Tidak ada catatan.'); ?>

            </div>

        </div>
    <?php endif; ?>


    

    <?php if($order->status === 'submitted'): ?>
        <div class="repair-card">

            <div class="action-card">

                <div class="action-card-info">

                    <strong>
                        Verifikasi Order
                    </strong>

                    <span>
                        Pastikan data order sudah sesuai.
                        Setelah diverifikasi, order langsung masuk proses repair.
                    </span>

                </div>

                <form method="POST" action="<?php echo e(route('omd.orders.verify', $order)); ?>">

                    <?php echo csrf_field(); ?>

                    <button type="submit" class="btn btn-primary">
                        Verifikasi Order
                    </button>

                </form>

            </div>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/show.blade.php ENDPATH**/ ?>