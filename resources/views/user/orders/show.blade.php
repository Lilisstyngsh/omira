@extends('layouts.app')

@section('title', 'Detail Order')
@section('header', 'Detail Order')

@section('content')

    <div class="page-head">
        <div>
            <h2>{{ $order->order_number }}</h2>

            <span class="badge badge-{{ $order->status }}">
                {{ $order->status_label }}
            </span>
        </div>

        <a class="btn btn-secondary" href="{{ route('user.orders.index') }}">
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="detail-grid">

            <div class="detail-item">
                <span>Area</span>
                <strong>
                    {{ $order->area->category }} - {{ $order->area->name }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Produk</span>
                <strong>
                    {{ $order->product->name }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Model</span>
                <strong>
                    {{ $order->masterModel?->model ?? $order->model ?? '-' }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Jenis NG</span>
                <strong>
                    {{ $order->ngType->code }} - {{ $order->ngType->name }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Quantity</span>
                <strong>
                    {{ $order->quantity }}
                </strong>
            </div>

            <div class="detail-item">
                <span>Tanggal</span>
                <strong>
                    {{ $order->order_date->format('d/m/Y') }}
                </strong>
            </div>

        </div>

        <div style="margin-top:18px">
            <b>Keterangan</b>

            <p class="muted">
                {{ $order->description ?: '-' }}
            </p>
        </div>
    </div>

    @if ($order->result)
        <div class="card" style="margin-top:18px">
            <h3>Hasil Repair</h3>

            <div class="detail-grid">

                <div class="detail-item">
                    <span>OK</span>
                    <strong>
                        {{ $order->result->ok_qty }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>SCRAP</span>
                    <strong>
                        {{ $order->result->scrap_qty }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>NG</span>
                    <strong>
                        {{ $order->result->ng_qty }}
                    </strong>
                </div>

            </div>

            <p class="muted">
                {{ $order->result->notes ?: 'Tidak ada catatan.' }}
            </p>
        </div>
    @endif

    @if ($order->status === 'completed')
        <form
            method="POST"
            action="{{ route('user.orders.confirm', $order) }}"
            style="margin-top:18px"
        >
            @csrf

            <button class="btn btn-success">
                Konfirmasi Hasil / Serah Terima
            </button>
        </form>
    @endif

@endsection