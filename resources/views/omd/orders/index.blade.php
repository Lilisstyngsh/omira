@extends('layouts.app')

@section('title', 'Order Repair Box')
@section('header', 'Order Repair Box')

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
        font-weight: 800;
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
        background: linear-gradient(135deg,
                #f8f7ff 0%,
                #fff 75%);
    }

    .order-table-header h3 {
        margin: 0 0 5px;
        font-size: 16px;
        font-weight: 800;
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
        font-weight: 800;
        white-space: nowrap;
    }

    .order-table-wrap {
        overflow-x: auto;
    }

    .order-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .order-table th {
        padding: 14px 16px;
        text-align: left;
        background: #fafbfc;
        border-bottom: 1px solid #e9eef4;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .order-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef2f6;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
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

    .order-no {
        width: 55px;
        color: #94a3b8 !important;
        font-weight: 700;
        text-align: center;
    }

    .order-number {
        color: #172033;
        font-weight: 800;
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
        font-weight: 800;
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
        font-weight: 800;
    }

    .order-qty {
        font-size: 13px;
        font-weight: 800;
        color: #172033;
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

    .order-action {
        height: 32px;
        padding: 0 12px;
        border: none;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .18s ease;
    }

    .order-action:hover {
        background: #e2e8f0;
        color: #334155;
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
            {{ $completedCount }} order selesai diproses dan menunggu verifikasi dari User.
        </span>
    </div>
</div>
@endif

<div class="order-table-card">

    <div class="order-table-header">

        <div>

            <h3>
                Daftar Order Repair Box
            </h3>

        </div>

        <div class="order-count">
            {{ $orders->total() }} Order
        </div>

    </div>


    <div class="order-table-wrap">

        <table class="order-table">

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

                @forelse($orders as $order)
                <tr onclick="window.location='{{ route('omd.orders.show', $order) }}'"
                    title="Klik untuk melihat detail order">

                    <td class="order-no">
                        {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                    </td>


                    <td>

                        {{ $order->created_at ? $order->created_at->format('d-m-Y H:i') : '-' }}

                    </td>


                    <td class="order-number">
                        {{ $order->order_number }}
                    </td>


                    <td>

                        <span class="order-type-badge">
                            Repair Box
                        </span>

                    </td>


                    <td>

                        <span class="order-line-badge">
                            {{ $order->line?->name ?? ($order->area ? $order->area->name : '-') }}
                        </span>

                    </td>


                    <td class="order-qty">
                        {{ $order->quantity }}
                    </td>


                    <td>

                        <span class="status-badge status-{{ $order->status }}">
                            {{ $order->status_label }}
                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="order-empty">

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