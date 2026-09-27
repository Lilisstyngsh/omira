<?php $__env->startSection('header'); ?>
Data Master Model & Produk
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* =====================================================
       PAGE
    ===================================================== */

    .page-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-heading h4 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 800;
    }

    .page-heading p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }


    /* =====================================================
       LINE DASHBOARD
    ===================================================== */

    .line-dashboard {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        max-height: 250px;
        overflow-y: auto;
        padding: 5px 8px 5px 5px;
    }

    .line-dashboard::-webkit-scrollbar {
        width: 7px;
    }

    .line-dashboard::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .line-dashboard::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .line-dashboard::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .line-card {
        background: #ffffff;
        border: 2px solid transparent;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 7px 20px rgba(0, 0, 0, .06);
        cursor: pointer;
        transition: .22s ease;
    }

    .line-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, .09);
    }

    .line-card.active {
        border-color: #7c3aed;
        background: #faf5ff;
        box-shadow: 0 8px 22px rgba(124, 58, 237, .10);
    }

    .line-card-top {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .line-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #ede9fe;
        font-size: 22px;
    }

    .line-info h4 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
    }

    .line-info span {
        display: block;
        font-size: 10px;
        color: #64748b;
    }


    /* =====================================================
       LINE STATS
    ===================================================== */

    .line-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 14px;
    }

    .stat-box {
        padding: 8px 10px;
        border-radius: 9px;
        background: #f8fafc;
    }

    .stat-box strong {
        display: block;
        font-size: 16px;
        font-weight: 800;
        color: #334155;
    }

    .stat-box span {
        display: block;
        margin-top: 1px;
        font-size: 9px;
        color: #94a3b8;
    }


    /* =====================================================
       TABLE HEADER
    ===================================================== */

    .table-header {
        margin-bottom: 18px;
    }

    .table-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 14px;
    }

    .table-title h4 {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
    }

    .table-title p {
        margin: 0;
        font-size: 11px;
        color: #64748b;
    }


    /* =====================================================
       SEARCH FULL WIDTH
    ===================================================== */

    .search-box {
        width: 100%;
        height: 40px;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 0 12px;
        border: 1px solid #dfe4ea;
        border-radius: 10px;
        background: #ffffff;
        box-sizing: border-box;
    }

    .search-box span {
        font-size: 18px;
        color: #94a3b8;
    }

    .search-box input {
        width: 100%;
        height: 100%;
        border: 0;
        outline: none;
        background: transparent;
        font-size: 12px;
        color: #334155;
    }

    .search-box input::placeholder {
        color: #a1aab5;
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .table-responsive {
        border-radius: 14px;
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        min-width: 930px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead {
        background: #f8fafc;
    }

    .modern-table th {
        padding: 12px 15px;
        border-bottom: 1px solid #e8ecf0;
        font-size: 10px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .modern-table td {
        padding: 14px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #eef1f4;
    }

    .modern-table tbody tr {
        transition: .15s ease;
    }

    .modern-table tbody tr:hover {
        background: #fcfcfd;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .number-cell {
        text-align: center;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
    }


    /* =====================================================
       MODEL
    ===================================================== */

    .model-info strong {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
    }

    .model-info span {
        font-size: 10px;
        color: #94a3b8;
    }


    /* =====================================================
       PRODUCT
    ===================================================== */

    .product-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
        max-width: 430px;
    }

    .product-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 7px 9px;
        border: 1px solid #edf0f3;
        border-radius: 8px;
        background: #f8fafc;
    }

    .product-name {
        min-width: 0;
        font-size: 11px;
        font-weight: 600;
        color: #475569;
        word-break: break-word;
    }

    .product-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }

    .product-actions form {
        margin: 0;
    }

    .mini-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25px;
        height: 25px;
        padding: 0;
        border: 0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 11px;
    }

    .mini-action.edit {
        background: #fff7e6;
        color: #b7791f;
    }

    .mini-action.delete {
        background: #fff0f0;
        color: #c24141;
    }

    .no-product {
        display: inline-block;
        padding: 7px 9px;
        border-radius: 8px;
        background: #f8fafc;
        color: #94a3b8;
        font-size: 10px;
        font-style: italic;
    }


    /* =====================================================
       STATUS
    ===================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
    }

    .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }

    .status-badge.active {
        color: #15803d;
        background: #ecfdf3;
    }

    .status-badge.active .status-dot {
        background: #22c55e;
    }

    .status-badge.inactive {
        color: #64748b;
        background: #f1f5f9;
    }

    .status-badge.inactive .status-dot {
        background: #94a3b8;
    }


    /* =====================================================
       ACTION
    ===================================================== */

    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-group form {
        margin: 0;
    }

    .action-group .btn {
        min-width: 54px;
        height: 34px;
        padding: 0 9px;
        border-radius: 9px;
        font-size: 10px;
        font-weight: 700;
    }


    /* =====================================================
       BUTTON
    ===================================================== */

    .btn {
        border-radius: 10px;
        font-weight: 700;
    }

    .btn-primary {
        background: #7c3aed;
        border-color: #7c3aed;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #6d28d9;
        border-color: #6d28d9;
        color: #ffffff;
    }

    .btn-success {
        background: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    .btn-warning {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #ffffff;
    }

    .btn-danger {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
    }

    .btn-secondary {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #475569;
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .empty-table {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 55px 20px;
        text-align: center;
    }

    .empty-table-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin-bottom: 10px;
        border-radius: 14px;
        background: #ede9fe;
        font-size: 22px;
    }

    .empty-table strong {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #334155;
    }

    .empty-table span {
        display: block;
        margin-bottom: 10px;
        font-size: 11px;
        color: #94a3b8;
    }


    /* =====================================================
       MODAL
    ===================================================== */

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .45);
        z-index: 9999;
    }

    .modal-box {
        width: 100%;
        max-width: 410px;
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .16);
    }

    .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
    }

    .modal-header h4 {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 800;
    }

    .modal-header p {
        margin: 0;
        font-size: 11px;
        color: #64748b;
    }

    .modal-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        padding: 0;
        border: 0;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 18px;
        cursor: pointer;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .form-control {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .selected-line-box {
        padding: 11px 12px;
        margin-bottom: 17px;
        border-radius: 10px;
        background: #faf5ff;
        border: 1px solid #ede9fe;
    }

    .selected-line-box span {
        display: block;
        margin-bottom: 3px;
        font-size: 9px;
        font-weight: 700;
        color: #8b5cf6;
        text-transform: uppercase;
    }

    .selected-line-box strong {
        font-size: 12px;
        color: #4c1d95;
    }

    .modal-action {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 20px;
    }


    /* =====================================================
       SWEETALERT - SAMA SEPERTI PLANT
    ===================================================== */

    .swal-plant-popup {
        width: 320px !important;
        padding: 20px !important;
        border-radius: 18px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .18) !important;
    }

    .swal-plant-popup .swal2-icon {
        width: 55px !important;
        height: 55px !important;
        margin: 0 auto 12px !important;
    }

    .swal-plant-popup .swal2-icon.swal2-success .swal2-success-ring {
        border-color: #a8df8f !important;
    }

    .swal-plant-popup .swal2-icon.swal2-success .swal2-success-line {
        background-color: #8fd16f !important;
    }

    .swal-plant-popup .swal2-icon.swal2-success .swal2-success-circular-line-left,
    .swal-plant-popup .swal2-icon.swal2-success .swal2-success-circular-line-right,
    .swal-plant-popup .swal2-icon.swal2-success .swal2-success-fix {
        display: none !important;
    }

    .swal-plant-title {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #172033 !important;
    }

    .swal-plant-text {
        font-size: 12px !important;
        color: #64748b !important;
    }

    .swal-plant-popup .swal2-actions {
        gap: 10px !important;
        margin-top: 18px !important;
    }

    .swal-plant-confirm {
        background: #dc5b68 !important;
        color: white !important;
        border: none !important;
        border-radius: 9px !important;
        height: 36px !important;
        padding: 0 18px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
    }

    .swal-plant-cancel {
        background: #f1f5f9 !important;
        color: #475569 !important;
        border: none !important;
        border-radius: 9px !important;
        height: 36px !important;
        padding: 0 18px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1000px) {

        .line-dashboard {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 700px) {

        .page-heading,
        .table-header-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-heading .btn,
        .table-header-top .btn {
            width: 100%;
        }

        .line-dashboard {
            grid-template-columns: 1fr;
            max-height: 300px;
        }

    }
</style>




<div class="card">
    <div class="card-body">

        <div class="page-heading">

        </div>


        <div class="line-dashboard">

            <?php $__empty_1 = true; $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php
            $lineModels = $models->where('line_id', $line->id);

            $modelCount = $lineModels->count();

            $productCount = $lineModels->sum(function ($model) {
            return $model->products->count();
            });
            ?>

            <div class="line-card"
                data-line-id="<?php echo e($line->id); ?>"
                data-line-name="<?php echo e($line->name); ?>"
                onclick="loadModel(<?php echo e($line->id); ?>, this)">

                <div class="line-card-top">

                    <div class="line-icon">
                        🏭
                    </div>

                    <div class="line-info">

                        <h4>
                            <?php echo e($line->name); ?>

                        </h4>

                        <span>
                            <?php echo e($line->plant->name ?? '-'); ?>

                        </span>

                    </div>

                </div>


                <div class="line-stats">

                    <div class="stat-box">
                        <strong>
                            <?php echo e($modelCount); ?>

                        </strong>

                        <span>
                            Model
                        </span>
                    </div>

                    <div class="stat-box">
                        <strong>
                            <?php echo e($productCount); ?>

                        </strong>

                        <span>
                            Produk
                        </span>
                    </div>

                </div>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="empty-table">

                <div class="empty-table-icon">
                    🏭
                </div>

                <strong>
                    Belum ada Line
                </strong>

                <span>
                    Tambahkan Line terlebih dahulu.
                </span>

            </div>

            <?php endif; ?>

        </div>

    </div>
</div>




<div class="card mt-4">

    <div class="card-body">

        <div class="table-header">

            <div class="table-header-top">

                <div class="table-title">

                    <h4 id="lineTitle">
                        <?php echo e($selectedLineId
                            ? optional($lines->firstWhere('id', $selectedLineId))->name
                            : ($lines->first()?->name ?? 'Pilih Line')); ?>

                    </h4>

                    <p id="lineSubtitle">
                        <?php echo e($selectedLineId
                            ? optional(optional($lines->firstWhere('id', $selectedLineId))->plant)->name
                            : ($lines->first()?->plant->name ?? 'Belum ada Line')); ?>

                    </p>

                </div>

                <button class="btn btn-primary"
                    onclick="openAddModelModal()">
                    + Tambah Model
                </button>

            </div>


            <div class="search-box">

                <span>⌕</span>

                <input type="text"
                    id="searchModel"
                    placeholder="Cari model atau produk...">

            </div>

        </div>


        <div class="table-responsive">

            <table class="table modern-table"
                id="modelTable">

                <thead>

                    <tr>

                        <th width="70">
                            No
                        </th>

                        <th>
                            Model
                        </th>

                        <th>
                            Produk
                        </th>

                        <th width="190">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr class="model-row"
                        data-line-id="<?php echo e($model->line_id); ?>">

                        <td class="number-cell">
                            <?php echo e($index + 1); ?>

                        </td>


                        <td>

                            <div class="model-info">

                                <strong>
                                    <?php echo e($model->model); ?>

                                </strong>

                            </div>

                        </td>


                        <td>

                            <div class="product-container">

                                <?php $__empty_2 = true; $__currentLoopData = $model->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

                                <div class="product-item">

                                    <span class="product-name">
                                        <?php echo e($product->name); ?>

                                    </span>

                                    <div class="product-actions">

                                        <button type="button"
                                            class="mini-action edit"
                                            title="Edit Produk"
                                            onclick='openEditProductModal(
                                                        <?php echo e($product->id); ?>,
                                                        <?php echo e($product->master_model_id); ?>,
                                                        <?php echo json_encode($product->name, 15, 512) ?>
                                                    )'>
                                            ✎
                                        </button>


                                        <form method="POST"
                                            action="<?php echo e(route('omd.master.product.destroy', $product->id)); ?>"
                                            class="delete-product-form">

                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <button type="submit"
                                                class="mini-action delete"
                                                title="Hapus Produk">
                                                ×
                                            </button>

                                        </form>

                                    </div>

                                </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>

                                <span class="no-product">
                                    Belum ada produk
                                </span>

                                <?php endif; ?>

                            </div>

                        </td>

                        <td>

                            <div class="action-group">

                                <button type="button"
                                    class="btn btn-success btn-sm"
                                    onclick='openAddProductModal(
                                            <?php echo e($model->id); ?>,
                                            <?php echo json_encode($model->model, 15, 512) ?>
                                        )'>
                                    + Produk
                                </button>


                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    onclick='openEditModelModal(
                                            <?php echo e($model->id); ?>,
                                            <?php echo e($model->line_id); ?>,
                                            <?php echo json_encode($model->model, 15, 512) ?>
                                        )'>
                                    Edit
                                </button>


                                <form method="POST"
                                    action="<?php echo e(route('omd.master.model.destroy', $model->id)); ?>"
                                    class="delete-model-form">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button type="submit"
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="5">

                            <div class="empty-table">

                                <span>
                                    Tidak ada data
                                </span>

                            </div>

                        </td>

                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>




<div id="addModelModal"
    class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">

            <div>
                <h4>
                    Tambah Model
                </h4>

                <p>
                    Tambahkan Model pada Line yang dipilih.
                </p>
            </div>

            <button type="button"
                class="modal-close"
                onclick="closeAddModelModal()">
                ×
            </button>

        </div>


        <form method="POST"
            action="<?php echo e(route('omd.master.model.store')); ?>">

            <?php echo csrf_field(); ?>

            <input type="hidden"
                name="line_id"
                id="addModelLineId"
                value="<?php echo e($selectedLineId); ?>">


            <div class="selected-line-box">

                <span>
                    Line yang dipilih
                </span>

                <strong id="selectedLineName">
                    <?php echo e($selectedLineId
                        ? optional($lines->firstWhere('id', $selectedLineId))->name
                        : ($lines->first()?->name ?? '-')); ?>

                </strong>

            </div>


            <label class="form-label">
                Nama Model
            </label>

            <input type="text"
                name="model"
                class="form-control"
                placeholder="Contoh: Model A"
                required>


            <div class="modal-action">

                <button type="button"
                    onclick="closeAddModelModal()"
                    class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>




<div id="editModelModal"
    class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">

            <div>
                <h4>
                    Edit Model
                </h4>

                <p>
                    Perbarui data Model.
                </p>
            </div>

            <button type="button"
                class="modal-close"
                onclick="closeEditModelModal()">
                ×
            </button>

        </div>


        <form method="POST"
            id="editModelForm">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden"
                name="line_id"
                id="editModelLineId">


            <label class="form-label">
                Nama Model
            </label>

            <input type="text"
                name="model"
                id="editModelName"
                class="form-control"
                required>


            <div class="modal-action">

                <button type="button"
                    onclick="closeEditModelModal()"
                    class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>




<div id="addProductModal"
    class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">

            <div>
                <h4>
                    Tambah Produk
                </h4>

                <p>
                    Tambahkan Produk ke dalam Model.
                </p>
            </div>

            <button type="button"
                class="modal-close"
                onclick="closeAddProductModal()">
                ×
            </button>

        </div>


        <form method="POST"
            action="<?php echo e(route('omd.master.product.store')); ?>">

            <?php echo csrf_field(); ?>

            <input type="hidden"
                name="master_model_id"
                id="addProductModelId">


            <div class="selected-line-box">

                <span>
                    Model
                </span>

                <strong id="selectedModelName">
                    -
                </strong>

            </div>


            <label class="form-label">
                Nama Produk
            </label>

            <input type="text"
                name="name"
                class="form-control"
                placeholder="Contoh: Produk A"
                required>


            <div class="modal-action">

                <button type="button"
                    onclick="closeAddProductModal()"
                    class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>




<div id="editProductModal"
    class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">

            <div>
                <h4>
                    Edit Produk
                </h4>

                <p>
                    Perbarui data Produk.
                </p>
            </div>

            <button type="button"
                class="modal-close"
                onclick="closeEditProductModal()">
                ×
            </button>

        </div>


        <form method="POST"
            id="editProductForm">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden"
                name="master_model_id"
                id="editProductModelId">


            <label class="form-label">
                Nama Produk
            </label>

            <input type="text"
                name="name"
                id="editProductName"
                class="form-control"
                required>


            <div class="modal-action">

                <button type="button"
                    onclick="closeEditProductModal()"
                    class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /* =====================================================
       SELECTED LINE
    ===================================================== */

    let selectedLineId = <?php echo json_encode($selectedLineId, 15, 512) ?>;


    /* =====================================================
       LOAD MODEL BY LINE
    ===================================================== */

    function loadModel(id, element) {

        document.querySelectorAll('.line-card')
            .forEach(card => {
                card.classList.remove('active');
            });

        element.classList.add('active');

        selectedLineId = String(id);

        document.getElementById('addModelLineId').value = id;

        document.getElementById('selectedLineName').innerText =
            element.dataset.lineName;

        document.getElementById('lineTitle').innerText =
            element.dataset.lineName;

        const plantName =
            element.querySelector('.line-info span')?.innerText || '-';

        document.getElementById('lineSubtitle').innerText =
            plantName;

        filterModels();
    }


    /* =====================================================
       FILTER
    ===================================================== */

    function filterModels() {

        const keyword =
            document.getElementById('searchModel')
            .value
            .toLowerCase()
            .trim();

        const rows =
            document.querySelectorAll('.model-row');

        let visibleCount = 0;

        rows.forEach(row => {

            const lineId =
                String(row.dataset.lineId);

            const text =
                row.innerText.toLowerCase();

            const sameLine =
                String(selectedLineId) === lineId;

            const matchSearch =
                keyword === '' ||
                text.includes(keyword);

            if (sameLine && matchSearch) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });

        document.querySelector('.empty-filter-row')
            ?.remove();

        if (visibleCount === 0) {

            const tbody =
                document.querySelector('#modelTable tbody');

            const row =
                document.createElement('tr');

            row.className =
                'empty-filter-row';

            tbody.appendChild(row);
        }

        let no = 1;

        document.querySelectorAll('.model-row').forEach(row => {
            if (row.style.display !== 'none') {
                const numberCell = row.querySelector('.number-cell');

                if (numberCell) {
                    numberCell.textContent = no++;
                }
            }
        });
    }


    /* =====================================================
       SEARCH
    ===================================================== */

    document
        .getElementById('searchModel')
        .addEventListener('input', function() {

            filterModels();

        });


    /* =====================================================
       ADD MODEL
    ===================================================== */

    function openAddModelModal() {

        if (!selectedLineId) {

            const firstLine =
                document.querySelector('.line-card');

            if (firstLine) {

                loadModel(
                    firstLine.dataset.lineId,
                    firstLine
                );

            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Line Belum Tersedia',
                    text: 'Tambahkan Line terlebih dahulu.',
                    width: 320,
                    customClass: {
                        popup: 'swal-plant-popup',
                        title: 'swal-plant-title',
                        htmlContainer: 'swal-plant-text'
                    }
                });

                return;
            }
        }

        document.getElementById('addModelModal')
            .style.display = 'flex';
    }


    function closeAddModelModal() {

        document.getElementById('addModelModal')
            .style.display = 'none';

    }


    /* =====================================================
       EDIT MODEL
    ===================================================== */

    function openEditModelModal(id, lineId, name) {

        document.getElementById('editModelModal')
            .style.display = 'flex';

        document.getElementById('editModelLineId')
            .value = lineId;

        document.getElementById('editModelName')
            .value = name;

        document.getElementById('editModelForm')
            .action =
            "<?php echo e(url('/omd/master/model')); ?>/" + id;
    }


    function closeEditModelModal() {

        document.getElementById('editModelModal')
            .style.display = 'none';

    }


    /* =====================================================
       ADD PRODUCT
    ===================================================== */

    function openAddProductModal(modelId, modelName) {

        document.getElementById('addProductModelId')
            .value = modelId;

        document.getElementById('selectedModelName')
            .innerText = modelName;

        document.getElementById('addProductModal')
            .style.display = 'flex';
    }


    function closeAddProductModal() {

        document.getElementById('addProductModal')
            .style.display = 'none';

    }


    /* =====================================================
       EDIT PRODUCT
    ===================================================== */

    function openEditProductModal(id, modelId, name) {

        document.getElementById('editProductModal')
            .style.display = 'flex';

        document.getElementById('editProductModelId')
            .value = modelId;

        document.getElementById('editProductName')
            .value = name;

        document.getElementById('editProductForm')
            .action =
            "<?php echo e(url('/omd/master/product')); ?>/" + id;
    }


    function closeEditProductModal() {

        document.getElementById('editProductModal')
            .style.display = 'none';

    }


    /* =====================================================
       CLICK OUTSIDE MODAL
    ===================================================== */

    document.querySelectorAll('.modal-overlay')
        .forEach(modal => {

            modal.addEventListener('click', function(e) {

                if (e.target === modal) {
                    modal.style.display = 'none';
                }

            });

        });


    /* =====================================================
       DELETE MODEL
    ===================================================== */

    document.addEventListener('submit', function(e) {

        if (!e.target.classList.contains('delete-model-form')) {
            return;
        }

        e.preventDefault();

        const form = e.target;

        Swal.fire({

            title: 'Hapus Model?',

            html: `
                Data <b>Model</b> yang dihapus
                tidak dapat dikembalikan.
            `,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Hapus',

            cancelButtonText: 'Batal',

            reverseButtons: true,

            buttonsStyling: false,

            customClass: {
                popup: 'swal-plant-popup',
                title: 'swal-plant-title',
                htmlContainer: 'swal-plant-text',
                confirmButton: 'swal-plant-confirm',
                cancelButton: 'swal-plant-cancel'
            }

        }).then(result => {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });


    /* =====================================================
       DELETE PRODUCT
    ===================================================== */

    document.addEventListener('submit', function(e) {

        if (!e.target.classList.contains('delete-product-form')) {
            return;
        }

        e.preventDefault();

        const form = e.target;

        Swal.fire({

            title: 'Hapus Produk?',

            html: `
                Data <b>Produk</b> yang dihapus
                tidak dapat dikembalikan.
            `,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Ya, Hapus',

            cancelButtonText: 'Batal',

            reverseButtons: true,

            buttonsStyling: false,

            customClass: {
                popup: 'swal-plant-popup',
                title: 'swal-plant-title',
                htmlContainer: 'swal-plant-text',
                confirmButton: 'swal-plant-confirm',
                cancelButton: 'swal-plant-cancel'
            }

        }).then(result => {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });


    /* =====================================================
       INITIAL LINE
    ===================================================== */

    document.addEventListener('DOMContentLoaded', function() {

        let targetId = selectedLineId;

        let targetCard = null;

        if (targetId) {

            targetCard =
                document.querySelector(
                    `.line-card[data-line-id="${targetId}"]`
                );

        }

        if (!targetCard) {

            targetCard =
                document.querySelector('.line-card');

        }

        if (targetCard) {

            loadModel(
                targetCard.dataset.lineId,
                targetCard
            );

        }

    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/master/model-product.blade.php ENDPATH**/ ?>