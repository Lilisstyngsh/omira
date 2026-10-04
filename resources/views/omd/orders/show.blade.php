@extends('layouts.app')

@php
    $activityTitle = match ($order->status) {
        'submitted' => 'Verifikasi Order Repair Box',
        'in_repair' => 'Input Hasil Repair OMD',
        'completed' => 'Hasil Repair OMD — Menunggu Konfirmasi User',
        'revision_requested' => 'Koreksi Hasil Repair OMD',
        'confirmed' => 'Hasil Repair & Serah Terima',
        default => 'Detail Order Repair Box',
    };
@endphp

@section('title', $activityTitle)
@section('header', $activityTitle)

@section('content')

    @php
        $hasOmdResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);

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
                                   INFO
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
                                   REPAIR TABLE (SAMA DENGAN HALAMAN USER)
                                ================================================== */

        .repair-table-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
        }

        .repair-table-header {
            min-width: 760px;
            background: #fafbfc;
        }

        .repair-table-body {
            min-width: 760px;
            max-height: 430px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        .repair-table {
            width: 100%;
            min-width: 760px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
        }

        .repair-table th {
            padding: 9px 5px;
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
            padding: 5px 5px;
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

        .ng-cell {
            text-align: center;
            vertical-align: middle !important;
        }

        /* =================================================
                                   INPUT OMD (STATUS in_repair)
                                ================================================== */

        .repair-table .ng-input {
            display: block;
            width: 44px;
            max-width: 100%;
            height: 36px;
            box-sizing: border-box;
            border: 2px solid #64748b;
            border-radius: 9px;
            outline: none;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            background: #fff;
            padding: 0 3px;
            margin: 0 auto;
            transition: border-color .15s ease, box-shadow .15s ease;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .repair-table .ng-input::-webkit-outer-spin-button,
        .repair-table .ng-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .repair-table .ng-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        .repair-table .ng-input::placeholder {
            color: #cbd5e1;
        }

        .repair-qty-control {
            display: grid;
            grid-template-columns: 28px 44px 28px;
            align-items: center;
            justify-content: center;
            gap: 2px;
            width: 104px;
            margin: 0 auto;
        }

        .repair-qty-control .ng-input {
            width: 44px;
            height: 36px;
            border-width: 2px;
            border-radius: 9px;
            font-size: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .05);
        }

        .repair-qty-step {
            width: 28px;
            height: 38px;
            border: 0;
            padding: 0;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: 500;
            line-height: 1;
            cursor: pointer;
            touch-action: none;
            user-select: none;
        }

        .repair-qty-step[data-step="-1"] { color: #b91c1c; }
        .repair-qty-step[data-step="1"] { color: #15803d; }
        .repair-qty-step:active { transform: scale(.94); opacity: .75; }
        .repair-qty-step:disabled { opacity: .35; cursor: not-allowed; }

        .repair-table .model-text { font-weight: 700; color: #0f172a; }

        .repair-table .ng-input.user-focus {
            background: #fff7d6;
            border-color: #eab308;
            box-shadow: 0 0 0 2px rgba(234, 179, 8, .12);
        }

        .repair-table .ng-input.user-focus:focus {
            border-color: #ca8a04;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, .16);
        }

        /* =================================================
                                   KETERANGAN
                                ================================================== */

        .repair-table .keterangan-head {
            width: 135px !important;
            min-width: 135px !important;
            text-align: center !important;
        }

        .repair-table .keterangan-cell {
            width: 135px !important;
            min-width: 135px !important;
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

        .repair-table .keterangan-input {
            width: 100%;
            min-height: 32px;
            box-sizing: border-box;
            padding: 6px 8px;
            border: 1px solid #64748b;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            line-height: 1.4;
            resize: vertical;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .repair-table .keterangan-input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        .repair-table .total-row td {
            background: #fff;
            color: #334155;
            font-weight: 550;
            border-top: 1px solid #dbe2ea;
        }

        .repair-table .grand-total-row td {
            background: #fff;
            color: #334155;
            font-weight: 600;
            border-top: 1px solid #dbe2ea;
        }

        .repair-table .total-label {
            text-align: right;
            white-space: nowrap;
            font-size: 10px;
            letter-spacing: .02em;
        }

        .repair-table .total-value {
            text-align: center;
            font-weight: 550;
        }


        .repair-table-unified {
            min-width: 620px;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }

        .repair-table-unified th,
        .repair-table-unified td {
            border-color: #dbe2ea;
        }

        .repair-table-unified th:last-child,
        .repair-table-unified td:last-child {
            border-right: 0;
        }

        .repair-table-unified tfoot td {
            border-bottom: 1px solid #dbe2ea;
        }

        .repair-table-editable {
            min-width: 1040px;
        }

        .repair-table .grand-total-value {
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: .01em;
        }

        .repair-result-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        .correction-actions {
            display: flex;
            gap: 9px;
        }

        .correction-actions[hidden],
        .correction-banner[hidden] {
            display: none !important;
        }

        .correction-banner {
            margin: 10px 0 12px;
            padding: 9px 11px;
            border: 1px solid #f0c36b;
            border-radius: 9px;
            background: #fffaf0;
            color: #8a5a13;
        }

        .correction-banner strong {
            display: block;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .correction-banner span {
            display: block;
            font-size: 10px;
            line-height: 1.4;
        }

        .correction-editor {
            display: none !important;
        }

        .correction-mode .correction-editor {
            display: grid !important;
        }

        .repair-result-actions [hidden] {
            display: none !important;
        }

        .correction-input {
            display: none !important;
        }

        .input-active {
            display: block !important;
        }

        .correction-mode .correction-readonly {
            display: none !important;
        }

        .correction-mode .correction-input {
            display: block !important;
        }

        .correction-mode .ng-input,
        .correction-mode .keterangan-input {
            background: #fffdfa;
            border-color: #d89a37;
            box-shadow: 0 0 0 2px rgba(216, 154, 55, .08);
        }

        .correction-mode .repair-result-match-cell,
        .correction-mode .repair-result-mismatch-cell {
            background: #fffdfa !important;
        }

        /* =================================================
                                   ACTION / COMPLETED
                                ================================================== */

        .action-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px;
            border-radius: 12px;
            background: #fafbfc;
            border: 1px solid #edf1f5;
        }

        .action-card-info strong {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            color: #334155;
        }

        .action-card-info span {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.5;
        }

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
            font-size: 10px;
            color: #4d7a5c;
            line-height: 1.5;
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

        .btn-primary {
            background: #7c3aed;
            color: #fff;
            box-shadow: 0 6px 15px rgba(124, 58, 237, .18);
        }

        .btn-primary:hover {
            background: #6d28d9;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
            box-shadow: inset 0 0 0 1px #dbe3ec;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-verify {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 6px 15px rgba(37, 99, 235, .18);
        }

        .btn-verify:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
            box-shadow: 0 6px 15px rgba(22, 163, 74, .18);
        }

        .btn-success:hover {
            background: #15803d;
            color: #fff;
        }

        .btn-warning {
            background: #d97706;
            color: #fff;
            box-shadow: 0 6px 15px rgba(217, 119, 6, .18);
        }

        .btn-warning:hover {
            background: #b45309;
            color: #fff;
        }

        /* =================================================
                                   LEGACY
                                ================================================== */

        .legacy-result-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        /* =================================================
                                   ERROR
                                ================================================== */

        .error-list {
            margin: 0 0 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff1f2;
            border: 1px solid #ffd8dd;
            color: #b42318;
            font-size: 11px;
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
            background: #f1f5f9;
            color: #64748b;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #cbd5e1;
        }

        .timeline-icon.completed {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: 0 0 0 2px #bbf7d0;
        }

        .timeline-icon.in-progress {
            background: #fef3c7;
            color: #d97706;
            box-shadow: 0 0 0 2px #fde68a;
        }

        .timeline-icon.pending {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: 0 0 0 2px #fecaca;
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

            .info-grid,
            .legacy-result-grid {
                grid-template-columns: 1fr;
            }

            .action-card {
                align-items: flex-start;
                flex-direction: column;
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
            <a href="{{ route('omd.orders.index') }}" class="btn btn-back" title="Kembali ke daftar Order Repair Box">
                ← Kembali
            </a>
        </div>

    </div>


    {{-- =====================================================
         ERROR
    ===================================================== --}}

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

                @php
                    $verifiedDone = (bool) $order->verified_at;
                    $repairStarted = (bool) $order->repair_started_at;
                    $repairDone = (bool) $order->repair_completed_at;
                    $handoverDone = (bool) $order->confirmation?->confirmed_at;

                    $verifiedState = $verifiedDone ? 'completed' : 'pending';

                    $repairState = $repairDone ? 'completed' : ($repairStarted ? 'in-progress' : 'pending');

                    $handoverState = $handoverDone
                        ? 'completed'
                        : ($order->status === 'completed'
                            ? 'in-progress'
                            : 'pending');
                @endphp


                {{-- USER SUBMIT --}}
                <div class="timeline-item">

                    <div class="timeline-icon completed">
                        ✓
                    </div>

                    <strong>
                        User Submit
                    </strong>

                    <span>
                        @if ($order->created_at)
                            {{ $order->created_at->format('d-m-Y H:i') }}

                            @if ($order->user?->name)
                                <br>
                                {{ $order->user->name }}
                            @endif
                        @else
                            -
                        @endif
                    </span>

                </div>


                {{-- VERIFIED OMD --}}
                <div class="timeline-item">

                    <div class="timeline-icon {{ $verifiedState }}">
                        {{ $verifiedState === 'completed' ? '✓' : '×' }}
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

                    <div class="timeline-icon {{ $repairState }}">
                        @if ($repairState === 'completed')
                            ✓
                        @elseif ($repairState === 'in-progress')
                            △
                        @else
                            ×
                        @endif
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

                    <div class="timeline-icon {{ $handoverState }}">
                        @if ($handoverState === 'completed')
                            ✓
                        @elseif ($handoverState === 'in-progress')
                            △
                        @else
                            ×
                        @endif
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


    @include('partials.repair-order-information', ['order' => $order, 'context' => 'omd-show'])

    @if($order->status === 'revision_requested' && $order->openFeedback)
        <div style="margin-bottom:14px;border:1px solid #f59e0b;border-radius:10px;padding:11px 13px;background:#fff;color:#92400e;font-size:12px;line-height:1.5">
            <strong style="display:block;margin-bottom:3px">⚠ Feedback User</strong>
            {{ $order->openFeedback->reason }}
            <div style="margin-top:4px;font-size:10px;color:#a16207">{{ $order->openFeedback->created_at?->format('d-m-Y H:i') }}</div>
        </div>
    @endif

    {{-- =====================================================
         DETAIL / HASIL REPAIR
    ===================================================== --}}

    @if ($order->items->isNotEmpty())

        @php
            $modelGroups = $order->items->groupBy('master_model_id');
            $ngCodes = ['P', 'H', 'C', 'S'];

            $isInRepair = $order->status === 'in_repair';
            $isCorrection = $order->status === 'revision_requested';
            $canEditResult = in_array($order->status, ['in_repair', 'revision_requested'], true);
            $showAfter = $order->status !== 'submitted';
            $showProductNote = $order->status !== 'submitted';
            $hasFinalResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);

            $beforeTotals = collect($ngCodes)->mapWithKeys(fn($code) => [
                $code => (int) $order->items->filter(
                    fn($item) => strtoupper($item->ngType?->code ?? '') === $code
                )->sum('before_qty'),
            ]);

            $afterTotals = collect($ngCodes)->mapWithKeys(fn($code) => [
                $code => (int) $order->items->filter(
                    fn($item) => strtoupper($item->ngType?->code ?? '') === $code
                )->sum('after_qty'),
            ]);

            $beforeGrandTotal = (int) $beforeTotals->sum();
            $afterGrandTotal = (int) $afterTotals->sum();
        @endphp

        <div class="repair-card" id="repairResultCard">

            <h3 class="repair-card-title">
                @switch($order->status)
                    @case('submitted')
                        Detail Order Repair Box
                    @break
                    @case('in_repair')
                        Input Hasil Repair OMD
                    @break
                    @case('completed')
                        Hasil Repair OMD — Menunggu Konfirmasi User
                    @break
                    @case('revision_requested')
                        Koreksi Hasil Repair OMD
                    @break
                    @case('confirmed')
                        Hasil Repair &amp; Serah Terima
                    @break
                    @default
                        Detail Order Repair Box
                @endswitch
            </h3>

            <p class="repair-card-desc">
                @if ($isInRepair)
                    Masukkan hasil repair untuk setiap Produk dan Jenis NG, lalu submit hasil repair.
                @elseif ($order->status === 'submitted')
                    Periksa Model, Produk, dan qty NG sebelum melakukan verifikasi order.
                @elseif ($order->status === 'completed')
                    Hasil repair sudah dikirim ke User dan sedang menunggu pengecekan barang serta konfirmasi serah terima.
                @elseif ($order->status === 'revision_requested')
                    User menyatakan barang tidak sesuai dengan data hasil repair. Aktifkan mode koreksi, perbaiki data, lalu kirim ulang hasil repair ke User.
                @elseif ($order->status === 'confirmed')
                    Hasil repair telah diverifikasi User dan proses serah terima selesai.
                @endif
            </p>

            @if ($isCorrection)
                <div class="correction-banner" id="correctionBanner" hidden>
                    <strong>⚠ Mode Koreksi</strong>
                    <span>Periksa qty sebelum menyimpan perubahan.</span>
                </div>
            @endif

            @if ($canEditResult)
                <form method="POST" action="{{ route('omd.orders.complete', $order) }}" id="repairResultForm">
                    @csrf
            @endif

            <div class="repair-table-scroll">
                <table class="repair-table repair-table-unified {{ $canEditResult && $showAfter ? 'repair-table-editable' : '' }}">
                    <colgroup>
                        <col style="width:38px;">
                        <col style="width:88px;">
                        <col style="width:120px;">
                        @foreach ($ngCodes as $code)
                            <col style="width:44px;">
                        @endforeach
                        @if ($showAfter)
                            @foreach ($ngCodes as $code)
                                <col style="width:{{ $canEditResult ? '104px' : '44px' }};">
                            @endforeach
                        @endif
                        @if ($showProductNote)
                            <col style="width:{{ $canEditResult ? '150px' : '128px' }};">
                        @endif
                    </colgroup>

                    <thead>
                        <tr>
                            <th rowspan="{{ $showAfter ? 3 : 2 }}">No</th>
                            <th rowspan="{{ $showAfter ? 3 : 2 }}">Model</th>
                            <th rowspan="{{ $showAfter ? 3 : 2 }}">Produk</th>
                            <th colspan="{{ $showAfter ? 8 : 4 }}">Qty NG</th>
                            @if ($showProductNote)
                                <th rowspan="{{ $showAfter ? 3 : 2 }}" class="keterangan-head">Catatan</th>
                            @endif
                        </tr>

                        @if ($showAfter)
                            <tr>
                                <th colspan="4">Order</th>
                                <th colspan="4">Hasil</th>
                            </tr>
                        @endif

                        <tr>
                            @foreach ($ngCodes as $code)
                                <th>{{ $code }}</th>
                            @endforeach
                            @if ($showAfter)
                                @foreach ($ngCodes as $code)
                                    <th>{{ $code }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($modelGroups as $modelItems)
                            @php
                                $productGroups = $modelItems->groupBy('product_id');
                                $modelRowspan = $productGroups->count();
                            @endphp

                            @foreach ($productGroups as $productItems)
                                @php
                                    $ngItems = $productItems->keyBy(fn($item) => strtoupper($item->ngType?->code ?? ''));
                                    $firstItem = $productItems->first();
                                    $productId = $firstItem->product_id;
                                    $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))?->mismatch_note;
                                @endphp

                                <tr>
                                    @if ($loop->first)
                                        <td rowspan="{{ $modelRowspan }}" class="no-cell">{{ $loop->parent->iteration }}</td>
                                        <td rowspan="{{ $modelRowspan }}" class="model-cell">
                                            <span class="model-text">{{ $modelItems->first()->masterModel?->model ?? '-' }}</span>
                                        </td>
                                    @endif

                                    <td><span class="product-text">{{ $firstItem->product?->name ?? '-' }}</span></td>

                                    @foreach ($ngCodes as $code)
                                        @php $ngItem = $ngItems->get($code); @endphp
                                        <td class="ng-cell">
                                            <div class="ng-value">{{ ((int) ($ngItem?->before_qty ?? 0)) > 0 ? (int) $ngItem->before_qty : '' }}</div>
                                        </td>
                                    @endforeach

                                    @if ($showAfter)
                                        @foreach ($ngCodes as $code)
                                            @php
                                                $ngItem = $ngItems->get($code);
                                                $beforeQty = (int) ($ngItem?->before_qty ?? 0);
                                                $afterQty = (int) ($ngItem?->after_qty ?? 0);
                                                $hasBeforeQty = $beforeQty > 0;

                                                $resultCellClass = '';
                                                if ($hasFinalResult && ($beforeQty > 0 || $afterQty > 0)) {
                                                    $resultCellClass = $beforeQty === $afterQty
                                                        ? 'repair-result-match-cell'
                                                        : 'repair-result-mismatch-cell';
                                                }
                                            @endphp
                                            <td class="ng-cell result-edit-cell {{ $resultCellClass }}">
                                                @if ($canEditResult)
                                                    @if ($isCorrection)
                                                        <div class="correction-readonly ng-value">{{ $afterQty > 0 ? $afterQty : '' }}</div>
                                                    @endif
                                                    @php $qtyInputId = $ngItem ? 'after_qty_' . $ngItem->id : 'new_after_' . $productId . '_' . $code; @endphp
                                                    <div class="repair-qty-control {{ $isCorrection ? 'correction-editor' : '' }}">
                                                        <button type="button" class="repair-qty-step correction-step" data-step="-1" data-target="{{ $qtyInputId }}" aria-label="Kurangi qty {{ $code }}" @disabled($isCorrection)>−</button>
                                                        @if ($ngItem)
                                                            <input type="number"
                                                                id="{{ $qtyInputId }}"
                                                                name="items[{{ $ngItem->id }}][after_qty]"
                                                                class="ng-input repair-after-input correction-input {{ $isInRepair ? 'input-active' : '' }} {{ $isInRepair && $hasBeforeQty ? 'user-focus' : '' }}"
                                                                data-ng-code="{{ $code }}" min="0" inputmode="numeric"
                                                                value="{{ old('items.' . $ngItem->id . '.after_qty', ((int) ($ngItem->after_qty ?? 0)) > 0 ? $ngItem->after_qty : '') }}"
                                                                @disabled($isCorrection)>
                                                        @else
                                                            <input type="number"
                                                                id="{{ $qtyInputId }}"
                                                                name="new_items[{{ $productId }}][{{ $code }}]"
                                                                class="ng-input repair-after-input correction-input {{ $isInRepair ? 'input-active' : '' }}"
                                                                data-ng-code="{{ $code }}" min="0" inputmode="numeric"
                                                                value="{{ old('new_items.' . $productId . '.' . $code, '') }}"
                                                                @disabled($isCorrection)>
                                                        @endif
                                                        <button type="button" class="repair-qty-step correction-step" data-step="1" data-target="{{ $qtyInputId }}" aria-label="Tambah qty {{ $code }}" @disabled($isCorrection)>+</button>
                                                    </div>
                                                @else
                                                    <div class="ng-value">{{ $afterQty > 0 ? $afterQty : '' }}</div>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif

                                    @if ($showProductNote)
                                        <td class="keterangan-cell">
                                            @if ($canEditResult)
                                                @if ($isCorrection)
                                                    <div class="correction-readonly keterangan-text">{{ $productNote ?: '-' }}</div>
                                                @endif
                                                <textarea name="product_notes[{{ $productId }}]"
                                                    class="keterangan-input correction-input {{ $isInRepair ? 'input-active' : '' }}"
                                                    placeholder="Keterangan hasil repair..."
                                                    @disabled($isCorrection)>{{ old('product_notes.' . $productId, $productNote ?? '') }}</textarea>
                                            @else
                                                <div class="keterangan-text">{{ $productNote ?: '-' }}</div>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" class="total-label">Total</td>
                            @foreach ($ngCodes as $code)
                                <td class="total-value">{{ $beforeTotals[$code] > 0 ? $beforeTotals[$code] : '' }}</td>
                            @endforeach
                            @if ($showAfter)
                                @foreach ($ngCodes as $code)
                                    <td class="total-value" data-total-after="{{ $code }}">{{ $afterTotals[$code] > 0 ? $afterTotals[$code] : '' }}</td>
                                @endforeach
                            @endif
                            @if ($showProductNote)
                                <td></td>
                            @endif
                        </tr>

                        <tr class="grand-total-row">
                            <td colspan="3" class="total-label">Grand Total</td>
                            <td colspan="4" class="grand-total-value">
                                {{ $beforeGrandTotal > 0 ? $beforeGrandTotal . ' NG' : '' }}
                            </td>
                            @if ($showAfter)
                                <td colspan="4" class="grand-total-value" data-grand-after>
                                    {{ $afterGrandTotal > 0 ? $afterGrandTotal . ' NG' : '' }}
                                </td>
                            @endif
                            @if ($showProductNote)
                                <td></td>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($canEditResult)
                <div class="repair-result-actions">
                    @if ($isCorrection)
                        <button type="button" class="btn btn-warning" id="enableCorrectionButton"
                            title="Aktifkan mode koreksi hasil repair">
                            Edit Hasil Repair
                        </button>

                        <div class="correction-actions" id="correctionActions" hidden>
                            <button type="button" class="btn btn-secondary" id="cancelCorrectionButton" title="Batalkan perubahan dan kembali ke data terakhir">Batal</button>
                            <button type="submit" class="btn btn-warning"
                                title="Simpan koreksi hasil repair agar User melihat data terbaru">
                                Update
                            </button>
                        </div>
                    @else
                        <button type="submit" class="btn btn-success"
                            title="Simpan hasil repair dan lanjutkan ke pengecekan serah terima oleh User">
                            Simpan &amp; Serah Terima Hasil Repair
                        </button>
                    @endif
                </div>
                </form>
            @endif

        </div>

        @if ($canEditResult)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const card = document.getElementById('repairResultCard');
                    const inputs = Array.from(document.querySelectorAll('.repair-after-input'));
                    const correctionInputs = Array.from(document.querySelectorAll('.correction-input'));
                    const correctionSteps = Array.from(document.querySelectorAll('.correction-step'));
                    const enableButton = document.getElementById('enableCorrectionButton');
                    const cancelButton = document.getElementById('cancelCorrectionButton');
                    const correctionActions = document.getElementById('correctionActions');
                    const correctionBanner = document.getElementById('correctionBanner');

                    function numberValue(input) {
                        const value = parseInt(input.value || '0', 10);
                        return Number.isFinite(value) ? Math.max(value, 0) : 0;
                    }

                    function refreshTotals() {
                        const totals = { P: 0, H: 0, C: 0, S: 0 };

                        inputs.forEach(function (input) {
                            const code = input.dataset.ngCode;
                            if (code && Object.prototype.hasOwnProperty.call(totals, code)) {
                                totals[code] += numberValue(input);
                            }
                        });

                        let grand = 0;

                        Object.entries(totals).forEach(function ([code, value]) {
                            grand += value;
                            const cell = document.querySelector('[data-total-after="' + code + '"]');
                            if (cell) cell.textContent = value > 0 ? value : '';
                        });

                        const grandCell = document.querySelector('[data-grand-after]');
                        if (grandCell) grandCell.textContent = grand > 0 ? grand + ' NG' : '';
                    }

                    inputs.forEach(function (input) {
                        input.addEventListener('input', refreshTotals);
                    });

                    function changeRepairQty(button) {
                        if (button.disabled) return;
                        const input = document.getElementById(button.dataset.target);
                        if (!input || input.disabled) return;
                        const step = parseInt(button.dataset.step || '0', 10);
                        const current = numberValue(input);
                        const next = Math.max(0, current + step);
                        input.value = next > 0 ? String(next) : '';
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    }

                    correctionSteps.forEach(function (button) {
                        let holdTimer = null;
                        let repeatTimer = null;
                        const stop = function () {
                            if (holdTimer) clearTimeout(holdTimer);
                            if (repeatTimer) clearInterval(repeatTimer);
                            holdTimer = null; repeatTimer = null;
                        };
                        button.addEventListener('pointerdown', function (event) {
                            event.preventDefault();
                            if (button.disabled) return;
                            changeRepairQty(button);
                            holdTimer = setTimeout(function () {
                                repeatTimer = setInterval(function () { changeRepairQty(button); }, 120);
                            }, 500);
                        });
                        ['pointerup','pointercancel','pointerleave'].forEach(function (name) { button.addEventListener(name, stop); });
                    });

                    if (enableButton) {
                        enableButton.addEventListener('click', function () {
                            card.classList.add('correction-mode');
                            correctionInputs.forEach(function (input) { input.disabled = false; });
                            correctionSteps.forEach(function (button) { button.disabled = false; });
                            enableButton.hidden = true;
                            correctionActions.hidden = false;
                            correctionBanner.hidden = false;
                            const firstInput = correctionInputs.find(function (input) { return input.tagName === 'INPUT'; });
                            if (firstInput) firstInput.focus();
                        });
                    }

                    if (cancelButton) {
                        cancelButton.addEventListener('click', function () {
                            window.location.reload();
                        });
                    }

                    refreshTotals();
                });
            </script>
        @endif
    @endif


    {{-- =====================================================
         LEGACY RESULT
    ===================================================== --}}

    @if ($order->items->isEmpty() && $order->result)
        <div class="repair-card">

            <h3 class="repair-card-title">
                Ringkasan Hasil Repair OMD
            </h3>

            <p class="repair-card-desc">
                Data hasil repair dari format order lama.
            </p>


            <div class="legacy-result-grid">

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

            </div>


            <div class="description-box" style="margin-top:14px;">
                {{ $order->result->notes ?: 'Tidak ada catatan.' }}
            </div>

        </div>
    @endif


    {{-- =====================================================
         VERIFIKASI ORDER
    ===================================================== --}}

    @if ($order->status === 'submitted')
        <div class="repair-card">

            <div class="action-card">

                <div class="action-card-info">

                    <strong>
                        Verifikasi Order
                    </strong>

                    <span>
                        Pastikan data order sudah sesuai.
                        Setelah diverifikasi, order langsung masuk proses repair.
                    </span>

                </div>

                <form method="POST" action="{{ route('omd.orders.verify', $order) }}">

                    @csrf

                    <button type="submit" class="btn btn-verify" title="Verifikasi order dan mulai proses repair">
                        Verifikasi Order
                    </button>

                </form>

            </div>

        </div>
    @endif

@endsection
