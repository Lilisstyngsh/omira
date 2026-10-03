@extends('layouts.app')

@section('title', 'Order Repair Box')
@section('header', 'Order Repair Box')

@section('content')

    <style>
        .page-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(135deg, #f8f7ff 0%, #fff 75%);
        }

        .page-head h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            color: #172033;
        }

        .btn-create {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            background: #2563eb;
            color: #fff;
            padding: 0 14px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            box-shadow: 0 6px 15px rgba(37, 99, 235, .18);
            transition: .18s ease;
        }

        .btn-create:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .orders-table-wrap {
            padding: 12px;
            overflow-x: hidden;
        }

        .orders-table {
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

        .orders-table th {
            padding: 10px 7px;
            text-align: center;
            background: #fafbfc;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .025em;
            color: #64748b;
            white-space: normal;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .orders-table th:last-child,
        .orders-table td:last-child {
            border-right: 0;
        }

        .orders-table thead tr:first-child th {
            background: #f6f7fb;
            text-align: center;
        }

        .orders-table thead tr:first-child th[rowspan="2"] {
            text-align: center;
            vertical-align: middle;
        }

        .orders-table thead tr:nth-child(2) th {
            text-align: center;
            font-size: 8.5px;
            padding: 9px 5px;
        }

        .orders-table th:nth-child(6),
        .orders-table td:nth-child(6) {
            border-left: 1px solid #dfe6ef;
        }

        .orders-table td {
            padding: 10px 7px;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
            font-size: 11px;
            color: #334155;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .orders-table tbody tr {
            cursor: pointer;
            transition: .18s ease;
        }

        .orders-table tbody tr:hover {
            background: #fafaff;
            transform: translateY(-1px);
        }

        .orders-table tbody tr:last-child td {
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
            text-align: center;
            color: #94a3b8 !important;
            font-weight: 700;
        }

        .order-number {
            font-weight: 500;
            color: #172033;
            white-space: normal;
            line-height: 1.3;
            word-break: break-word;
        }

        .order-line-badge {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            padding: 0 8px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 9.5px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-qty {
            font-size: 12px !important;
            font-weight: 500;
            color: #172033 !important;
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
            width: 27px;
            height: 27px;
            border-radius: 50%;
            font-size: 12px;
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

        .order-alert,
        .success-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border-radius: 14px;
        }

        .order-alert {
            border: 1px solid #fde68a;
            background: #fffbeb;
        }

        .success-alert {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
        }

        .order-alert-icon,
        .success-alert-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 800;
        }

        .order-alert-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .success-alert-icon {
            background: #dcfce7;
            color: #16a34a;
        }

        .order-alert strong,
        .success-alert strong {
            display: block;
            margin-bottom: 3px;
            font-size: 12px;
            font-weight: 800;
        }

        .order-alert strong { color: #92400e; }
        .success-alert strong { color: #166534; }
        .order-alert span { color: #a16207; font-size: 10px; }
        .success-alert span { color: #4d7a5c; font-size: 10px; }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 900px) {
            .orders-table-wrap {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 760px;
            }
        }

        @media (max-width: 640px) {
            .page-head {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    @php
        $completedOrders = $orders->where('status', 'completed')->count();
    @endphp

    @if ($completedOrders > 0)
        <div class="order-alert">
            <div class="order-alert-icon">
                !
            </div>

            <div>
                <strong>
                    Order Repair Box Selesai
                </strong>

                <span>
                    {{ $completedOrders }} order sudah selesai diperbaiki dan menunggu konfirmasi User.
                </span>
            </div>
        </div>
    @endif

    <div class="page-card">

        <div class="page-head">
            <h2>Daftar Order Repair Box</h2>

            <a href="{{ route('user.orders.create') }}" class="btn-create" title="Buat Order Repair Box baru">
                + Buat Order Repair
            </a>
        </div>

        <div class="orders-table-wrap">

            <table class="orders-table">

                <colgroup>
                    <col style="width:44px;">
                    <col style="width:94px;">
                    <col style="width:122px;">
                    <col style="width:110px;">
                    <col style="width:55px;">
                    <col style="width:78px;">
                    <col style="width:82px;">
                    <col style="width:78px;">
                    <col style="width:88px;">
                </colgroup>

                <thead>

                    <tr>

                        <th rowspan="2">
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
                            Status
                        </th>

                    </tr>

                    <tr>

                        <th class="process-cell">
                            User Submit
                        </th>

                        <th class="process-cell">
                            Verified OMD
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

                    @forelse ($orders as $index => $order)
                        <tr onclick="window.location='{{ route('user.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="order-no">
                                {{ $orders->firstItem() + $index }}
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
                            </td>

                            <td>
                                <span class="order-line-badge">
                                    {{ $order->line?->name ?? '-' }}
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

                            <td colspan="9" class="order-empty">
                                <div class="order-empty-text">
                                    Belum ada Order Repair Box
                                </div>
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div style="margin-top:20px;">
            {{ $orders->links() }}
        </div>

    </div>

@endsection
