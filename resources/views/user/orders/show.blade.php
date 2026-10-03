@extends('layouts.app')

@section('title', 'Detail Order Repair Box')
@section('header', 'Detail Order Repair Box')

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
                                                                           REPAIR TABLE
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
        /* REVISI: sebelumnya 11px 8px */
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
        /* REVISI: sebelumnya 8px 10px */
        text-align: left !important;
        color: #475569;
        font-size: 11px;
        line-height: 1.4;
        /* REVISI: sebelumnya 1.5 */
        white-space: pre-wrap;
        word-break: break-word;
        box-sizing: border-box;
    }

    .repair-table .keterangan-input {
        width: 100%;
        min-height: 40px;
        box-sizing: border-box;
        padding: 8px 10px;
        text-align: left !important;
        border: 1px solid #64748b;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: 11px;
        line-height: 1.5;
        resize: vertical;
        outline: none;
    }

    .repair-table .total-row td {
        background: #f8fafc;
        font-weight: 800;
        border-top: 1px solid #cbd5e1;
    }

    .repair-table .grand-total-row td {
        background: #eef2ff;
        color: #312e81;
        font-weight: 900;
        border-top: 2px solid #818cf8;
    }

    .repair-table .total-label {
        text-align: right;
        white-space: nowrap;
        font-size: 10px;
        letter-spacing: .02em;
    }

    .repair-table .total-value {
        text-align: center;
        font-weight: 900;
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
        border-bottom: 0;
    }

    .repair-table .grand-total-value {
        text-align: center;
        font-weight: 900;
        font-size: 12px;
        letter-spacing: .02em;
    }

    /* =================================================
                                                                           ACTION CARD
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

    /* =================================================
                                                                           CONFIRMED
                                                                        ================================================== */

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

    .btn-confirm {
        background: #16a34a;
        color: #fff;
        box-shadow: 0 6px 15px rgba(22, 163, 74, .18);
    }

    .btn-confirm:hover {
        background: #15803d;
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

    .btn-back-strong {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 14px;
        border-radius: 9px;
        background: #334155;
        color: #fff !important;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #334155
    }

    .btn-back-strong:hover {
        background: #1e293b;
        border-color: #1e293b
    }

    .handover-direct {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
        flex-wrap: wrap
    }

    .handover-direct .btn {
        min-height: 44px;
        padding: 0 16px;
        border-radius: 9px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer
    }

    .handover-direct .btn-mismatch {
        background: #fff;
        color: #b91c1c;
        border-color: #ef4444
    }

    .handover-direct .btn-mismatch:hover {
        background: #fef2f2
    }

    .handover-direct .btn-confirm {
        background: #16a34a;
        color: #fff;
        border-color: #16a34a
    }

    .handover-direct .btn-confirm:hover {
        background: #15803d
    }

    .feedback-waiting {
        margin-top: 12px;
        padding: 10px 12px;
        border: 1px solid #f59e0b;
        border-radius: 9px;
        color: #92400e;
        background: #fff;
        font-size: 11px
    }

    .repair-dialog {
        border: 0;
        border-radius: 14px;
        padding: 0;
        max-width: 460px;
        width: calc(100% - 28px);
        box-shadow: 0 24px 70px rgba(15, 23, 42, .24)
    }

    .repair-dialog::backdrop {
        background: rgba(15, 23, 42, .45)
    }

    .repair-dialog-body {
        padding: 18px
    }

    .repair-dialog h4 {
        margin: 0 0 6px;
        font-size: 16px;
        color: #0f172a
    }

    .repair-dialog p {
        margin: 0 0 14px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5
    }

    .repair-dialog textarea {
        width: 100%;
        min-height: 110px;
        border: 1.5px solid #94a3b8;
        border-radius: 9px;
        padding: 10px 11px;
        font-size: 13px;
        resize: vertical;
        outline: none
    }

    .repair-dialog textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1)
    }

    .repair-dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 14px
    }

    .repair-dialog-actions button {
        min-height: 40px;
        padding: 0 13px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer
    }

    .dialog-cancel {
        background: #fff;
        color: #334155;
        border: 1px solid #94a3b8
    }

    .dialog-danger {
        background: #dc2626;
        color: #fff;
        border: 1px solid #dc2626
    }

    .dialog-confirm {
        background: #16a34a;
        color: #fff;
        border: 1px solid #16a34a
    }

    @media(max-width:600px) {
        .handover-direct {
            display: grid;
            grid-template-columns: 1fr
        }

        .handover-direct .btn {
            width: 100%
        }

        .repair-dialog-actions {
            display: grid;
            grid-template-columns: 1fr 1fr
        }
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

        <p>
            Order Repair Box
        </p>

    </div>


    <div class="repair-head-actions">

        <a href="{{ route('user.orders.index') }}" class="btn btn-back-strong" title="Kembali ke daftar Order Repair Box">
            ← Kembali ke Daftar Order
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
            : (in_array($order->status, ['completed', 'revision_requested'], true)
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
                    @if ($verifiedState === 'completed')
                    ✓
                    @else
                    ×
                    @endif
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

                    @if ($order->user?->name)
                    <br>
                    {{ $order->user->name }}
                    @endif
                    @else
                    -
                    @endif

                </span>

            </div>


        </div>

    </div>

</div>

@include('partials.repair-order-information', ['order' => $order, 'context' => 'user-show'])

{{-- =====================================================
         DETAIL / HASIL REPAIR
    ===================================================== --}}

@if ($order->items->isNotEmpty())
@php
$showAfter = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
@endphp
<div class="repair-card">
    <h3 class="repair-card-title">
        @if ($order->status === 'submitted')
        Detail Order Repair Box
        @elseif ($order->status === 'in_repair')
        Order Sedang Diproses OMD
        @elseif ($order->status === 'completed')
        Hasil Repair OMD
        @elseif ($order->status === 'revision_requested')
        Menunggu Koreksi OMD
        @else
        Hasil Repair &amp; Serah Terima
        @endif
    </h3>
    @include('partials.repair-order-readonly-table', [
    'order' => $order,
    'showAfter' => $showAfter,
    'showProductNote' => $showAfter,
    ])
</div>
@elseif ($order->result)
<div class="repair-card">
    <h3 class="repair-card-title">Hasil Repair</h3>
    <div class="legacy-result-grid">
        <div class="info-item"><span>OK</span><strong>{{ $order->result->ok_qty }}</strong></div>
        <div class="info-item"><span>SCRAP</span><strong>{{ $order->result->scrap_qty }}</strong></div>
        <div class="info-item"><span>NG</span><strong>{{ $order->result->ng_qty }}</strong></div>
    </div>
</div>
@endif

{{-- =====================================================
         KONFIRMASI USER
    ===================================================== --}}

@if ($order->status === 'completed')
<div class="handover-direct">
    <button type="button" class="btn btn-mismatch" onclick="document.getElementById('mismatchDialog').showModal()">
        Barang Tidak Sesuai
    </button>
    <button type="button" class="btn btn-confirm" onclick="document.getElementById('confirmDialog').showModal()">
        Barang Sesuai
    </button>
</div>

<dialog id="mismatchDialog" class="repair-dialog">
    <form method="POST" action="{{ route('user.orders.requestRevision', $order) }}" class="repair-dialog-body">
        @csrf
        <h4>Barang Tidak Sesuai</h4>
        <p>{{ $order->order_number }} — jelaskan perbedaan jumlah barang fisik dengan data pada sistem.</p>
        <textarea name="reason" required maxlength="1000" placeholder="Contoh: Qty Handle fisik 8, di sistem tertulis 10.">{{ old('reason') }}</textarea>
        <div class="repair-dialog-actions">
            <button type="button" class="dialog-cancel" onclick="document.getElementById('mismatchDialog').close()">Batal</button>
            <button type="submit" class="dialog-danger">Kirim ke OMD</button>
        </div>
    </form>
</dialog>

@if($errors->has('reason'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('mismatchDialog')?.showModal();
    });
</script>
@endif

<dialog id="confirmDialog" class="repair-dialog">
    <form method="POST" action="{{ route('user.orders.confirm', $order) }}" class="repair-dialog-body">
        @csrf
        <h4>Yakin barang sudah sesuai?</h4>
        <p>Pastikan jumlah barang fisik sudah sesuai dengan hasil repair pada sistem. Setelah dikonfirmasi, order akan selesai dan masuk History.</p>
        <div class="repair-dialog-actions">
            <button type="button" class="dialog-cancel" onclick="document.getElementById('confirmDialog').close()">Batal</button>
            <button type="submit" class="dialog-confirm">Ya, Barang Sesuai</button>
        </div>
    </form>
</dialog>
@elseif ($order->status === 'revision_requested')
<div class="feedback-waiting">
    Feedback ketidaksesuaian sudah dikirim ke OMD. Tunggu hasil koreksi sebelum melakukan pengecekan ulang.
    @if($order->openFeedback?->reason)
    <br><strong>Alasan:</strong> {{ $order->openFeedback->reason }}
    @endif
</div>
@endif

@endsection