@php
$context = $context ?? 'default';
$infoId = 'repair-info-' . $order->id . '-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $context);
$hasOmdResult = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
$totalQtyOmd = (int) $order->items->sum(fn ($item) => (int) ($item->after_qty ?? 0));
@endphp

<style>
    .repair-info-disclosure {
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden
    }

    .repair-info-toggle {
        width: 100%;
        min-height: 52px;
        padding: 12px 15px;
        border: 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        cursor: pointer;
        color: #0f172a;
        text-align: left
    }

    .repair-info-toggle:hover {
        background: #f8fafc
    }

    .repair-info-toggle strong {
        font-size: 14px;
        font-weight: 650
    }

    .repair-info-chevron {
        font-size: 22px;
        line-height: 1;
        color: #475569;
        transition: transform .18s ease
    }

    .repair-info-toggle[aria-expanded="true"] .repair-info-chevron {
        transform: rotate(90deg)
    }

    .repair-info-content {
        padding: 0 15px 15px;
        border-top: 1px solid #e2e8f0
    }

    .repair-info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        padding-top: 14px
    }

    .repair-info-item {
        min-width: 0
    }

    .repair-info-item span {
        display: block;
        font-size: 10px;
        color: #64748b;
        margin-bottom: 3px
    }

    .repair-info-item strong {
        display: block;
        font-size: 12px;
        font-weight: 550;
        color: #0f172a;
        word-break: break-word
    }

    .repair-info-description {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0
    }

    .repair-info-description span {
        display: block;
        font-size: 10px;
        color: #64748b;
        margin-bottom: 5px
    }

    .repair-info-description div {
        font-size: 12px;
        color: #334155;
        line-height: 1.5;
        white-space: pre-wrap
    }

    @media(max-width:900px) {
        .repair-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }
    }

    @media(max-width:520px) {
        .repair-info-grid {
            grid-template-columns: 1fr
        }

        .repair-info-toggle {
            min-height: 56px
        }
    }
</style>

<div class="repair-info-disclosure">
    <button type="button"
        class="repair-info-toggle"
        aria-expanded="false"
        aria-controls="{{ $infoId }}"
        onclick="(function(btn){var panel=document.getElementById(btn.getAttribute('aria-controls'));var open=btn.getAttribute('aria-expanded')==='true';btn.setAttribute('aria-expanded',open?'false':'true');panel.hidden=open;})(this)">
        <strong>Informasi Order</strong>
        <span class="repair-info-chevron" aria-hidden="true">›</span>
    </button>

    <div id="{{ $infoId }}" class="repair-info-content" hidden>
        <div class="repair-info-grid">
            <div class="repair-info-item"><span>No Order</span><strong>{{ $order->order_number }}</strong></div>
            <div class="repair-info-item"><span>Tanggal</span><strong>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</strong></div>
            <div class="repair-info-item"><span>Nama</span><strong>{{ $order->user?->name ?? '-' }}</strong></div>
            <div class="repair-info-item"><span>Plant</span><strong>{{ $order->line?->plant?->name ?? '-' }}</strong></div>
            <div class="repair-info-item"><span>Line</span><strong>{{ $order->line?->name ?? '-' }}</strong></div>
            <div class="repair-info-item"><span>Jenis Order</span><strong>Repair Box</strong></div>
            <div class="repair-info-item"><span>Qty User</span><strong>{{ (int) $order->items->sum('before_qty') }}</strong></div>
            @if($hasOmdResult)
            <div class="repair-info-item"><span>Qty OMD</span><strong>{{ $totalQtyOmd }}</strong></div>
            @endif
        </div>
        <div class="repair-info-description">
            <span>Keterangan</span>
            <div>{{ $order->description ?: '-' }}</div>
        </div>
    </div>
</div>