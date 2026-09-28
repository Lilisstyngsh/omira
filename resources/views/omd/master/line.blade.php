@extends('layouts.app')

@section('header')
    Data Master Plant & Line
@endsection

@section('content')
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
            color: #172033;
        }

        .page-heading p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
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

        .btn-danger:hover {
            background: #c94d5a;
            border-color: #c94d5a;
            color: #ffffff;
        }

        .btn-secondary {
            background: #f1f5f9;
            border-color: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            border-color: #e2e8f0;
            color: #475569;
        }


        /* =====================================================
                               PLANT DASHBOARD
                            ===================================================== */

        .plant-dashboard-card {
            border-radius: 18px;
            overflow: hidden;
        }

        .plant-dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .plant-dashboard-header h4 {
            margin: 0 0 5px;
            font-size: 17px;
            font-weight: 800;
            color: #172033;
        }

        .plant-dashboard-header p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }

        .add-plant-btn {
            height: 38px;
            padding: 0 15px;
            border-radius: 10px;
            white-space: nowrap;
        }

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
            position: relative;
            min-width: 235px;
            background: #ffffff;
            border-radius: 18px;
            padding: 18px;
            border: 2px solid transparent;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
            cursor: pointer;
            transition: .25s ease;
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

        .plant-manage {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #edf1f5;
        }

        .plant-mini-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: .2s ease;
        }

        .plant-mini-btn i {
            font-size: 12px;
        }

        .plant-mini-edit {
            background: #f1f5f9;
            color: #475569;
        }

        .plant-mini-edit:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .plant-mini-delete {
            background: #fff0f1;
            color: #d14d5b;
        }

        .plant-mini-delete:hover {
            background: #ffe0e3;
            color: #c43f4d;
        }

        .plant-delete-form {
            margin: 0;
        }


        /* =====================================================
                               EMPTY PLANT
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
                               LINE TABLE CARD
                            ===================================================== */

        .line-table-card {
            border-radius: 18px;
            overflow: hidden;
        }

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
                               ACTION LINE
                            ===================================================== */

        /* =====================================================
       ACTION LINE
    ===================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-group form {
            margin: 0;
        }

        .action-group .btn {
            height: 32px;
            padding: 0 13px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
        }

        .action-group .btn-warning {
            background: #f1f5f9;
            border-color: #f1f5f9;
            color: #475569;
        }

        .action-group .btn-warning:hover {
            background: #e2e8f0;
            border-color: #e2e8f0;
            color: #334155;
        }

        .action-group .btn-danger {
            background: #fff0f1;
            border-color: #fff0f1;
            color: #d14d5b;
        }

        .action-group .btn-danger:hover {
            background: #ffe0e3;
            border-color: #ffe0e3;
            color: #c43f4d;
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
            max-width: 420px;
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
            flex-shrink: 0;
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 11px;
            font-weight: 700;
            color: #334155;
        }

        .modal-box input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid #dce3ec;
            border-radius: 10px;
            outline: none;
            font-size: 13px;
            box-sizing: border-box;
        }

        .modal-box input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .10);
        }

        .modal-action {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }


        /* =====================================================
                               SWEETALERT
                               SAMA DENGAN KONSEP PLANT
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
            cursor: pointer !important;
        }

        .swal-plant-confirm:hover {
            background: #c94d5a !important;
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
            cursor: pointer !important;
        }

        .swal-plant-cancel:hover {
            background: #e2e8f0 !important;
        }


        /* =====================================================
                               RESPONSIVE
                            ===================================================== */

        @media (max-width: 700px) {

            .page-heading,
            .plant-dashboard-header,
            .table-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .plant-dashboard-header .btn,
            .table-header .btn {
                width: 100%;
            }

            .add-plant-btn {
                width: 100%;
            }

            .plant-dashboard {
                overflow-x: auto;
            }

            .plant-card {
                min-width: 210px;
            }

            .modal-action {
                flex-direction: column;
            }

            .modal-action .btn {
                width: 100%;
            }
        }
    </style>


    {{-- =====================================================
    DASHBOARD PLANT
===================================================== --}}

    <div class="card plant-dashboard-card">

        <div class="card-body">

            <div class="plant-dashboard-header">

                <div>

                    <h4>
                        Dashboard Plant & Line
                    </h4>

                </div>

                <button type="button" class="btn btn-primary add-plant-btn" onclick="openAddPlantModal()">
                    + Tambah Plant
                </button>

            </div>


            <div class="plant-dashboard">

                @forelse ($plants as $plant)
                    <div class="plant-card {{ $selectedPlant && $selectedPlant->id == $plant->id ? 'active' : '' }}"
                        data-plant-id="{{ $plant->id }}" data-name="{{ $plant->name }}"
                        onclick="loadLine({{ $plant->id }}, this)">

                        <div class="plant-header">

                            <div class="plant-icon">
                                🏭
                            </div>

                            <div>

                                <h4>
                                    {{ $plant->name }}
                                </h4>

                                <p>
                                    {{ $plant->lines_count }} Line
                                </p>

                            </div>

                        </div>


                        {{-- AKSI PLANT --}}
                        <div class="plant-manage" onclick="event.stopPropagation()">

                            <button type="button" class="plant-mini-btn plant-mini-edit"
                                onclick="openEditPlantModal(
        {{ $plant->id }},
        this.closest('.plant-card').dataset.name
    )"
                                data-name="{{ $plant->name }}" title="Edit Plant">
                                <i class="fas fa-pen"></i>
                            </button>


                            <form method="POST" action="{{ route('omd.master.plant.destroy', $plant->id) }}"
                                class="plant-delete-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="plant-mini-btn plant-mini-delete" title="Hapus Plant">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            </form>

                        </div>

                    </div>

                @empty

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
                @endforelse

            </div>

        </div>

    </div>


    {{-- =====================================================
    TABLE LINE
===================================================== --}}

    <div class="card mt-4 line-table-card">

        <div class="card-body">

            <div class="table-header">

                <div>

                    <h4 id="plantTitle">
                        {{ $selectedPlant?->name ?? 'Pilih Plant' }}
                    </h4>

                </div>


                <button type="button" class="btn btn-primary" onclick="openAddLineModal()">
                    + Tambah Line
                </button>

            </div>


            <div class="table-responsive" id="lineTable">

                @include('omd.master.partials.line_table', [
                    'lines' => $lines,
                    'selectedPlant' => $selectedPlant,
                ])

            </div>

        </div>

    </div>


    {{-- =====================================================
    MODAL TAMBAH PLANT
===================================================== --}}

    <div id="addPlantModal" class="modal-overlay">

        <div class="modal-box">

            <div class="modal-header">

                <div>

                    <h4>
                        Tambah Plant
                    </h4>

                    <p>
                        Tambahkan Plant baru ke dalam sistem.
                    </p>

                </div>

                <button type="button" onclick="closeAddPlantModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.plant.store') }}">

                @csrf


                <label for="addPlantName" class="form-label">
                    Nama Plant
                </label>

                <input type="text" id="addPlantName" name="name" class="form-control" placeholder="Contoh : Body"
                    maxlength="100" required>


                <div class="modal-action">

                    <button type="button" onclick="closeAddPlantModal()" class="btn btn-secondary">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
    MODAL EDIT PLANT
===================================================== --}}

    <div id="editPlantModal" class="modal-overlay">

        <div class="modal-box">

            <div class="modal-header">

                <div>

                    <h4>
                        Edit Plant
                    </h4>

                    <p>
                        Perbarui data Plant.
                    </p>

                </div>

                <button type="button" onclick="closeEditPlantModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" id="editPlantForm">

                @csrf
                @method('PUT')


                <label for="editPlantName" class="form-label">
                    Nama Plant
                </label>

                <input type="text" id="editPlantName" name="name" class="form-control" maxlength="100" required>


                <div class="modal-action">

                    <button type="button" onclick="closeEditPlantModal()" class="btn btn-secondary">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
    MODAL TAMBAH LINE
===================================================== --}}

    <div id="addLineModal" class="modal-overlay">

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

                <button type="button" onclick="closeAddLineModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.line.store') }}">

                @csrf


                <input type="hidden" name="plant_id" id="addPlantId" value="{{ $selectedPlant?->id }}">


                <label for="addLineName" class="form-label">
                    Nama Line
                </label>

                <input type="text" name="name" id="addLineName" class="form-control"
                    placeholder="Contoh : AS Body" maxlength="100" required>


                <div class="modal-action">

                    <button type="button" onclick="closeAddLineModal()" class="btn btn-secondary">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
    MODAL EDIT LINE
===================================================== --}}

    <div id="editLineModal" class="modal-overlay">

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

                <button type="button" onclick="closeEditLineModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" id="editForm">

                @csrf
                @method('PUT')


                <input type="hidden" name="plant_id" id="editPlantId" value="{{ $selectedPlant?->id }}">


                <label for="editName" class="form-label">
                    Nama Line
                </label>

                <input type="text" name="name" id="editName" class="form-control" maxlength="100" required>


                <div class="modal-action">

                    <button type="button" onclick="closeEditLineModal()" class="btn btn-secondary">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
    SWEETALERT2
===================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        /* =====================================================
                               LOAD LINE BY PLANT
                            ===================================================== */

        function loadLine(id, element) {

            document
                .querySelectorAll('.plant-card')
                .forEach(function(card) {
                    card.classList.remove('active');
                });


            if (element) {

                element.classList.add('active');

            }


            /* SET PLANT AKTIF */

            document.getElementById('addPlantId').value = id;

            document.getElementById('editPlantId').value = id;


            /* LOAD LINE */

            fetch(
                    "{{ url('/omd/master/line/by-plant') }}/" + id
                )
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {

                    document.getElementById(
                        'plantTitle'
                    ).innerHTML = data.plant;


                    var html = `
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
                        <td
                            colspan="3"
                            style="text-align:center;padding:35px;"
                        >

                            <div class="empty-dashboard">

                                <div class="empty-dashboard-icon">
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


                    data.lines.forEach(
                        function(line, index) {

                            var safeName =
                                String(line.name)
                                .replace(/\\/g, '\\\\')
                                .replace(/'/g, "\\'");


                            html += `
                        <tr>

                            <td>
                                ${index + 1}
                            </td>

                            <td>
                                <strong>
                                    ${line.name}
                                </strong>
                            </td>

                            <td>

                                <div class="action-group">

                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm"
                                        onclick="editLine(
                                            ${line.id},
                                            '${safeName}',
                                            ${id}
                                        )"
                                    >
                                        Edit
                                    </button>


                                    <form
                                        class="delete-line-form"
                                        method="POST"
                                        action="/omd/master/line/${line.id}"
                                    >

                                        <input
                                            type="hidden"
                                            name="_token"
                                            value="{{ csrf_token() }}"
                                        >

                                        <input
                                            type="hidden"
                                            name="_method"
                                            value="DELETE"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>
                    `;

                        }
                    );


                    html += `
                    </tbody>
                </table>
            `;


                    document.getElementById(
                        'lineTable'
                    ).innerHTML = html;


                    bindDeleteLine();

                })
                .catch(function(error) {
                    console.error(error);
                });

        }


        /* =====================================================
           PLANT - TAMBAH
        ===================================================== */

        function openAddPlantModal() {

            document.getElementById(
                'addPlantName'
            ).value = '';


            document.getElementById(
                'addPlantModal'
            ).style.display = 'flex';

        }


        function closeAddPlantModal() {

            document.getElementById(
                'addPlantModal'
            ).style.display = 'none';

        }


        /* =====================================================
           PLANT - EDIT
        ===================================================== */

        function openEditPlantModal(id, name) {

            document.getElementById(
                'editPlantName'
            ).value = name;


            document.getElementById(
                    'editPlantForm'
                ).action =
                "{{ url('/omd/master/plant') }}/" + id;


            document.getElementById(
                'editPlantModal'
            ).style.display = 'flex';

        }


        function closeEditPlantModal() {

            document.getElementById(
                'editPlantModal'
            ).style.display = 'none';

        }


        /* =====================================================
           LINE - TAMBAH
        ===================================================== */

        function openAddLineModal() {

            var plantId =
                document.getElementById(
                    'addPlantId'
                ).value;


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


            document.getElementById(
                'addLineName'
            ).value = '';


            document.getElementById(
                'addLineModal'
            ).style.display = 'flex';

        }


        function closeAddLineModal() {

            document.getElementById(
                'addLineModal'
            ).style.display = 'none';

        }


        /* =====================================================
           LINE - EDIT
        ===================================================== */

        function editLine(
            id,
            name,
            plantId
        ) {
            if (!plantId) {

                plantId =
                    document.getElementById('addPlantId').value;

            }


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


            document.getElementById(
                'editLineModal'
            ).style.display = 'flex';


            document.getElementById(
                'editName'
            ).value = name;


            document.getElementById(
                'editPlantId'
            ).value = plantId;


            document.getElementById(
                    'editForm'
                ).action =
                "{{ url('/omd/master/line') }}/" + id;
        }


        function closeEditLineModal() {

            document.getElementById(
                'editLineModal'
            ).style.display = 'none';

        }


        /* =====================================================
           DELETE PLANT
        ===================================================== */

        function bindDeletePlant() {

            document
                .querySelectorAll('.plant-delete-form')
                .forEach(function(form) {

                    form.addEventListener(
                        'submit',
                        function(event) {

                            event.preventDefault();


                            Swal.fire({

                                    icon: 'warning',

                                    title: 'Hapus Plant?',

                                    html: `
                                Data <b>Plant</b> yang dihapus
                                tidak dapat dikembalikan.
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

                                })
                                .then(
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


        /* =====================================================
           DELETE LINE
        ===================================================== */

        function bindDeleteLine() {

            document
                .querySelectorAll('.delete-line-form')
                .forEach(function(form) {

                    form.onsubmit =
                        function(event) {

                            event.preventDefault();


                            var formTarget =
                                this;


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

                                })
                                .then(
                                    function(result) {

                                        if (
                                            result.isConfirmed
                                        ) {

                                            formTarget.submit();

                                        }

                                    }
                                );

                        };

                });

        }


        /* =====================================================
           CLICK OUTSIDE MODAL
        ===================================================== */

        document
            .querySelectorAll('.modal-overlay')
            .forEach(
                function(modal) {

                    modal.addEventListener(
                        'click',
                        function(event) {

                            if (
                                event.target === modal
                            ) {

                                modal.style.display =
                                    'none';

                            }

                        }
                    );

                }
            );


        /* =====================================================
           INITIALIZE
        ===================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                bindDeletePlant();

                bindDeleteLine();

            }
        );
    </script>
@endsection
