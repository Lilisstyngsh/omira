

<?php $__env->startSection('header'); ?>
Data Master Line
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>



<div class="card">
    <div class="card-body">
        <div class="page-heading">
            <div>
                <h4 class="fw-bold mb-1">
                    Dashboard Master Line
                </h4>
                <p>
                    Pilih Plant untuk melihat Line yang tersedia.
                </p>
            </div>
        </div>

        <div class="plant-dashboard">
            <?php $__empty_1 = true; $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="plant-card <?php echo e($selectedPlant && $selectedPlant->id == $plant->id ? 'active' : ''); ?>"
                onclick="loadLine(<?php echo e($plant->id); ?>, this)">
                <div class="plant-header">
                    <div class="plant-icon">
                        🏭
                    </div>
                    <div>
                        <h4>
                            <?php echo e($plant->name); ?>

                        </h4>
                        <p>
                            <?php echo e($plant->lines_count); ?> Line
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-dashboard">
                <div class="empty-dashboard-icon">
                    🏭
                </div>
                <strong>
                    Belum ada Plant
                </strong>
                <span>
                    Tambahkan Plant terlebih dahulu.
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<div class="card mt-4">
    <div class="card-body">
        <div class="table-header">
            <div>
                <h4 id="plantTitle">
                    <?php echo e($selectedPlant?->name ?? 'Pilih Plant'); ?>

                </h4>
                <p>
                    Daftar Line pada Plant yang dipilih.
                </p>
            </div>

            <button class="btn btn-primary" onclick="openAddModal()">
                + Tambah Line
            </button>
        </div>

        <div class="table-responsive" id="lineTable">
            <?php echo $__env->make('omd.master.partials.line_table', [
            'lines' => $lines,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>



<div id="addModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h4>
                    Tambah Line
                </h4>
                <p>
                    Tambahkan Line pada Plant yang dipilih.
                </p>
            </div>

            <button type="button" onclick="closeAddModal()" class="modal-close">
                ×
            </button>
        </div>

        <form method="POST" action="<?php echo e(route('omd.master.line.store')); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="plant_id" id="addPlantId" value="<?php echo e($selectedPlant?->id); ?>">

            <label class="form-label">
                Nama Line
            </label>

            <input type="text" name="name" class="form-control" placeholder="Contoh : AS Body" required>

            <div class="modal-action">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>



<div id="editModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h4>
                    Edit Line
                </h4>
                <p>
                    Perbarui data Line.
                </p>
            </div>

            <button type="button" onclick="closeModal()" class="modal-close">
                ×
            </button>
        </div>

        <form method="POST" id="editForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden" name="plant_id" id="editPlantId" value="<?php echo e($selectedPlant?->id); ?>">

            <label class="form-label">
                Nama Line
            </label>

            <input type="text" name="name" id="editName" class="form-control" required>

            <div class="modal-action">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* =====================================================
       ALERT
    ===================================================== */
    .line-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 13px;
    }

    .line-alert-success {
        background: #edf8f1;
        color: #28744d;
        border: 1px solid #d8eee0;
    }

    .line-alert-danger {
        background: #fff1f1;
        color: #b44343;
        border: 1px solid #f2d7d7;
    }

    .line-alert-icon {
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
       PLANT DASHBOARD
    ===================================================== */
    .plant-dashboard {
        display: flex;
        gap: 18px;
        overflow-x: auto;
        padding: 5px;
    }

    .plant-dashboard::-webkit-scrollbar {
        height: 7px;
    }

    .plant-dashboard::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .plant-dashboard::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .plant-dashboard::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .plant-card {
        min-width: 220px;
        background: #ffffff;
        border-radius: 18px;
        padding: 18px;
        border: 2px solid transparent;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        cursor: pointer;
        transition: .25s;
    }

    .plant-card:hover {
        transform: translateY(-4px);
    }

    .plant-card.active {
        border-color: #7c3aed;
        background: #faf5ff;
        box-shadow: 0 8px 22px rgba(124, 58, 237, .10);
    }

    .plant-header {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .plant-icon {
        width: 55px;
        height: 55px;
        border-radius: 15px;
        background: #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    .plant-card h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
    }

    .plant-card p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    /* =====================================================
       EMPTY DASHBOARD
    ===================================================== */
    .empty-dashboard {
        width: 100%;
        padding: 35px;
        text-align: center;
    }

    .empty-dashboard-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin: 0 auto 10px;
        border-radius: 14px;
        background: #ede9fe;
        font-size: 22px;
    }

    .empty-dashboard strong {
        display: block;
        margin-bottom: 4px;
        font-size: 14px;
        color: #334155;
    }

    .empty-dashboard span {
        font-size: 11px;
        color: #94a3b8;
    }

    /* =====================================================
       TABLE HEADER
    ===================================================== */
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
    }

    .table-header h4 {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
    }

    .table-header p {
        margin: 0;
        font-size: 11px;
        color: #64748b;
    }

    /* =====================================================
       TABLE RESPONSIVE
    ===================================================== */
    .table-responsive {
        border-radius: 16px;
        overflow-x: auto;
    }

    #lineTable table {
        width: 100%;
        table-layout: fixed;
        margin: 0;
    }

    #lineTable th,
    #lineTable td {
        vertical-align: middle;
    }

    #lineTable th:nth-child(1),
    #lineTable td:nth-child(1) {
        width: 80px;
        text-align: center;
    }

    #lineTable th:nth-child(2),
    #lineTable td:nth-child(2) {
        width: auto;
    }

    #lineTable th:nth-child(3),
    #lineTable td:nth-child(3) {
        width: 220px;
    }

    #lineTable table tbody tr {
        height: 65px;
    }

    #lineTable table tbody tr:hover {
        background: #faf5ff;
    }

    /* =====================================================
       ACTION BUTTON
    ===================================================== */
    .action-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-group form {
        margin: 0;
    }

    .action-group .btn {
        min-width: 75px;
        height: 40px;
        padding: 0 13px;
        border-radius: 12px;
        font-weight: 700;
    }

    /* =====================================================
       MODAL
    ===================================================== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-box {
        background: white;
        width: 100%;
        max-width: 400px;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
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
        color: #172033;
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
        width: 32px;
        height: 32px;
        border: none;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
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

    .modal-box input {
        height: 42px;
        border-radius: 10px;
    }

    .modal-action {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
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

    .btn-danger {
        background: #dc5b68;
        border-color: #dc5b68;
        color: #ffffff;
    }

    .btn-secondary {
        background: #f1f5f9;
        border-color: #f1f5f9;
        color: #475569;
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

        .page-heading,
        .table-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-heading .btn,
        .table-header .btn {
            width: 100%;
        }

        .plant-dashboard {
            overflow-x: auto;
        }

        .plant-card {
            min-width: 200px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /* =====================================================
       LOAD LINE BY PLANT
    ===================================================== */
    function loadLine(id, element) {

        document.querySelectorAll('.plant-card').forEach(card => {
            card.classList.remove('active');
        });

        element.classList.add('active');

        /* SET PLANT AKTIF */
        document.getElementById('addPlantId').value = id;
        document.getElementById('editPlantId').value = id;

        /* LOAD LINE */
        fetch("<?php echo e(url('/omd/master/line/by-plant')); ?>/" + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('plantTitle').innerHTML = data.plant;

                let html = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Line</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                if (data.lines.length === 0) {
                    html += `
                        <tr>
                            <td colspan="3" style="text-align:center;padding:35px;">
                                <div class="empty-table">
                                    <div class="empty-table-icon">
                                        📋
                                    </div>
                                    <strong>
                                        Belum ada Line
                                    </strong>
                                    <span>
                                        Plant ini belum memiliki Line.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                data.lines.forEach((line, index) => {

                    const safeName = String(line.name).replace(/'/g, "\\'");

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${line.name}</td>
                            <td>
                                <div class="action-group">
                                    <button type="button" class="btn btn-warning btn-sm" onclick="editLine(${line.id}, '${safeName}', ${id})">
                                        Edit
                                    </button>

                                    <form class="delete-line-form" method="POST" action="/omd/master/line/${line.id}">
                                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                `;

                document.getElementById('lineTable').innerHTML = html;

                /* REBIND DELETE */
                bindDeleteLine();
            })
            .catch(error => {
                console.error(error);
            });
    }

    /* =====================================================
       EDIT LINE
    ===================================================== */
    function editLine(id, name, plantId) {
        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('editName').value = name;
        document.getElementById('editPlantId').value = plantId;
        document.getElementById('editForm').action = "/omd/master/line/" + id;
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    /* =====================================================
       ADD MODAL
    ===================================================== */
    function openAddModal() {

        const plantId = document.getElementById('addPlantId').value;

        if (!plantId) {
            Swal.fire({
                icon: 'warning',
                title: 'Plant Belum Dipilih',
                text: 'Pilih Plant terlebih dahulu.',
                width: 320,
                customClass: {
                    popup: 'swal-plant-popup',
                    title: 'swal-plant-title',
                    htmlContainer: 'swal-plant-text'
                }
            });
            return;
        }

        document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    /* =====================================================
       DELETE LINE
    ===================================================== */
    function bindDeleteLine() {

        document.querySelectorAll('.delete-line-form').forEach(form => {

            form.onsubmit = function(e) {

                e.preventDefault();

                const formTarget = this;

                Swal.fire({
                    title: 'Hapus Line?',
                    html: `
                        Data <b>Line</b> yang dihapus
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
                        formTarget.submit();
                    }
                });
            };
        });
    }

    /* =====================================================
       CLICK OUTSIDE MODAL
    ===================================================== */
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    /* =====================================================
       INITIALIZE
    ===================================================== */
    document.addEventListener('DOMContentLoaded', function() {
        bindDeleteLine();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/master/line.blade.php ENDPATH**/ ?>