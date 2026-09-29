@extends('layouts.app')

@section('title', 'Detail Order Repair Box')
@section('header', 'Detail Order Repair Box')

@section('content')

    <style>
        .repair-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .repair-head h2 {
            margin: 0 0 7px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .repair-head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .repair-head-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .repair-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            padding: 22px;
        }

        .repair-card+.repair-card {
            margin-top: 18px;
        }

        .repair-card-title {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .repair-card-desc {
            margin: 0 0 18px;
            font-size: 11px;
            color: #64748b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-submitted {
            background: #f1efff;
            color: #6557dc;
        }

        .status-in_repair {
            background: #fff7e8;
            color: #b77906;
        }

        .status-completed {
            background: #ecfdf3;
            color: #15803d;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-draft {
            background: #f1f5f9;
            color: #64748b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .info-item {
            padding: 13px;
            border-radius: 11px;
            background: #f8fafc;
            border: 1px solid #edf1f5;
        }

        .info-item span {
            display: block;
            margin-bottom: 5px;
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .info-item strong {
            font-size: 12px;
            color: #334155;
        }

        .description-box {
            padding: 13px;
            border-radius: 11px;
            background: #fafbfc;
            border: 1px solid #edf1f5;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .repair-table-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #edf1f5;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 920px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 920px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 920px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }

        .repair-table th {
            padding: 10px 8px;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: center;
            white-space: nowrap;
        }

        .repair-table td {
            padding: 11px 8px;
            border-bottom: 1px solid #eef2f6;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
            background: #fff;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: none;
        }

        .repair-table tbody tr:hover td {
            background: #fcfcff;
        }

        .model-cell,
        .no-cell {
            font-weight: 800;
            color: #172033;
            vertical-align: middle !important;
        }

        .model-cell {
            text-align: left;
        }

        .no-cell {
            text-align: center;
        }

        .model-text {
            font-weight: 800;
            color: #172033;
        }

        .product-text {
            font-weight: 650;
            color: #475569;
        }

        .ng-value {
            min-height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #334155;
        }

        .empty-ng {
            color: #cbd5e1;
            font-size: 12px;
        }

        .confirm-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .confirm-box-info strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .confirm-box-info span {
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
        }

        .confirmed-box {
            padding: 16px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .confirmed-box strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .confirmed-box span {
            color: #4d7a5c;
            font-size: 10px;
            line-height: 1.5;
        }

        .btn {
            height: 36px;
            padding: 0 14px;
            border: none;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .18s ease;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
            box-shadow: 0 6px 15px rgba(22, 163, 74, .16);
        }

        .btn-success:hover {
            background: #15803d;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .timeline-wrap {
            overflow-x: auto;
            padding: 8px 2px 5px;
        }

        .timeline {
            min-width: 620px;
            display: grid;
            grid-template-columns: repeat(4, minmax(140px, 1fr));
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 12.5%;
            right: 12.5%;
            top: 18px;
            height: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            text-align: center;
            padding: 0 8px;
            z-index: 1;
        }

        .timeline-icon {
            width: 28px;
            height: 28px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 15px;
            font-weight: 800;
            background: #fee2e2;
            color: #dc2626;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #fecaca;
        }

        .timeline-item.active .timeline-icon {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 0 0 2px #bbf7d0;
        }

        .timeline-item strong {
            display: block;
            margin-bottom: 5px;
            color: #334155;
            font-size: 11px;
            font-weight: 800;
        }

        .timeline-item span {
            display: block;
            font-size: 9px;
            line-height: 1.5;
            color: #dc2626;
        }

        .timeline-item.active span {
            color: #64748b;
        }

        .error-list {
            margin: 0 0 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff1f2;
            border: 1px solid #ffd8dd;
            color: #b42318;
            font-size: 11px;
        }

        @media (max-width: 1000px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {

            .repair-head {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .confirm-box {
                align-items: flex-start;
                flex-direction: column;
            }

            .confirm-box .btn {
                width: 100%;
            }
        }
    </style>


    <div class="repair-head">

        <div>

            <h2>
                {{ $order->order_number }}
            </h2>

            <p>
                Order Repair Box
            </p>

        </div>


        <div class="repair-head-actions">

            <span class="status-badge status-{{ $order->status }}">
                {{ $order->status_label }}
            </span>


            <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>


    @if ($errors->any())

        <div class="error-list">

            <ul style="margin:0;padding-left:16px;">

                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
     INFORMASI ORDER
===================================================== --}}

    <div class="repair-card">

        <h3 class="repair-card-title">
            Informasi Order
        </h3>


        <div class="info-grid">

            <div class="info-item">

                <span>
                    No Order
                </span>

                <strong>
                    {{ $order->order_number }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Tanggal
                </span>

                <strong>
                    {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Nama
                </span>

                <strong>
                    {{ $order->user?->name ?? '-' }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Plant
                </span>

                <strong>
                    {{ $order->line?->plant?->name ?? '-' }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Line
                </span>

                <strong>
                    {{ $order->line?->name ?? '-' }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Jenis Order
                </span>

                <strong>
                    Repair Box
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Total Qty
                </span>

                <strong>
                    {{ $order->quantity }}
                </strong>

            </div>


            <div class="info-item">

                <span>
                    Status
                </span>

                <strong>
                    {{ $order->status_label }}
                </strong>

            </div>

        </div>


        <div style="margin-top:18px;">

            <div style="margin-bottom:7px;font-size:11px;font-weight:800;color:#334155;">
                Keterangan
            </div>

            <div class="description-box">
                {{ $order->description ?: '-' }}
            </div>

        </div>

    </div>


    {{-- =====================================================
     DETAIL HASIL REPAIR
===================================================== --}}

    @if ($order->items->isNotEmpty())

        @php

            $modelGroups = $order->items->groupBy('master_model_id');

            $ngCodes = ['P', 'H', 'C', 'S'];

        @endphp


        <div class="repair-card">

            <h3 class="repair-card-title">
                Detail Repair Box
            </h3>


            <p class="repair-card-desc">
                Perbandingan quantity NG sebelum dan sesudah repair.
            </p>


            <div class="repair-table-scroll">


                {{-- HEADER --}}
                <div class="repair-table-header">

                    <table class="repair-table">

                        <colgroup>

                            <col style="width:55px;">
                            <col style="width:150px;">
                            <col style="width:170px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                        </colgroup>


                        <thead>

                            <tr>

                                <th rowspan="3">
                                    No
                                </th>

                                <th rowspan="3" style="text-align:left;">
                                    Model
                                </th>

                                <th rowspan="3" style="text-align:left;">
                                    Produk
                                </th>

                                <th colspan="8">
                                    Jenis &amp; Qty NG
                                </th>

                            </tr>


                            <tr>

                                <th colspan="4">
                                    Sebelum
                                </th>

                                <th colspan="4">
                                    Sesudah
                                </th>

                            </tr>


                            <tr>

                                @foreach ($ngCodes as $code)
                                    <th>
                                        {{ $code }}
                                    </th>
                                @endforeach


                                @foreach ($ngCodes as $code)
                                    <th>
                                        {{ $code }}
                                    </th>
                                @endforeach

                            </tr>

                        </thead>

                    </table>

                </div>


                {{-- BODY --}}
                <div class="repair-table-body">

                    <table class="repair-table">

                        <colgroup>

                            <col style="width:55px;">
                            <col style="width:150px;">
                            <col style="width:170px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">
                            <col style="width:68px;">

                        </colgroup>


                        <tbody>

                            @foreach ($modelGroups as $modelItems)
                                @php

                                    $productGroups = $modelItems->groupBy('product_id');

                                    $modelRowspan = $productGroups->count();

                                @endphp


                                @foreach ($productGroups as $productItems)
                                    @php

                                        $ngItems = $productItems->keyBy(function ($item) {
                                            return strtoupper($item->ngType?->code ?? '');
                                        });

                                    @endphp


                                    <tr>

                                        @if ($loop->first)
                                            <td rowspan="{{ $modelRowspan }}" class="no-cell">
                                                {{ $loop->parent->iteration }}
                                            </td>


                                            <td rowspan="{{ $modelRowspan }}" class="model-cell">

                                                <span class="model-text">
                                                    {{ $modelItems->first()->masterModel?->model ?? '-' }}
                                                </span>

                                            </td>
                                        @endif


                                        <td>

                                            <span class="product-text">
                                                {{ $productItems->first()->product?->name ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- SEBELUM --}}
                                        @foreach ($ngCodes as $code)
                                            @php
                                                $ngItem = $ngItems->get($code);
                                            @endphp


                                            <td style="text-align:center;">

                                                @if ($ngItem)
                                                    <div class="ng-value">
                                                        {{ $ngItem->before_qty }}
                                                    </div>
                                                @else
                                                    <div class="ng-value empty-ng">
                                                        -
                                                    </div>
                                                @endif

                                            </td>
                                        @endforeach


                                        {{-- SESUDAH --}}
                                        @foreach ($ngCodes as $code)
                                            @php
                                                $ngItem = $ngItems->get($code);
                                            @endphp


                                            <td style="text-align:center;">

                                                @if ($ngItem)
                                                    <div class="ng-value">

                                                        @if ($ngItem->after_qty !== null)
                                                            {{ $ngItem->after_qty }}
                                                        @else
                                                            -
                                                        @endif

                                                    </div>
                                                @else
                                                    <div class="ng-value empty-ng">
                                                        -
                                                    </div>
                                                @endif

                                            </td>
                                        @endforeach

                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    @elseif ($order->result)
        {{-- FALLBACK DATA LAMA --}}

        <div class="repair-card">

            <h3 class="repair-card-title">
                Hasil Repair
            </h3>


            <div class="info-grid">

                <div class="info-item">

                    <span>
                        OK
                    </span>

                    <strong>
                        {{ $order->result->ok_qty }}
                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        SCRAP
                    </span>

                    <strong>
                        {{ $order->result->scrap_qty }}
                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        NG
                    </span>

                    <strong>
                        {{ $order->result->ng_qty }}
                    </strong>

                </div>

            </div>


            <div class="description-box" style="margin-top:14px;">
                {{ $order->result->notes ?: 'Tidak ada catatan.' }}
            </div>

        </div>

    @endif


    {{-- =====================================================
     KONFIRMASI USER
===================================================== --}}

    @if ($order->status === 'completed')

        <div class="repair-card">

            <div class="confirm-box">

                <div class="confirm-box-info">

                    <strong>
                        Order Repair Box Selesai
                    </strong>

                    <span>
                        OMD telah menyelesaikan proses repair.
                        Silakan periksa hasil repair dan lakukan konfirmasi penerimaan.
                    </span>

                </div>


                <form method="POST" action="{{ route('user.orders.confirm', $order) }}">

                    @csrf

                    <button type="submit" class="btn btn-success">
                        Konfirmasi Penerimaan
                    </button>

                </form>

            </div>

        </div>
    @elseif ($order->status === 'confirmed')
        <div class="repair-card">

            <div class="confirmed-box">

                <strong>
                    Order Telah Dikonfirmasi
                </strong>

                <span>
                    Order Repair Box telah dikonfirmasi dan proses telah selesai.
                </span>


                @if ($order->confirmation?->confirmed_at)
                    <div style="margin-top:8px;font-size:10px;color:#4d7a5c;">

                        Dikonfirmasi pada

                        <strong style="display:inline;font-size:10px;">
                            {{ $order->confirmation->confirmed_at->format('d-m-Y H:i') }}
                        </strong>

                    </div>
                @endif

            </div>

        </div>

    @endif


    {{-- =====================================================
     PROGRESS ORDER
===================================================== --}}

    <div class="repair-card">

        <h3 class="repair-card-title">
            Progress Order
        </h3>


        <p class="repair-card-desc">
            Riwayat tahapan Order Repair Box.
        </p>


        <div class="timeline-wrap">

            <div class="timeline">


                {{-- USER SUBMIT --}}
                <div class="timeline-item active">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        User Submit
                    </strong>

                    <span>
                        {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </span>

                </div>


                {{-- VERIFIED OMD --}}
                <div
                    class="timeline-item
                {{ in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? 'active' : '' }}">

                    <div class="timeline-icon">

                        {{ in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? '✓' : '✕' }}

                    </div>


                    <strong>
                        Verified OMD
                    </strong>


                    <span>

                        @if ($order->verified_at)
                            {{ $order->verified_at->format('d-m-Y H:i') }}
                        @else
                            Belum dilakukan
                        @endif

                    </span>

                </div>


                {{-- REPAIR OMD --}}
                <div
                    class="timeline-item
                {{ in_array($order->status, ['completed', 'confirmed']) ? 'active' : '' }}">

                    <div class="timeline-icon">

                        @if (in_array($order->status, ['completed', 'confirmed']))
                            ✓
                        @elseif ($order->status === 'in_repair')
                            △
                        @else
                            ✕
                        @endif

                    </div>


                    <strong>
                        Repair OMD
                    </strong>


                    <span>

                        @if ($order->repair_completed_at)
                            {{ $order->repair_completed_at->format('d-m-Y H:i') }}
                        @elseif ($order->repair_started_at)
                            Sedang diproses
                        @else
                            Belum dilakukan
                        @endif

                    </span>

                </div>


                {{-- SERAH TERIMA --}}
                <div
                    class="timeline-item
                {{ $order->status === 'confirmed' ? 'active' : '' }}">

                    <div class="timeline-icon">

                        @if ($order->status === 'confirmed')
                            ✓
                        @elseif ($order->status === 'completed')
                            △
                        @else
                            ✕
                        @endif

                    </div>


                    <strong>
                        Serah Terima
                    </strong>


                    <span>

                        @if ($order->status === 'confirmed' && $order->confirmation?->confirmed_at)
                            {{ $order->confirmation->confirmed_at->format('d-m-Y H:i') }}
                        @elseif ($order->status === 'completed')
                            Menunggu konfirmasi User
                        @else
                            Belum dilakukan
                        @endif

                    </span>

                </div>

            </div>

        </div>

    </div>

@endsection
