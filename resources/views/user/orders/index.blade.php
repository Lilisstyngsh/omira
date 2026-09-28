@extends('layouts.app')

@section('header', 'Order Repair Box')

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

        .btn-create {
            text-decoration: none;
            background: #4f46e5;
            color: #fff;
            padding: 10px 15px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px;
            font-size: 11px;
            color: #6b7280;
        }

        .orders-table td {
            padding: 13px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #374151;
        }

        .order-number {
            font-weight: 700;
            color: #111827;
        }

        .status {
            display: inline-flex;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .status-submitted {
            background: #eff6ff;
            color: #2563eb;
        }

        .status-verified {
            background: #fef3c7;
            color: #92400e;
        }

        .status-in_repair {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .status-completed {
            background: #ecfdf5;
            color: #047857;
        }

        .status-confirmed {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-detail {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f3f4f6;
            color: #374151;
        }

        @media (max-width: 900px) {
            .table-wrap {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 900px;
            }
        }
    </style>

    <div class="page-card">

        <div class="page-head">
            <h2>Daftar Order Repair Box</h2>

            <a href="{{ route('user.orders.create') }}" class="btn-create">
                + Buat Order
            </a>
        </div>

        <div class="table-wrap">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No Order</th>
                        <th>Jenis Order</th>
                        <th>Line</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($orders as $index => $order)
                        <tr>
                            <td>
                                {{ $orders->firstItem() + $index }}
                            </td>

                            <td>
                                {{ $order->created_at->format('d-m-Y H:i') }}
                            </td>

                            <td class="order-number">
                                {{ $order->order_number }}
                            </td>

                            <td>
                                Repair Box
                            </td>

                            <td>
                                {{ $order->line?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $order->quantity }}
                            </td>

                            <td>
                                <span class="status status-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('user.orders.show', $order) }}" class="btn-detail" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" style="text-align:center;padding:40px;color:#9ca3af;">
                                Belum ada order Repair Box.
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
