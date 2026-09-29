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

        /* =================================================
                           REPAIR TABLE WRAPPER (scroll area)
                        ================================================== */

        .repair-table-scroll {
            width: 100%;
            max-height: 480px;
            overflow: auto;
            border: 1px solid #000;
            border-radius: 12px;
            background: #fff;
        }

        /* Scrollbar tipis & rapi (Chrome, Edge, Safari) */
        .repair-table-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .repair-table-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .repair-table-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .repair-table-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* =================================================
                           TABLE
                        ================================================== */

        .repair-table {
            width: 100%;
            min-width: 1100px;

            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;

            background: #fff;
        }

        /* =================================================
                           KOLOM
                        ================================================== */

        .repair-table th:nth-child(1),
        .repair-table td:nth-child(1) {
            width: 55px;
        }

        .repair-table th:nth-child(2),
        .repair-table td:nth-child(2) {
            width: 150px;
        }

        .repair-table th:nth-child(3),
        .repair-table td:nth-child(3) {
            width: 170px;
        }

        /* Kolom NG sebelum dan sesudah */
        .repair-table th:nth-child(n+4),
        .repair-table td:nth-child(n+4) {
            width: 68px;
        }

        /* Keterangan */
        .repair-table .keterangan-head {
            width: 180px !important;
            min-width: 180px !important;
        }

        .repair-table .keterangan-cell {
            width: 180px !important;
            min-width: 180px !important;

            text-align: left;
            vertical-align: middle !important;
        }

        /* =================================================
                           HEADER (sticky saat tabel di-scroll)
                        ================================================== */

        .repair-table thead {
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .repair-table th {
            padding: 10px 8px;

            background: #f8fafc;

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

        .repair-table thead tr:nth-child(2) th {
            background: #f1f5f9;
        }

        .repair-table thead tr:nth-child(3) th {
            background: #f8fafc;
        }

        /* garis pemisah tegas antara header (3 baris) dan body saat sticky */
        .repair-table thead tr:last-child th {
            box-shadow: 0 2px 0 #000;
        }

        /* =================================================
                           BODY
                        ================================================== */

        .repair-table td {
            padding: 11px 8px;

            background: #fff;

            border-bottom: 1px solid #000;
            border-right: 1px solid #000;

            font-size: 12px;
            color: #334155;

            vertical-align: middle;
        }

        .repair-table tbody tr:hover td {
            background: #fafbfc;
        }

        /* garis lebih tegas setiap ganti Model (rowspan) */
        .repair-table td.no-cell,
        .repair-table td.model-cell {
            border-right: 1px solid #000;
        }

        /* =================================================
                           NO & MODEL
                        ================================================== */

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

        /* =================================================
                           NILAI NG SEBELUM
                        ================================================== */

        .ng-value {
            min-height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ng-value.before {
            font-size: 12px;
            font-weight: 800;
            color: #334155;
        }

        .empty-ng {
            color: #cbd5e1;

            font-size: 12px;
            font-weight: 700;
        }

        /* =================================================
                           CELL NG
                        ================================================== */

        .ng-cell {
            padding: 6px !important;

            text-align: center;
            vertical-align: middle !important;
        }

        /* =================================================
                           INPUT SESUDAH
                        ================================================== */

        .repair-table .ng-input {
            display: block;

            width: 44px;
            max-width: 100%;

            height: 34px;

            box-sizing: border-box;

            border: 1px solid #64748b;
            border-radius: 8px;

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

        /* =================================================
                           INPUT KETERANGAN
                        ================================================== */

        .repair-table .keterangan-input {
            width: 100%;
            min-height: 40px;

            box-sizing: border-box;

            padding: 8px 10px;

            border: 1px solid #64748b;
            border-radius: 8px;

            background: #fff;

            color: #334155;

            font-size: 11px;
            line-height: 1.5;

            resize: vertical;

            outline: none;

            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .repair-table .keterangan-input:focus {
            border-color: #7c3aed;

            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        /* =================================================
                           ACTION CARD
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

        /* =================================================
                           COMPLETED BOX
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
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
        }

        /* =================================================
                           LEGACY RESULT
                        ================================================== */

        .legacy-result-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
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

            box-shadow:
                0 0 0 2px #fecaca;
        }

        .timeline-item.active .timeline-icon {
            background: #dcfce7;
            color: #16a34a;

            box-shadow:
                0 0 0 2px #bbf7d0;
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
    </style>


    <div class="repair-head">

        <div>

            <h2>
                <?php echo e($order->order_number); ?>

            </h2>
            
        </div>


        <div class="repair-head-actions">

            <a href="<?php echo e(route('omd.orders.index')); ?>" class="btn btn-secondary">
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

                <?php if($order->status === 'in_repair'): ?>
                    Diisi Oleh OMD Setelah Repair
                <?php else: ?>
                    Hasil Repair
                <?php endif; ?>

            </h3>


            <p class="repair-card-desc">

                <?php if($order->status === 'in_repair'): ?>
                    Masukkan hasil repair untuk setiap Produk dan Jenis NG.
                <?php else: ?>
                    Hasil repair yang telah disimpan oleh OMD.
                <?php endif; ?>

            </p>


            <?php if($order->status === 'in_repair'): ?>
                <form method="POST" action="<?php echo e(route('omd.orders.complete', $order)); ?>">

                    <?php echo csrf_field(); ?>
            <?php endif; ?>


            <div class="repair-table-scroll">

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
                                <th><?php echo e($code); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($code); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                    $ngItems = $productItems->keyBy(function ($item) {
                                        return strtoupper($item->ngType?->code ?? '');
                                    });

                                    $productId = $productItems->first()->product_id;
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

                                        <td class="ng-cell">

                                            <?php if($ngItem): ?>
                                                <div class="ng-value before">
                                                    <?php echo e($ngItem->before_qty > 0 ? $ngItem->before_qty : ''); ?>

                                                </div>
                                            <?php else: ?>
                                                <div class="ng-value empty-ng"></div>
                                            <?php endif; ?>

                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    

                                    <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $ngItem = $ngItems->get($code);
                                            $hasBeforeQty = $ngItem && $ngItem->before_qty > 0;
                                        ?>

                                        <td class="ng-cell">

                                            <?php if($order->status === 'in_repair'): ?>
                                                <?php if($hasBeforeQty): ?>
                                                    <input type="number" name="items[<?php echo e($ngItem->id); ?>][after_qty]"
                                                        class="ng-input" min="0"
                                                        value="<?php echo e(old('items.' . $ngItem->id . '.after_qty', '')); ?>"
                                                        placeholder="">
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($hasBeforeQty): ?>
                                                    <div class="ng-value before">
                                                        <?php echo e(($ngItem->after_qty ?? 0) > 0 ? $ngItem->after_qty : ''); ?>

                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    

                                    <td class="keterangan-cell">

                                        <?php if($order->status === 'in_repair'): ?>
                                            <textarea name="product_notes[<?php echo e($productId); ?>]" class="keterangan-input" placeholder="Keterangan..."><?php echo e(old('product_notes.' . $productId, '')); ?></textarea>
                                        <?php else: ?>
                                            <div class="keterangan-text">
                                                -
                                            </div>
                                        <?php endif; ?>

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>

            </div>


            <?php if($order->status === 'in_repair'): ?>
                <div style="display:flex;justify-content:flex-end;margin-top:12px;">

                    <button type="submit" class="btn btn-primary">
                        Simpan Hasil Repair
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


    

    <?php if($order->status === 'completed'): ?>
        <div class="repair-card">

            <div class="completed-box">

                <strong>
                    Repair Selesai
                </strong>

                <span>
                    Hasil repair sudah disimpan dan order menunggu konfirmasi dari User.
                </span>

            </div>

        </div>
    <?php endif; ?>


    

    <?php if($order->status === 'confirmed'): ?>
        <div class="repair-card">

            <div class="completed-box">

                <strong>
                    Order Sudah Dikonfirmasi
                </strong>

                <span>
                    User sudah mengonfirmasi penerimaan hasil repair.
                </span>

            </div>

        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/show.blade.php ENDPATH**/ ?>