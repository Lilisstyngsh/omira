@extends('layouts.app')

@section('title', 'Detail History Order Repair Box')
@section('header', 'Detail History Order Repair Box')

@section('content')

    @php

        $hasOmdResult = in_array($order->status, ['completed', 'confirmed'], true);

        $totalQtyOmd = 0;

        if ($hasOmdResult) {
            $totalQtyOmd = $order->items->sum(fn($item) => (int) ($item->after_qty ?? 0));
        }
    @endphp

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


        /* =================================================
                       CARD
                    ================================================== */

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


        /* =================================================
                       STATUS
                    ================================================== */

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

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }


        /* =================================================
                       INFORMASI ORDER
                    ================================================== */

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


        /* =================================================
                       REPAIR TABLE (SAMA PERSIS DENGAN show.blade)
                    ================================================== */

        .repair-table-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 1100px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 1100px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 1100px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
        }

        .repair-table th {
            padding: 10px 8px;
            background: #fafbfc;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            color: #475569;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .repair-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
            background: #fff;
        }

        .repair-table tbody tr:last-child td {
            border-bottom: 1px solid #cbd5e1;
        }

        .repair-table tbody tr:hover td {
            background: #fafbfc;
        }

        .repair-table td.no-cell,
        .repair-table td.model-cell {
            border-right: 1px solid #cbd5e1;
        }

        .repair-table .model-cell,
        .repair-table .no-cell {
            font-weight: 500;
            color: #172033;
            vertical-align: middle !important;
        }

        .repair-table .model-cell {
            text-align: left;
        }

        .repair-table .no-cell {
            text-align: center;
        }

        .model-text {
            font-weight: 500;
            color: #172033;
        }

        .product-text {
            font-weight: 400;
            color: #475569;
        }

        .repair-before-after-label {
            display: block;
            margin-top: 5px;
            margin-bottom: 2px;
            color: #94a3b8;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .repair-result-match-cell {
            background: #dcfce7 !important;
        }

        .repair-result-mismatch-cell {
            background: #fee2e2 !important;
        }

        .repair-result-match-cell .ng-value,
        .repair-result-mismatch-cell .ng-value {
            color: #000;
        }

        .ng-value {
            min-height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 500;
            color: #000;
        }

        .empty-ng {
            color: #cbd5e1;
            font-size: 12px;
        }


        /* =================================================
                       KETERANGAN
                    ================================================== */

        .repair-table .keterangan-head {
            width: 180px !important;
            min-width: 180px !important;
            text-align: center !important;
        }

        .repair-table .keterangan-cell {
            width: 180px !important;
            min-width: 180px !important;
            padding: 2px !important;
            text-align: left !important;
            vertical-align: middle !important;
        }

        .repair-table .keterangan-text {
            width: 100%;
            padding: 3px 8px;
            text-align: left !important;
            color: #475569;
            font-size: 11px;
            line-height: 1.4;
            white-space: pre-wrap;
            word-break: break-word;
            box-sizing: border-box;
        }


        /* =================================================
                       COMPLETED
                    ================================================== */

        .completed-box {
            padding: 15px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: #f0fdf4;
        }

        .completed-box strong {
            display: block;
            margin-bottom: 4px;
            color: #166534;
            font-size: 12px;
        }

        .completed-box span {
            display: block;
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
        }


        /* =================================================
                       TIMELINE
                    ================================================== */

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
            background: #bbf7d0;
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
            background: #dcfce7;
            color: #16a34a;
            border: 3px solid #fff;
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
            color: #64748b;
        }


        /* =================================================
                       BUTTON
                    ================================================== */

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

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }


        /* =================================================
                       RESPONSIVE
                    ================================================== */

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
        }

        .info-item.total-qty-omd {
            background: #ecfdf5;
            border: 1px solid #86efac;
        }

        .info-item.total-qty-omd span {
            color: #15803d;
        }

        .info-item.total-qty-omd strong {
            color: #166534;
            font-size: 14px;
        }
    </style>


    {{-- =====================================================
         HEADER
    ===================================================== --}}

    <div class="repair-head">

        <div>
            <h2>
                {{ $order->order_number }}
            </h2>
        </div>

        <div class="repair-head-actions">
            <a href="{{ route('omd.orders.history') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </div>


    {{-- =====================================================
         PROGRESS ORDER
    ===================================================== --}}

    <div class="repair-card">

        <h3 class="repair-card-title">
            Progress Order
        </h3>

        <div class="timeline-wrap">

            <div class="timeline">

                {{-- USER SUBMIT --}}
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        User Submit
                    </strong>

                    <span>
                        {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}

                        @if ($order->user?->name)
                            <br>
                            {{ $order->user->name }}
                        @endif
                    </span>

                </div>


                {{-- VERIFIED OMD --}}
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Verified OMD
                    </strong>

                    <span>
                        @if ($order->verified_at)
                            {{ $order->verified_at->format('d-m-Y H:i') }}

                            @if ($order->omdVerifier?->name)
                                <br>
                                {{ $order->omdVerifier->name }}
                            @endif
                        @else
                            -
                        @endif
                    </span>

                </div>


                {{-- REPAIR OMD --}}
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Repair OMD
                    </strong>

                    <span>
                        @if ($order->repair_completed_at)
                            {{ $order->repair_completed_at->format('d-m-Y H:i') }}

                            @if ($order->result?->processedBy?->name)
                                <br>
                                {{ $order->result->processedBy->name }}
                            @endif
                        @elseif ($order->repair_started_at)
                            {{ $order->repair_started_at->format('d-m-Y H:i') }}

                            @if ($order->result?->processedBy?->name)
                                <br>
                                {{ $order->result->processedBy->name }}
                            @endif
                        @else
                            -
                        @endif
                    </span>

                </div>


                {{-- SERAH TERIMA --}}
                <div class="timeline-item">

                    <div class="timeline-icon">
                        ✓
                    </div>

                    <strong>
                        Serah Terima
                    </strong>

                    <span>
                        @if ($order->confirmation?->confirmed_at)
                            {{ $order->confirmation->confirmed_at->format('d-m-Y H:i') }}

                            @if ($order->confirmation->user?->name)
                                <br>
                                {{ $order->confirmation->user->name }}
                            @endif
                        @else
                            -
                        @endif
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         INFORMASI ORDER
    ===================================================== --}}

    <div class="repair-card">

        <h3 class="repair-card-title">
            Informasi Order
        </h3>


        <div class="info-grid">

            <div class="info-item">
                <span>No Order</span>
                <strong>{{ $order->order_number }}</strong>
            </div>

            <div class="info-item">
                <span>Tanggal</span>
                <strong>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Nama</span>
                <strong>{{ $order->user?->name ?? '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Plant</span>
                <strong>{{ $order->line?->plant?->name ?? '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Line</span>
                <strong>{{ $order->line?->name ?? '-' }}</strong>
            </div>

            <div class="info-item">
                <span>Jenis Order</span>
                <strong>Repair Box</strong>
            </div>

            <div class="info-item">
                <span>Total Qty</span>
                <strong>{{ $order->quantity }}</strong>
            </div>

            @if ($hasOmdResult)
                <div class="info-item total-qty-omd">

                    <span>
                        Qty OMD
                    </span>

                    <strong>
                        {{ $totalQtyOmd }}
                    </strong>

                </div>
            @endif

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
         DETAIL REPAIR BOX
    ===================================================== --}}

    @if ($order->items->isNotEmpty())

        @php

            $modelGroups = $order->items->groupBy('master_model_id');

            $ngCodes = ['P', 'H', 'C', 'S'];

            $showAfter = in_array($order->status, ['completed', 'confirmed'], true);

        @endphp


        <div class="repair-card">

            <h3 class="repair-card-title">
                Detail Repair Box
            </h3>


            <div class="repair-table-scroll">


                {{-- =================================================
                     HEADER
                ================================================== --}}

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

                            @if ($showAfter)
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                            @endif

                            <col style="width:180px;">

                        </colgroup>


                        <thead>

                            <tr>

                                <th rowspan="{{ $showAfter ? 3 : 2 }}">
                                    No
                                </th>

                                <th rowspan="{{ $showAfter ? 3 : 2 }}" style="text-align:left;">
                                    Model
                                </th>

                                <th rowspan="{{ $showAfter ? 3 : 2 }}" style="text-align:left;">
                                    Produk
                                </th>

                                <th colspan="{{ $showAfter ? 8 : 4 }}">
                                    Jenis &amp; Qty NG
                                </th>

                                <th rowspan="{{ $showAfter ? 3 : 2 }}" class="keterangan-head">
                                    Keterangan
                                </th>

                            </tr>


                            @if ($showAfter)
                                <tr>

                                    <th colspan="4">
                                        Sebelum
                                    </th>

                                    <th colspan="4">
                                        Sesudah
                                    </th>

                                </tr>
                            @endif


                            <tr>

                                @foreach ($ngCodes as $code)
                                    <th>
                                        {{ $code }}
                                    </th>
                                @endforeach


                                @if ($showAfter)
                                    @foreach ($ngCodes as $code)
                                        <th>
                                            {{ $code }}
                                        </th>
                                    @endforeach
                                @endif

                            </tr>

                        </thead>

                    </table>

                </div>


                {{-- =================================================
                     BODY
                ================================================== --}}

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

                            @if ($showAfter)
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                                <col style="width:68px;">
                            @endif

                            <col style="width:180px;">

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

                                        $firstItem = $productItems->first();
                                        $productId = $firstItem->product_id;

                                        $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))
                                            ?->mismatch_note;

                                        $hasFinalResult = in_array($order->status, ['completed', 'confirmed'], true);
                                    @endphp


                                    <tr>


                                        {{-- =========================
                                             NO + MODEL
                                        ========================== --}}

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


                                        {{-- =========================
                                             PRODUK
                                        ========================== --}}

                                        <td>

                                            <span class="product-text">
                                                {{ $firstItem->product?->name ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- =========================
                                             SEBELUM
                                        ========================== --}}

                                        @foreach ($ngCodes as $code)
                                            @php
                                                $ngItem = $ngItems->get($code);
                                            @endphp

                                            <td class="ng-cell">

                                                @if ($ngItem)
                                                    <div class="ng-value">
                                                        {{ $ngItem->before_qty > 0 ? $ngItem->before_qty : '' }}
                                                    </div>
                                                @else
                                                    <div class="ng-value empty-ng"></div>
                                                @endif

                                            </td>
                                        @endforeach


                                        {{-- =========================
                                             SESUDAH
                                        ========================== --}}

                                        @if ($showAfter)
                                            @foreach ($ngCodes as $code)
                                                @php
                                                    $ngItem = $ngItems->get($code);

                                                    $beforeQty = (int) ($ngItem?->before_qty ?? 0);
                                                    $afterQty = (int) ($ngItem?->after_qty ?? 0);

                                                    /*
                                                     * Logika highlight (User/PPIC vs OMD):
                                                     *
                                                     * P = 2      | P = 2      -> HIJAU (sesuai)
                                                     * P = 2      | P = 1      -> MERAH (tidak sesuai)
                                                     * P = 2      | P kosong   -> MERAH (tidak sesuai)
                                                     * P kosong   | P = 2      -> MERAH (tidak sesuai)
                                                     * P kosong   | P kosong   -> tanpa highlight
                                                     */
                                                    $resultCellClass = '';

                                                    if ($hasFinalResult && ($beforeQty > 0 || $afterQty > 0)) {
                                                        $resultCellClass =
                                                            $beforeQty === $afterQty
                                                                ? 'repair-result-match-cell'
                                                                : 'repair-result-mismatch-cell';
                                                    }
                                                @endphp

                                                <td class="ng-cell {{ $resultCellClass }}">

                                                    <div class="ng-value">
                                                        {{ $afterQty > 0 ? $afterQty : '' }}
                                                    </div>

                                                </td>
                                            @endforeach
                                        @endif


                                        {{-- =========================
                                             KETERANGAN PER PRODUK
                                        ========================== --}}

                                        <td class="keterangan-cell">
                                            <div class="keterangan-text">{{ $productNote ?: '-' }}</div>
                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>

                    </table>

                </div>


            </div>

        </div>
    @elseif ($order->result)
        {{-- =====================================================
             FALLBACK DATA LAMA
        ===================================================== --}}

        <div class="repair-card">

            <h3 class="repair-card-title">
                Ringkasan Hasil Repair
            </h3>

            <p class="repair-card-desc">
                Ringkasan hasil repair untuk order ini.
            </p>


            <div class="info-grid">

                <div class="info-item">
                    <span>OK</span>
                    <strong>{{ $order->result->ok_qty }}</strong>
                </div>

                <div class="info-item">
                    <span>Scrap</span>
                    <strong>{{ $order->result->scrap_qty }}</strong>
                </div>

                <div class="info-item">
                    <span>NG</span>
                    <strong>{{ $order->result->ng_qty }}</strong>
                </div>

                <div class="info-item">
                    <span>Diproses Oleh</span>
                    <strong>{{ $order->result->processedBy?->name ?? '-' }}</strong>
                </div>

            </div>


            <div class="description-box" style="margin-top:14px;">
                {{ $order->result->notes ?: 'Tidak ada catatan.' }}
            </div>

        </div>

    @endif

@endsection
