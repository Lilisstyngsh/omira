<?php $__env->startSection('title', 'Detail Order'); ?>
<?php $__env->startSection('header', 'Detail Order'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-head">
        <div>
            <h2><?php echo e($order->order_number); ?></h2>

            <span class="badge badge-<?php echo e($order->status); ?>">
                <?php echo e($order->status_label); ?>

            </span>
        </div>

        <a class="btn btn-secondary" href="<?php echo e(route('user.orders.index')); ?>">
            Kembali
        </a>
    </div>

    
    <div class="card">
        <div class="detail-grid">

            <div class="detail-item">
                <span>Plant</span>
                <strong>
                    <?php echo e($order->line?->plant?->name ?? '-'); ?>

                </strong>
            </div>

            <div class="detail-item">
                <span>Line</span>
                <strong>
                    <?php echo e($order->line?->name ?? '-'); ?>

                </strong>
            </div>

            <div class="detail-item">
                <span>Jenis Order</span>
                <strong>
                    Repair Box
                </strong>
            </div>

            <div class="detail-item">
                <span>Total Quantity</span>
                <strong>
                    <?php echo e($order->quantity); ?>

                </strong>
            </div>

            <div class="detail-item">
                <span>Tanggal Order</span>
                <strong>
                    <?php echo e($order->created_at?->format('d/m/Y H:i') ?? '-'); ?>

                </strong>
            </div>

            <div class="detail-item">
                <span>Diorder Oleh</span>
                <strong>
                    <?php echo e($order->user?->name ?? '-'); ?>

                </strong>
            </div>

        </div>

        <div style="margin-top:18px">
            <b>Keterangan</b>

            <p class="muted">
                <?php echo e($order->description ?: '-'); ?>

            </p>
        </div>
    </div>

    
    <div class="card" style="margin-top:18px">

        <h3 style="margin-bottom:16px">
            Detail Model, Produk & Jenis NG
        </h3>

        <div style="overflow-x:auto">

            <table style="width:100%;border-collapse:collapse">

                <thead>
                    <tr>
                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                            No
                        </th>

                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                            Model
                        </th>

                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                            Produk
                        </th>

                        <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                            Jenis NG
                        </th>

                        <th style="text-align:right;padding:10px;border-bottom:1px solid #e5e7eb">
                            Qty
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>

                            <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                <?php echo e($index + 1); ?>

                            </td>

                            <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                <?php echo e($item->masterModel?->model ?? '-'); ?>

                            </td>

                            <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                <?php echo e($item->product?->name ?? '-'); ?>

                            </td>

                            <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                <?php if($item->ngType): ?>
                                    <?php echo e($item->ngType->code); ?> -
                                    <?php echo e($item->ngType->name); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td style="padding:10px;text-align:right;border-bottom:1px solid #f1f5f9">
                                <?php echo e($item->before_qty); ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="5" style="padding:30px;text-align:center;color:#9ca3af">
                                Belum ada detail item order.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    
    <?php if($order->items->isNotEmpty()): ?>

        <?php if($order->items->whereNotNull('after_qty')->isNotEmpty()): ?>

            <div class="card" style="margin-top:18px">

                <h3 style="margin-bottom:16px">
                    Hasil Repair
                </h3>

                <div style="overflow-x:auto">

                    <table style="width:100%;border-collapse:collapse">

                        <thead>
                            <tr>

                                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                                    No
                                </th>

                                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Model
                                </th>

                                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Produk
                                </th>

                                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Jenis NG
                                </th>

                                <th style="text-align:right;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Sebelum
                                </th>

                                <th style="text-align:right;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Setelah
                                </th>

                                <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">
                                    Keterangan
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($index + 1); ?>

                                    </td>

                                    <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($item->masterModel?->model ?? '-'); ?>

                                    </td>

                                    <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($item->product?->name ?? '-'); ?>

                                    </td>

                                    <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                        <?php if($item->ngType): ?>
                                            <?php echo e($item->ngType->code); ?> -
                                            <?php echo e($item->ngType->name); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td style="padding:10px;text-align:right;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($item->before_qty); ?>

                                    </td>

                                    <td style="padding:10px;text-align:right;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($item->after_qty ?? '-'); ?>

                                    </td>

                                    <td style="padding:10px;border-bottom:1px solid #f1f5f9">
                                        <?php echo e($item->mismatch_note ?: '-'); ?>

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>

            </div>

        <?php endif; ?>
    <?php elseif($order->result): ?>
        
        <div class="card" style="margin-top:18px">

            <h3>Hasil Repair</h3>

            <div class="detail-grid">

                <div class="detail-item">
                    <span>OK</span>
                    <strong>
                        <?php echo e($order->result->ok_qty); ?>

                    </strong>
                </div>

                <div class="detail-item">
                    <span>SCRAP</span>
                    <strong>
                        <?php echo e($order->result->scrap_qty); ?>

                    </strong>
                </div>

                <div class="detail-item">
                    <span>NG</span>
                    <strong>
                        <?php echo e($order->result->ng_qty); ?>

                    </strong>
                </div>

            </div>

            <p class="muted">
                <?php echo e($order->result->notes ?: 'Tidak ada catatan.'); ?>

            </p>

        </div>
    <?php endif; ?>

    
    <?php if($order->handed_over_at): ?>
        <div class="card" style="margin-top:18px">

            <h3>Serah Terima</h3>

            <div class="detail-grid">

                <div class="detail-item">
                    <span>Diserahterimakan Oleh</span>

                    <strong>
                        <?php echo e($order->handedOverBy?->name ?? '-'); ?>

                    </strong>
                </div>

                <div class="detail-item">
                    <span>Tanggal Serah Terima</span>

                    <strong>
                        <?php echo e($order->handed_over_at->format('d/m/Y H:i')); ?>

                    </strong>
                </div>

            </div>

        </div>
    <?php endif; ?>

    
    <?php if($order->status === 'completed' && $order->handed_over_at): ?>

        <div class="card"
            style="
                margin-top:18px;
                border:1px solid #bbf7d0;
                background:#f0fdf4;
            ">

            <h3 style="margin-bottom:8px">
                Order Siap Dikonfirmasi
            </h3>

            <p class="muted">
                OMD telah menyelesaikan repair dan melakukan serah terima.
                Silakan konfirmasi bahwa hasil repair sudah diterima.
            </p>

            <form method="POST" action="<?php echo e(route('user.orders.confirm', $order)); ?>" style="margin-top:16px">
                <?php echo csrf_field(); ?>

                <button class="btn btn-success">
                    Konfirmasi Hasil / Serah Terima
                </button>
            </form>

        </div>
    <?php elseif($order->status === 'completed'): ?>
        <div class="card"
            style="
                margin-top:18px;
                background:#fffbeb;
                border:1px solid #fde68a;
            ">

            <h3 style="margin-bottom:8px">
                Menunggu Serah Terima
            </h3>

            <p class="muted">
                Repair telah selesai. Saat ini masih menunggu
                serah terima dari OMD.
            </p>

        </div>
    <?php elseif($order->status === 'confirmed'): ?>
        <div class="card"
            style="
                margin-top:18px;
                background:#f0fdf4;
                border:1px solid #bbf7d0;
            ">

            <h3 style="margin-bottom:8px">
                Order Telah Dikonfirmasi
            </h3>

            <p class="muted">
                Order Repair Box telah dikonfirmasi oleh user
                dan proses telah selesai.
            </p>

            <?php if($order->confirmation?->confirmed_at): ?>
                <p class="muted" style="margin-top:8px">
                    Dikonfirmasi pada:
                    <b>
                        <?php echo e($order->confirmation->confirmed_at->format('d/m/Y H:i')); ?>

                    </b>
                </p>
            <?php endif; ?>

        </div>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/show.blade.php ENDPATH**/ ?>