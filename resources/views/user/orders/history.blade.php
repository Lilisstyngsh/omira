@extends('layouts.app')

@section('title', 'History Order Repair Box')
@section('header', 'History Order Repair Box')

@section('content')

    <style>
        .page-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-head h2 {
            margin: 0;
            font-size: 17px;
            color: #111827;
        }

        /* =========================================================
                               TABLE
                            ========================================================= */

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px 14px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            white-space: nowrap;
            border-bottom: 1px solid #e9eef4;
        }

        .orders-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #374151;
            vertical-align: middle;
        }

        .orders-table tbody tr {
            cursor: pointer;
            transition: .18s ease;
        }

        .orders-table tbody tr:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .orders-table tbody tr:active {
            background: #f1f5f9;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 700;
            text-align: center;
        }

        .order-number {
            font-weight: 800;
            color: #111827;
            white-space: nowrap;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        /* =========================================================
                               HISTORY FILTER
                            ========================================================= */

        .history-filter-box {
            margin-top: 18px;
            margin-bottom: 20px;
            padding: 16px 18px;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            background: linear-gradient(180deg,
                    #fbfcfd 0%,
                    #f8fafc 100%);
            box-shadow: 0 4px 16px rgba(15, 23, 42, .03);
        }

        .history-filter-form {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            flex-wrap: wrap;
        }

        .history-filter-field {
            min-width: 180px;
        }

        .history-filter-field label {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .history-date-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .history-date-input i {
            position: absolute;
            left: 11px;
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
        }

        .history-date-input input {
            width: 100%;
            height: 38px;
            padding: 0 34px 0 32px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            outline: none;
            transition:
                border-color .18s ease,
                box-shadow .18s ease,
                background .18s ease;
        }

        .history-date-input input:hover {
            border-color: #cbd5e1;
        }

        .history-date-input input:focus {
            border-color: #818cf8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-filter-actions {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .history-filter-btn {
            height: 38px;
            padding: 0 15px;
            border: none;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition:
                transform .16s ease,
                background .16s ease,
                box-shadow .16s ease;
        }

        .history-filter-btn:hover {
            transform: translateY(-1px);
        }

        .history-filter-btn-primary {
            background: #334155;
            color: #fff;
            box-shadow: 0 5px 12px rgba(51, 65, 85, .14);
        }

        .history-filter-btn-primary:hover {
            background: #1e293b;
            color: #fff;
            box-shadow: 0 7px 16px rgba(51, 65, 85, .18);
        }

        .history-filter-btn-reset {
            background: #fff;
            color: #64748b;
            border: 1px solid #dbe2ea;
        }

        .history-filter-btn-reset:hover {
            background: #f8fafc;
            color: #334155;
        }

        .history-filter-result {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            padding-top: 11px;
            border-top: 1px dashed #e2e8f0;
            color: #64748b;
            font-size: 10px;
        }

        .history-filter-result strong {
            color: #334155;
            font-weight: 800;
        }

        /* =========================================================
                               TOOLBAR
                            ========================================================= */

        .history-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 17px;
        }

        .history-per-page {
            display: flex;
            align-items: center;
        }

        .history-per-page-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .history-per-page-label select {
            width: 72px;
            min-width: 72px;
            height: 34px;
            padding: 0 22px 0 10px;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            outline: none;
            cursor: pointer;
            box-sizing: border-box;
            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .history-per-page-label select:hover {
            border-color: #cbd5e1;
        }

        .history-per-page-label select:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-search {
            width: 320px;
            max-width: 100%;
        }

        .history-search-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .history-search-input>i {
            position: absolute;
            left: 12px;
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
        }

        .history-search-input input {
            width: 100%;
            height: 34px;
            padding: 0 38px 0 32px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 10px;
            font-weight: 600;
            outline: none;
            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .history-search-input input::placeholder {
            color: #a3adba;
            font-weight: 500;
        }

        .history-search-input input:hover {
            border-color: #cbd5e1;
        }

        .history-search-input input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-search-clear {
            position: absolute;
            right: 8px;
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
        }

        .history-search-clear:hover {
            background: #f1f5f9;
            color: #475569;
        }

        /* =========================================================
                       PAGINATION
                    ========================================================= */

        .history-pagination {
            margin-top: 20px;

            display: flex;
            justify-content: flex-end;
            align-items: center;

            width: 100%;
        }

        .history-pagination-list {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .history-pagination-btn {
            min-width: 34px;
            height: 34px;

            padding: 0 10px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            box-sizing: border-box;

            border: 1px solid #dbe2ea;
            border-radius: 7px;

            background: #fff;

            color: #64748b;

            font-size: 10px;
            font-weight: 700;

            line-height: 1;
            text-decoration: none;

            transition:
                background .15s ease,
                border-color .15s ease,
                color .15s ease;
        }

        .history-pagination-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .history-pagination-btn.active {
            background: #334155;
            border-color: #334155;
            color: #fff;
        }

        .history-pagination-btn.disabled {
            background: #f8fafc;
            border-color: #e5e7eb;
            color: #cbd5e1;

            cursor: not-allowed;
        }

        .history-pagination-prev,
        .history-pagination-next {
            min-width: 78px;
        }

        .history-pagination-dots {
            min-width: 28px;
            height: 34px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            color: #94a3b8;

            font-size: 11px;
            font-weight: 700;
        }

        @media (max-width: 600px) {
            .history-pagination {
                justify-content: center;
            }

            .history-pagination-list {
                gap: 3px;
            }

            .history-pagination-prev,
            .history-pagination-next {
                min-width: 34px;
                padding: 0 8px;
                font-size: 0;
            }

            .history-pagination-prev::before {
                content: '‹';
                font-size: 14px;
            }

            .history-pagination-next::before {
                content: '›';
                font-size: 14px;
            }
        }

        /* =========================================================
                               RESPONSIVE
                            ========================================================= */

        @media (max-width: 900px) {
            .history-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .history-search {
                width: 100%;
            }

            .history-filter-form {
                align-items: stretch;
            }

            .history-filter-field {
                flex: 1 1 180px;
            }

            .history-filter-actions {
                width: 100%;
            }

            .history-filter-btn {
                flex: 1;
            }
        }

        @media (max-width: 600px) {
            .history-filter-form {
                flex-direction: column;
            }

            .history-filter-field {
                width: 100%;
            }

            .history-filter-actions {
                width: 100%;
            }
        }
    </style>

    <div class="page-card">

        <div class="page-head">
            <h2>
                History Order Repair Box
            </h2>
        </div>

        {{-- =========================================================
             FORM UTAMA
        ========================================================= --}}

        <form action="{{ route('user.orders.history') }}" method="GET">

            {{-- =========================================================
                 FILTER TANGGAL
            ========================================================= --}}

            <div class="history-filter-box">

                <div class="history-filter-form">

                    {{-- DARI TANGGAL --}}
                    <div class="history-filter-field">

                        <label for="start_date">
                            Dari Tanggal
                        </label>

                        <div class="history-date-input">

                            <i class="fa-regular fa-calendar"></i>

                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">

                        </div>

                    </div>

                    {{-- SAMPAI TANGGAL --}}
                    <div class="history-filter-field">

                        <label for="end_date">
                            Sampai Tanggal
                        </label>

                        <div class="history-date-input">

                            <i class="fa-regular fa-calendar"></i>

                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="history-filter-actions">

                        <button type="submit" class="history-filter-btn history-filter-btn-primary">
                            <i class="fa-solid fa-filter"></i>
                            Terapkan Filter
                        </button>

                        @if (request('start_date') || request('end_date'))
                            <a href="{{ route('user.orders.history') }}"
                                class="history-filter-btn history-filter-btn-reset">
                                <i class="fa-solid fa-rotate-left"></i>
                                Reset
                            </a>
                        @endif

                    </div>

                </div>

            </div>

            {{-- =========================================================
                 TOOLBAR
            ========================================================= --}}

            <div class="history-toolbar">

                {{-- PER PAGE --}}
                <div class="history-per-page">

                    <div class="history-per-page-label">

                        <select name="per_page" id="per_page" onchange="this.form.submit()">

                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                                10
                            </option>

                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>
                                25
                            </option>

                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                                50
                            </option>

                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>
                                100
                            </option>

                        </select>

                    </div>

                </div>

                {{-- SEARCH --}}
                <div class="history-search">

                    <div class="history-search-input">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari No Order, User, atau Line..." autocomplete="off">

                        @if (request('search'))
                            <button type="button" class="history-search-clear"
                                onclick="
                                    this.closest('form').querySelector('input[name=search]').value = '';
                                    this.closest('form').submit();
                                "
                                title="Hapus pencarian">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif

                    </div>

                </div>

            </div>

        </form>

        {{-- =========================================================
             TABLE
        ========================================================= --}}

        <div class="orders-table-wrap">

            <table class="orders-table">

                <thead>

                    <tr>

                        <th style="width:55px;">
                            No
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            No Order
                        </th>

                        <th>
                            Jenis Order
                        </th>

                        <th>
                            Line
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($orders as $index => $order)
                        <tr onclick="window.location='{{ route('user.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="order-no">
                                {{ $orders->firstItem() + $index }}
                            </td>

                            <td>
                                {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}
                            </td>

                            <td class="order-number">
                                {{ $order->order_number }}
                            </td>

                            <td>
                                {{ 'Repair Box' }}
                            </td>

                            <td>
                                {{ $order->line?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $order->quantity }}
                            </td>

                            <td>
                                <span class="status status-confirmed">
                                    Selesai
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="order-empty">

                                <div class="order-empty-text">
                                    Belum ada history Order Repair Box.
                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- =========================================================
             PAGINATION
        ========================================================= --}}

        @php
            $orders->appends(request()->query());
            $currentPage = $orders->currentPage();
            $lastPage = $orders->lastPage();
        @endphp

        @if ($lastPage > 1)

            <div class="history-pagination">

                <div class="history-pagination-list">

                    {{-- PREVIOUS --}}
                    @if ($orders->onFirstPage())
                        <span class="history-pagination-btn history-pagination-prev disabled">
                            Previous
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="history-pagination-btn history-pagination-prev">
                            Previous
                        </a>
                    @endif


                    {{-- PAGE 1 --}}
                    <a href="{{ $orders->url(1) }}"
                        class="history-pagination-btn {{ $currentPage == 1 ? 'active' : '' }}">
                        1
                    </a>


                    {{-- DOTS SETELAH PAGE 1 --}}
                    @if ($currentPage > 3)
                        <span class="history-pagination-dots">
                            ...
                        </span>
                    @endif


                    {{-- PAGE SEKITAR CURRENT --}}
                    @for ($page = max(2, $currentPage - 1); $page <= min($lastPage - 1, $currentPage + 1); $page++)
                        <a href="{{ $orders->url($page) }}"
                            class="history-pagination-btn {{ $currentPage == $page ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endfor


                    {{-- DOTS SEBELUM LAST PAGE --}}
                    @if ($currentPage < $lastPage - 2)
                        <span class="history-pagination-dots">
                            ...
                        </span>
                    @endif


                    {{-- LAST PAGE --}}
                    @if ($lastPage > 1)
                        <a href="{{ $orders->url($lastPage) }}"
                            class="history-pagination-btn {{ $currentPage == $lastPage ? 'active' : '' }}">
                            {{ $lastPage }}
                        </a>
                    @endif


                    {{-- NEXT --}}
                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="history-pagination-btn history-pagination-next">
                            Next
                        </a>
                    @else
                        <span class="history-pagination-btn history-pagination-next disabled">
                            Next
                        </span>
                    @endif

                </div>

            </div>

        @endif

    </div>

@endsection
