@extends('layouts.app')

@section('title', 'Dashboard User')

@section('header', 'Dashboard User')

@section('content')
    <div class="page-head">
        <div>
            <h2>Selamat datang, {{ auth()->user()->name }}</h2>

            <div class="muted">
                Pantau pengajuan order repair yang Anda buat.
            </div>
        </div>

        <a
            class="btn btn-primary"
            href="{{ route('user.orders.create') }}"
        >
            + Buat Order Repair
        </a>
    </div>

    <div class="cards">
        <div class="card">
            <div class="stat-label">Total Order</div>
            <div class="stat-value">{{ $total }}</div>
        </div>

        <div class="card">
            <div class="stat-label">Menunggu Verifikasi OMD</div>
            <div class="stat-value">{{ $submitted }}</div>
        </div>

        <div class="card">
            <div class="stat-label">Sedang Repair</div>
            <div class="stat-value">{{ $inRepair }}</div>
        </div>

        <div class="card">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $completed }}</div>
        </div>
    </div>

    <div class="card">
        <div class="page-head">
            <div>
                <h3>Order Terbaru</h3>

                <div class="muted">
                    Enam order terakhir.
                </div>
            </div>

            <a
                class="btn btn-secondary"
                href="{{ route('user.orders.index') }}"
            >
                Lihat Semua
            </a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Area</th>
                        <th>Produk</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('user.orders.show', $order) }}">
                                    <b>{{ $order->order_number }}</b>
                                </a>
                            </td>

                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $order->area->category }} -
                                {{ $order->area->name }}
                            </td>

                            <td>
                                {{ $order->product->name }}
                            </td>

                            <td>
                                <span class="badge badge-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                Belum ada order.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection