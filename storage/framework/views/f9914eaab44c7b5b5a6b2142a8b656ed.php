<?php $__env->startSection('header'); ?>
Jenis NG
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* =====================================================
       ALERT
    ===================================================== */

    .ng-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 13px;
    }

    .ng-alert-success {
        background: #edf8f1;
        color: #28744d;
        border: 1px solid #d8eee0;
    }

    .ng-alert-danger {
        background: #fff1f1;
        color: #b44343;
        border: 1px solid #f2d7d7;
    }

    .ng-alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        border-radius: 50%;
        background: rgba(0, 0, 0, .05);
        font-size: 11px;
        font-weight: 800;
    }


    /* =====================================================
       CARD
    ===================================================== */

    .ng-card {
        border-radius: 16px;
    }


    /* =====================================================
       PAGE TITLE
    ===================================================== */

    .ng-page-heading {
        margin-bottom: 18px;
    }

    .ng-page-heading h4 {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
    }

    .ng-page-heading p {
        margin: 0;
        font-size: 12px;
        color: #64748b;
    }


    /* =====================================================
       FORM TAMBAH
    ===================================================== */

    .ng-form-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .ng-form-group {
        flex: 1;
    }

    .ng-form-group.code {
        max-width: 140px;
    }

    .ng-form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .ng-form-group input {
        width: 100%;
        height: 40px;
        padding: 0 11px;
        border: 1px solid #dfe6e2;
        border-radius: 10px;
        outline: none;
        box-sizing: border-box;
        font-size: 12px;
        color: #334155;
        background: #ffffff;
    }

    .ng-form-group input:focus {
        border-color: #78a88c;
        box-shadow: 0 0 0 3px rgba(111, 159, 132, .10);
    }

    .ng-save-btn {
        height: 40px;
        padding: 0 20px;
        border-radius: 10px;
        white-space: nowrap;
    }


    /* =====================================================
       TABLE HEADER
    ===================================================== */

    .ng-table-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 15px;
    }

    .ng-table-heading h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
    }

    .ng-table-count {
        padding: 5px 9px;
        border-radius: 999px;
        background: #f1f5f3;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .ng-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
    }

    .ng-table {
        width: 100%;
        min-width: 650px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .ng-table th {
        padding: 12px 15px;
        background: #f8faf9;
        border-bottom: 1px solid #e8eeea;
        font-size: 10px;
        font-weight: 800;
        color: #7d8982;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ng-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #eef2ef;
        vertical-align: middle;
        font-size: 12px;
        color: #475569;
    }

    .ng-table tbody tr {
        transition: .15s ease;
    }

    .ng-table tbody tr:hover {
        background: #fbfdfc;
    }

    .ng-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .ng-no {
        width: 65px;
        text-align: center;
        color: #94a3b8 !important;
        font-size: 11px !important;
        font-weight: 700;
    }

    .ng-code {
        width: 100px;
    }

    .ng-name {
        font-weight: 700;
        color: #334155;
    }


    /* =====================================================
       CODE BADGE
    ===================================================== */

    .code-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 30px;
        padding: 0 9px;
        border-radius: 8px;
        background: #ede9fe;
        color: #6d28d9;
        font-size: 11px;
        font-weight: 800;
    }


    /* =====================================================
       STATUS
    ===================================================== */

    .ng-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
    }

    .ng-status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }

    .ng-status.active {
        background: #ecfdf3;
        color: #15803d;
    }

    .ng-status.active .ng-status-dot {
        background: #22c55e;
    }

    .ng-status.inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .ng-status.inactive .ng-status-dot {
        background: #94a3b8;
    }


    /* =====================================================
       ACTION
    ===================================================== */

    .ng-action {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ng-action form {
        margin: 0;
    }

    .ng-action .btn {
        height: 32px;
        padding: 0 13px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .ng-empty {
        padding: 45px 20px;
        text-align: center;
    }

    .ng-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        margin: 0 auto 10px;
        border-radius: 13px;
        background: #ede9fe;
        color: #7c3aed;
        font-size: 20px;
    }

    .ng-empty strong {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #334155;
    }

    .ng-empty span {
        font-size: 11px;
        color: #94a3b8;
    }


    /* =====================================================
       EDIT MODAL
    ===================================================== */

    .ng-edit-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .45);
    }

    .ng-edit-box {
        width: 100%;
        max-width: 420px;
        padding: 22px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
    }

    .ng-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
    }

    .ng-edit-title {
        font-size: 17px;
        font-weight: 800;
        color: #172033;
    }

    .ng-edit-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        border: none;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 18px;
        cursor: pointer;
    }

    .ng-edit-form-group {
        margin-bottom: 15px;
    }

    .ng-edit-form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .ng-edit-form-group input {
        width: 100%;
        height: 41px;
        padding: 0 11px;
        border: 1px solid #dfe6e2;
        border-radius: 10px;
        outline: none;
        box-sizing: border-box;
        font-size: 12px;
    }

    .ng-edit-form-group input:focus {
        border-color: #78a88c;
        box-shadow: 0 0 0 3px rgba(111, 159, 132, .10);
    }

    .ng-edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 20px;
    }


    /* =====================================================
       SWEETALERT - SAMA DENGAN PLANT & MODEL
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

    @media (max-width: 700px) {

        .ng-form-row {
            align-items: stretch;
            flex-direction: column;
        }

        .ng-form-group.code {
            max-width: none;
        }

        .ng-save-btn {
            width: 100%;
        }

        .ng-table-heading {
            align-items: flex-start;
        }

        .ng-action {
            flex-wrap: wrap;
        }

    }
</style>




<div class="card ng-card">

    <div class="card-body">

        <div class="ng-page-heading">

            <h4>
                Tambah Jenis NG
            </h4>

        </div>


        <form method="POST"
            action="<?php echo e(route('omd.master.ng-type.store')); ?>">

            <?php echo csrf_field(); ?>

            <div class="ng-form-row">

                <div class="ng-form-group code">

                    <label>
                        Kode
                    </label>

                    <input type="text"
                        name="code"
                        maxlength="5"
                        placeholder="Contoh: P"
                        required>

                </div>


                <div class="ng-form-group">

                    <label>
                        Nama Jenis NG
                    </label>

                    <input type="text"
                        name="name"
                        maxlength="100"
                        placeholder="Contoh: NG Part"
                        required>

                </div>


                <button type="submit"
                    class="btn btn-primary ng-save-btn">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>




<div class="card ng-card mt-4">

    <div class="card-body">

        <div class="ng-table-heading">

            <h4>
                Daftar Jenis NG
            </h4>

            <span class="ng-table-count">
                <?php echo e($ngTypes->count()); ?> Jenis NG
            </span>

        </div>


        <div class="ng-table-wrap">

            <table class="table ng-table">

                <thead>

                    <tr>

                        <th class="ng-no">
                            No
                        </th>

                        <th class="ng-code">
                            Kode
                        </th>

                        <th>
                            Jenis NG
                        </th>

                        <th width="180">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $ngTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ngType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td class="ng-no">
                            <?php echo e($loop->iteration); ?>

                        </td>


                        <td>

                            <span class="code-badge">
                                <?php echo e($ngType->code); ?>

                            </span>

                        </td>


                        <td>

                            <span class="ng-name">
                                <?php echo e($ngType->name); ?>

                            </span>

                        </td>

                        <td>

                            <div class="ng-action">

                                <button type="button"
                                    class="btn btn-secondary edit-ng-btn"
                                    data-id="<?php echo e($ngType->id); ?>"
                                    data-code="<?php echo e($ngType->code); ?>"
                                    data-name="<?php echo e($ngType->name); ?>">

                                    Edit

                                </button>


                                <form method="POST"
                                    action="<?php echo e(route('omd.master.ng-type.destroy', $ngType->id)); ?>"
                                    class="delete-ng-form">

                                    <?php echo csrf_field(); ?>

                                    <?php echo method_field('DELETE'); ?>

                                    <button type="submit"
                                        class="btn btn-danger">

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td colspan="5">

                            <div class="ng-empty">

                                <div class="ng-empty-icon">
                                    !
                                </div>

                                <strong>
                                    Belum ada Jenis NG
                                </strong>

                                <span>
                                    Tambahkan Jenis NG terlebih dahulu.
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




<div id="editNgModal"
    class="ng-edit-overlay">

    <div class="ng-edit-box">

        <div class="ng-edit-header">

            <div class="ng-edit-title">
                Edit Jenis NG
            </div>

            <button type="button"
                onclick="closeEditNg()"
                class="ng-edit-close">

                ×

            </button>

        </div>


        <form method="POST"
            id="editNgForm">

            <?php echo csrf_field(); ?>

            <?php echo method_field('PUT'); ?>


            <div class="ng-edit-form-group">

                <label>
                    Kode
                </label>

                <input type="text"
                    name="code"
                    id="editNgCode"
                    maxlength="5"
                    required>

            </div>


            <div class="ng-edit-form-group">

                <label>
                    Nama Jenis NG
                </label>

                <input type="text"
                    name="name"
                    id="editNgName"
                    maxlength="100"
                    required>

            </div>


            <div class="ng-edit-actions">

                <button type="button"
                    onclick="closeEditNg()"
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
       EDIT
    ===================================================== */

    const editNgModal =
        document.getElementById('editNgModal');

    const editNgForm =
        document.getElementById('editNgForm');

    const editNgCode =
        document.getElementById('editNgCode');

    const editNgName =
        document.getElementById('editNgName');


    document.querySelectorAll('.edit-ng-btn')
        .forEach(btn => {

            btn.addEventListener('click', function() {

                editNgCode.value =
                    this.dataset.code;

                editNgName.value =
                    this.dataset.name;

                editNgForm.action =
                    "<?php echo e(url('/omd/master/ng-type')); ?>/" +
                    this.dataset.id;

                editNgModal.style.display =
                    'flex';

            });

        });


    function closeEditNg() {

        editNgModal.style.display =
            'none';

    }


    /* =====================================================
       CLICK OUTSIDE MODAL
    ===================================================== */

    editNgModal.addEventListener('click', function(e) {

        if (e.target === editNgModal) {

            closeEditNg();

        }

    });


    /* =====================================================
       DELETE
    ===================================================== */

    document.querySelectorAll('.delete-ng-form')
        .forEach(form => {

            form.addEventListener('submit', function(e) {

                e.preventDefault();

                const targetForm = this;


                Swal.fire({

                    title: 'Hapus Jenis NG?',

                    html: `
                        Data <b>Jenis NG</b> yang dihapus
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

                        targetForm.submit();

                    }

                });

            });

        });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/master/ng-type.blade.php ENDPATH**/ ?>