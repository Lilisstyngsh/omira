@extends('layouts.app')

@section('title', 'Proses Order')
@section('header', 'Proses Order')

@section('content')

    <div class="page-head">
        <div>
            <h2>{{ $order->order_number }}</h2>

            <span class="badge badge-{{ $order->status }}">
                {{ $order->status_label }}
            </span>
        </div>

        <a class="btn btn-secondary" href="{{ route('omd.orders.index') }}">
            Kembali
        </a>
    </div>

    <div class="grid2">

        <div>
            <div class="card">
                <h3>Informasi Order</h3>

                <div class="detail-grid">

                    <div class="detail-item">
                        <span>User</span>
                        <strong>{{ $order->user->name }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Area</span>
                        <strong>
                            {{ $order->area->category }} - {{ $order->area->name }}
                        </strong>
                    </div>

                    <div class="detail-item">
                        <span>Produk</span>
                        <strong>{{ $order->product->name }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Model</span>
                        <strong>{{ $order->masterModel?->model ?? $order->model ?? '-' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Jenis NG</span>
                        <strong>
                            {{ $order->ngType->code }} - {{ $order->ngType->name }}
                        </strong>
                    </div>

                    <div class="detail-item">
                        <span>Quantity</span>
                        <strong>{{ $order->quantity }}</strong>
                    </div>

                </div>

                <p class="muted" style="margin-top:16px">
                    {{ $order->description ?: 'Tidak ada keterangan.' }}
                </p>
            </div>

            <div class="card" style="margin-top:18px">
                <h3>Aksi OMD</h3>

                <div class="actions">

                    @if ($order->status === 'submitted')
                        <form method="POST" action="{{ route('omd.orders.verify', $order) }}">
                            @csrf

                            <button class="btn btn-primary">
                                Verifikasi Order
                            </button>
                        </form>

                    @elseif ($order->status === 'verified')
                        <form method="POST" action="{{ route('omd.orders.start', $order) }}">
                            @csrf

                            <button class="btn btn-warning">
                                Mulai Repair
                            </button>
                        </form>

                    @elseif ($order->status === 'in_repair')
                        <span class="muted">
                            Silakan isi hasil repair di form berikut.
                        </span>
                    @endif

                </div>
            </div>

            @if (in_array($order->status, ['verified', 'in_repair']))
                <div class="card" style="margin-top:18px">
                    <h3>Input Hasil Repair</h3>

                    <form method="POST" action="{{ route('omd.orders.complete', $order) }}">
                        @csrf

                        <div class="form-grid">

                            <div class="field">
                                <label>OK</label>

                                <input
                                    type="number"
                                    name="ok_qty"
                                    min="0"
                                    max="{{ $order->quantity }}"
                                    value="{{ old('ok_qty', $order->quantity) }}"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label>SCRAP</label>

                                <input
                                    type="number"
                                    name="scrap_qty"
                                    min="0"
                                    value="{{ old('scrap_qty', 0) }}"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label>NG</label>

                                <input
                                    type="number"
                                    name="ng_qty"
                                    min="0"
                                    value="{{ old('ng_qty', 0) }}"
                                    required
                                >
                            </div>

                            <div class="field full">
                                <label>Catatan</label>

                                <textarea name="notes">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <button class="btn btn-success" style="margin-top:14px">
                            Simpan Hasil & Selesaikan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div>
            <div class="card">
                <h3>Progress</h3>

                <div class="timeline">

                    <div class="timeline-item">
                        <b>Submitted</b>
                        <p>
                            Order dibuat oleh user.
                        </p>
                    </div>

                    <div class="timeline-item">
                        <b>Verified</b>
                        <p>
                            {{ $order->verified_at
                                ? $order->verified_at->format('d/m/Y H:i')
                                : 'Menunggu verifikasi.' }}
                        </p>
                    </div>

                    <div class="timeline-item">
                        <b>In Repair</b>
                        <p>
                            {{ $order->repair_started_at
                                ? $order->repair_started_at->format('d/m/Y H:i')
                                : 'Menunggu proses repair.' }}
                        </p>
                    </div>

                    <div class="timeline-item">
                        <b>Completed</b>
                        <p>
                            {{ $order->repair_completed_at
                                ? $order->repair_completed_at->format('d/m/Y H:i')
                                : 'Menunggu hasil repair.' }}
                        </p>
                    </div>

                    <div class="timeline-item">
                        <b>Confirmed</b>
                        <p>
                            {{ $order->status === 'confirmed'
                                ? 'Sudah dikonfirmasi user.'
                                : 'Menunggu konfirmasi user.' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection