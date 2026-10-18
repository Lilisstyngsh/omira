@php
    $hasOmdResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
    $qtyUser = (int) $order->items->sum(fn ($item) => (int) ($item->before_qty ?? 0));
    $qtyOmd = (int) $order->items->sum(fn ($item) => (int) ($item->after_qty ?? 0));
@endphp

<style>
    .repair-info-card{
        background:#fff;
        border:1px solid #dbe3ee;
        border-radius:14px;
        padding:14px;
        margin-bottom:16px;
    }
    .repair-info-title{
        margin:0 0 11px;
        font-size:14px;
        font-weight:650;
        color:#0f172a;
    }
    .repair-info-grid{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:9px;
    }
    .repair-info-card .repair-info-grid .repair-info-mini{
        margin:0;
        min-width:0;
        min-height:62px;
        padding:10px 11px;
        border:1px solid #4B5563;
        border-color:#4B5563 !important;
        border-radius:10px;
        background:#fff !important;
    }
    .repair-info-mini legend{
        display:table;
        width:auto;
        max-width:100%;
        padding:0 5px;
        margin:0 0 0 -5px;
        font-size:9px;
        font-weight:550;
        letter-spacing:.02em;
        text-transform:uppercase;
        color:#64748b;
    }
    .repair-info-mini strong{
        display:block;
        font-size:12px;
        font-weight:600;
        line-height:1.35;
        color:#0f172a;
        word-break:break-word;
    }
    .repair-info-green legend,.repair-info-green strong{color:#166534}
    .repair-info-qty strong{font-size:15px}
    .repair-info-description legend{color:#9a3412}
    .repair-info-description div{
        font-size:11px;
        line-height:1.45;
        color:#7c2d12;
        white-space:pre-wrap;
        word-break:break-word;
    }
    @media(max-width:900px){
        .repair-info-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
    }
    @media(max-width:680px){
        .repair-info-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
    }
    @media(max-width:420px){
        .repair-info-grid{grid-template-columns:1fr}
    }
</style>

<div class="repair-info-card">
    <h3 class="repair-info-title">Informasi Order</h3>

    <div class="repair-info-grid">
        <fieldset class="repair-info-mini repair-info-blue">
            <legend>No Order</legend>
            <strong>{{ $order->order_number }}</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-slate">
            <legend>Tanggal</legend>
            <strong>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-violet">
            <legend>User</legend>
            <strong>{{ $order->user?->name ?? '-' }}</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-cyan">
            <legend>Plant</legend>
            <strong>{{ $order->line?->plant?->name ?? '-' }}</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-indigo">
            <legend>Line</legend>
            <strong>{{ $order->line?->name ?? '-' }}</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-slate">
            <legend>Jenis</legend>
            <strong>Repair Box</strong>
        </fieldset>
        <fieldset class="repair-info-mini repair-info-amber repair-info-qty">
            <legend>Qty User</legend>
            <strong>{{ $qtyUser > 0 ? $qtyUser . ' NG' : '-' }}</strong>
        </fieldset>
        @if($hasOmdResult)
            <fieldset class="repair-info-mini repair-info-green repair-info-qty">
                <legend>Qty OMD</legend>
                <strong>{{ $qtyOmd > 0 ? $qtyOmd . ' NG' : '-' }}</strong>
            </fieldset>
        @endif
        <fieldset class="repair-info-mini repair-info-description">
            <legend>Keterangan</legend>
            <div>{{ $order->description ?: '-' }}</div>
        </fieldset>
    </div>
</div>
