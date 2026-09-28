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

        .status-verified {
            background: #eff6ff;
            color: #3478c5;
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

        .order-table-wrap {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #edf1f5;
        }

        .repair-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        .repair-table th {
            padding: 12px 13px;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .repair-table td {
            padding: 12px 13px;
            border-bottom: 1px solid #eef2f6;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: none;
        }

        .repair-table tbody tr:hover {
            background: #fcfcff;
        }

        .model-text {
            font-weight: 800;
            color: #172033;
        }

        .product-text {
            font-weight: 650;
            color: #475569;
        }

        .ng-badge {
            display: inline-flex;
            align-items: center;
            min-width: 34px;
            height: 25px;
            justify-content: center;
            padding: 0 8px;
            border-radius: 7px;
            background: #f5f3ff;
            color: #6557dc;
            font-size: 10px;
            font-weight: 800;
        }

        .qty-input {
            width: 95px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #dce3ec;
            border-radius: 9px;
            outline: none;
            font-size: 12px;
            box-sizing: border-box;
        }

        .qty-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .10);
        }

        .note-input {
            width: 100%;
            min-width: 180px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #dce3ec;
            border-radius: 9px;
            outline: none;
            font-size: 11px;
            box-sizing: border-box;
        }

        .note-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .10);
        }

        .legacy-result-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

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

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            box-shadow: 0 6px 15px rgba(124, 58, 237, .18);
        }

        .btn-primary:hover {
            background: #6d28d9;
            color: #fff;
        }

        .btn-warning {
            background: #fff7e8;
            color: #b77906;
        }

        .btn-warning:hover {
            background: #ffedc2;
        }

        .btn-success {
            background: #ecfdf3;
            color: #15803d;
        }

        .btn-success:hover {
            background: #dcfce7;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .handover-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 15px;
            border: 1px solid #d9eee2;
            border-radius: 12px;
            background: #f2fbf5;
        }

        .handover-box strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
        }

        .handover-box span {
            font-size: 10px;
            color: #4d7a5c;
        }

        .timeline {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #ede9fe;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 19px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -27px;
            top: 2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #c4b5fd;
            box-shadow: 0 0 0 4px #faf5ff;
        }

        .timeline-item.active::before {
            background: #7c3aed;
        }

        .timeline-item strong {
            display: block;
            margin-bottom: 4px;
            color: #334155;
            font-size: 12px;
        }

        .timeline-item span {
            font-size: 10px;
            color: #94a3b8;
        }

        .timeline-item.active span {
            color: #64748b;
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

        .empty-detail {
            padding: 35px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
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

            .info-grid,
            .legacy-result-grid {
                grid-template-columns: 1fr;
            }

            .action-card,
            .handover-box {
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

            <p>
                Order Repair Box
            </p>

        </div>


        <div class="repair-head-actions">

            <span class="status-badge status-<?php echo e($order->status); ?>">
                <?php echo e($order->status_label); ?>

            </span>

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
                    <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : '-'); ?>

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
                    Line
                </span>

                <strong>
                    <?php echo e($order->line?->name ?? ($order->area ? $order->area->name : '-')); ?>

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


            <div class="info-item">

                <span>
                    Verifikator OMD
                </span>

                <strong>
                    <?php echo e($order->omdVerifier?->name ?? '-'); ?>

                </strong>

            </div>

        </div>


        <?php if($order->description): ?>
            <div class="description-box" style="margin-top:14px;">
                <?php echo e($order->description); ?>

            </div>
        <?php endif; ?>

    </div>


    

    <div class="repair-card">

        <h3 class="repair-card-title">
            Detail Order Repair Box
        </h3>

        <div class="order-table-wrap">

            <table class="repair-table">

                <thead>

                    <tr>

                        <th style="width:55px;">
                            No
                        </th>

                        <th>
                            Model
                        </th>

                        <th>
                            Produk
                        </th>

                        <th>
                            Jenis NG
                        </th>

                        <th>
                            Qty Sebelum
                        </th>

                        <th>
                            Qty Sesudah
                        </th>

                        <th>
                            Catatan
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if($order->items->isNotEmpty()): ?>

                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>

                                <td>
                                    <?php echo e($loop->iteration); ?>

                                </td>

                                <td>

                                    <span class="model-text">
                                        <?php echo e($item->masterModel?->model ?? '-'); ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="product-text">
                                        <?php echo e($item->product?->name ?? '-'); ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="ng-badge">
                                        <?php echo e($item->ngType?->code ?? '-'); ?>

                                    </span>

                                </td>

                                <td>
                                    <?php echo e($item->before_qty); ?>

                                </td>

                                <td>
                                    <?php echo e($item->after_qty ?? '-'); ?>

                                </td>

                                <td>
                                    <?php echo e($item->mismatch_note ?? '-'); ?>

                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        

                        <tr>

                            <td>
                                1
                            </td>

                            <td>

                                <span class="model-text">
                                    <?php echo e($order->masterModel?->model ?? ($order->model ?? '-')); ?>

                                </span>

                            </td>

                            <td>

                                <span class="product-text">
                                    <?php echo e($order->product?->name ?? '-'); ?>

                                </span>

                            </td>

                            <td>

                                <span class="ng-badge">
                                    <?php echo e($order->ngType?->code ?? '-'); ?>

                                </span>

                            </td>

                            <td>
                                <?php echo e($order->quantity); ?>

                            </td>

                            <td>
                                -
                            </td>

                            <td>
                                -
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    

    <?php if($order->status === 'in_repair' && $order->items->isNotEmpty()): ?>

        <div class="repair-card">

            <h3 class="repair-card-title">
                Diisi Oleh OMD Setelah Repair
            </h3>

            <p class="repair-card-desc">
                Masukkan hasil repair untuk setiap detail order.
            </p>


            <form method="POST" action="<?php echo e(route('omd.orders.complete', $order)); ?>">

                <?php echo csrf_field(); ?>


                <div class="order-table-wrap">

                    <table class="repair-table">

                        <thead>

                            <tr>

                                <th>
                                    Model
                                </th>

                                <th>
                                    Produk
                                </th>

                                <th>
                                    Jenis NG
                                </th>

                                <th>
                                    Qty Sebelum
                                </th>

                                <th>
                                    Qty Sesudah
                                </th>

                                <th>
                                    Catatan Ketidaksesuaian
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    <td>
                                        <?php echo e($item->masterModel?->model ?? '-'); ?>

                                    </td>

                                    <td>
                                        <?php echo e($item->product?->name ?? '-'); ?>

                                    </td>

                                    <td>

                                        <span class="ng-badge">
                                            <?php echo e($item->ngType?->code ?? '-'); ?>

                                        </span>

                                    </td>

                                    <td>
                                        <?php echo e($item->before_qty); ?>

                                    </td>

                                    <td>

                                        <input type="number" name="items[<?php echo e($item->id); ?>][after_qty]"
                                            class="qty-input" min="0"
                                            value="<?php echo e(old('items.' . $item->id . '.after_qty', $item->after_qty ?? $item->before_qty)); ?>"
                                            required>

                                    </td>

                                    <td>

                                        <input type="text" name="items[<?php echo e($item->id); ?>][mismatch_note]"
                                            class="note-input"
                                            value="<?php echo e(old('items.' . $item->id . '.mismatch_note', $item->mismatch_note)); ?>"
                                            placeholder="Catatan bila ada ketidaksesuaian">

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>


                <div style="margin-top:16px;">

                    <button type="submit" class="btn btn-success">
                        Simpan Hasil Repair
                    </button>

                </div>

            </form>

        </div>

    <?php endif; ?>


    

    <?php if($order->status === 'in_repair' && $order->items->isEmpty()): ?>
        <div class="repair-card">

            <h3 class="repair-card-title">
                Input Hasil Repair
            </h3>

            <p class="repair-card-desc">
                Form kompatibilitas untuk order lama.
            </p>


            <form method="POST" action="<?php echo e(route('omd.orders.complete', $order)); ?>">

                <?php echo csrf_field(); ?>


                <div class="legacy-result-grid">

                    <div>

                        <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                            OK
                        </label>

                        <input type="number" name="ok_qty" min="0" max="<?php echo e($order->quantity); ?>"
                            value="<?php echo e(old('ok_qty', $order->quantity)); ?>"
                            class="qty-input" required>

                    </div>


                    <div>

                        <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                            SCRAP
                        </label>

                        <input type="number" name="scrap_qty" min="0"
                            value="<?php echo e(old('scrap_qty', 0)); ?>"
                            class="qty-input" required>

                    </div>


                    <div>

                        <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                            NG
                        </label>

                        <input type="number" name="ng_qty" min="0"
                            value="<?php echo e(old('ng_qty', 0)); ?>"
                            class="qty-input" required>

                    </div>

                </div>


                <div style="margin-top:16px;">

                    <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                        Catatan
                    </label>

                    <input type="text" name="notes" value="<?php echo e(old('notes')); ?>" class="note-input" style="width:100%;"
                        placeholder="Catatan hasil repair">

                </div>


                <div style="margin-top:16px;">

                    <button type="submit" class="btn btn-success">
                        Simpan Hasil Repair
                    </button>

                </div>

            </form>

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
                        Pastikan data order sudah sesuai sebelum diproses.
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


    <?php if($order->status === 'verified'): ?>
        <div class="repair-card">

            <div class="action-card">

                <div class="action-card-info">

                    <strong>
                        Mulai Repair
                    </strong>

                    <span>
                        Order sudah diverifikasi dan siap dikerjakan.
                    </span>

                </div>


                <form method="POST" action="<?php echo e(route('omd.orders.start', $order)); ?>">

                    <?php echo csrf_field(); ?>

                    <button type="submit" class="btn btn-warning">
                        Mulai Repair
                    </button>

                </form>

            </div>

        </div>
    <?php endif; ?>


    

    <?php if($order->status === 'completed'): ?>

        <div class="repair-card">

            <?php if($order->handed_over_at): ?>
                <div class="handover-box">

                    <div>

                        <strong>
                            Barang sudah diserahterimakan
                        </strong>

                        <span>
                            <?php echo e($order->handed_over_at->format('d-m-Y H:i')); ?>

                            oleh
                            <?php echo e($order->handedOverBy?->name ?? '-'); ?>.
                            Menunggu konfirmasi User.
                        </span>

                    </div>

                </div>
            <?php else: ?>
                <div class="handover-box">

                    <div>

                        <strong>
                            Serah Terima ke User
                        </strong>

                        <span>
                            Pastikan barang repair sudah dikirim kepada User sebelum menekan tombol.
                        </span>

                    </div>


                    <form method="POST" action="<?php echo e(route('omd.orders.handover', $order)); ?>">

                        <?php echo csrf_field(); ?>

                        <button type="submit" class="btn btn-success">
                            Serah Terima ke User
                        </button>

                    </form>

                </div>
            <?php endif; ?>

        </div>

    <?php endif; ?>


    

    <?php if($order->status === 'confirmed'): ?>
        <div class="repair-card">

            <div class="handover-box">

                <div>

                    <strong>
                        Order sudah ditutup
                    </strong>

                    <span>
                        User sudah mengonfirmasi penerimaan barang repair.
                    </span>

                </div>

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


        <div class="timeline">

            <div class="timeline-item active">

                <strong>
                    Submitted
                </strong>

                <span>
                    Order dikirim oleh User.
                    <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : ''); ?>

                </span>

            </div>


            <div
                class="timeline-item
                <?php echo e(in_array($order->status, ['verified', 'in_repair', 'completed', 'confirmed']) ? 'active' : ''); ?>">

                <strong>
                    Verified
                </strong>

                <span>

                    <?php if($order->verified_at): ?>
                        <?php echo e($order->verified_at->format('d-m-Y H:i')); ?>


                        oleh

                        <?php echo e($order->omdVerifier?->name ?? '-'); ?>

                    <?php else: ?>
                        Menunggu verifikasi.
                    <?php endif; ?>

                </span>

            </div>


            <div
                class="timeline-item
                <?php echo e(in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? 'active' : ''); ?>">

                <strong>
                    In Repair
                </strong>

                <span>

                    <?php if($order->repair_started_at): ?>
                        <?php echo e($order->repair_started_at->format('d-m-Y H:i')); ?>

                    <?php else: ?>
                        Menunggu proses repair.
                    <?php endif; ?>

                </span>

            </div>


            <div
                class="timeline-item
                <?php echo e(in_array($order->status, ['completed', 'confirmed']) ? 'active' : ''); ?>">

                <strong>
                    Completed
                </strong>

                <span>

                    <?php if($order->repair_completed_at): ?>
                        <?php echo e($order->repair_completed_at->format('d-m-Y H:i')); ?>

                    <?php else: ?>
                        Menunggu hasil repair.
                    <?php endif; ?>

                </span>

            </div>


            <div class="timeline-item
                <?php echo e($order->handed_over_at ? 'active' : ''); ?>">

                <strong>
                    Serah Terima
                </strong>

                <span>

                    <?php if($order->handed_over_at): ?>
                        <?php echo e($order->handed_over_at->format('d-m-Y H:i')); ?>


                        oleh

                        <?php echo e($order->handedOverBy?->name ?? '-'); ?>

                    <?php else: ?>
                        Menunggu serah terima OMD ke User.
                    <?php endif; ?>

                </span>

            </div>


            <div
                class="timeline-item
                <?php echo e($order->status === 'confirmed' ? 'active' : ''); ?>">

                <strong>
                    Confirmed
                </strong>

                <span>

                    <?php if($order->status === 'confirmed' && $order->confirmation?->confirmed_at): ?>
                        <?php echo e($order->confirmation->confirmed_at->format('d-m-Y H:i')); ?>


                        oleh

                        <?php echo e($order->confirmation->confirmedByUser?->name ?? '-'); ?>

                    <?php else: ?>
                        Menunggu konfirmasi User.
                    <?php endif; ?>

                </span>

            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/show.blade.php ENDPATH**/ ?>