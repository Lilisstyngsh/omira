<?php
    $activityTitle = match ($order->status) {
        'submitted' => 'Verifikasi Order Repair Box',
        'in_repair' => 'Input Hasil Repair OMD',
        'completed' => 'Hasil Repair OMD — Menunggu Konfirmasi User',
        'revision_requested' => 'Koreksi Hasil Repair OMD',
        'confirmed' => 'Hasil Repair & Serah Terima',
        default => 'Detail Order Repair Box',
    };
?>

<?php $__env->startSection('title', $activityTitle); ?>
<?php $__env->startSection('header', $activityTitle); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $hasOmdResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);

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
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 760px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 760px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 760px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
        }

        .repair-table th {
            padding: 9px 5px;
            background: #fafbfc;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
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
            padding: 5px 5px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
            background: #fff;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: 1px solid #cbd5e1;
        }

        .repair-table tbody tr:hover td {
            background: #fafbfc;
        }

        .repair-table td.no-cell,
        .repair-table td.model-cell {
            border-right: 1px solid #cbd5e1;
        }

        .repair-table .model-cell,
        .repair-table .no-cell {
            font-weight: 500;
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
            font-weight: 500;
            color: #172033;
        }

        .product-text {
            font-weight: 400;
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
            font-weight: 500;
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
            height: 36px;
            box-sizing: border-box;
            border: 2px solid #64748b;
            border-radius: 9px;
            outline: none;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            background: #fff;
            padding: 0 3px;
            margin: 0 auto;
            transition: border-color .15s ease, box-shadow .15s ease;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .repair-table .ng-input::-webkit-outer-spin-button,
        .repair-table .ng-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .repair-table .ng-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        .repair-table .ng-input::placeholder {
            color: #cbd5e1;
        }

        .repair-qty-control {
            display: grid;
            grid-template-columns: 28px 44px 28px;
            align-items: center;
            justify-content: center;
            gap: 2px;
            width: 104px;
            margin: 0 auto;
        }

        .repair-qty-control .ng-input {
            width: 44px;
            height: 36px;
            border-width: 2px;
            border-radius: 9px;
            font-size: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .05);
        }

        .repair-qty-step {
            width: 28px;
            height: 38px;
            border: 0;
            padding: 0;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: 500;
            line-height: 1;
            cursor: pointer;
            touch-action: none;
            user-select: none;
        }

        .repair-qty-step[data-step="-1"] { color: #b91c1c; }
        .repair-qty-step[data-step="1"] { color: #15803d; }
        .repair-qty-step:active { transform: scale(.94); opacity: .75; }
        .repair-qty-step:disabled { opacity: .35; cursor: not-allowed; }

        .repair-table .model-text { font-weight: 700; color: #0f172a; }

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
            width: 135px !important;
            min-width: 135px !important;
            text-align: center !important;
        }

        .repair-table .keterangan-cell {
            width: 135px !important;
            min-width: 135px !important;
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

        .repair-table .total-row td {
            background: #fff;
            color: #334155;
            font-weight: 550;
            border-top: 1px solid #dbe2ea;
        }

        .repair-table .grand-total-row td {
            background: #fff;
            color: #334155;
            font-weight: 600;
            border-top: 1px solid #dbe2ea;
        }

        .repair-table .total-label {
            text-align: right;
            white-space: nowrap;
            font-size: 10px;
            letter-spacing: .02em;
        }

        .repair-table .total-value {
            text-align: center;
            font-weight: 550;
        }


        .repair-table-unified {
            min-width: 620px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }

        .repair-table-unified th,
        .repair-table-unified td {
            border-color: #dbe2ea;
        }

        .repair-table-unified th:last-child,
        .repair-table-unified td:last-child {
            border-right: 0;
        }

        .repair-table-unified tfoot td {
            border-bottom: 1px solid #dbe2ea;
        }

        .repair-table-editable {
            min-width: 1040px;
        }

        .repair-table .grand-total-value {
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: .01em;
        }

        .repair-result-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        .correction-actions {
            display: flex;
            gap: 9px;
        }

        .correction-actions[hidden],
        .correction-banner[hidden] {
            display: none !important;
        }

        .correction-banner {
            margin: 10px 0 12px;
            padding: 9px 11px;
            border: 1px solid #f0c36b;
            border-radius: 9px;
            background: #fffaf0;
            color: #8a5a13;
        }

        .correction-banner strong {
            display: block;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .correction-banner span {
            display: block;
            font-size: 10px;
            line-height: 1.4;
        }

        .correction-editor {
            display: none !important;
        }

        .correction-mode .correction-editor {
            display: grid !important;
        }

        .repair-result-actions [hidden] {
            display: none !important;
        }

        .correction-input {
            display: none !important;
        }

        .input-active {
            display: block !important;
        }

        .correction-mode .correction-readonly {
            display: none !important;
        }

        .correction-mode .correction-input {
            display: block !important;
        }

        .correction-mode .ng-input,
        .correction-mode .keterangan-input {
            background: #fffdfa;
            border-color: #d89a37;
            box-shadow: 0 0 0 2px rgba(216, 154, 55, .08);
        }

        .correction-mode .repair-result-match-cell,
        .correction-mode .repair-result-mismatch-cell {
            background: #fffdfa !important;
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
            background: #f1f5f9;
            color: #475569;
            box-shadow: inset 0 0 0 1px #dbe3ec;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-verify {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 6px 15px rgba(37, 99, 235, .18);
        }

        .btn-verify:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
            box-shadow: 0 6px 15px rgba(22, 163, 74, .18);
        }

        .btn-success:hover {
            background: #15803d;
            color: #fff;
        }

        .btn-warning {
            background: #d97706;
            color: #fff;
            box-shadow: 0 6px 15px rgba(217, 119, 6, .18);
        }

        .btn-warning:hover {
            background: #b45309;
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
            <a href="<?php echo e(route('omd.orders.index')); ?>" class="btn btn-back" title="Kembali ke daftar Order Repair Box">
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


    <?php echo $__env->make('partials.repair-order-information', ['order' => $order, 'context' => 'omd-show'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($order->status === 'revision_requested' && $order->openFeedback): ?>
        <div style="margin-bottom:14px;border:1px solid #f59e0b;border-radius:10px;padding:11px 13px;background:#fff;color:#92400e;font-size:12px;line-height:1.5">
            <strong style="display:block;margin-bottom:3px">⚠ Feedback User</strong>
            <?php echo e($order->openFeedback->reason); ?>

            <div style="margin-top:4px;font-size:10px;color:#a16207"><?php echo e($order->openFeedback->created_at?->format('d-m-Y H:i')); ?></div>
        </div>
    <?php endif; ?>

    

    <?php if($order->items->isNotEmpty()): ?>

        <?php
            $modelGroups = $order->items->groupBy('master_model_id');
            $ngCodes = ['P', 'H', 'C', 'S'];

            $isInRepair = $order->status === 'in_repair';
            $isCorrection = $order->status === 'revision_requested';
            $canEditResult = in_array($order->status, ['in_repair', 'revision_requested'], true);
            $showAfter = $order->status !== 'submitted';
            $showProductNote = $order->status !== 'submitted';
            $hasFinalResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);

            $beforeTotals = collect($ngCodes)->mapWithKeys(fn($code) => [
                $code => (int) $order->items->filter(
                    fn($item) => strtoupper($item->ngType?->code ?? '') === $code
                )->sum('before_qty'),
            ]);

            $afterTotals = collect($ngCodes)->mapWithKeys(fn($code) => [
                $code => (int) $order->items->filter(
                    fn($item) => strtoupper($item->ngType?->code ?? '') === $code
                )->sum('after_qty'),
            ]);

            $beforeGrandTotal = (int) $beforeTotals->sum();
            $afterGrandTotal = (int) $afterTotals->sum();
        ?>

        <div class="repair-card" id="repairResultCard">

            <h3 class="repair-card-title">
                <?php switch($order->status):
                    case ('submitted'): ?>
                        Detail Order Repair Box
                    <?php break; ?>
                    <?php case ('in_repair'): ?>
                        Input Hasil Repair OMD
                    <?php break; ?>
                    <?php case ('completed'): ?>
                        Hasil Repair OMD — Menunggu Konfirmasi User
                    <?php break; ?>
                    <?php case ('revision_requested'): ?>
                        Koreksi Hasil Repair OMD
                    <?php break; ?>
                    <?php case ('confirmed'): ?>
                        Hasil Repair &amp; Serah Terima
                    <?php break; ?>
                    <?php default: ?>
                        Detail Order Repair Box
                <?php endswitch; ?>
            </h3>

            <p class="repair-card-desc">
                <?php if($isInRepair): ?>
                    Masukkan hasil repair untuk setiap Produk dan Jenis NG, lalu submit hasil repair.
                <?php elseif($order->status === 'submitted'): ?>
                    Periksa Model, Produk, dan qty NG sebelum melakukan verifikasi order.
                <?php elseif($order->status === 'completed'): ?>
                    Hasil repair sudah dikirim ke User dan sedang menunggu pengecekan barang serta konfirmasi serah terima.
                <?php elseif($order->status === 'revision_requested'): ?>
                    User menyatakan barang tidak sesuai dengan data hasil repair. Aktifkan mode koreksi, perbaiki data, lalu kirim ulang hasil repair ke User.
                <?php elseif($order->status === 'confirmed'): ?>
                    Hasil repair telah diverifikasi User dan proses serah terima selesai.
                <?php endif; ?>
            </p>

            <?php if($isCorrection): ?>
                <div class="correction-banner" id="correctionBanner" hidden>
                    <strong>⚠ Mode Koreksi</strong>
                    <span>Periksa qty sebelum menyimpan perubahan.</span>
                </div>
            <?php endif; ?>

            <?php if($canEditResult): ?>
                <form method="POST" action="<?php echo e(route('omd.orders.complete', $order)); ?>" id="repairResultForm">
                    <?php echo csrf_field(); ?>
            <?php endif; ?>

            <div class="repair-table-scroll">
                <table class="repair-table repair-table-unified <?php echo e($canEditResult && $showAfter ? 'repair-table-editable' : ''); ?>">
                    <colgroup>
                        <col style="width:38px;">
                        <col style="width:88px;">
                        <col style="width:120px;">
                        <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <col style="width:44px;">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($showAfter): ?>
                            <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <col style="width:<?php echo e($canEditResult ? '104px' : '44px'); ?>;">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <?php if($showProductNote): ?>
                            <col style="width:<?php echo e($canEditResult ? '150px' : '128px'); ?>;">
                        <?php endif; ?>
                    </colgroup>

                    <thead>
                        <tr>
                            <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>">No</th>
                            <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>">Model</th>
                            <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>">Produk</th>
                            <th colspan="<?php echo e($showAfter ? 8 : 4); ?>">Qty NG</th>
                            <?php if($showProductNote): ?>
                                <th rowspan="<?php echo e($showAfter ? 3 : 2); ?>" class="keterangan-head">Catatan</th>
                            <?php endif; ?>
                        </tr>

                        <?php if($showAfter): ?>
                            <tr>
                                <th colspan="4">Order</th>
                                <th colspan="4">Hasil</th>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($code); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($showAfter): ?>
                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><?php echo e($code); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $modelGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $productGroups = $modelItems->groupBy('product_id');
                                $modelRowspan = $productGroups->count();
                            ?>

                            <?php $__currentLoopData = $productGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $ngItems = $productItems->keyBy(fn($item) => strtoupper($item->ngType?->code ?? ''));
                                    $firstItem = $productItems->first();
                                    $productId = $firstItem->product_id;
                                    $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))?->mismatch_note;
                                ?>

                                <tr>
                                    <?php if($loop->first): ?>
                                        <td rowspan="<?php echo e($modelRowspan); ?>" class="no-cell"><?php echo e($loop->parent->iteration); ?></td>
                                        <td rowspan="<?php echo e($modelRowspan); ?>" class="model-cell">
                                            <span class="model-text"><?php echo e($modelItems->first()->masterModel?->model ?? '-'); ?></span>
                                        </td>
                                    <?php endif; ?>

                                    <td><span class="product-text"><?php echo e($firstItem->product?->name ?? '-'); ?></span></td>

                                    <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $ngItem = $ngItems->get($code); ?>
                                        <td class="ng-cell">
                                            <div class="ng-value"><?php echo e(((int) ($ngItem?->before_qty ?? 0)) > 0 ? (int) $ngItem->before_qty : ''); ?></div>
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
                                                    $resultCellClass = $beforeQty === $afterQty
                                                        ? 'repair-result-match-cell'
                                                        : 'repair-result-mismatch-cell';
                                                }
                                            ?>
                                            <td class="ng-cell result-edit-cell <?php echo e($resultCellClass); ?>">
                                                <?php if($canEditResult): ?>
                                                    <?php if($isCorrection): ?>
                                                        <div class="correction-readonly ng-value"><?php echo e($afterQty > 0 ? $afterQty : ''); ?></div>
                                                    <?php endif; ?>
                                                    <?php $qtyInputId = $ngItem ? 'after_qty_' . $ngItem->id : 'new_after_' . $productId . '_' . $code; ?>
                                                    <div class="repair-qty-control <?php echo e($isCorrection ? 'correction-editor' : ''); ?>">
                                                        <button type="button" class="repair-qty-step correction-step" data-step="-1" data-target="<?php echo e($qtyInputId); ?>" aria-label="Kurangi qty <?php echo e($code); ?>" <?php if($isCorrection): echo 'disabled'; endif; ?>>−</button>
                                                        <?php if($ngItem): ?>
                                                            <input type="number"
                                                                id="<?php echo e($qtyInputId); ?>"
                                                                name="items[<?php echo e($ngItem->id); ?>][after_qty]"
                                                                class="ng-input repair-after-input correction-input <?php echo e($isInRepair ? 'input-active' : ''); ?> <?php echo e($isInRepair && $hasBeforeQty ? 'user-focus' : ''); ?>"
                                                                data-ng-code="<?php echo e($code); ?>" min="0" inputmode="numeric"
                                                                value="<?php echo e(old('items.' . $ngItem->id . '.after_qty', ((int) ($ngItem->after_qty ?? 0)) > 0 ? $ngItem->after_qty : '')); ?>"
                                                                <?php if($isCorrection): echo 'disabled'; endif; ?>>
                                                        <?php else: ?>
                                                            <input type="number"
                                                                id="<?php echo e($qtyInputId); ?>"
                                                                name="new_items[<?php echo e($productId); ?>][<?php echo e($code); ?>]"
                                                                class="ng-input repair-after-input correction-input <?php echo e($isInRepair ? 'input-active' : ''); ?>"
                                                                data-ng-code="<?php echo e($code); ?>" min="0" inputmode="numeric"
                                                                value="<?php echo e(old('new_items.' . $productId . '.' . $code, '')); ?>"
                                                                <?php if($isCorrection): echo 'disabled'; endif; ?>>
                                                        <?php endif; ?>
                                                        <button type="button" class="repair-qty-step correction-step" data-step="1" data-target="<?php echo e($qtyInputId); ?>" aria-label="Tambah qty <?php echo e($code); ?>" <?php if($isCorrection): echo 'disabled'; endif; ?>>+</button>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="ng-value"><?php echo e($afterQty > 0 ? $afterQty : ''); ?></div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                    <?php if($showProductNote): ?>
                                        <td class="keterangan-cell">
                                            <?php if($canEditResult): ?>
                                                <?php if($isCorrection): ?>
                                                    <div class="correction-readonly keterangan-text"><?php echo e($productNote ?: '-'); ?></div>
                                                <?php endif; ?>
                                                <textarea name="product_notes[<?php echo e($productId); ?>]"
                                                    class="keterangan-input correction-input <?php echo e($isInRepair ? 'input-active' : ''); ?>"
                                                    placeholder="Keterangan hasil repair..."
                                                    <?php if($isCorrection): echo 'disabled'; endif; ?>><?php echo e(old('product_notes.' . $productId, $productNote ?? '')); ?></textarea>
                                            <?php else: ?>
                                                <div class="keterangan-text"><?php echo e($productNote ?: '-'); ?></div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" class="total-label">Total</td>
                            <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="total-value"><?php echo e($beforeTotals[$code] > 0 ? $beforeTotals[$code] : ''); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($showAfter): ?>
                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="total-value" data-total-after="<?php echo e($code); ?>"><?php echo e($afterTotals[$code] > 0 ? $afterTotals[$code] : ''); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <?php if($showProductNote): ?>
                                <td></td>
                            <?php endif; ?>
                        </tr>

                        <tr class="grand-total-row">
                            <td colspan="3" class="total-label">Grand Total</td>
                            <td colspan="4" class="grand-total-value">
                                <?php echo e($beforeGrandTotal > 0 ? $beforeGrandTotal . ' NG' : ''); ?>

                            </td>
                            <?php if($showAfter): ?>
                                <td colspan="4" class="grand-total-value" data-grand-after>
                                    <?php echo e($afterGrandTotal > 0 ? $afterGrandTotal . ' NG' : ''); ?>

                                </td>
                            <?php endif; ?>
                            <?php if($showProductNote): ?>
                                <td></td>
                            <?php endif; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <?php if($canEditResult): ?>
                <div class="repair-result-actions">
                    <?php if($isCorrection): ?>
                        <button type="button" class="btn btn-warning" id="enableCorrectionButton"
                            title="Aktifkan mode koreksi hasil repair">
                            Edit Hasil Repair
                        </button>

                        <div class="correction-actions" id="correctionActions" hidden>
                            <button type="button" class="btn btn-secondary" id="cancelCorrectionButton" title="Batalkan perubahan dan kembali ke data terakhir">Batal</button>
                            <button type="submit" class="btn btn-warning"
                                title="Simpan koreksi hasil repair agar User melihat data terbaru">
                                Update
                            </button>
                        </div>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success"
                            title="Simpan hasil repair dan lanjutkan ke pengecekan serah terima oleh User">
                            Simpan &amp; Serah Terima Hasil Repair
                        </button>
                    <?php endif; ?>
                </div>
                </form>
            <?php endif; ?>

        </div>

        <?php if($canEditResult): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const card = document.getElementById('repairResultCard');
                    const inputs = Array.from(document.querySelectorAll('.repair-after-input'));
                    const correctionInputs = Array.from(document.querySelectorAll('.correction-input'));
                    const correctionSteps = Array.from(document.querySelectorAll('.correction-step'));
                    const enableButton = document.getElementById('enableCorrectionButton');
                    const cancelButton = document.getElementById('cancelCorrectionButton');
                    const correctionActions = document.getElementById('correctionActions');
                    const correctionBanner = document.getElementById('correctionBanner');

                    function numberValue(input) {
                        const value = parseInt(input.value || '0', 10);
                        return Number.isFinite(value) ? Math.max(value, 0) : 0;
                    }

                    function refreshTotals() {
                        const totals = { P: 0, H: 0, C: 0, S: 0 };

                        inputs.forEach(function (input) {
                            const code = input.dataset.ngCode;
                            if (code && Object.prototype.hasOwnProperty.call(totals, code)) {
                                totals[code] += numberValue(input);
                            }
                        });

                        let grand = 0;

                        Object.entries(totals).forEach(function ([code, value]) {
                            grand += value;
                            const cell = document.querySelector('[data-total-after="' + code + '"]');
                            if (cell) cell.textContent = value > 0 ? value : '';
                        });

                        const grandCell = document.querySelector('[data-grand-after]');
                        if (grandCell) grandCell.textContent = grand > 0 ? grand + ' NG' : '';
                    }

                    inputs.forEach(function (input) {
                        input.addEventListener('input', refreshTotals);
                    });

                    function changeRepairQty(button) {
                        if (button.disabled) return;
                        const input = document.getElementById(button.dataset.target);
                        if (!input || input.disabled) return;
                        const step = parseInt(button.dataset.step || '0', 10);
                        const current = numberValue(input);
                        const next = Math.max(0, current + step);
                        input.value = next > 0 ? String(next) : '';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    correctionSteps.forEach(function (button) {
                        let holdTimer = null;
                        let repeatTimer = null;
                        const stop = function () {
                            if (holdTimer) clearTimeout(holdTimer);
                            if (repeatTimer) clearInterval(repeatTimer);
                            holdTimer = null; repeatTimer = null;
                        };
                        button.addEventListener('pointerdown', function (event) {
                            event.preventDefault();
                            if (button.disabled) return;
                            changeRepairQty(button);
                            holdTimer = setTimeout(function () {
                                repeatTimer = setInterval(function () { changeRepairQty(button); }, 120);
                            }, 500);
                        });
                        ['pointerup','pointercancel','pointerleave'].forEach(function (name) { button.addEventListener(name, stop); });
                    });

                    if (enableButton) {
                        enableButton.addEventListener('click', function () {
                            card.classList.add('correction-mode');
                            correctionInputs.forEach(function (input) { input.disabled = false; });
                            correctionSteps.forEach(function (button) { button.disabled = false; });
                            enableButton.hidden = true;
                            correctionActions.hidden = false;
                            correctionBanner.hidden = false;
                            const firstInput = correctionInputs.find(function (input) { return input.tagName === 'INPUT'; });
                            if (firstInput) firstInput.focus();
                        });
                    }

                    if (cancelButton) {
                        cancelButton.addEventListener('click', function () {
                            window.location.reload();
                        });
                    }

                    refreshTotals();
                });
            </script>
        <?php endif; ?>
    <?php endif; ?>


    

    <?php if($order->items->isEmpty() && $order->result): ?>
        <div class="repair-card">

            <h3 class="repair-card-title">
                Ringkasan Hasil Repair OMD
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

                    <button type="submit" class="btn btn-verify" title="Verifikasi order dan mulai proses repair">
                        Verifikasi Order
                    </button>

                </form>

            </div>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/show.blade.php ENDPATH**/ ?>