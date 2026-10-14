@extends('layouts.app')

@section('title', 'Dashboard User')
@section('header', 'Dashboard')

@section('content')
<style>
    .user-dashboard {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .dashboard-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .dashboard-welcome h2 {
        margin: 0 0 4px;
        color: #172033;
        font-size: 21px;
        font-weight: 800;
    }

    .dashboard-welcome p {
        margin: 0;
        color: #64748b;
        font-size: 11px;
    }

    .dashboard-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .dashboard-kpi {
        position: relative;
        min-height: 102px;
        padding: 17px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .045);
    }

    .dashboard-kpi-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .dashboard-kpi-label {
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .035em;
        text-transform: uppercase;
    }

    .dashboard-kpi.attention {
        border-color: #E4DFA0;
        box-shadow: 0 8px 26px rgba(119, 105, 58, .07);
    }

    .dashboard-create-order.btn.btn-primary {
        background: #347554;
        border: 1px solid #347554;
        color: #fff;
        font-weight: 750;
        box-shadow: 0 5px 14px rgba(38, 99, 68, .18);
        min-height: 44px;
        transition: background .18s ease, box-shadow .18s ease;
    }

    .dashboard-create-order.btn.btn-primary:hover,
    .dashboard-create-order.btn.btn-primary:focus-visible {
        background: #285E43;
        border-color: #285E43;
        color: #fff;
        box-shadow: 0 7px 17px rgba(38, 99, 68, .24);
    }

    .dashboard-kpis > .dashboard-kpi:nth-child(1) {
        background: linear-gradient(110deg, #fcfbf5 0%, #E3E4D0 56%, #F6F6EC 100%);
        border-color: #DFE0C9;
    }

    .dashboard-kpis > .dashboard-kpi:nth-child(2) {
        background: radial-gradient(ellipse at 50% 48%, #FEFAF3 0%, #F8F2E8 62%, #F2EBDD 100%);
        border-color: #EEE8DB;
    }

    .dashboard-kpis > .dashboard-kpi:nth-child(3) {
        background: linear-gradient(245deg, #FFFFEC 0%, #FFFDD3 58%, #FFFBA7 100%);
        border-color: #F1EFAE;
    }

    .dashboard-kpis > .dashboard-kpi:nth-child(4) {
        background: radial-gradient(ellipse at 78% 24%, #FFFFFF 0%, #FBEFEF 58%, #F6E3E3 100%);
        border-color: #F0E2E2;
    }

    .dashboard-kpis > .dashboard-kpi.attention {
        border-color: #F1EFAE;
        box-shadow: 0 8px 26px rgba(119, 105, 58, .07);
    }

    .dashboard-kpi-value {
        margin-top: 10px;
        color: #172033;
        font-size: 27px;
        font-weight: 850;
        line-height: 1;
    }

    .dashboard-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 17px;
        box-shadow: 0 9px 28px rgba(15, 23, 42, .045);
    }

    .dashboard-panel-head {
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 18px;
        border-bottom: 1px solid #edf1f5;
    }

    .dashboard-panel-title {
        margin: 0;
        color: #172033;
        font-size: 14px;
        font-weight: 800;
    }

    /* Operational priority: terlihat saat ada order, tetap tenang saat kosong. */
    .dashboard-panel.action-panel.has-actions {
        border-color: #E9DEAB;
        background: #FFFEFA;
    }

    .action-panel.has-actions .dashboard-panel-head {
        background: #fff;
        border-bottom-color: #edf1f5;
    }

    .action-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-heading-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: #FFF0BB;
        color: #9B722F;
        font-size: 14px;
    }

    .action-subtitle {
        margin: 4px 0 0;
        font-size: 10px;
        color: #88794F;
    }

    .action-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        border-radius: 999px;
        padding: 5px 11px;
        background: #FFF0B8;
        color: #84601F;
        font-size: 10.5px;
        font-weight: 800;
        white-space: nowrap;
    }

    .action-list {
        display: grid;
        gap: 10px;
        padding: 12px;
    }

    .action-order {
        display: grid;
        grid-template-columns: minmax(150px, 1fr) minmax(170px, 1.4fr) auto;
        align-items: center;
        gap: 14px;
        padding: 13px 15px;
        border: 1px solid #F1EAD8;
        border-radius: 12px;
        background: #FFFCF2;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        transition: background-color .18s ease, border-color .18s ease;
    }

    .action-order:hover,
    .action-order:focus-visible {
        background: #FFF8E5;
        border-color: #E5D8B5;
        outline-offset: 2px;
    }

    .action-order-number {
        color: #172033;
        font-size: 11px;
        font-weight: 800;
    }

    .action-order-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 5px 10px;
        margin-top: 4px;
        color: #64748b;
        font-size: 9.5px;
    }

    .action-order-message {
        color: #475569;
        font-size: 10.5px;
        line-height: 1.45;
    }

    .action-order-message strong {
        display: block;
        margin-bottom: 2px;
        color: #91652C;
        font-size: 10.5px;
    }

    .action-order-message strong::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        margin-right: 7px;
        border-radius: 50%;
        background: #D5A44D;
        vertical-align: 1px;
    }

    .action-check-order {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        min-height: 44px;
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        color: #405B7D;
        font-size: 11px;
        font-weight: 800;
        text-decoration: underline;
        text-underline-offset: 3px;
        white-space: nowrap;
    }

    .action-order:hover .action-check-order,
    .action-order:focus-visible .action-check-order {
        color: #2F4B6F;
        text-decoration-thickness: 2px;
    }

    .dashboard-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 58px;
        padding: 14px 18px;
        color: #64748b;
        font-size: 10.5px;
    }

    .dashboard-empty i {
        color: #16a34a;
    }

    /* Tabel berdiri sebagai panel tersendiri di dalam card Order Terbaru. */
    .recent-table-wrap {
        margin: 16px;
        overflow-x: auto;
        border: 1px solid #dce5ef;
        border-radius: 14px;
        background: #fff;
    }

    .recent-table {
        width: 100%;
        min-width: 660px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .recent-table th {
        padding: 10px 13px;
        background: #fafbfc;
        border-right: 1px solid #dce5ef;
        border-bottom: 1px solid #dce5ef;
        color: #64748b;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .025em;
        text-align: center;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .recent-table td {
        padding: 12px 13px;
        border-right: 1px solid #e3eaf2;
        border-bottom: 1px solid #e3eaf2;
        color: #334155;
        font-size: 10.5px;
        vertical-align: middle;
    }

    .recent-table th:last-child,
    .recent-table td:last-child {
        border-right: 0;
    }

    .recent-table tbody tr {
        cursor: pointer;
        transition: background .16s ease;
    }

    .recent-table tbody tr:hover {
        background: #f5f9f6;
    }

    .recent-table tbody tr:focus-visible {
        outline: 2px solid #405B7D;
        outline-offset: -2px;
        background: #f5f9f6;
    }

    .recent-all-link {
        display: inline-flex;
        align-items: center;
        min-height: 44px;
        color: #405B7D;
        font-size: 11px;
        font-weight: 800;
        text-decoration: underline;
        text-underline-offset: 3px;
        border: 0;
        background: transparent;
        padding: 0;
    }

    .recent-all-link:hover,
    .recent-all-link:focus-visible {
        color: #2c4463;
    }

    .recent-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .order-number {
        color: #172033;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-model-product {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .order-model-product strong {
        color: #172033;
        font-size: 10.5px;
    }

    .order-model-product span {
        color: #64748b;
        font-size: 9.5px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 29px;
        padding: 5px 11px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-pill.submitted { color: #2563eb; background: #eff6ff; border-color: #dbeafe; }
    .status-pill.in-repair { color: #9a651e; background: #fff7e9; border-color: #f6e4be; }
    .status-pill.completed { color: #b56817; background: #fff4e2; border-color: #f5d8a9; }
    .status-pill.revision { color: #be123c; background: #fff1f2; border-color: #fecdd3; }
    .status-pill.confirmed { color: #21804a; background: #ecfdf3; border-color: #bbf7d0; }

    @media (max-width: 760px) {
        .recent-table-wrap { margin: 12px; }
    }

    @media (max-width: 1100px) {
        .dashboard-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 760px) {
        .dashboard-welcome,
        .dashboard-panel-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .action-order {
            grid-template-columns: 1fr auto;
        }

        .action-order-message {
            grid-column: 1 / -1;
            grid-row: 2;
        }
    }

    @media (max-width: 520px) {
        .dashboard-kpis {
            grid-template-columns: 1fr;
        }

        .action-order {
            grid-template-columns: 1fr;
        }

        .action-order-message {
            grid-column: auto;
            grid-row: auto;
        }
    }

    .scrap-limit-panel { overflow:hidden; }
    .scrap-limit-summary { cursor:pointer; border:1px solid #efb4b6; background:#fff5f5; border-radius:14px; padding:16px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .scrap-limit-summary:hover { background:#fff0f0; }
    .scrap-limit-summary-title { display:flex; align-items:center; gap:10px; color:#b4232d; font-weight:800; }
    .scrap-limit-summary-text { color:#475569; font-size:13px; margin-top:4px; }
    .scrap-limit-modal[hidden] { display:none !important; }
    .scrap-limit-modal { position:fixed; inset:0; background:rgba(15,23,42,.45); display:flex; align-items:center; justify-content:center; z-index:1200; padding:18px; }
    .scrap-limit-dialog { width:min(100%,430px); background:#fff; border-radius:16px; padding:22px; }
    .scrap-modal-close { position:absolute; top:12px; right:14px; border:0; background:transparent; font-size:22px; color:#64748b; cursor:pointer; } .scrap-limit-dialog { position:relative; } .scrap-limit-product-row { padding:10px 0; border-bottom:1px solid #e5e7eb; font-size:13px; }
    .scrap-limit-top { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:8px; color:#9a681e; font-size:13px; }
    .scrap-limit-alert.is-reached .scrap-limit-top { color:#ad323a; }
    .scrap-limit-top span { font-size:11px; font-weight:800; background:#fff; border:1px solid currentColor; border-radius:999px; padding:5px 10px; }
    .scrap-limit-product { margin-top:9px; font-size:14px; font-weight:800; color:#263342; }
    .scrap-limit-meta,.scrap-limit-notice { margin-top:5px; font-size:12px; color:#64748b; }
    .scrap-limit-question { margin:12px 0 8px; font-weight:750; font-size:13px; }
    .scrap-limit-actions,.scrap-modal-actions { display:flex; flex-wrap:wrap; gap:9px; align-items:center; }
    .scrap-btn { border:0; border-radius:9px; padding:10px 16px; font-size:12px; font-weight:800; cursor:pointer; }
    .scrap-btn-order { background:#347554; color:#fff; }
    .scrap-btn-no { background:#e5eaf0; color:#324255; }
    .scrap-reason-modal[hidden],[data-scrap-submit][hidden] { display:none !important; }
    .scrap-reason-modal { position:fixed; inset:0; background:rgba(15,23,42,.55); display:flex; align-items:center; justify-content:center; z-index:1100; padding:18px; }
    .scrap-reason-dialog { width:min(100%,440px); background:#fff; border-radius:16px; padding:22px; box-shadow:0 24px 60px rgba(15,23,42,.22); }
    .scrap-reason-dialog h3 { font-size:16px; margin:0; }
    .scrap-reason-dialog p { margin:5px 0 15px; color:#64748b; font-size:12px; }
    .scrap-reason-dialog textarea { width:100%; box-sizing:border-box; min-height:110px; border:1px solid #cbd5e1; border-radius:9px; padding:10px; font:inherit; font-size:13px; resize:vertical; }
    .scrap-modal-actions { justify-content:flex-end; margin-top:12px; }
</style>

@php
    $currentHour = now('Asia/Jakarta')->hour;
    $greeting = match (true) {
        $currentHour >= 4 && $currentHour < 10 => 'Pagi',
        $currentHour >= 10 && $currentHour < 15 => 'Siang',
        $currentHour >= 15 && $currentHour < 18 => 'Sore',
        default => 'Malam',
    };

    $statusMap = [
        'submitted' => ['label' => 'Menunggu Verifikasi', 'class' => 'submitted', 'icon' => 'fa-clock'],
        'in_repair' => ['label' => 'Sedang Repair', 'class' => 'in-repair', 'icon' => 'fa-screwdriver-wrench'],
        'completed' => ['label' => 'Perlu Konfirmasi', 'class' => 'completed', 'icon' => 'fa-circle-check'],
        'revision_requested' => ['label' => 'Dalam Koreksi', 'class' => 'revision', 'icon' => 'fa-triangle-exclamation'],
        'confirmed' => ['label' => 'Selesai', 'class' => 'confirmed', 'icon' => 'fa-check-double'],
    ];
@endphp

<div class="user-dashboard">
    <div class="dashboard-welcome">
        <div>
            <h2>Selamat {{ strtolower($greeting) }}, Mr. {{ auth()->user()->name }}</h2>
        </div>

        <a class="btn btn-primary dashboard-create-order" href="{{ route('user.orders.create') }}">
            <i class="fa-solid fa-plus"></i>
            Buat Order Repair
        </a>
    </div>

    @if($scrapAlerts->isNotEmpty())
    <section class="dashboard-panel scrap-limit-panel">
        <div class="scrap-limit-summary" data-scrap-detail-open>
            <div>
                <div class="scrap-limit-summary-title"><i class="fa-solid fa-circle-info"></i> Scrap Limit Tercapai</div>
                <div class="scrap-limit-summary-text">{{ $scrapAlerts->count() }} produk mencapai/melewati Scrap Limit</div>
            </div>
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </section>

    <div class="scrap-limit-modal" id="scrap-limit-detail-modal" hidden>
        <div class="scrap-limit-dialog">
            <button type="button" class="scrap-modal-close" data-scrap-detail-close aria-label="Close">×</button>
            <h3>Scrap Limit Tercapai</h3>
            @foreach($scrapAlerts as $cycle)
                <div class="scrap-limit-product-row">
                    <strong>{{ $cycle->product?->name }}</strong><br>
                    Scrap {{ $cycle->used_qty }} / {{ $cycle->limit_qty }}
                </div>
            @endforeach
            <div class="scrap-modal-actions">
                <form method="POST" action="{{ route('user.orders.create') }}">
                    @csrf
                    <button type="submit" class="scrap-btn scrap-btn-order">Ya, Order</button>
                </form>
                <button type="button" class="scrap-btn scrap-btn-no" data-scrap-detail-close>Tidak</button>
            </div>
        </div>
    </div>
    @endif

    <div class="dashboard-kpis">
        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Order Aktif</div>
            </div>
            <div class="dashboard-kpi-value">{{ $orderActive }}</div>
        </div>

        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Diproses OMD</div>
            </div>
            <div class="dashboard-kpi-value">{{ $processedOmd }}</div>
        </div>

        <div class="dashboard-kpi {{ $needConfirmation > 0 ? 'attention' : '' }}">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Perlu Konfirmasi</div>
            </div>
            <div class="dashboard-kpi-value">{{ $needConfirmation }}</div>
        </div>

        <div class="dashboard-kpi">
            <div class="dashboard-kpi-head">
                <div class="dashboard-kpi-label">Selesai Bulan Ini</div>
            </div>
            <div class="dashboard-kpi-value">{{ $completedThisMonth }}</div>
        </div>
    </div>

    <section class="dashboard-panel action-panel {{ $actionOrders->isNotEmpty() ? 'has-actions' : '' }}">
        <div class="dashboard-panel-head">
            <div class="action-heading">
                @if ($actionOrders->isNotEmpty())
                    <span class="action-heading-icon" aria-hidden="true"><i class="fa-solid fa-circle-exclamation"></i></span>
                @endif
                <div>
                    <h3 class="dashboard-panel-title">Perlu Tindakan</h3>
                </div>
            </div>
            @if ($actionOrders->isNotEmpty())
                <span class="action-count">{{ $needConfirmation }} Order</span>
            @endif
        </div>

        @if ($actionOrders->isNotEmpty())
            <div class="action-list">
                @foreach ($actionOrders as $order)
                    @php
                        $qty = $order->after_qty_sum !== null
                            ? (int) $order->after_qty_sum
                            : (int) ($order->quantity ?? 0);
                        $isCorrection = (int) $order->feedbacks_count > 0;
                    @endphp

                    <a href="{{ route('user.orders.show', $order) }}" class="action-order" aria-label="Cek Order {{ $order->order_number }}">
                        <div>
                            <div class="action-order-number">{{ $order->order_number }}</div>
                            <div class="action-order-meta"><span>{{ $qty }} NG</span></div>
                        </div>

                        <div class="action-order-message">
                            <strong>{{ $isCorrection ? 'Hasil koreksi siap diperiksa' : 'Hasil repair siap diperiksa' }}</strong>
                        </div>

                        <span class="action-check-order">Cek Order</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="dashboard-empty">
                <i class="fa-solid fa-circle-check"></i>
                Tidak ada order yang perlu dikonfirmasi.
            </div>
        @endif
    </section>

    <section class="dashboard-panel">
        <div class="dashboard-panel-head">
            <div>
                <h3 class="dashboard-panel-title">Order Terbaru</h3>
            </div>

            <a class="recent-all-link" href="{{ route('user.orders.index') }}">
                Lihat Semua Order
            </a>
        </div>

        <div class="recent-table-wrap">
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Model / Produk</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        @php
                            $firstItem = $order->items->first();
                            $modelName = $firstItem?->masterModel?->model ?? $order->model ?? '-';
                            $productName = $firstItem?->product?->name ?? $order->product?->name ?? '-';
                            $productCount = $order->items->pluck('product_id')->filter()->unique()->count();
                            $showAfterQty = in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
                            $qty = $showAfterQty
                                ? ($order->after_qty_sum !== null
                                    ? (int) $order->after_qty_sum
                                    : (int) ($order->quantity ?? 0))
                                : ($order->before_qty_sum !== null
                                    ? (int) $order->before_qty_sum
                                    : (int) ($order->quantity ?? 0));
                            $status = $statusMap[$order->status] ?? [
                                'label' => $order->status_label,
                                'class' => 'submitted',
                                'icon' => 'fa-circle',
                            ];
                        @endphp

                        <tr onclick="window.location.href=this.dataset.href"
                            onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href=this.dataset.href; }"
                            data-href="{{ route('user.orders.show', $order) }}"
                            tabindex="0"
                            role="link"
                            aria-label="Lihat detail order {{ $order->order_number }}"
                            title="Lihat detail order">
                            <td class="order-number">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>
                                <div class="order-model-product">
                                    <strong>{{ $modelName }}</strong>
                                    <span>
                                        {{ $productName }}
                                        @if ($productCount > 1)
                                            +{{ $productCount - 1 }} produk
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td>{{ $qty }} NG</td>
                            <td>
                                <span class="status-pill {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="dashboard-empty">
                                    <i class="fa-solid fa-box-open"></i>
                                    Belum ada Order Repair Box.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
/* Unified operational status chips; numeric and status cells centered. */
.recent-table th:nth-child(4), .recent-table th:nth-child(5),
.recent-table td:nth-child(4), .recent-table td:nth-child(5) {text-align:center!important;}
.recent-table .status-pill {display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:8px!important;border:0!important;border-radius:999px!important;padding:8px 13px!important;font-weight:800!important;white-space:nowrap;}
.recent-table .status-pill::before {content:"";display:inline-block;width:7px;height:7px;min-width:7px;background:currentColor;border-radius:50%;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-scrap-modal-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            var modal = document.getElementById(button.dataset.scrapModalOpen);
            if (!modal) return;
            modal.hidden = false;
            var input = modal.querySelector('[data-scrap-reason]');
            if (input) input.focus();
        });
    });
    document.querySelectorAll('.scrap-reason-modal').forEach(function (modal) {
        var reason = modal.querySelector('[data-scrap-reason]');
        var submit = modal.querySelector('[data-scrap-submit]');
        var update = function () { if (submit) submit.hidden = !reason || !reason.value.trim(); };
        if (reason) reason.addEventListener('input', update);
        update();
        modal.querySelectorAll('[data-scrap-modal-close]').forEach(function (button) {
            button.addEventListener('click', function () { modal.hidden = true; });
        });
        modal.addEventListener('click', function (event) { if (event.target === modal) modal.hidden = true; });
        modal.querySelector('form')?.addEventListener('submit', function (event) {
            if (!reason || !reason.value.trim()) { event.preventDefault(); update(); }
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') document.querySelectorAll('.scrap-reason-modal').forEach(function (modal) { modal.hidden = true; });
    });
});
</script>
<script>
    document.querySelector('[data-scrap-detail-open]')?.addEventListener('click',()=>document.getElementById('scrap-limit-detail-modal').removeAttribute('hidden'));
    document.querySelector('[data-scrap-detail-close]')?.addEventListener('click',()=>document.getElementById('scrap-limit-detail-modal').setAttribute('hidden','hidden'));
document.getElementById('scrap-limit-detail-modal')?.addEventListener('click',function(e){ if(e.target===this){ this.setAttribute('hidden','hidden'); }});
</script>

@endsection
