@extends('layouts.app')

@section('title', 'Order Repair')
@section('header', 'Order Repair')

@section('content')

    <div class="page-head">
        <div>
            <h2>Order Masuk</h2>

            <div class="muted">
                Kelola order repair yang dikirim user.
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Area</th>
                    <th>Model</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <b>{{ $order->order_number }}</b>
                        </td>

                        <td>
                            {{ $order->user->name }}
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
                                href="{{ route('omd.orders.show', $order) }}"
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