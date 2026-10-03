@extends('layouts.app')

@section('title', 'Monitoring Order Repair Box')
@section('header', 'Monitoring Order Repair Box')

@section('content')

    <style>
        .order-page-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .order-page-title {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 500;
            color: #172033;
        }

        .order-page-desc {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .order-table-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .order-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 20px 22px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(135deg, #f8f7ff 0%, #fff 75%);
        }

        .order-table-header h3 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 500;
            color: #172033;
        }

        .order-table-header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .order-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            padding: 0 12px;
            border-radius: 10px;
            background: #f1efff;
            color: #6557dc;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-table-wrap {
            padding: 12px;
            overflow-x: hidden;
        }

        .order-table {
            width: 100%;
            min-width: 0;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #dfe6ef;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .order-table th {
            padding: 10px 7px;
            text-align: center;
            background: #fafbfc;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .025em;
            white-space: normal;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .order-table th:last-child,
        .order-table td:last-child {
            border-right: 0;
        }

        .order-table thead tr:first-child th {
            background: #f6f7fb;
            border-bottom: 1px solid #dfe6ef;
            text-align: center;
        }

        .order-table thead tr:first-child th[rowspan="2"] {
            text-align: center;
            vertical-align: middle;
        }

        .order-table thead tr:nth-child(2) th {
            text-align: center;
            font-size: 8.5px;
            padding: 9px 5px;
        }

        .order-table th:nth-child(6),
        .order-table td:nth-child(6) {
            border-left: 1px solid #dfe6ef;
        }

        .order-table td {
            padding: 11px 7px;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
            color: #334155;
            font-size: 11px;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .order-table tbody tr {
            transition: .18s ease;
            cursor: pointer;
        }

        .order-table tbody tr:hover {
            background: #fafaff;
            transform: translateY(-1px);
        }

        .order-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-date {
            text-align: center;
            line-height: 1.2;
            white-space: nowrap;
        }

        .order-date strong {
            display: block;
            color: #334155;
            font-size: 10.5px;
            font-weight: 600;
        }

        .order-date span {
            display: block;
            margin-top: 2px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 400;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 400;
            text-align: center;
        }

        .order-number {
            color: #172033;
            font-weight: 600;
            white-space: normal;
            line-height: 1.3;
            word-break: break-word;
        }

        .order-user {
            color: #475569;
        }

        .order-line-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-type-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #f5f3ff;
            color: #6659df;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-qty {
            font-size: 13px;
            font-weight: 500;
            color: #172033;
            text-align: center;
        }

        .process-cell {
            text-align: center !important;
            padding: 9px 4px !important;
        }

        .process-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 900;
            line-height: 1;
        }

        .process-done {
            background: #dcfce7;
            color: #16a34a;
            box-shadow: inset 0 0 0 1px #bbf7d0;
        }

        .process-progress {
            background: #fef3c7;
            color: #d97706;
            box-shadow: inset 0 0 0 1px #fde68a;
        }

        .process-pending {
            background: #fee2e2;
            color: #dc2626;
            box-shadow: inset 0 0 0 1px #fecaca;
        }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #f5f3ff;
            color: #7c3aed;
            font-size: 19px;
            font-weight: 800;
        }

        .order-empty-title {
            margin-bottom: 3px;
            color: #475569;
            font-weight: 750;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 900px) {
            .order-table-wrap {
                overflow-x: auto;
            }

            .order-table {
                min-width: 760px;
            }
        }

        @media (max-width: 768px) {
            .order-page-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .order-table-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        .order-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            background: #f0fdf4;
        }

        .order-alert-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #dcfce7;
            color: #16a34a;
            font-size: 16px;
            font-weight: 800;
        }

        .order-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #166534;
            font-size: 12px;
            font-weight: 800;
        }

        .order-alert span {
            color: #4d7a5c;
            font-size: 10px;
        }

        .feedback-flag{position:relative;display:inline-flex;align-items:center;margin-left:6px;vertical-align:middle}
        .feedback-flag-btn{border:0;background:transparent;color:#d97706;font-size:15px;line-height:1;cursor:pointer;padding:4px;border-radius:6px}
        .feedback-flag-btn:hover{background:#fff7ed}
        .feedback-popover{position:absolute;z-index:40;top:28px;right:0;width:270px;padding:10px 11px;border:1px solid #f59e0b;border-radius:9px;background:#fff;color:#78350f;box-shadow:0 12px 32px rgba(15,23,42,.16);font-size:11px;line-height:1.45;text-align:left}
        .feedback-popover strong{display:block;margin-bottom:4px;font-weight:650}.feedback-popover small{display:block;margin-top:5px;color:#a16207}
    </style>

    @if ($completedCount > 0)
        <div class="order-alert">
            <div class="order-alert-icon">
                ✓
            </div>

            <div>
                <strong>
                    Order Repair Box Selesai
                </strong>

                <span>
                    {{ $completedCount }} order selesai diproses dan menunggu konfirmasi dari User.
                </span>
            </div>
        </div>
    @endif

    <div class="order-table-card">

        <div class="order-table-header">

            <div>

                <h3>
                    Daftar Monitoring Repair Box
                </h3>

                <p>
                    Pantau status order dari submit User hingga serah terima.
                </p>

            </div>
        </div>

        <div class="order-table-wrap">

            <table class="order-table">

                <colgroup>
                    <col style="width:5%;">
                    <col style="width:11%;">
                    <col style="width:17%;">
                    <col style="width:14%;">
                    <col style="width:7%;">
                    <col style="width:11.5%;">
                    <col style="width:11.5%;">
                    <col style="width:11.5%;">
                    <col style="width:11.5%;">
                </colgroup>

                <thead>

                    <tr>

                        <th rowspan="2" style="text-align:center;">
                            No
                        </th>

                        <th rowspan="2">
                            Tanggal
                        </th>

                        <th rowspan="2">
                            No Order
                        </th>

                        <th rowspan="2">
                            Line
                        </th>

                        <th rowspan="2">
                            Qty
                        </th>

                        <th colspan="4" style="text-align:center;">
                            Status Proses
                        </th>

                    </tr>

                    <tr>

                        <th class="process-cell">
                            User Submit
                        </th>

                        <th class="process-cell">
                            Verifikasi OMD
                        </th>

                        <th class="process-cell">
                            Repair OMD
                        </th>

                        <th class="process-cell">
                            Serah Terima
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($orders as $order)
                        <tr onclick="window.location='{{ route('omd.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="order-no">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                            </td>

                            <td class="order-date">
                                @if ($order->created_at)
                                    <strong>{{ $order->created_at->format('d-m-Y') }}</strong>
                                    <span>{{ $order->created_at->format('H:i') }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="order-number">
                                {{ $order->order_number }}
                                @if($order->openFeedback)
                                    <span class="feedback-flag">
                                        <button type="button" class="feedback-flag-btn" title="Ada feedback ketidaksesuaian dari User"
                                            onclick="event.stopPropagation(); var p=this.nextElementSibling; document.querySelectorAll('.feedback-popover').forEach(function(el){ if(el!==p) el.hidden=true; }); p.hidden=!p.hidden;">⚠</button>
                                        <span class="feedback-popover" hidden onclick="event.stopPropagation()">
                                            <strong>Feedback User</strong>
                                            {{ $order->openFeedback->reason }}
                                            <small>{{ $order->openFeedback->created_at?->format('d-m-Y H:i') }}</small>
                                        </span>
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="order-line-badge">
                                    {{ $order->line?->name ?? ($order->area ? $order->area->name : '-') }}
                                </span>
                            </td>

                            <td class="order-qty">
                                {{ in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true) ? (int) ($order->after_qty_sum ?? 0) : (int) ($order->before_qty_sum ?? $order->quantity) }}
                            </td>

                            {{-- USER SUBMIT --}}
                            <td class="process-cell">
                                <span class="process-icon process-done" title="User sudah submit order">
                                    ✓
                                </span>
                            </td>

                            {{-- VERIFIED OMD --}}
                            <td class="process-cell">

                                @if (in_array($order->status, ['in_repair', 'completed', 'revision_requested', 'confirmed']))
                                    <span class="process-icon process-done" title="Order sudah diverifikasi OMD">
                                        ✓
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Belum diverifikasi OMD">
                                        ✕
                                    </span>
                                @endif

                            </td>

                            {{-- REPAIR OMD --}}
                            <td class="process-cell">

                                @if (in_array($order->status, ['completed', 'revision_requested', 'confirmed']))
                                    <span class="process-icon process-done" title="Repair OMD sudah selesai">
                                        ✓
                                    </span>
                                @elseif ($order->status === 'in_repair')
                                    <span class="process-icon process-progress" title="Repair OMD sedang diproses">
                                        △
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Repair OMD belum dimulai">
                                        ✕
                                    </span>
                                @endif

                            </td>

                            {{-- SERAH TERIMA --}}
                            <td class="process-cell">

                                @if ($order->status === 'confirmed')
                                    <span class="process-icon process-done" title="Serah terima sudah selesai">
                                        ✓
                                    </span>
                                @elseif ($order->status === 'revision_requested')
                                    <span class="process-icon process-progress"
                                        title="User meminta koreksi hasil repair dari OMD">
                                        △
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span class="process-icon process-progress"
                                        title="Menunggu serah terima / konfirmasi User">
                                        △
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Belum masuk tahap serah terima">
                                        ✕
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="order-empty">

                                <div class="order-empty-text">
                                    Belum ada Order Repair Box
                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div style="margin-top:18px;">
        {{ $orders->links() }}
    </div>

@endsection
