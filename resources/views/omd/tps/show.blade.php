@extends('layouts.app')

@section('title', 'Proses TPS Tool')
@section('header', 'Proses TPS Tool')

@section('content')

    <div class="page-head">
        <div>
            <h2>{{ $order->order_number }}</h2>

            <span class="badge badge-{{ $order->status }}">
                {{ $order->status_label }}
            </span>
        </div>

        <a class="btn btn-secondary" href="{{ route('omd.tps.index') }}">
            Kembali
        </a>
    </div>

    <div class="grid2">

        <div>
            <div class="card">

                <div class="detail-grid">

                    <div class="detail-item">
                        <span>User</span>
                        <strong>{{ $order->user->name }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Tool</span>
                        <strong>{{ $order->tool_name }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Tanggal</span>
                        <strong>
                            {{ $order->reported_date->format('d/m/Y') }}
                        </strong>
                    </div>

                </div>

                <p>
                    <b>Problem</b>
                </p>

                <p class="muted">
                    {{ $order->problem_description }}
                </p>

            </div>

            <div class="card" style="margin-top:18px">

                <h3>Aksi</h3>

                @if ($order->status === 'submitted' && auth()->user()->role === 'omd_leader')

                    <form method="POST" action="{{ route('omd.tps.leader-check', $order) }}">
                        @csrf

                        <button class="btn btn-primary">
                            Leader Check
                        </button>
                    </form>

                @elseif ($order->status === 'leader_checked')

                    <form method="POST" action="{{ route('omd.tps.verify', $order) }}">
                        @csrf

                        <button class="btn btn-primary">
                            Verifikasi Problem
                        </button>
                    </form>

                @elseif ($order->status === 'verified' && auth()->user()->role === 'omd_leader')

                    <form method="POST" action="{{ route('omd.tps.schedule', $order) }}">

                        <div class="field">
                            <label>Jadwal Repair</label>

                            <input
                                type="datetime-local"
                                name="scheduled_at"
                                required
                            >
                        </div>

                        <button class="btn btn-warning" style="margin-top:10px">
                            Simpan Schedule
                        </button>

                    </form>

                @elseif (in_array($order->status, ['scheduled', 'in_repair']))

                    <form method="POST" action="{{ route('omd.tps.complete', $order) }}">

                        @csrf

                        <div class="field">
                            <label>Hasil Repair</label>

                            <textarea name="repair_result" required>{{ old('repair_result') }}</textarea>
                        </div>

                        <button class="btn btn-success" style="margin-top:10px">
                            Simpan Hasil Repair
                        </button>

                    </form>

                @else

                    <span class="muted">
                        Menunggu proses berikutnya.
                    </span>

                @endif

            </div>
        </div>

        <div class="card">

            <h3>Progress</h3>

            <div class="timeline">

                <div class="timeline-item">
                    <b>Submitted</b>
                    <p>
                        Form dibuat user.
                    </p>
                </div>

                <div class="timeline-item">
                    <b>Leader Checked</b>
                    <p>
                        {{ $order->leader_checked_at
                            ? $order->leader_checked_at->format('d/m/Y H:i')
                            : 'Menunggu.' }}
                    </p>
                </div>

                <div class="timeline-item">
                    <b>Verified</b>
                    <p>
                        {{ $order->member_verified_at
                            ? $order->member_verified_at->format('d/m/Y H:i')
                            : 'Menunggu.' }}
                    </p>
                </div>

                <div class="timeline-item">
                    <b>Scheduled</b>
                    <p>
                        {{ $order->scheduled_at
                            ? $order->scheduled_at->format('d/m/Y H:i')
                            : 'Menunggu.' }}
                    </p>
                </div>

                <div class="timeline-item">
                    <b>Completed</b>
                    <p>
                        {{ $order->repaired_at
                            ? $order->repaired_at->format('d/m/Y H:i')
                            : 'Menunggu.' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

@endsection