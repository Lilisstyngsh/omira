@extends('layouts.app')

@section('header')
    Data Master Model & Produk
@endsection

@section('content')

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
                   SEARCH
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
            min-width: 760px;
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
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eef1f4;
        }

        .modern-table tbody tr {
            transition: .15s ease;
            cursor: pointer;
        }

        .modern-table tbody tr:hover {
            background: #faf5ff;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .number-cell {
            width: 70px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
        }


        /* =====================================================
                   MODEL
                ===================================================== */

        .model-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .model-name {
            display: inline-block;
            color: #1e293b;
            font-size: 13px;
            font-weight: 800;
        }

        .model-info {
            display: flex;
            align-items: center;
        }

        .model-name {
            display: inline-block;
            color: #1e293b;
            font-size: 13px;
            font-weight: 800;
        }


        /* =====================================================
                   PRODUCT COUNT
                ===================================================== */

        .product-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 75px;
            height: 29px;
            padding: 0 10px;
            border-radius: 999px;
            background: #f1efff;
            color: #6557dc;
            font-size: 10px;
            font-weight: 800;
        }


        /* =====================================================
                   ACTION MODEL
                ===================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
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
                   PRODUCT LIST MODAL
                ===================================================== */

        .product-list-box {
            width: 100%;
            max-width: 580px;
            max-height: 85vh;
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
            display: flex;
            flex-direction: column;
        }

        .product-list-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 16px;
            border-bottom: 1px solid #edf1f5;
        }

        .product-list-header h4 {
            margin: 0 0 5px;
            font-size: 17px;
            font-weight: 800;
            color: #172033;
        }

        .product-list-header p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }

        .product-list-header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-list-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 65px;
            height: 29px;
            padding: 0 9px;
            border-radius: 999px;
            background: #f1efff;
            color: #6557dc;
            font-size: 10px;
            font-weight: 800;
        }

        .product-list-content {
            margin-top: 16px;
            max-height: 390px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .product-list-content::-webkit-scrollbar {
            width: 6px;
        }

        .product-list-content::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }

        .product-list-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .popup-product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 12px;
            margin-bottom: 8px;
            background: #f8fafc;
            border: 1px solid #edf0f3;
            border-radius: 10px;
        }

        .popup-product-item:last-child {
            margin-bottom: 0;
        }

        .popup-product-name {
            min-width: 0;
            color: #334155;
            font-size: 12px;
            font-weight: 650;
            word-break: break-word;
        }

        .popup-product-actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
        }

        .popup-product-actions form {
            margin: 0;
        }

        .popup-action-btn {
            height: 30px;
            padding: 0 11px;
            border: none;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: .15s ease;
        }

        .popup-action-edit {
            background: #f1f5f9;
            color: #475569;
        }

        .popup-action-edit:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .popup-action-delete {
            background: #fff0f1;
            color: #d14d5b;
        }

        .popup-action-delete:hover {
            background: #ffe0e3;
            color: #c43f4d;
        }

        .popup-empty {
            padding: 35px 20px;
            text-align: center;
            background: #fafbfc;
            border: 1px dashed #dfe5eb;
            border-radius: 12px;
        }

        .popup-empty strong {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: #475569;
        }

        .popup-empty span {
            font-size: 10px;
            color: #94a3b8;
        }

        .product-list-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #edf1f5;
        }

        .product-list-footer .btn {
            height: 36px;
            padding: 0 14px;
            border-radius: 9px;
            font-size: 11px;
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

        .btn-secondary {
            background: #e2e8f0;
            border-color: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
            border-color: #cbd5e1;
            color: #334155;
        }


        /* =====================================================
                   SWEETALERT
                ===================================================== */
        .swal2-container {
            z-index: 11000 !important;
        }

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

            .product-list-box {
                max-height: 90vh;
                padding: 18px;
            }

            .popup-product-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .popup-product-actions {
                width: 100%;
            }

            .popup-action-btn {
                flex: 1;
            }

            .product-list-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .product-list-footer .btn {
                width: 100%;
            }

        }
    </style>


    {{-- =====================================================
    LINE DASHBOARD
===================================================== --}}

    <div class="card">

        <div class="card-body">

            <div class="line-dashboard">

                @forelse($lines as $line)
                    @php
                        $lineModels = $models->where('line_id', $line->id);
                        $modelCount = $lineModels->count();
                        $productCount = $lineModels->sum(function ($model) {
                            return $model->products->count();
                        });
                    @endphp

                    <div class="line-card" data-line-id="{{ $line->id }}" data-line-name="{{ $line->name }}"
                        onclick="loadModel({{ $line->id }}, this)">

                        <div class="line-card-top">

                            <div class="line-icon">
                                🏭
                            </div>

                            <div class="line-info">

                                <h4>
                                    {{ $line->name }}
                                </h4>

                                <span>
                                    {{ $line->plant->name ?? '-' }}
                                </span>

                            </div>

                        </div>


                        <div class="line-stats">

                            <div class="stat-box">

                                <strong>
                                    {{ $modelCount }}
                                </strong>

                                <span>
                                    Model
                                </span>

                            </div>


                            <div class="stat-box">

                                <strong>
                                    {{ $productCount }}
                                </strong>

                                <span>
                                    Produk
                                </span>

                            </div>

                        </div>

                    </div>

                @empty

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
                @endforelse

            </div>

        </div>

    </div>


    {{-- =====================================================
    MODEL TABLE
===================================================== --}}

    <div class="card mt-4">

        <div class="card-body">

            <div class="table-header">

                <div class="table-header-top">

                    <div class="table-title">

                        <h4 id="lineTitle">
                            {{ $selectedLineId
                                ? optional($lines->firstWhere('id', $selectedLineId))->name
                                : $lines->first()?->name ?? 'Pilih Line' }}
                        </h4>

                        <p id="lineSubtitle">
                            {{ $selectedLineId
                                ? optional(optional($lines->firstWhere('id', $selectedLineId))->plant)->name
                                : $lines->first()?->plant->name ?? 'Belum ada Line' }}
                        </p>

                    </div>


                    <button type="button" class="btn btn-primary" onclick="openAddModelModal()">
                        + Tambah Model
                    </button>

                </div>


                <div class="search-box">

                    <span>
                        ⌕
                    </span>

                    <input type="text" id="searchModel" placeholder="Cari model...">

                </div>

            </div>


            <div class="table-responsive">

                <table class="table modern-table" id="modelTable">

                    <thead>

                        <tr>

                            <th width="70">
                                No
                            </th>

                            <th>
                                Model
                            </th>

                            <th width="150">
                                Jumlah Produk
                            </th>

                            <th width="190">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($models as $index => $model)
                            <tr class="model-row" data-line-id="{{ $model->line_id }}" data-model-id="{{ $model->id }}"
                                data-model-name="{{ $model->model }}">

                                <td class="number-cell">
                                    {{ $index + 1 }}
                                </td>


                                {{-- MODEL --}}
                                <td>

                                    <div class="model-info">

                                        <strong class="model-name">
                                            {{ $model->model }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- JUMLAH PRODUK --}}
                                <td>

                                    <span class="product-count-badge">
                                        {{ $model->products->count() }}
                                        Produk
                                    </span>

                                </td>


                                {{-- AKSI MODEL --}}
                                <td class="model-action-cell">

                                    <div class="action-group">

                                        <button type="button" class="btn btn-warning btn-sm edit-model-btn"
                                            data-id="{{ $model->id }}" data-line-id="{{ $model->line_id }}"
                                            data-name="{{ $model->model }}">
                                            Edit
                                        </button>


                                        <form method="POST" action="{{ route('omd.master.model.destroy', $model->id) }}"
                                            class="delete-model-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- =================================================
                             PRODUCT SOURCE UNTUK POPUP
                        ================================================== --}}

                            <template id="product-source-{{ $model->id }}">

                                @forelse($model->products as $product)
                                    <div class="popup-product-item">

                                        <span class="popup-product-name">
                                            {{ $product->name }}
                                        </span>


                                        <div class="popup-product-actions">

                                            <button type="button"
                                                class="popup-action-btn popup-action-edit edit-product-btn"
                                                data-id="{{ $product->id }}"
                                                data-model-id="{{ $product->master_model_id }}"
                                                data-name="{{ $product->name }}">
                                                Edit
                                            </button>


                                            <form method="POST"
                                                action="{{ route('omd.master.product.destroy', $product->id) }}"
                                                class="delete-product-form">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="popup-action-btn popup-action-delete">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                @empty

                                    <div class="popup-empty">

                                        <strong>
                                            Belum ada Produk
                                        </strong>

                                        <span>
                                            Tambahkan Produk untuk Model ini.
                                        </span>

                                    </div>
                                @endforelse

                            </template>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="empty-table">

                                        <div class="empty-table-icon">
                                            !
                                        </div>

                                        <strong>
                                            Belum ada Model
                                        </strong>

                                        <span>
                                            Tambahkan Model terlebih dahulu.
                                        </span>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =====================================================
    MODAL PRODUCT LIST
===================================================== --}}

    <div id="productListModal" class="modal-overlay">

        <div class="product-list-box">

            <div class="product-list-header">

                <div>

                    <h4 id="productListTitle">
                        Produk Model
                    </h4>

                </div>


                <div class="product-list-header-right">

                    <span id="productListCount" class="product-list-count">
                        0 Produk
                    </span>

                    <button type="button" class="modal-close" onclick="closeProductListModal()">
                        ×
                    </button>

                </div>

            </div>


            <div id="productListContent" class="product-list-content">
            </div>


            <div class="product-list-footer">

                <button type="button" class="btn btn-secondary" onclick="closeProductListModal()">
                    Tutup
                </button>


                <button type="button" class="btn btn-primary" id="addProductFromListBtn">
                    + Tambah Produk
                </button>

            </div>

        </div>

    </div>


    {{-- =====================================================
    MODAL TAMBAH MODEL
===================================================== --}}

    <div id="addModelModal" class="modal-overlay">

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

                <button type="button" class="modal-close" onclick="closeAddModelModal()">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.model.store') }}">

                @csrf


                <input type="hidden" name="line_id" id="addModelLineId" value="{{ $selectedLineId }}">


                <div class="selected-line-box">

                    <span>
                        Line yang dipilih
                    </span>

                    <strong id="selectedLineName">
                        {{ $selectedLineId ? optional($lines->firstWhere('id', $selectedLineId))->name : $lines->first()?->name ?? '-' }}
                    </strong>

                </div>


                <label for="addModelName" class="form-label">
                    Nama Model
                </label>

                <input type="text" name="model" id="addModelName" class="form-control"
                    placeholder="Contoh: Model A" required>


                <div class="modal-action">

                    <button type="button" onclick="closeAddModelModal()" class="btn btn-secondary">
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
    MODAL EDIT MODEL
===================================================== --}}

    <div id="editModelModal" class="modal-overlay">

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

                <button type="button" class="modal-close" onclick="closeEditModelModal()">
                    ×
                </button>

            </div>


            <form method="POST" id="editModelForm">

                @csrf
                @method('PUT')


                <input type="hidden" name="line_id" id="editModelLineId">


                <label for="editModelName" class="form-label">
                    Nama Model
                </label>

                <input type="text" name="model" id="editModelName" class="form-control" required>


                <div class="modal-action">

                    <button type="button" onclick="closeEditModelModal()" class="btn btn-secondary">
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
    MODAL TAMBAH PRODUK
===================================================== --}}

    <div id="addProductModal" class="modal-overlay">

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

                <button type="button" class="modal-close" onclick="closeAddProductModal()">
                    ×
                </button>

            </div>


            <form method="POST" action="{{ route('omd.master.product.store') }}">

                @csrf


                <input type="hidden" name="master_model_id" id="addProductModelId">


                <div class="selected-line-box">

                    <span>
                        Model
                    </span>

                    <strong id="selectedModelName">
                        -
                    </strong>

                </div>


                <label for="addProductName" class="form-label">
                    Nama Produk
                </label>

                <input type="text" name="name" id="addProductName" class="form-control"
                    placeholder="Contoh: Produk A" required>


                <div class="modal-action">

                    <button type="button" onclick="closeAddProductModal()" class="btn btn-secondary">
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
    MODAL EDIT PRODUK
===================================================== --}}

    <div id="editProductModal" class="modal-overlay">

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

                <button type="button" class="modal-close" onclick="closeEditProductModal()">
                    ×
                </button>

            </div>


            <form method="POST" id="editProductForm">

                @csrf
                @method('PUT')


                <input type="hidden" name="master_model_id" id="editProductModelId">


                <label for="editProductName" class="form-label">
                    Nama Produk
                </label>

                <input type="text" name="name" id="editProductName" class="form-control" required>


                <div class="modal-action">

                    <button type="button" onclick="closeEditProductModal()" class="btn btn-secondary">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
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

        let selectedLineId =
            @json($selectedLineId);


        /* =====================================================
           PRODUCT STATE
        ===================================================== */

        let activeProductModelId =
            null;

        let activeProductModelName =
            '';


        /* =====================================================
           LOAD MODEL BY LINE
        ===================================================== */

        function loadModel(id, element) {

            document
                .querySelectorAll('.line-card')
                .forEach(
                    function(card) {
                        card.classList.remove('active');
                    }
                );


            if (element) {

                element.classList.add('active');

            }


            selectedLineId =
                String(id);


            document.getElementById(
                    'addModelLineId'
                ).value =
                id;


            document.getElementById(
                    'selectedLineName'
                ).innerText =
                element.dataset.lineName;


            document.getElementById(
                    'lineTitle'
                ).innerText =
                element.dataset.lineName;


            const plantName =
                element.querySelector(
                    '.line-info span'
                )?.innerText || '-';


            document.getElementById(
                    'lineSubtitle'
                ).innerText =
                plantName;


            filterModels();

        }


        /* =====================================================
           FILTER MODEL
        ===================================================== */

        function filterModels() {

            const keyword =
                document.getElementById(
                    'searchModel'
                )
                .value
                .toLowerCase()
                .trim();


            const rows =
                document.querySelectorAll(
                    '.model-row'
                );


            let visibleCount =
                0;


            rows.forEach(
                function(row) {

                    const lineId =
                        String(
                            row.dataset.lineId
                        );


                    const text =
                        row.innerText
                        .toLowerCase();


                    const sameLine =
                        String(
                            selectedLineId
                        ) === lineId;


                    const matchSearch =
                        keyword === '' ||
                        text.includes(keyword);


                    if (
                        sameLine &&
                        matchSearch
                    ) {

                        row.style.display =
                            '';

                        visibleCount++;

                    } else {

                        row.style.display =
                            'none';

                    }

                }
            );


            document
                .querySelector(
                    '.empty-filter-row'
                )
                ?.remove();


            if (
                visibleCount === 0 &&
                selectedLineId
            ) {

                const tbody =
                    document.querySelector(
                        '#modelTable tbody'
                    );


                const row =
                    document.createElement(
                        'tr'
                    );


                row.className =
                    'empty-filter-row';


                row.innerHTML = `
                <td colspan="4">

                    <div class="empty-table">

                        <div class="empty-table-icon">
                            !
                        </div>

                        <strong>
                            Data tidak ditemukan
                        </strong>

                        <span>
                            Tidak ada Model yang sesuai.
                        </span>

                    </div>

                </td>
            `;


                tbody.appendChild(row);

            }


            let no =
                1;


            document
                .querySelectorAll(
                    '.model-row'
                )
                .forEach(
                    function(row) {

                        if (
                            row.style.display !==
                            'none'
                        ) {

                            const numberCell =
                                row.querySelector(
                                    '.number-cell'
                                );


                            if (
                                numberCell
                            ) {

                                numberCell.textContent =
                                    no++;

                            }

                        }

                    }
                );

        }


        /* =====================================================
           SEARCH
        ===================================================== */

        document
            .getElementById(
                'searchModel'
            )
            .addEventListener(
                'input',
                function() {

                    filterModels();

                }
            );


        /* =====================================================
           CLICK ENTIRE MODEL ROW
        ===================================================== */

        document
            .querySelector(
                '#modelTable tbody'
            )
            .addEventListener(
                'click',
                function(event) {

                    const actionCell =
                        event.target.closest(
                            '.model-action-cell'
                        );


                    if (
                        actionCell
                    ) {

                        return;

                    }


                    const row =
                        event.target.closest(
                            '.model-row'
                        );


                    if (
                        !row
                    ) {

                        return;

                    }


                    const modelId =
                        row.dataset.modelId;


                    const modelName =
                        row.dataset.modelName;


                    openProductListModal(
                        modelId,
                        modelName
                    );

                }
            );


        /* =====================================================
           EDIT MODEL BUTTON
        ===================================================== */

        document
            .querySelectorAll(
                '.edit-model-btn'
            )
            .forEach(
                function(button) {

                    button.addEventListener(
                        'click',
                        function(event) {

                            event.stopPropagation();


                            openEditModelModal(
                                this.dataset.id,
                                this.dataset.lineId,
                                this.dataset.name
                            );

                        }
                    );

                }
            );


        /* =====================================================
           PRODUCT LIST MODAL
        ===================================================== */

        function openProductListModal(
            modelId,
            modelName
        ) {

            activeProductModelId =
                modelId;


            activeProductModelName =
                modelName;


            document.getElementById(
                    'productListTitle'
                ).innerText =
                'Daftar Produk ' + modelName;


            const source =
                document.getElementById(
                    'product-source-' + modelId
                );


            const content =
                document.getElementById(
                    'productListContent'
                );


            if (
                source
            ) {

                content.innerHTML =
                    source.innerHTML;

            } else {

                content.innerHTML = `
                <div class="popup-empty">

                    <strong>
                        Belum ada Produk
                    </strong>

                    <span>
                        Tambahkan Produk untuk Model ini.
                    </span>

                </div>
            `;

            }


            const productItems =
                content.querySelectorAll(
                    '.popup-product-item'
                );


            document.getElementById(
                    'productListCount'
                ).innerText =
                productItems.length +
                ' Produk';


            document.getElementById(
                    'addProductFromListBtn'
                ).onclick =
                function() {

                    openAddProductModal(
                        modelId,
                        modelName
                    );

                };


            bindPopupProductActions();


            document.getElementById(
                    'productListModal'
                ).style.display =
                'flex';

        }


        function closeProductListModal() {

            document.getElementById(
                    'productListModal'
                ).style.display =
                'none';

        }


        /* =====================================================
           POPUP PRODUCT ACTION
        ===================================================== */

        function bindPopupProductActions() {

            document
                .querySelectorAll(
                    '#productListContent .edit-product-btn'
                )
                .forEach(
                    function(button) {

                        button.addEventListener(
                            'click',
                            function(event) {

                                event.stopPropagation();


                                openEditProductModal(
                                    this.dataset.id,
                                    this.dataset.modelId,
                                    this.dataset.name
                                );

                            }
                        );

                    }
                );


            document
                .querySelectorAll(
                    '#productListContent .delete-product-form'
                )
                .forEach(
                    function(form) {

                        form.addEventListener(
                            'submit',
                            function(event) {

                                event.preventDefault();


                                const targetForm =
                                    this;


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

                                    })
                                    .then(
                                        function(result) {

                                            if (
                                                result.isConfirmed
                                            ) {

                                                targetForm.submit();

                                            }

                                        }
                                    );

                            }
                        );

                    }
                );

        }


        /* =====================================================
           ADD MODEL
        ===================================================== */

        function openAddModelModal() {

            if (
                !selectedLineId
            ) {

                const firstLine =
                    document.querySelector(
                        '.line-card'
                    );


                if (
                    firstLine
                ) {

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


            document.getElementById(
                    'addModelModal'
                ).style.display =
                'flex';

        }


        function closeAddModelModal() {

            document.getElementById(
                    'addModelModal'
                ).style.display =
                'none';

        }


        /* =====================================================
           EDIT MODEL
        ===================================================== */

        function openEditModelModal(
            id,
            lineId,
            name
        ) {

            document.getElementById(
                    'editModelModal'
                ).style.display =
                'flex';


            document.getElementById(
                    'editModelLineId'
                ).value =
                lineId;


            document.getElementById(
                    'editModelName'
                ).value =
                name;


            document.getElementById(
                    'editModelForm'
                ).action =
                "{{ url('/omd/master/model') }}/" +
                id;

        }


        function closeEditModelModal() {

            document.getElementById(
                    'editModelModal'
                ).style.display =
                'none';

        }


        /* =====================================================
           ADD PRODUCT
        ===================================================== */

        function openAddProductModal(
            modelId,
            modelName
        ) {

            activeProductModelId =
                modelId;


            activeProductModelName =
                modelName;


            document.getElementById(
                    'addProductModelId'
                ).value =
                modelId;


            document.getElementById(
                    'selectedModelName'
                ).innerText =
                modelName;


            document.getElementById(
                    'productListModal'
                ).style.display =
                'none';


            document.getElementById(
                    'addProductModal'
                ).style.display =
                'flex';

        }


        function closeAddProductModal() {

            document.getElementById(
                    'addProductModal'
                ).style.display =
                'none';

        }


        /* =====================================================
           EDIT PRODUCT
        ===================================================== */

        function openEditProductModal(
            id,
            modelId,
            name
        ) {

            document.getElementById(
                    'editProductModal'
                ).style.display =
                'flex';


            document.getElementById(
                    'editProductModelId'
                ).value =
                modelId;


            document.getElementById(
                    'editProductName'
                ).value =
                name;


            document.getElementById(
                    'editProductForm'
                ).action =
                "{{ url('/omd/master/product') }}/" +
                id;

        }


        function closeEditProductModal() {

            document.getElementById(
                    'editProductModal'
                ).style.display =
                'none';

        }


        /* =====================================================
           DELETE MODEL
        ===================================================== */

        document.addEventListener(
            'submit',
            function(e) {

                if (
                    !e.target.classList.contains(
                        'delete-model-form'
                    )
                ) {

                    return;

                }


                e.preventDefault();


                const form =
                    e.target;


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


        /* =====================================================
           CLICK OUTSIDE MODAL
        ===================================================== */

        document
            .querySelectorAll(
                '.modal-overlay'
            )
            .forEach(
                function(modal) {

                    modal.addEventListener(
                        'click',
                        function(e) {

                            if (
                                e.target === modal
                            ) {

                                modal.style.display =
                                    'none';

                            }

                        }
                    );

                }
            );


        /* =====================================================
           INITIAL LINE
        ===================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                let targetId =
                    selectedLineId;


                let targetCard =
                    null;


                if (
                    targetId
                ) {

                    targetCard =
                        document.querySelector(
                            `.line-card[data-line-id="${targetId}"]`
                        );

                }


                if (
                    !targetCard
                ) {

                    targetCard =
                        document.querySelector(
                            '.line-card'
                        );

                }


                if (
                    targetCard
                ) {

                    loadModel(
                        targetCard.dataset.lineId,
                        targetCard
                    );

                }

            }
        );
    </script>

@endsection
