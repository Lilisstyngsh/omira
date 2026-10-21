@extends('layouts.app')

@section('header')
    Plant & Line
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
            background: #5F9278;
            border-color: #5F9278;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #376B52;
            border-color: #376B52;
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
            border-color: #5F9278;
            background: #faf5ff;
            box-shadow: 0 8px 22px rgba(95,146,120,.12);
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
            background: #E7F1E9;
            color: #376B52;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
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
            min-height: 45px;
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
            position: absolute;
            top: 8px;
            right: 8px;
        }

        .plant-details {
            flex: 1;
            min-width: 0;
            padding-right: 22px;
        }

        .plant-name-edit,
        .editable-line-row {
            cursor: text;
        }

        .plant-name-edit {
            overflow-wrap: anywhere;
        }

        .inline-name input {
            width: 100%;
            min-width: 0;
            font: inherit;
        }

        .master-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            color: #475569;
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
            cursor: pointer;
        }

        .master-delete:hover {
            color: #1e293b;
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
            background: #E7F1E9;
            color: #376B52;
            font-size: 20px;
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
            width: auto;
        }

        #lineTable th:nth-child(2),
        #lineTable td:nth-child(2) {
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
            border-color: #5F9278;
            box-shadow: 0 0 0 3px rgba(95,146,120,.12);
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

    @include('omd.master.partials.master_colors')


    {{-- Visual override khusus Plant & Line: gunakan surface tema, tanpa mengubah master lain. --}}
    <style>
        /* Surface halaman mengikuti komponen OMIRA lainnya (light / dark). */
        .card.plant-dashboard-card.omd-master-accent,
        .card.line-table-card.omd-master-accent {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 15px;
            box-shadow: var(--shadow-sm) !important;
        }

        .plant-dashboard-card .plant-dashboard-header h4,
        .line-table-card .table-header h4 {
            color: var(--text) !important;
            font-size: 16px;
            letter-spacing: -.2px;
        }

        .plant-dashboard-card .plant-dashboard {
            gap: 13px;
            padding: 4px 2px 9px;
        }

        /* Card tetap horizontal; hanya visual yang diperhalus. */
        .plant-dashboard-card.omd-master-accent .plant-dashboard .plant-card {
            min-width: 235px;
            padding: 17px;
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 13px;
            box-shadow: 0 2px 9px rgba(31, 52, 39, .035) !important;
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .plant-dashboard-card.omd-master-accent .plant-dashboard .plant-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary) !important;
            box-shadow: 0 5px 15px rgba(31, 52, 39, .07) !important;
        }

        .plant-dashboard-card.omd-master-accent .plant-dashboard .plant-card.active {
            background: var(--surface) !important;
            border-color: var(--primary) !important;
            box-shadow: inset 3px 0 0 var(--primary), 0 3px 12px rgba(31, 52, 39, .055) !important;
        }

        .plant-dashboard-card.omd-master-accent .plant-icon {
            width: 46px;
            height: 46px;
            border-radius: 11px;
            background: var(--surface-hover) !important;
            color: var(--primary-dark) !important;
            font-size: 19px;
        }

        .plant-dashboard-card.omd-master-accent .plant-card h4 {
            color: var(--text) !important;
            font-size: 15px;
            font-weight: 700;
        }

        .plant-dashboard-card.omd-master-accent .plant-card p {
            color: var(--text-soft) !important;
            font-size: 12px;
        }

        .plant-dashboard-card .plant-manage {
            min-height: 0;
            margin-top: 9px;
            padding-top: 0;
            border-top: 0 !important;
        }

        /* Underline hanya saat teks nama disorot / diakses lewat keyboard. */
        .plant-dashboard-card .plant-name-edit [data-name-display],
        .line-table-card .inline-name [data-name-display] {
            text-decoration: none;
            text-underline-offset: 3px;
            text-decoration-thickness: 1px;
            cursor: text;
        }

        .plant-dashboard-card .plant-name-edit [data-name-display]:hover,
        .plant-dashboard-card .plant-name-edit:focus-visible [data-name-display],
        .line-table-card .inline-name [data-name-display]:hover,
        .line-table-card .editable-line-row:focus-visible .inline-name [data-name-display] {
            text-decoration: underline;
        }

        .plant-dashboard-card .plant-card:focus-visible,
        .plant-dashboard-card .plant-name-edit:focus-visible,
        .line-table-card .editable-line-row:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Tabel Line compact, tetap memakai palet surface halaman lain. */
        .line-table-card #lineTable {
            border: 1px solid var(--border);
            border-radius: 11px;
            background: var(--surface);
        }

        .line-table-card.omd-master-accent #lineTable table thead th {
            background: var(--table-head-bg) !important;
            color: var(--text-soft) !important;
            border-bottom: 1px solid var(--border) !important;
            font-size: 11px;
            letter-spacing: .35px;
        }

        .line-table-card #lineTable th,
        .line-table-card #lineTable td {
            padding: 11px 16px;
        }

        .line-table-card #lineTable th:nth-child(2),
        .line-table-card #lineTable td:nth-child(2) {
            width: 92px;
            text-align: right;
        }

        .line-table-card #lineTable table tbody tr {
            height: 54px;
        }

        .line-table-card.omd-master-accent #lineTable table tbody tr:hover,
        .line-table-card.omd-master-accent #lineTable table tbody tr:hover td {
            background: var(--surface-hover) !important;
        }

        .line-table-card #lineTable table tbody td {
            border-bottom-color: var(--border-soft) !important;
        }

        .line-table-card #lineTable .inline-name [data-name-display] {
            color: var(--text);
            font-weight: 650;
        }

        .line-table-card #lineTable .action-group {
            justify-content: flex-end;
        }

        .plant-dashboard-card .master-delete,
        .line-table-card .master-delete {
            color: var(--text-soft);
            border-radius: 7px;
        }

        .plant-dashboard-card .master-delete:hover,
        .line-table-card .master-delete:hover {
            background: var(--surface-hover);
            color: var(--danger);
        }

        @media (max-width: 700px) {
            .plant-dashboard-card.omd-master-accent .plant-dashboard .plant-card {
                min-width: 210px;
            }
        }
    </style>


    {{-- =====================================================
    DASHBOARD PLANT
===================================================== --}}

    <div id="masterFeedback" class="alert" role="status" aria-live="polite" hidden></div>

    <div class="card plant-dashboard-card omd-master-accent">

        <div class="card-body">

            <div class="plant-dashboard-header">

                <div>

                    <h4>
                        Plant
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
                        data-update-url="{{ route('omd.master.plant.update', $plant) }}"
                        tabindex="0" aria-label="Pilih Plant {{ $plant->name }}">

                        <div class="plant-header">

                            <div class="plant-icon" title="Plant">
                                <i class="fa-solid fa-industry" aria-hidden="true"></i>
                            </div>

                            <div class="plant-details">

                                <h4 class="plant-name-edit inline-name" data-editor="plant" role="button"
                                    tabindex="0" title="Klik untuk mengedit nama Plant"
                                    aria-label="Edit nama Plant {{ $plant->name }}">
                                    <span data-name-display>{{ $plant->name }}</span>
                                </h4>

                                <p>
                                    {{ $plant->lines_count }} Line
                                </p>

                            </div>

                        </div>


                        {{-- AKSI PLANT --}}
                        <div class="plant-manage">

                            <form method="POST" action="{{ route('omd.master.plant.destroy', $plant->id) }}"
                                class="plant-delete-form">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="master-delete" title="Hapus Plant" aria-label="Hapus Plant">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
                                        <path d="m6 6 12 12M18 6 6 18" />
                                    </svg>
                                </button>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="empty-dashboard">

                        <div class="empty-dashboard-icon">
                            <i class="fa-solid fa-industry" aria-hidden="true"></i>
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

    <div class="card mt-4 line-table-card omd-master-accent">

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

    {{-- Prepare each small Line table once so switching Plants needs no round trip. --}}
    @foreach ($plants as $plant)
        <template data-plant-lines="{{ $plant->id }}">
            @include('omd.master.partials.line_table', [
                'lines' => $plant->lines,
                'selectedPlant' => $plant,
            ])
        </template>
    @endforeach

    <div id="addPlantModal" class="modal-overlay">

        <div class="modal-box">

            <div class="modal-header">

                <div>

                    <h4>
                        Tambah Plant
                    </h4>

                </div>

                <button type="button" onclick="closeAddPlantModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.plant.store') }}" data-master-name-form="plant">
                <input type="hidden" name="_master_form" value="plant">

                @csrf


                <label for="addPlantName" class="form-label">
                    Nama Plant
                </label>

                <input type="text" id="addPlantName" name="name" class="form-control" placeholder="Contoh : Body"
                    value="{{ old('_master_form') === 'plant' ? old('name') : '' }}" maxlength="100" required>


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
    MODAL TAMBAH LINE
===================================================== --}}

    <div id="addLineModal" class="modal-overlay">

        <div class="modal-box">

            <div class="modal-header">

                <div>

                    <h4>
                        Tambah Line
                    </h4>

                </div>

                <button type="button" onclick="closeAddLineModal()" class="modal-close">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.line.store') }}" data-master-name-form="line">
                <input type="hidden" name="_master_form" value="line">

                @csrf


                <input type="hidden" name="plant_id" id="addPlantId" value="{{ old('_master_form') === 'line' ? old('plant_id') : $selectedPlant?->id }}">


                <label for="addLineName" class="form-label">
                    Nama Line
                </label>

                <input type="text" name="name" id="addLineName" class="form-control"
                    placeholder="Contoh: Assy Body" value="{{ old('_master_form') === 'line' ? old('name') : '' }}" maxlength="100" required>


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
    SWEETALERT2
===================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.master-validation-alert')


    <script>
        /* =====================================================
                               LOAD LINE BY PLANT
                            ===================================================== */

        const plantDashboard = document.querySelector('.plant-dashboard');
        const lineTable = document.getElementById('lineTable');
        const lineTemplates = new Map(Array.from(document.querySelectorAll('template[data-plant-lines]'),
            template => [Number(template.dataset.plantLines), template]));
        const csrfToken = @json(csrf_token());
        let selectedPlantId = @json($selectedPlant?->id);
        let activeEditor = null;

        function keepSelectedPlantInUrl() {
            if (!selectedPlantId) return;
            const url = new URL(window.location.href);
            url.searchParams.set('plant', selectedPlantId);
            window.history.replaceState(null, '', url);
        }

        // Persist even the initial default selection before a rename changes sort order.
        keepSelectedPlantInUrl();

        function showMasterFeedback(message, type = 'success') {
            if (type === 'danger') {
                showOmiraMasterWarning(message);
                return;
            }
            const feedback = document.getElementById('masterFeedback');
            feedback.className = 'alert alert-' + type;
            feedback.textContent = message;
            feedback.hidden = false;
        }

        async function readMasterResponse(response) {
            if (!response.headers.get('content-type')?.includes('application/json')) {
                throw new Error('Respons server tidak valid. Muat ulang halaman dan coba lagi.');
            }
            const data = await response.json();
            if (!response.ok) {
                const validationMessage = Object.values(data.errors || {}).flat()[0];
                throw new Error(response.status >= 500
                    ? 'Perubahan gagal disimpan. Coba lagi.'
                    : validationMessage || data.message || 'Permintaan gagal diproses.');
            }
            return data;
        }

        function closeInlineEditor(editor) {
            if (activeEditor !== editor) return;
            activeEditor = null;
            editor.input.remove();
            editor.display.hidden = false;
        }

        function beginInlineEdit(container) {
            if (activeEditor?.saving) return;
            if (activeEditor?.container === container) {
                activeEditor.input.focus();
                return;
            }
            if (activeEditor) closeInlineEditor(activeEditor);

            const kind = container.dataset.editor;
            const owner = container.closest(kind === 'plant' ? '.plant-card' : '.editable-line-row');
            const display = container.querySelector('[data-name-display]');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.value = owner.dataset.name;
            input.maxLength = 100;
            input.setAttribute('aria-label', kind === 'plant' ? 'Nama Plant' : 'Nama Line');
            const editor = {container, owner, display, input, kind, saving: false};
            activeEditor = editor;
            display.hidden = true;
            container.append(input);
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);

            input.addEventListener('click', event => event.stopPropagation());
            input.addEventListener('keydown', event => {
                event.stopPropagation();
                if (event.isComposing) return;
                if (event.key === 'Enter') {
                    event.preventDefault();
                    saveInlineEditor(editor);
                } else if (event.key === 'Escape' && !editor.saving) {
                    event.preventDefault();
                    closeInlineEditor(editor);
                    container.focus();
                }
            });
            // Only Enter saves; clicking away restores the last saved name.
            input.addEventListener('blur', () => {
                if (!editor.saving) closeInlineEditor(editor);
            });
        }

        async function saveInlineEditor(editor) {
            if (editor.saving || activeEditor !== editor) return;
            const name = editor.input.value.trim();
            if (name === editor.owner.dataset.name) {
                closeInlineEditor(editor);
                return;
            }
            editor.saving = true;
            editor.input.readOnly = true;
            editor.input.setAttribute('aria-busy', 'true');
            const payload = {name};
            if (editor.kind === 'line') {
                // Use this Line's persisted parent, never a mutable global selection.
                payload.plant_id = Number(editor.owner.dataset.plantId);
            }

            try {
                const response = await fetch(editor.owner.dataset.updateUrl, {
                    method: 'PUT',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });
                const data = await readMasterResponse(response);
                const saved = data[editor.kind];
                editor.owner.dataset.name = saved.name;
                editor.display.textContent = saved.name;
                if (editor.kind === 'plant') {
                    editor.container.setAttribute('aria-label', 'Edit nama Plant ' + saved.name);
                    editor.owner.setAttribute('aria-label', 'Pilih Plant ' + saved.name);
                    if (String(selectedPlantId) === String(saved.id)) {
                        document.getElementById('plantTitle').textContent = saved.name;
                    }
                } else {
                    editor.owner.setAttribute('aria-label', 'Edit nama Line ' + saved.name);
                }
                closeInlineEditor(editor);
                if (editor.kind === 'line') {
                    lineTemplates.get(Number(saved.plant_id)).innerHTML = lineTable.innerHTML;
                }
                showMasterFeedback(data.message);
            } catch (error) {
                // Preserve the original display/data and allow correction or Escape.
                showMasterFeedback(error.message, 'danger');
                editor.input.focus();
            } finally {
                editor.saving = false;
                editor.input.readOnly = false;
                editor.input.removeAttribute('aria-busy');
            }
        }

        function loadLine(id, element) {
            if (activeEditor?.saving) return;
            if (activeEditor) closeInlineEditor(activeEditor);
            const template = lineTemplates.get(Number(id));
            if (!template) {
                showMasterFeedback('Data Plant tidak tersedia. Muat ulang halaman dan coba lagi.', 'danger');
                return;
            }
            selectedPlantId = Number(id);
            plantDashboard.querySelectorAll('.plant-card').forEach(card => {
                card.classList.toggle('active', card === element);
            });
            document.getElementById('addPlantId').value = id;
            document.getElementById('plantTitle').textContent = element.dataset.name;
            lineTable.replaceChildren(template.content.cloneNode(true));
            keepSelectedPlantInUrl();
            bindDeleteLine();
        }

        // Delegate once: new table rows inherit the same editing behavior.
        plantDashboard.addEventListener('click', event => {
            const name = event.target.closest('.plant-name-edit');
            if (name) {
                if (!event.target.closest('input')) beginInlineEdit(name);
                return;
            }
            if (event.target.closest('button, input, select, textarea, a, form, [data-card-control]')) return;
            const card = event.target.closest('.plant-card');
            if (card) loadLine(card.dataset.plantId, card);
        });
        plantDashboard.addEventListener('keydown', event => {
            if (event.key !== 'Enter' && event.key !== ' ') return;
            const target = event.target;
            if (target.matches('.plant-name-edit')) {
                event.preventDefault();
                beginInlineEdit(target);
            } else if (target.matches('.plant-card')) {
                event.preventDefault();
                loadLine(target.dataset.plantId, target);
            }
        });
        lineTable.addEventListener('click', event => {
            if (event.target.closest('button, input, select, textarea, a, form')) return;
            const row = event.target.closest('.editable-line-row');
            if (row) beginInlineEdit(row.querySelector('.inline-name'));
        });
        lineTable.addEventListener('keydown', event => {
            if ((event.key === 'Enter' || event.key === ' ') && event.target.matches('.editable-line-row')) {
                event.preventDefault();
                beginInlineEdit(event.target.querySelector('.inline-name'));
            }
        });


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
