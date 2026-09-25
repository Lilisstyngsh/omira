@extends('layouts.app')

@section('title', 'Order Saya')

@section('header', 'Order Saya')

@section('content')
    <div class="page-head">
        <div>
            <h2>Order Repair Box</h2>
            <div class="muted">
                Daftar order yang dibuat oleh akun Anda.
            </div>
        </div>

        <a class="btn btn-primary" href="{{ route('user.orders.create') }}">
            + Buat Order
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Tanggal</th>
                    <th>Area</th>
                    <th>Model</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <b>{{ $order->order_number }}</b>
                        </td>

                        <td>
                            {{ $order->order_date->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $order->area->category }} - {{ $order->area->name }}
                        </td>

                        <td>
                            {{ $order->masterModel?->model ?? $order->model ?? '-' }}
                        </td>

                        <td>
                            {{ $order->product->name }}
                        </td>

                        <td>
                            {{ $order->quantity }}
                        </td>

                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ $order->status_label }}
                            </span>
                        </td>

                        <td>
                            <a
                                class="btn btn-secondary"
                                href="{{ route('user.orders.show', $order) }}"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            Belum ada order.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
@endsection