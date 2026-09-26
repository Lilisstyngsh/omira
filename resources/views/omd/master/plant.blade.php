@extends('layouts.app')

@section('header')
Data Master Plant
@endsection

@section('content')
<style>
    /* =====================================================
           PLANT PAGE
        ===================================================== */
    .plant-card {
        border-radius: 16px;
    }

    .plant-input-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .plant-input-row input {
        flex: 1;
        height: 40px;
        border-radius: 10px;
    }

    .plant-save-btn {
        height: 40px;
        padding: 0 20px;
        border-radius: 10px;
    }

    .plant-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
    }

    .plant-table {
        min-width: 600px;
    }

    .plant-table th,
    .plant-table td {
        padding: 13px 15px;
        vertical-align: middle;
    }

    .plant-name {
        font-weight: 700;
        font-size: 14px;
    }

    .plant-action {
        display: flex;
        gap: 8px;
    }

    .plant-action button,
    .plant-action .btn {
        height: 32px;
        padding: 0 13px;
        border-radius: 8px;
        font-size: 12px;
    }

    /* =====================================================
           EDIT MODAL
        ===================================================== */
    .edit-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .edit-box {
        width: 100%;
        max-width: 420px;
        background: white;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .edit-title {
        font-size: 17px;
        font-weight: 800;
    }

    .edit-close {
        border: none;
        background: #f1f5f9;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
    }

    .edit-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* =====================================================
           SWEETALERT MODERN OMIRA
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

    @media(max-width:600px) {
        .plant-input-row {
            flex-direction: column;
        }

        .plant-save-btn {
            width: 100%;
        }

        .edit-actions {
            flex-direction: column;
        }

        .edit-actions button {
            width: 100%;
        }
    }
</style>

{{-- =====================================================
    FORM TAMBAH
    ===================================================== --}}

<div class="card plant-card">
    <div class="card-body">
        <h3 class="fw-bold mb-3">
            Tambah Plant
        </h3>

        <form method="POST" action="{{ route('omd.master.plant.store') }}">
            @csrf

            <div class="plant-input-row">
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama plant" required>

                <button class="btn btn-primary plant-save-btn">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================
    LIST PLANT
    ===================================================== --}}

<div class="card mt-4 plant-card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="fw-bold">
                Daftar Plant
            </h3>
        </div>

        <div class="plant-table-wrap">
            <table class="table plant-table">
                <thead>
                    <tr>
                        <th width="60">
                            No
                        </th>
                        <th>
                            Plant
                        </th>
                        <th width="170">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($plants as $plant)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <div class="plant-name">
                                {{ $plant->name }}
                            </div>
                        </td>

                        <td>
                            <div class="plant-action">
                                <button type="button" class="btn btn-secondary edit-plant-btn"
                                    data-id="{{ $plant->id }}" data-name="{{ $plant->name }}">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('omd.master.plant.destroy', $plant->id) }}"
                                    class="delete-plant-form">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data plant
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- =====================================================
    MODAL EDIT
    ===================================================== --}}

<div id="editPlantModal" class="edit-overlay">
    <div class="edit-box">
        <div class="edit-header">
            <div class="edit-title">
                Edit Plant
            </div>

            <button onclick="closeEditPlant()" class="edit-close">
                ×
            </button>
        </div>

        <form id="editPlantForm" method="POST">
            @csrf
            @method('PUT')

            <input type="text" id="editPlantName" name="name" class="form-control" required>

            <div class="edit-actions">
                <button type="button" onclick="closeEditPlant()" class="btn btn-secondary">
                    Batal
                </button>

                <button class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================
    SCRIPT EDIT
    ===================================================== --}}

<script>
    const editModal =
        document.getElementById('editPlantModal');

    const editForm =
        document.getElementById('editPlantForm');

    const editName =
        document.getElementById('editPlantName');

    document.querySelectorAll('.edit-plant-btn')
        .forEach(btn => {
            btn.onclick = function() {
                editName.value = this.dataset.name;

                editForm.action =
                    "{{ url('/omd/master/plant') }}/" +
                    this.dataset.id;

                editModal.style.display = 'flex';
            }
        });

    function closeEditPlant() {
        editModal.style.display = 'none';
    }
</script>

{{-- =====================================================
    SWEETALERT
    ===================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.delete-plant-form')
        .forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Hapus Plant?',
                    html: `
                            Data <b>Plant</b> yang dihapus tidak dapat dikembalikan.
                        `,
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
        });
</script>
@endsection