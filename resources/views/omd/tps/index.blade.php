@extends('layouts.app')

@section('title', 'TPS Tool OMD')
@section('header', 'TPS Tool OMD')

@section('content')

    <div class="page-head">
        <div>
            <h2>Order TPS Tool</h2>

            <div class="muted">
                Monitoring workflow TPS Tool.
            </div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>User</th>
                    <th>Tool</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            {{ $order->order_number }}
                        </td>

                        <td>
                            {{ $order->user->name }}
                        </td>

                        <td>
                            {{ $order->tool_name }}
                        </td>

                        <td>
                            {{ $order->reported_date->format('d/m/Y') }}
                        </td>

                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ $order->status_label }}
                            </span>
                        </td>

                        <td>
                            <a
                                class="btn btn-secondary"
                                href="{{ route('omd.tps.show', $order) }}"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="empty">
                            Belum ada order TPS Tool.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}

@endsection