<?php $__env->startSection('title', 'Manajemen Akun'); ?>
<?php $__env->startSection('header', 'Manajemen Akun'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .account-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .account-page-title {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .account-page-desc {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }


        /* =====================================================
           SUCCESS ALERT
        ===================================================== */

        .account-success-alert {
            margin-bottom: 22px;
            padding: 18px 20px;
            border: 1px solid #75e6bc;
            border-radius: 16px;
            background: #effdf6;
            color: #059669;
            font-size: 13px;
            line-height: 1.5;
        }


        /* =====================================================
           PRIMARY BUTTON
        ===================================================== */

        .account-btn-primary {
            height: 38px;
            padding: 0 16px;
            border-radius: 9px;
            background: #6d5dfc;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 750;
            box-shadow: 0 6px 15px rgba(109, 93, 252, .18);
            transition: .2s ease;
        }

        .account-btn-primary:hover {
            background: #5d4df0;
            transform: translateY(-1px);
            color: #fff;
        }


        /* =====================================================
           TABLE CARD
        ===================================================== */

        .account-table-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .account-table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #edf1f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            background: linear-gradient(135deg,
                    #f8f7ff 0%,
                    #fff 75%);
        }

        .account-table-header h3 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .account-table-header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .account-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            padding: 0 12px;
            border-radius: 10px;
            background: #f1efff;
            color: #6557dc;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .account-table-wrap {
            overflow-x: auto;
        }

        .account-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        .account-table thead th {
            padding: 14px 18px;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .account-table tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 13px;
            vertical-align: middle;
        }

        .account-table tbody tr {
            transition: .2s ease;
        }

        .account-table tbody tr:hover {
            background: #fafaff;
        }

        .account-table tbody tr:last-child td {
            border-bottom: none;
        }

        .account-number {
            width: 65px;
            color: #94a3b8 !important;
            font-weight: 700;
        }


        /* =====================================================
           USER
        ===================================================== */

        .account-user {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .account-avatar {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: #f1efff;
            color: #6659df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .account-user-name {
            font-weight: 750;
            color: #172033;
            margin-bottom: 2px;
        }

        .account-user-label {
            font-size: 10px;
            color: #94a3b8;
        }

        .account-email {
            color: #64748b;
        }


        /* =====================================================
           BADGES
        ===================================================== */

        .account-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 750;
        }

        .account-badge-plant {
            background: #f5f3ff;
            color: #6659df;
        }

        .account-badge-line {
            background: #eff6ff;
            color: #3478c5;
        }


        /* =====================================================
           ACTION
        ===================================================== */

        .account-actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .account-action-btn {
            height: 32px;
            padding: 0 11px;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
            transition: .2s ease;
        }

        .account-action-edit {
            background: #f1f5f9;
            color: #475569;
        }

        .account-action-edit:hover {
            background: #e2e8f0;
        }

        .account-action-reset {
            background: #fff7e8;
            color: #b77906;
        }

        .account-action-reset:hover {
            background: #ffedc2;
        }

        .account-action-delete {
            background: #fff0f1;
            color: #d14d5b;
        }

        .account-action-delete:hover {
            background: #ffe0e3;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .account-empty {
            padding: 50px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .account-empty-icon {
            width: 46px;
            height: 46px;
            margin: 0 auto 10px;
            border-radius: 13px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .account-empty-title {
            font-weight: 750;
            color: #475569;
            margin-bottom: 3px;
        }

        .account-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }


        /* =====================================================
           SWEETALERT
           MENGIKUTI KONSEP DATA MASTER
        ===================================================== */

        .swal-account-popup {
            width: 320px !important;
            padding: 20px !important;
            border-radius: 18px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18) !important;
        }

        .swal-account-popup .swal2-icon {
            width: 55px !important;
            height: 55px !important;
            margin: 0 auto 12px !important;
        }

        .swal-account-title {
            font-size: 18px !important;
            font-weight: 800 !important;
            color: #172033 !important;
        }

        .swal-account-text {
            font-size: 12px !important;
            color: #64748b !important;
        }

        .swal-account-popup .swal2-actions {
            gap: 10px !important;
            margin-top: 18px !important;
        }


        /* =====================================================
           SWEETALERT CONFIRM BUTTON
        ===================================================== */

        .swal-account-confirm {
            background: #dc5b68 !important;
            color: white !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-confirm:hover {
            background: #c94d5a !important;
        }


        /* =====================================================
           SWEETALERT RESET BUTTON
        ===================================================== */

        .swal-account-reset {
            background: #f59e0b !important;
            color: white !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-reset:hover {
            background: #d98800 !important;
        }


        /* =====================================================
           SWEETALERT CANCEL BUTTON
        ===================================================== */

        .swal-account-cancel {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-cancel:hover {
            background: #e2e8f0 !important;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 768px) {

            .account-page-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .account-table-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .account-success-alert {
                padding: 16px;
            }
        }
    </style>


    

    <div class="account-page-head">

        <div>

            <h2 class="account-page-title">
                Manajemen Akun
            </h2>

        </div>


        <a href="<?php echo e(route('omd.users.create')); ?>" class="account-btn-primary">
            + Tambah Akun
        </a>

    </div>


    

    <?php if(session('account_success')): ?>
        <div class="account-success-alert">
            <?php echo e(session('account_success')); ?>

        </div>
    <?php endif; ?>


    

    <div class="account-table-card">


        <div class="account-table-wrap">

            <table class="account-table">

                <thead>

                    <tr>

                        <th style="width: 65px;">
                            No
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Email
                        </th>

                        <th style="width: 110px;">
                            Role
                        </th>

                        <th style="width: 150px;">
                            Plant
                        </th>

                        <th style="width: 160px;">
                            Line
                        </th>

                        <th style="width: 270px;">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>

                            
                            <td class="account-number">
                                <?php echo e($index + 1); ?>

                            </td>


                            
                            <td>

                                <div class="account-user">

                                    <div>

                                        <div class="account-user-name">
                                            <?php echo e($user->name); ?>

                                        </div>

                                    </div>

                                </div>

                            </td>


                            
                            <td class="account-email">
                                <?php echo e($user->email); ?>

                            </td>


                            
                            <td>

                                <?php echo e($user->role === 'omd' ? 'OMD' : 'User'); ?>


                            </td>


                            
                            <td>

                                <?php if($user->role === 'omd'): ?>
                                    -
                                <?php else: ?>
                                    <span class="account-badge account-badge-plant">
                                        <?php echo e($user->line?->plant?->name ?? '-'); ?>

                                    </span>
                                <?php endif; ?>

                            </td>


                            
                            <td>

                                <?php if($user->role === 'omd'): ?>
                                    -
                                <?php else: ?>
                                    <span class="account-badge account-badge-line">
                                        <?php echo e($user->line?->name ?? '-'); ?>

                                    </span>
                                <?php endif; ?>

                            </td>


                            
                            <td>

                                <div class="account-actions">


                                    
                                    <a href="<?php echo e(route('omd.users.edit', $user)); ?>"
                                        class="account-action-btn account-action-edit">
                                        Edit
                                    </a>


                                    
                                    <form method="POST" action="<?php echo e(route('omd.users.reset-password', $user)); ?>"
                                        class="reset-password-form" data-name="<?php echo e($user->name); ?>">

                                        <?php echo csrf_field(); ?>

                                        <button type="submit" class="account-action-btn account-action-reset">
                                            Reset
                                        </button>

                                    </form>


                                    
                                    <form method="POST" action="<?php echo e(route('omd.users.destroy', $user)); ?>"
                                        class="delete-user-form" data-name="<?php echo e($user->name); ?>">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit" class="account-action-btn account-action-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="7" class="account-empty">

                                <div class="account-empty-icon">
                                    👤
                                </div>

                                <div class="account-empty-title">
                                    Belum ada akun
                                </div>

                                <div class="account-empty-text">
                                    Tambahkan akun User atau OMD untuk mulai menggunakan sistem.
                                </div>

                            </td>

                        </tr>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                /* =================================================
                   DELETE USER
                ================================================= */

                document
                    .querySelectorAll('.delete-user-form')
                    .forEach(function(form) {

                        form.addEventListener(
                            'submit',
                            function(event) {

                                event.preventDefault();

                                var userName =
                                    form.dataset.name;


                                Swal.fire({

                                    icon: 'warning',

                                    title: 'Hapus Akun?',

                                    html: 'Akun <b>' +
                                        userName +
                                        '</b> akan dihapus dan data tidak dapat dikembalikan.',

                                    showCancelButton: true,

                                    confirmButtonText: 'Ya, Hapus',

                                    cancelButtonText: 'Batal',

                                    reverseButtons: true,

                                    buttonsStyling: false,

                                    customClass: {

                                        popup: 'swal-account-popup',

                                        title: 'swal-account-title',

                                        htmlContainer: 'swal-account-text',

                                        confirmButton: 'swal-account-confirm',

                                        cancelButton: 'swal-account-cancel'

                                    }

                                }).then(
                                    function(result) {

                                        if (
                                            result.isConfirmed
                                        ) {

                                            form.submit();

                                        }

                                    }
                                );

                            }
                        );

                    });


                /* =================================================
                   RESET PASSWORD
                ================================================= */

                document
                    .querySelectorAll('.reset-password-form')
                    .forEach(function(form) {

                        form.addEventListener(
                            'submit',
                            function(event) {

                                event.preventDefault();

                                var userName =
                                    form.dataset.name;


                                Swal.fire({

                                    icon: 'warning',

                                    title: 'Reset Password?',

                                    html: 'Password akun <b>' +
                                        userName +
                                        '</b> akan dikembalikan ke <b>password default sistem</b>.',

                                    showCancelButton: true,

                                    confirmButtonText: 'Ya, Reset',

                                    cancelButtonText: 'Batal',

                                    reverseButtons: true,

                                    buttonsStyling: false,

                                    customClass: {

                                        popup: 'swal-account-popup',

                                        title: 'swal-account-title',

                                        htmlContainer: 'swal-account-text',

                                        confirmButton: 'swal-account-reset',

                                        cancelButton: 'swal-account-cancel'

                                    }

                                }).then(
                                    function(result) {

                                        if (
                                            result.isConfirmed
                                        ) {

                                            form.submit();

                                        }

                                    }
                                );

                            }
                        );

                    });

            }
        );
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/users/index.blade.php ENDPATH**/ ?>