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
    .repair-info-mini{
        min-width:0;
        min-height:62px;
        padding:10px 11px;
        border:1px solid transparent;
        border-radius:10px;
    }
    .repair-info-mini span{
        display:block;
        margin-bottom:4px;
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
    .repair-info-blue{background:#eff6ff;border-color:#dbeafe}
    .repair-info-slate{background:#f8fafc;border-color:#e2e8f0}
    .repair-info-indigo{background:#eef2ff;border-color:#e0e7ff}
    .repair-info-violet{background:#f5f3ff;border-color:#ede9fe}
    .repair-info-cyan{background:#ecfeff;border-color:#cffafe}
    .repair-info-amber{background:#fffbeb;border-color:#fde68a}
    .repair-info-green{background:#f0fdf4;border-color:#bbf7d0}
    .repair-info-green span,.repair-info-green strong{color:#166534}
    .repair-info-qty-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:9px;
        margin-top:9px;
    }
    .repair-info-qty-grid .repair-info-mini strong{font-size:15px}
    .repair-info-description{
        margin-top:9px;
        padding:10px 11px;
        border:1px solid #e2e8f0;
        border-radius:10px;
        background:#fff;
    }
    .repair-info-description span{
        display:block;
        margin-bottom:4px;
        font-size:9px;
        font-weight:550;
        text-transform:uppercase;
        color:#64748b;
    }
    .repair-info-description div{
        font-size:11px;
        line-height:1.5;
        color:#334155;
        white-space:pre-wrap;
        word-break:break-word;
    }
    @media(max-width:900px){
        .repair-info-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
    }
    @media(max-width:680px){
        .repair-info-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        .repair-info-qty-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
    }
    @media(max-width:420px){
        .repair-info-grid,.repair-info-qty-grid{grid-template-columns:1fr}
    }
</style>

<div class="repair-info-card">
    <h3 class="repair-info-title">Informasi Order</h3>

    <div class="repair-info-grid">
        <div class="repair-info-mini repair-info-blue">
            <span>No Order</span>
            <strong>{{ $order->order_number }}</strong>
        </div>
        <div class="repair-info-mini repair-info-slate">
            <span>Tanggal</span>
            <strong>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</strong>
        </div>
        <div class="repair-info-mini repair-info-violet">
            <span>User</span>
            <strong>{{ $order->user?->name ?? '-' }}</strong>
        </div>
        <div class="repair-info-mini repair-info-cyan">
            <span>Plant</span>
            <strong>{{ $order->line?->plant?->name ?? '-' }}</strong>
        </div>
        <div class="repair-info-mini repair-info-indigo">
            <span>Line</span>
            <strong>{{ $order->line?->name ?? '-' }}</strong>
        </div>
        <div class="repair-info-mini repair-info-slate">
            <span>Jenis</span>
            <strong>Repair Box</strong>
        </div>
    </div>

    <div class="repair-info-qty-grid">
        <div class="repair-info-mini repair-info-amber">
            <span>Qty User</span>
            <strong>{{ $qtyUser > 0 ? $qtyUser . ' NG' : '-' }}</strong>
        </div>
        @if($hasOmdResult)
            <div class="repair-info-mini repair-info-green">
                <span>Qty OMD</span>
                <strong>{{ $qtyOmd > 0 ? $qtyOmd . ' NG' : '-' }}</strong>
            </div>
        @endif
    </div>

    <div class="repair-info-description">
        <span>Keterangan</span>
        <div>{{ $order->description ?: '-' }}</div>
    </div>
</div>
