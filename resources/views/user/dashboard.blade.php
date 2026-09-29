@extends('layouts.app')

@section('title', 'Dashboard User')

@section('header', 'Dashboard User')

@section('content')

    <style>
        .dashboard-welcome {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .dashboard-welcome h2 {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .dashboard-welcome .muted {
            font-size: 12px;
        }

        .dashboard-alert {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #fde68a;
            border-radius: 14px;
            background: #fffbeb;
        }

        .dashboard-alert-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fef3c7;
            color: #d97706;
            font-size: 16px;
            font-weight: 800;
        }

        .dashboard-alert strong {
            display: block;
            margin-bottom: 3px;
            color: #92400e;
            font-size: 12px;
            font-weight: 800;
        }

        .dashboard-alert span {
            color: #a16207;
            font-size: 10px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .dashboard-stat-card {
            position: relative;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
            transition: .18s ease;
        }

        .dashboard-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(15, 23, 42, .07);
        }

        .dashboard-stat-card::after {
            content: '';
            position: absolute;
            width: 90px;
            height: 90px;
            right: -35px;
            top: -35px;
            border-radius: 50%;
            background: rgba(124, 58, 237, .05);
        }

        .dashboard-stat-label {
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .dashboard-stat-value {
            margin-top: 8px;
            color: #172033;
            font-size: 26px;
            font-weight: 850;
            letter-spacing: -.6px;
        }

        .dashboard-stat-help {
            margin-top: 4px;
            color: #94a3b8;
            font-size: 9px;
            line-height: 1.4;
        }

        .dashboard-section {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .05);
            overflow: hidden;
        }

        .dashboard-section-head {
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

        .dashboard-section-head h3 {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 800;
            color: #172033;
        }

        .dashboard-section-head p {
            margin: 0;
            font-size: 10px;
            color: #64748b;
        }

        .dashboard-section-head .btn {
            white-space: nowrap;
        }

        .dashboard-table-wrap {
            overflow-x: auto;
        }

        .dashboard-table {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
        }

        .dashboard-table th {
            padding: 12px 13px;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
            white-space: nowrap;
        }

        .dashboard-table thead tr:first-child th {
            background: #f6f7fb;
            text-align: center;
        }

        .dashboard-table thead tr:first-child th[rowspan="2"] {
            text-align: left;
            vertical-align: middle;
        }

        .dashboard-table thead tr:nth-child(2) th {
            text-align: center;
            font-size: 8px;
            padding: 10px 8px;
        }

        .dashboard-table td {
            padding: 13px;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 11px;
            vertical-align: middle;
        }

        .dashboard-table tbody tr {
            cursor: pointer;
            transition: .18s ease;
        }

        .dashboard-table tbody tr:hover {
            background: #fafaff;
            transform: translateY(-1px);
        }

        .dashboard-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-number {
            color: #172033;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-line {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            padding: 0 9px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .order-product {
            max-width: 180px;
            color: #475569;
        }

        .process-cell {
            width: 105px;
            text-align: center !important;
            padding: 10px 7px !important;
        }

        .process-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 900;
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

        .empty-dashboard {
            padding: 45px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
            font-size: 11px !important;
        }

        @media (max-width: 1100px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .dashboard-welcome {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .dashboard-section-head {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>


    @php
        $completedOrders = $recentOrders->where('status', 'completed')->count();
    @endphp


    {{-- =========================================================
     WELCOME
========================================================= --}}

    <div class="dashboard-welcome">

        <div>

            <h2>
                Selamat datang, {{ auth()->user()->name }}
            </h2>

            <div class="muted">
                Pantau proses Order Repair Box yang Anda buat.
            </div>

        </div>

        <a class="btn btn-primary" href="{{ route('user.orders.create') }}">
            <i class="fa-solid fa-plus"></i>
            Buat Order Repair
        </a>

    </div>


    {{-- =========================================================
     NOTIFICATION
========================================================= --}}

    @if ($completedOrders > 0)
        <div class="dashboard-alert">

            <div class="dashboard-alert-icon">
                !
            </div>

            <div>

                <strong>
                    Hasil Repair dari OMD
                </strong>

                <span>
                    {{ $completedOrders }}
                    order telah selesai diperbaiki dan menunggu konfirmasi Anda.
                </span>

            </div>

        </div>
    @endif


    {{-- =========================================================
     STAT CARDS
========================================================= --}}

    <div class="dashboard-cards">

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-label">
                Total Order
            </div>

            <div class="dashboard-stat-value">
                {{ $total }}
            </div>

            <div class="dashboard-stat-help">
                Seluruh Order Repair Box yang pernah dibuat.
            </div>

        </div>


        <div class="dashboard-stat-card">

            <div class="dashboard-stat-label">
                Open
            </div>

            <div class="dashboard-stat-value">
                {{ $submitted }}
            </div>

            <div class="dashboard-stat-help">
                Menunggu verifikasi dari OMD.
            </div>

        </div>


        <div class="dashboard-stat-card">

            <div class="dashboard-stat-label">
                Progress
            </div>

            <div class="dashboard-stat-value">
                {{ $inRepair }}
            </div>

            <div class="dashboard-stat-help">
                Sedang berada dalam proses repair.
            </div>

        </div>


        <div class="dashboard-stat-card">

            <div class="dashboard-stat-label">
                Waiting Confirmation
            </div>

            <div class="dashboard-stat-value">
                {{ $completed }}
            </div>

            <div class="dashboard-stat-help">
                Repair selesai dan menunggu konfirmasi User.
            </div>

        </div>

    </div>


    {{-- =========================================================
     RECENT ORDERS
========================================================= --}}

    <div class="dashboard-section">

        <div class="dashboard-section-head">

            <div>

                <h3>
                    Order Terbaru
                </h3>

                <p>
                    Enam Order Repair Box terakhir.
                </p>

            </div>

            <a class="btn btn-secondary" href="{{ route('user.orders.index') }}">
                Lihat Semua
            </a>

        </div>


        <div class="dashboard-table-wrap">

            <table class="dashboard-table">

                <thead>

                    <tr>

                        <th rowspan="2">
                            No Order
                        </th>

                        <th rowspan="2">
                            Tanggal
                        </th>

                        <th rowspan="2">
                            Line
                        </th>

                        <th rowspan="2">
                            Produk
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

                    @forelse($recentOrders as $order)

                        <tr onclick="window.location='{{ route('user.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="order-number">
                                {{ $order->order_number }}
                            </td>


                            <td>
                                {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}
                            </td>


                            <td>

                                <span class="order-line">
                                    {{ $order->line?->name ?? '-' }}
                                </span>

                            </td>


                            <td class="order-product">

                                @if ($order->items->isNotEmpty())
                                    {{ $order->items->first()->product?->name ?? '-' }}

                                    @if ($order->items->count() > 1)
                                        <span class="muted">
                                            +{{ $order->items->count() - 1 }}
                                        </span>
                                    @endif
                                @else
                                    {{ $order->product?->name ?? '-' }}
                                @endif

                            </td>


                            {{-- USER SUBMIT --}}
                            <td class="process-cell">

                                <span class="process-icon process-done" title="User sudah submit order">
                                    ✓
                                </span>

                            </td>


                            {{-- VERIFIED OMD --}}
                            <td class="process-cell">

                                @if (in_array($order->status, ['in_repair', 'completed', 'confirmed']))
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

                                @if (in_array($order->status, ['completed', 'confirmed']))
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
                                @elseif ($order->status === 'completed')
                                    <span class="process-icon process-progress" title="Menunggu konfirmasi User">
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

                            <td colspan="8" class="empty-dashboard">
                                Belum ada Order Repair Box.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
