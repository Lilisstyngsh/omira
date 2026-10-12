@extends('layouts.app')

@section('title', 'Detail History Order Repair Box')
@section('header', 'Detail History Order Repair Box')

@section('content')
<style>
/* OMIRA tablet-first detail: predictable spacing and touch-size export actions. */
.omira-detail-export-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.omira-detail-export-head h3{margin:0!important}
.omira-detail-export-actions{display:flex;gap:8px;flex-wrap:wrap}
.omira-detail-export-actions a{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:42px;padding:8px 14px;border:1px solid;border-radius:9px;text-decoration:none!important;font-size:12px;font-weight:700;white-space:nowrap}
.omira-detail-export-actions .pdf{background:#fff1f1;border-color:#f2cccc;color:#ad454d!important}
.omira-detail-export-actions .excel{background:#edf8f0;border-color:#cbe9d1;color:#247447!important}
.omira-detail-export-actions .pdf:hover{background:#ffe7e7}
.omira-detail-export-actions .excel:hover{background:#dff2e5}
.repair-card + .repair-info-card,.repair-info-card + .repair-card,.history-card + .repair-info-card,.repair-info-card + .history-card{margin-top:20px!important}
@media(max-width:768px){.omira-detail-export-actions a{min-height:44px}.repair-card + .repair-info-card,.repair-info-card + .repair-card,.history-card + .repair-info-card,.repair-info-card + .history-card{margin-top:16px!important}}

.omira-export-action{display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border:1px solid #b7d2bf;background:#eff7f1;border-radius:9px;color:#2b6046!important;font-size:12px;font-weight:700;text-decoration:none}.omira-export-action:hover{background:#dfefe4}

    .history-detail-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px}.history-detail-head h2{margin:0;font-size:20px;font-weight:700;color:#0f172a}.history-detail-head p{margin:3px 0 0;font-size:11px;color:#64748b}
    .btn-history-back{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 14px;border-radius:9px;background:#2563eb;color:#fff!important;text-decoration:none;font-size:12px;font-weight:700;border:1px solid #2563eb}.btn-history-back:hover{background:#1d4ed8}
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
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
    <a href="{{ route('user.orders.history') }}" class="btn-history-back">← Kembali</a>
    </div>
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
    <div class="omira-detail-export-head"><h3>Detail Repair</h3><div class="omira-detail-export-actions">
    <a class="pdf" href="{{ route('user.orders.history.export', ['order'=>$order, 'format'=>'pdf']) }}"><i class="fa-regular fa-file-pdf" aria-hidden="true"></i> PDF</a>
    <a class="excel" href="{{ route('user.orders.history.export', ['order'=>$order, 'format'=>'excel']) }}"><i class="fa-regular fa-file-excel" aria-hidden="true"></i> Excel</a>
</div></div>
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
