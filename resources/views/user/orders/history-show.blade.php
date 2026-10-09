@extends('layouts.app')

@section('title', 'Detail History Order Repair Box')
@section('header', 'Detail History Order Repair Box')

@section('content')
<style>
    .history-detail-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px}.history-detail-head h2{margin:0;font-size:20px;font-weight:700;color:#0f172a}.history-detail-head p{margin:3px 0 0;font-size:11px;color:#64748b}
    .btn-history-back{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 14px;border-radius:9px;background:#334155;color:#fff!important;text-decoration:none;font-size:12px;font-weight:600;border:1px solid #334155}.btn-history-back:hover{background:#1e293b}
    .history-card{background:#fff;border:1px solid #cbd5e1;border-radius:12px;padding:16px;margin-bottom:16px}.history-card + .repair-info-card,.repair-info-card + .history-card{margin-top:16px}.history-card h3{margin:0 0 12px;font-size:14px;font-weight:650;color:#0f172a}

    /* Progress Order dipertahankan seperti tampilan awal */
    .timeline-wrap{overflow-x:auto;padding:8px 2px 5px}
    .timeline{min-width:620px;display:grid;grid-template-columns:repeat(4,minmax(140px,1fr));position:relative}
    .timeline::before{content:'';position:absolute;left:12.5%;right:12.5%;top:18px;height:2px;background:#bbf7d0}
    .timeline-item{position:relative;text-align:center;padding:0 8px;z-index:1}
    .timeline-icon{width:28px;height:28px;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;border-radius:50%;font-size:15px;font-weight:800;background:#dcfce7;color:#16a34a;border:3px solid #fff;box-shadow:0 0 0 2px #bbf7d0}
    .timeline-item strong{display:block;margin-bottom:5px;color:#334155;font-size:11px;font-weight:800}
    .timeline-item span{display:block;font-size:9px;line-height:1.5;color:#64748b}

    .feedback-history{display:grid;gap:8px}.feedback-row{border:1px solid #e2e8f0;border-radius:9px;padding:10px 11px;font-size:11px;color:#334155}.feedback-row strong{font-weight:600}.feedback-row small{display:block;margin-top:4px;color:#64748b}
    @media(max-width:760px){.history-detail-head{align-items:flex-start}.btn-history-back{min-height:46px}}
</style>

<div class="history-detail-head">
    <div><h2>{{ $order->order_number }}</h2><p>History</p></div>
    <a href="{{ route('user.orders.history') }}" class="btn-history-back">← Kembali ke History</a>
</div>

<div class="history-card">
    <h3>Progress Order</h3>
    <div class="timeline-wrap">
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-icon">✓</div>
                <strong>User Submit</strong>
                <span>
                    {{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}
                    @if($order->user?->name)<br>{{ $order->user->name }}@endif
                </span>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">✓</div>
                <strong>Verified OMD</strong>
                <span>
                    @if($order->verified_at)
                        {{ $order->verified_at->format('d-m-Y H:i') }}
                        @if($order->omdVerifier?->name)<br>{{ $order->omdVerifier->name }}@endif
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">✓</div>
                <strong>Repair OMD</strong>
                <span>
                    @if($order->repair_completed_at)
                        {{ $order->repair_completed_at->format('d-m-Y H:i') }}
                        @if($order->result?->processedBy?->name)<br>{{ $order->result->processedBy->name }}@endif
                    @elseif($order->repair_started_at)
                        {{ $order->repair_started_at->format('d-m-Y H:i') }}
                        @if($order->result?->processedBy?->name)<br>{{ $order->result->processedBy->name }}@endif
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">✓</div>
                <strong>Serah Terima</strong>
                <span>
                    @if($order->confirmation?->confirmed_at)
                        {{ $order->confirmation->confirmed_at->format('d-m-Y H:i') }}
                        @if($order->confirmation->user?->name)<br>{{ $order->confirmation->user->name }}@endif
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

@include('partials.repair-order-information', ['order' => $order, 'context' => 'user-history'])

<div class="history-card">
    <h3>Detail Repair</h3>
    @include('partials.repair-order-readonly-table', ['order' => $order, 'showAfter' => true, 'showProductNote' => true])
</div>

@if($order->feedbacks->isNotEmpty())
    <div class="history-card">
        <h3>Feedback</h3>
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
