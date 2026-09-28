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

    .status-verified {
        background: #eff6ff;
        color: #3478c5;
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

    .order-table-wrap {
        max-height: 430px;
        overflow: auto;
        border-radius: 12px;
        border: 1px solid #edf1f5;
    }

    .repair-table {
        width: 100%;
        min-width: 850px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .repair-table th {
        position: sticky;
        top: 0;
        z-index: 10;
        padding: 12px 13px;
        text-align: left;
        background: #fafbfc;
        border-bottom: 1px solid #e9eef4;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .repair-table td {
        padding: 12px 13px;
        border-bottom: 1px solid #eef2f6;
        font-size: 12px;
        color: #334155;
        vertical-align: middle;
    }

    .repair-table tbody tr:last-child td {
        border-bottom: none;
    }

    .repair-table tbody tr:hover {
        background: #fcfcff;
    }

    .model-text {
        font-weight: 800;
        color: #172033;
    }

    .product-text {
        font-weight: 650;
        color: #475569;
    }

    .ng-badge {
        display: inline-flex;
        align-items: center;
        min-width: 34px;
        height: 25px;
        justify-content: center;
        padding: 0 8px;
        border-radius: 7px;
        background: #f5f3ff;
        color: #6557dc;
        font-size: 10px;
        font-weight: 800;
    }

    .qty-input {
        width: 95px;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #dce3ec;
        border-radius: 9px;
        outline: none;
        font-size: 12px;
        box-sizing: border-box;
    }

    .qty-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, .10);
    }

    .note-input {
        width: 100%;
        min-width: 180px;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #dce3ec;
        border-radius: 9px;
        outline: none;
        font-size: 11px;
        box-sizing: border-box;
    }

    .note-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, .10);
    }

    .legacy-result-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

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
    }

    .action-button-wrap {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
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

    .btn-primary {
        background: #7c3aed;
        color: #fff;
        box-shadow: 0 6px 15px rgba(124, 58, 237, .18);
    }

    .btn-primary:hover {
        background: #6d28d9;
        color: #fff;
    }

    .btn-warning {
        background: #fff7e8;
        color: #b77906;
    }

    .btn-warning:hover {
        background: #ffedc2;
    }

    .btn-success {
        background: #ecfdf3;
        color: #15803d;
    }

    .btn-success:hover {
        background: #dcfce7;
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
        min-width: 760px;
        display: grid;
        grid-template-columns: repeat(5, minmax(130px, 1fr));
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10%;
        right: 10%;
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

    .description-box {
        padding: 13px;
        border-radius: 11px;
        background: #fafbfc;
        border: 1px solid #edf1f5;
        color: #64748b;
        font-size: 11px;
        line-height: 1.6;
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

    .completed-box {
        padding: 15px;
        border: 1px solid #d9eee2;
        border-radius: 12px;
        background: #f2fbf5;
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

        .info-grid,
        .legacy-result-grid {
            grid-template-columns: 1fr;
        }

        .action-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .action-button-wrap {
            width: 100%;
        }

        .action-button-wrap .btn {
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

        <a href="{{ route('omd.orders.index') }}" class="btn btn-secondary">
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
                {{ $order->created_at ? $order->created_at->format('d-m-Y H:i') : '-' }}
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
                Line
            </span>

            <strong>
                {{ $order->line?->name ?? ($order->area ? $order->area->name : '-') }}
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


        <div class="info-item">

            <span>
                Verifikator OMD
            </span>

            <strong>
                {{ $order->omdVerifier?->name ?? '-' }}
            </strong>

        </div>

    </div>


    @if ($order->description)

    <div class="description-box" style="margin-top:14px;">
        {{ $order->description }}
    </div>

    @endif

</div>

{{-- =====================================================
     FORM HASIL REPAIR - DATA BARU
    ===================================================== --}}

@if ($order->status === 'in_repair' && $order->items->isNotEmpty())

<div class="repair-card">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:15px;margin-bottom:18px;">

        <div>

            <h3 class="repair-card-title">
                Diisi Oleh OMD Setelah Repair
            </h3>

            <p class="repair-card-desc" style="margin-bottom:0;">
                Masukkan hasil repair untuk setiap detail order.
            </p>

        </div>

        <div>
            <span class="status-badge status-in_repair">
                In Repair
            </span>
        </div>

    </div>


    <form method="POST" action="{{ route('omd.orders.complete', $order) }}">

        @csrf


        <div class="order-table-wrap">

            <table class="repair-table">

                <thead>

                    <tr>

                        <th>
                            Model
                        </th>

                        <th>
                            Produk
                        </th>

                        <th>
                            Jenis NG
                        </th>

                        <th>
                            Qty Sebelum
                        </th>

                        <th>
                            Qty Sesudah
                        </th>

                        <th>
                            Catatan Ketidaksesuaian
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($order->items as $item)

                    <tr>

                        <td>
                            {{ $item->masterModel?->model ?? '-' }}
                        </td>

                        <td>
                            {{ $item->product?->name ?? '-' }}
                        </td>

                        <td>

                            <span class="ng-badge">
                                {{ $item->ngType?->code ?? '-' }}
                            </span>

                        </td>

                        <td>
                            {{ $item->before_qty }}
                        </td>

                        <td>

                            <input
                                type="number"
                                name="items[{{ $item->id }}][after_qty]"
                                class="qty-input"
                                min="0"
                                value="{{ old('items.' . $item->id . '.after_qty') }}"
                                required>

                        </td>

                        <td>

                            <input
                                type="text"
                                name="items[{{ $item->id }}][mismatch_note]"
                                class="note-input"
                                value="{{ old('items.' . $item->id . '.mismatch_note') }}"
                                placeholder="Catatan bila ada ketidaksesuaian">

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        <div style="display:flex;justify-content:flex-end;margin-top:16px;">

            <button type="submit" class="btn btn-primary">
                Simpan Hasil Repair
            </button>

        </div>

    </form>

</div>

@endif


{{-- =====================================================
     FORM LEGACY HASIL REPAIR
    ===================================================== --}}

@if ($order->status === 'in_repair' && $order->items->isEmpty())

<div class="repair-card">

    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:15px;margin-bottom:18px;">

        <div>

            <h3 class="repair-card-title">
                Input Hasil Repair
            </h3>

            <p class="repair-card-desc" style="margin-bottom:0;">
                Form kompatibilitas untuk order lama.
            </p>

        </div>

        <div>
            <span class="status-badge status-in_repair">
                In Repair
            </span>
        </div>

    </div>


    <form method="POST" action="{{ route('omd.orders.complete', $order) }}">

        @csrf


        <div class="legacy-result-grid">

            <div>

                <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                    OK
                </label>

                <input
                    type="number"
                    name="ok_qty"
                    min="0"
                    max="{{ $order->quantity }}"
                    value="{{ old('ok_qty') }}"
                    class="qty-input"
                    required>

            </div>


            <div>

                <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                    SCRAP
                </label>

                <input
                    type="number"
                    name="scrap_qty"
                    min="0"
                    value="{{ old('scrap_qty') }}"
                    class="qty-input"
                    required>

            </div>


            <div>

                <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                    NG
                </label>

                <input
                    type="number"
                    name="ng_qty"
                    min="0"
                    value="{{ old('ng_qty') }}"
                    class="qty-input"
                    required>

            </div>

        </div>


        <div style="margin-top:16px;">

            <label style="display:block;margin-bottom:7px;font-size:11px;font-weight:700;">
                Catatan
            </label>

            <input
                type="text"
                name="notes"
                value="{{ old('notes') }}"
                class="note-input"
                style="width:100%;"
                placeholder="Catatan hasil repair">

        </div>


        <div style="display:flex;justify-content:flex-end;margin-top:16px;">

            <button type="submit" class="btn btn-primary">
                Simpan Hasil Repair
            </button>

        </div>

    </form>

</div>

@endif


{{-- =====================================================
     AKSI VERIFIKASI
    ===================================================== --}}

@if ($order->status === 'submitted')

<div class="repair-card">

    <div class="action-card">

        <div class="action-card-info">

            <strong>
                Verifikasi Order
            </strong>

            <span>
                Pastikan data order sudah sesuai sebelum diproses.
            </span>

        </div>


        <div class="action-button-wrap">

            <form method="POST" action="{{ route('omd.orders.verify', $order) }}">

                @csrf

                <button type="submit" class="btn btn-primary">
                    Verifikasi Order
                </button>

            </form>

        </div>

    </div>

</div>

@endif


{{-- =====================================================
     AKSI MULAI REPAIR
    ===================================================== --}}

@if ($order->status === 'verified')

<div class="repair-card">

    <div class="action-card">

        <div class="action-card-info">

            <strong>
                Mulai Repair
            </strong>

            <span>
                Order sudah diverifikasi dan siap dikerjakan.
            </span>

        </div>


        <div class="action-button-wrap">

            <form method="POST" action="{{ route('omd.orders.start', $order) }}">

                @csrf

                <button type="submit" class="btn btn-primary">
                    Mulai Repair
                </button>

            </form>

        </div>

    </div>

</div>

@endif



{{-- =====================================================
     CONFIRMED
    ===================================================== --}}

@if ($order->status === 'confirmed')

<div class="repair-card">

    <div class="completed-box">

        <strong>
            Order Sudah Dikonfirmasi
        </strong>

        <span>
            User sudah mengonfirmasi penerimaan hasil repair.
        </span>

    </div>

</div>

@endif


{{-- =====================================================
     TIMELINE
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

            {{-- SUBMITTED --}}
            <div class="timeline-item active">

                <div class="timeline-icon">
                    ✓
                </div>

                <strong>
                    Submitted
                </strong>

                <span>
                    {{ $order->created_at ? $order->created_at->format('d-m-Y H:i') : '-' }}
                </span>

            </div>


            {{-- VERIFIED --}}
            <div
                class="timeline-item
            {{ in_array($order->status, ['verified', 'in_repair', 'completed', 'confirmed']) ? 'active' : '' }}">

                <div class="timeline-icon">
                    {{ in_array($order->status, ['verified', 'in_repair', 'completed', 'confirmed']) ? '✓' : '✕' }}
                </div>

                <strong>
                    Verified
                </strong>

                <span>

                    @if ($order->verified_at)

                    {{ $order->verified_at->format('d-m-Y H:i') }}

                    <br>

                    {{ $order->omdVerifier?->name ?? '-' }}

                    @else

                    Belum dilakukan

                    @endif

                </span>

            </div>


            {{-- IN REPAIR --}}
            <div
                class="timeline-item
            {{ in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? 'active' : '' }}">

                <div class="timeline-icon">
                    {{ in_array($order->status, ['in_repair', 'completed', 'confirmed']) ? '✓' : '✕' }}
                </div>

                <strong>
                    In Repair
                </strong>

                <span>

                    @if ($order->repair_started_at)

                    {{ $order->repair_started_at->format('d-m-Y H:i') }}

                    @else

                    Belum dilakukan

                    @endif

                </span>

            </div>


            {{-- COMPLETED --}}
            <div
                class="timeline-item
            {{ in_array($order->status, ['completed', 'confirmed']) ? 'active' : '' }}">

                <div class="timeline-icon">
                    {{ in_array($order->status, ['completed', 'confirmed']) ? '✓' : '✕' }}
                </div>

                <strong>
                    Completed
                </strong>

                <span>

                    @if ($order->repair_completed_at)

                    {{ $order->repair_completed_at->format('d-m-Y H:i') }}

                    @else

                    Belum dilakukan

                    @endif

                </span>

            </div>


            {{-- CONFIRMED --}}
            <div
                class="timeline-item
            {{ $order->status === 'confirmed' ? 'active' : '' }}">

                <div class="timeline-icon">
                    {{ $order->status === 'confirmed' ? '✓' : '✕' }}
                </div>

                <strong>
                    Confirmed
                </strong>

                <span>

                    @if ($order->status === 'confirmed' && $order->confirmation?->confirmed_at)

                    {{ $order->confirmation->confirmed_at->format('d-m-Y H:i') }}

                    <br>

                    {{ $order->confirmation->confirmedByUser?->name ?? '-' }}

                    @else

                    Belum dilakukan

                    @endif

                </span>

            </div>

        </div>

    </div>

</div>

@endsection