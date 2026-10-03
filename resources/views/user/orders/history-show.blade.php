@extends('layouts.app')

@section('title', 'Detail History Order Repair Box')
@section('header', 'Detail History Order Repair Box')

@section('content')
<style>
    .history-detail-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px
    }

    .history-detail-head h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a
    }

    .history-detail-head p {
        margin: 3px 0 0;
        font-size: 11px;
        color: #64748b
    }

    .btn-history-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 14px;
        border-radius: 9px;
        background: #334155;
        color: #fff !important;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #334155
    }

    .btn-history-back:hover {
        background: #1e293b
    }

    .history-card {
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px
    }

    .history-card h3 {
        margin: 0 0 12px;
        font-size: 14px;
        font-weight: 650;
        color: #0f172a
    }

    .timeline {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px
    }

    .timeline-item {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 10px
    }

    .timeline-item strong {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #334155
    }

    .timeline-item span {
        display: block;
        margin-top: 4px;
        font-size: 10px;
        color: #64748b;
        line-height: 1.4
    }

    .feedback-history {
        display: grid;
        gap: 8px
    }

    .feedback-row {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 10px 11px;
        font-size: 11px;
        color: #334155
    }

    .feedback-row strong {
        font-weight: 600
    }

    .feedback-row small {
        display: block;
        margin-top: 4px;
        color: #64748b
    }

    @media(max-width:760px) {
        .history-detail-head {
            align-items: flex-start
        }

        .timeline {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        .btn-history-back {
            min-height: 46px
        }
    }
</style>

<div class="history-detail-head">
    <div>
        <h2>{{ $order->order_number }}</h2>
        <p>History Repair Box</p>
    </div>
    <a href="{{ route('user.orders.history') }}" class="btn-history-back">← Kembali ke History</a>
</div>

<div class="history-card">
    <h3>Progress Order</h3>
    <div class="timeline">
        <div class="timeline-item"><strong>User Submit</strong><span>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</span></div>
        <div class="timeline-item"><strong>Verifikasi OMD</strong><span>{{ $order->verified_at?->format('d-m-Y H:i') ?? '-' }}@if($order->omdVerifier?->name)<br>{{ $order->omdVerifier->name }}@endif</span></div>
        <div class="timeline-item"><strong>Repair OMD</strong><span>{{ $order->repair_completed_at?->format('d-m-Y H:i') ?? '-' }}@if($order->result?->processedBy?->name)<br>{{ $order->result->processedBy->name }}@endif</span></div>
        <div class="timeline-item"><strong>Serah Terima</strong><span>{{ $order->confirmation?->confirmed_at?->format('d-m-Y H:i') ?? '-' }}</span></div>
    </div>
</div>

@include('partials.repair-order-information', ['order' => $order, 'context' => 'user-history'])

<div class="history-card">
    <h3>Detail Order Repair Box</h3>
    @include('partials.repair-order-readonly-table', ['order' => $order, 'showAfter' => true, 'showProductNote' => true])
</div>

@if($order->feedbacks->isNotEmpty())
<div class="history-card">
    <h3>Riwayat Feedback</h3>
    <div class="feedback-history">
        @foreach($order->feedbacks->sortByDesc('created_at') as $feedback)
        <div class="feedback-row">
            <strong>{{ $feedback->reason }}</strong>
            <small>{{ $feedback->created_at?->format('d-m-Y H:i') }} · {{ $feedback->status === 'resolved' ? 'Sudah dikoreksi OMD' : 'Open' }}</small>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection