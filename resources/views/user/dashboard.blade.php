@extends('layouts.app')

@section('title', 'Dashboard')
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
        gap: 16px;
        align-items: stretch;
    }

    .dashboard-kpi {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        min-width: 0;
        min-height: 112px;
        padding: 18px 19px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5ece7;
        border-radius: 17px;
        box-shadow: 0 6px 20px rgba(34, 75, 53, .045);
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .dashboard-kpi:hover {
        border-color: #cbded1;
        box-shadow: 0 9px 24px rgba(34, 75, 53, .08);
        transform: translateY(-1px);
    }

    .dashboard-kpi-head {
        display: flex;
        flex: 1 1 auto;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 7px;
        min-width: 0;
        order: 2;
    }

    .dashboard-kpi-label {
        min-width: 0;
        color: #64766b;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .01em;
        line-height: 1.35;
        text-transform: none;
    }

    .dashboard-kpi-icon {
        width: 64px;
        height: 64px;
        flex: 0 0 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        order: 1;
        border-radius: 50%;
        background: #edf4e8;
        color: #63805d;
        font-size: 25px;
    }

    .dashboard-kpi.attention {
        border-color: #e6dcae;
        box-shadow: 0 6px 20px rgba(119, 105, 58, .07);
    }

    .dashboard-kpi.attention .dashboard-kpi-icon {
        background: #fbf4d9;
        color: #96772b;
    }

    .dashboard-kpi-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 7px;
        flex: 1 1 auto;
        min-width: 0;
        order: 2;
    }

    .dashboard-kpi-content .dashboard-kpi-head {
        display: contents;
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

    .dashboard-kpi-value {
        margin-top: 0;
        color: #172b20;
        font-size: clamp(25px, 2vw, 30px);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -.035em;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
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
        font-weight: 600;
    }

    /* Header Perlu Tindakan memakai pola dropdown Scrap Limit dengan tema oranye. */
    .dashboard-panel.action-panel.has-actions {
        border-color: #F1EAD8;
        background: #ffffff;
        overflow: hidden;
    }

    .action-panel .action-panel-toggle {
        width: 100%;
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 18px;
        border: 0;
        border-bottom: 1px solid #F1EAD8;
        background: #FFFCF2;
        color: #263342;
        text-align: left;
        cursor: pointer;
    }

    .action-panel .action-panel-toggle:focus-visible {
        outline: 2px solid #d97706;
        outline-offset: -2px;
    }

    .action-panel .action-panel-chevron {
        flex: 0 0 auto;
        color: #9a681e;
        transition: transform .18s ease;
    }

    .action-panel .action-panel-toggle[aria-expanded="true"] .action-panel-chevron {
        transform: rotate(180deg);
    }

    .action-panel .action-panel-content[hidden] { display: none !important; }
    .action-panel .action-panel-content { background: #ffffff; }

    .action-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .action-heading-icon {
        width: auto;
        height: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        color: #d97706;
        font-size: 16px;
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
        min-width: 26px;
        height: 26px;
        padding: 0 8px;
        border: 1px solid #f3d6a3;
        border-radius: 999px;
        background: #fff0cf;
        color: #9a5b12;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .action-list {
        display: grid;
        gap: 6px;
        padding: 8px 10px;
        background: #ffffff;
    }

    .action-order {
        display: grid;
        grid-template-columns: minmax(150px, 1fr) minmax(170px, 1.4fr) auto;
        align-items: center;
        gap: 10px;
        padding: 7px 10px;
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
        font-weight: 500;
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
        min-height: 30px;
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        color: #405B7D;
        font-size: 12px;
        font-weight: 600;
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
        font-size: 12px;
        font-weight: 600;
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

        .dashboard-kpi {
            gap: 12px;
            padding: 15px 13px;
        }

        .dashboard-kpi-icon {
            width: 52px;
            height: 52px;
            flex-basis: 52px;
            font-size: 21px;
        }

        .dashboard-kpi-label {
            font-size: 11px;
        }

        .dashboard-kpi-value {
            font-size: clamp(22px, 5vw, 27px);
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .dashboard-kpi {
            min-height: 104px;
            gap: 12px;
            padding: 14px;
            border-radius: 14px;
            align-items: center;
        }

        .dashboard-kpi-icon {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 50%;
            font-size: 20px;
            align-self: center;
        }

        .dashboard-kpi-label {
            font-size: 10px;
        }

        .dashboard-kpi-value {
            margin-top: 11px;
            font-size: 25px;
        }

        .action-order {
            grid-template-columns: 1fr;
        }

        .action-order-message {
            grid-column: auto;
            grid-row: auto;
        }
    }

    .scrap-limit-panel { border:1px solid #f1d4d6; overflow:hidden; }
    .scrap-limit-panel .dashboard-panel-title { display:flex; align-items:center; gap:8px; }
    .scrap-limit-toggle { width:100%; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 18px; border:0; background:#fffafa; color:#263342; text-align:left; cursor:pointer; }
    .scrap-limit-toggle-main { display:flex; align-items:center; gap:10px; min-width:0; }
    .scrap-limit-toggle-icon { display:inline-flex; align-items:center; justify-content:center; flex:0 0 auto; color:#ad323a; background:transparent; border:0; border-radius:0; width:auto; height:auto; font-size:16px; }
    .user-dashboard .scrap-limit-toggle,
    .user-dashboard .action-panel-toggle { font-family:inherit; }
    .user-dashboard .scrap-limit-toggle-title,
    .user-dashboard .action-panel .dashboard-panel-title {
        display:block;
        margin:0;
        font-family:inherit;
        font-size:12px !important;
        font-weight:900 !important;
        line-height:1.5 !important;
    }
    .recent-orders-panel .dashboard-panel-title { font-size:12px; font-weight:600; line-height:1.5; }
    .scrap-limit-count { display:inline-flex; align-items:center; justify-content:center; min-width:26px; height:26px; padding:0 8px; border-radius:999px; background:#fff449; color:#263342; font-size:12px; font-weight:800; }
    .scrap-limit-chevron { transition:transform .18s ease; color:#64748b; }
    .scrap-limit-toggle[aria-expanded="true"] .scrap-limit-chevron { transform:rotate(180deg); }
    .scrap-limit-items { padding:12px; border-top:1px solid #f1d4d6; }
    #additional-box-form { display:grid; gap:8px; }
    .scrap-limit-footer { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:4px; }
    .scrap-limit-progress { font-size:11px; color:#64748b; }
    .scrap-limit-footer .scrap-btn { min-width:110px; }
    .scrap-limit-actions .scrap-btn.is-selected { box-shadow:0 0 0 2px currentColor inset; }
    .scrap-limit-actions .scrap-btn-order.is-selected {
        width:35px; height:35px; min-width:35px; padding:0;
        border-radius:50%; display:inline-flex; justify-content:center; align-items:center;
        background:#24563d; color:#fff; font-size:15px;
        box-shadow:0 0 0 2px #b8dfc3;
    }
    .scrap-limit-actions .scrap-btn-order:focus-visible { outline:2px solid #24563d; outline-offset:3px; }
    .scrap-limit-alert:has([data-scrap-order].is-selected) { border-color:#90c49f; background:#f1faf2; }
    .scrap-limit-alert:has([data-scrap-no].is-selected) { border-color:#c3d2e5; background:#f6f9fd; }
    .scrap-limit-actions .scrap-btn-no.is-selected { background:#d6e1f1; color:#1d3e6d; }
    .scrap-limit-product .scrap-limit-quantity { display:block; margin-top:3px; }
    .scrap-limit-alert { padding:10px 12px !important; }
    @media(max-width:500px) { .scrap-limit-footer { align-items:stretch; } .scrap-limit-footer .scrap-btn { min-width:92px; } }
    .scrap-limit-items[hidden] { display:none !important; }
    .scrap-limit-alert { border:1px solid #eadba7; background:#fffbeb; border-radius:12px; padding:15px; }
    .scrap-limit-alert.is-reached { border-color:#efb4b6; background:#fff4f4; }
    .scrap-limit-row { display:grid; grid-template-columns:minmax(0,1fr) auto; align-items:center; gap:12px; }
    .scrap-limit-product { min-width:0; font-size:12px; font-weight:600; color:#263342; overflow-wrap:anywhere; }
    .scrap-limit-quantity { font-size:12px; font-weight:400; color:#64748b; white-space:nowrap; }
    .scrap-limit-row .scrap-limit-actions { justify-content:flex-end; flex-wrap:wrap; }
    .scrap-limit-actions form { margin:0; }
    .scrap-limit-decision { justify-self:end; font-size:12px; font-weight:600; color:#64748b; white-space:nowrap; }
    @media(max-width:680px) {
        .scrap-limit-row { grid-template-columns:minmax(0,1fr) auto; gap:10px; }
        .scrap-limit-row .scrap-limit-product { grid-column:1 / -1; }
        .scrap-limit-row .scrap-btn { padding:9px 12px; }
    }
    .scrap-limit-actions,.scrap-modal-actions { display:flex; flex-wrap:wrap; gap:9px; align-items:center; }
    .scrap-btn { border:0; border-radius:9px; padding:10px 16px; font-size:12px; font-weight:600; cursor:pointer; }
    .scrap-btn-order { background:#347554; color:#fff; }
    .scrap-btn-no { background:#e5eaf0; color:#324255; }
    #additional-box-submit:disabled,
    .scrap-reason-dialog [data-scrap-reason-save]:disabled {
        background:#e5e7eb !important; color:#9ca3af !important;
        box-shadow:none !important; cursor:not-allowed !important; opacity:1;
    }
    .scrap-reason-modal[hidden] { display:none !important; }
    .scrap-reason-modal { position:fixed; inset:0; background:rgba(15,23,42,.55); display:flex; align-items:center; justify-content:center; z-index:1100; padding:18px; }
    .scrap-reason-dialog { width:min(100%,440px); background:#fff; border-radius:16px; padding:22px; box-shadow:0 24px 60px rgba(15,23,42,.22); }
    .scrap-reason-dialog h3 { font-size:16px; font-weight:600; margin:0; }
    .scrap-reason-dialog p { margin:5px 0 15px; color:#64748b; font-size:12px; font-weight:400; }
    .scrap-reason-dialog textarea { width:100%; box-sizing:border-box; min-height:110px; border:1px solid #cbd5e1; border-radius:9px; padding:10px; font:inherit; font-size:13px; resize:vertical; }
    .scrap-modal-actions { justify-content:flex-end; margin-top:12px; }
    .scrap-save-error { color:#b91c1c !important; margin:7px 0 0 !important; }
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

    @if($errors->any())<div role="alert" style="padding:12px;background:#fff1f2;color:#991b1b">{{ $errors->first() }}</div>@endif
    @if($scrapAlerts->isNotEmpty())
    <section class="dashboard-panel scrap-limit-panel">
        <button type="button" class="scrap-limit-toggle" id="scrap-limit-toggle" aria-expanded="false" aria-controls="scrap-limit-items">
            <span class="scrap-limit-toggle-main">
                <span class="scrap-limit-toggle-icon"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i></span>
                <span>
                    <span class="scrap-limit-toggle-title">Peringatan Scrap Limit Produk</span>
                </span>
            </span>
            <span style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                <span class="scrap-limit-count">{{ $scrapAlerts->count() }}</span>
                <i class="fa-solid fa-chevron-down scrap-limit-chevron" aria-hidden="true"></i>
            </span>
        </button>
        <div class="scrap-limit-items" id="scrap-limit-items" hidden>
            <form id="additional-box-form" method="POST" action="{{ route('user.additional-box.store') }}">
                @csrf
                <input type="hidden" name="submission_token" value="{{ old('submission_token', (string) \Illuminate\Support\Str::uuid()) }}">
                @foreach($scrapAlerts as $cycle)
                    <div class="scrap-limit-alert is-reached" data-scrap-choice-row="{{ $cycle->id }}">
                        <input type="hidden" name="decisions[{{ $cycle->id }}][cycle_id]" value="{{ $cycle->id }}">
                        <input type="hidden" name="decisions[{{ $cycle->id }}][decision]" value="" data-scrap-decision="{{ $cycle->id }}">
                        <input type="hidden" name="decisions[{{ $cycle->id }}][reason]" value="" data-scrap-saved-reason="{{ $cycle->id }}">
                        <div class="scrap-limit-row">
                            <div class="scrap-limit-product">
                                {{ $cycle->product?->masterModel?->model }} — {{ $cycle->product?->name }}
                                <span class="scrap-limit-quantity">Scrap {{ $cycle->used_qty }} / {{ $cycle->limit_qty }}</span>
                            </div>
                            <div class="scrap-limit-actions">
                                <button type="button" class="scrap-btn scrap-btn-order" data-scrap-order="{{ $cycle->id }}" aria-pressed="false">Order</button>
                                <button type="button" class="scrap-btn scrap-btn-no" data-scrap-no="{{ $cycle->id }}" aria-pressed="false">Tidak</button>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="scrap-limit-footer">
                    <span class="scrap-limit-progress" id="scrap-limit-progress" aria-live="polite"></span>
                    <button type="submit" id="additional-box-submit" class="scrap-btn scrap-btn-order" disabled>Submit</button>
                </div>
            </form>
            @foreach($scrapAlerts as $cycle)
                <div class="scrap-reason-modal" id="scrap-reason-{{ $cycle->id }}" data-decision-url="{{ route('user.scrap-limit.decision', $cycle) }}" hidden role="dialog" aria-modal="true" aria-labelledby="scrap-modal-title-{{ $cycle->id }}">
                    <div class="scrap-reason-dialog">
                        <h3 id="scrap-modal-title-{{ $cycle->id }}">Alasan Tidak Order</h3>
                        <p>{{ $cycle->product?->name }}</p>
                        <textarea maxlength="1000" rows="3" placeholder="Tulis alasan..." data-scrap-reason="{{ $cycle->id }}" required></textarea>
                        <p class="scrap-save-error" data-scrap-save-error role="alert" hidden></p>
                        <div class="scrap-modal-actions">
                            <button type="button" class="scrap-btn scrap-btn-no" data-scrap-modal-close>Batal</button>
                            <button type="button" class="scrap-btn scrap-btn-order" data-scrap-reason-save="{{ $cycle->id }}" disabled>Simpan</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if ($actionOrders->isNotEmpty())
        <section class="dashboard-panel action-panel has-actions">
            <button type="button" class="action-panel-toggle" id="action-panel-toggle" aria-expanded="false" aria-controls="action-panel-content">
                <span class="action-heading">
                    <span class="action-heading-icon" aria-hidden="true"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <span class="dashboard-panel-title">Perlu Konfirmasi</span>
                </span>
                <span style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    <span class="action-count">{{ $needConfirmation }} Order</span>
                    <i class="fa-solid fa-chevron-down action-panel-chevron" aria-hidden="true"></i>
                </span>
            </button>
            <div class="action-panel-content" id="action-panel-content" hidden>
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

                            <span class="action-check-order">Buka <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="dashboard-kpis">
        <div class="dashboard-kpi">
            <span class="dashboard-kpi-icon" aria-hidden="true"><i class="fa-solid fa-boxes-stacked"></i></span>
            <div class="dashboard-kpi-content">
                <div class="dashboard-kpi-label">Order Aktif</div>
                <div class="dashboard-kpi-value">{{ $orderActive }}</div>
            </div>
        </div>

        <div class="dashboard-kpi">
            <span class="dashboard-kpi-icon" aria-hidden="true"><i class="fa-solid fa-screwdriver-wrench"></i></span>
            <div class="dashboard-kpi-content">
                <div class="dashboard-kpi-label">Diproses OMD</div>
                <div class="dashboard-kpi-value">{{ $processedOmd }}</div>
            </div>
        </div>

        <div class="dashboard-kpi {{ $needConfirmation > 0 ? 'attention' : '' }}">
            <span class="dashboard-kpi-icon" aria-hidden="true"><i class="fa-solid fa-clipboard-check"></i></span>
            <div class="dashboard-kpi-content">
                <div class="dashboard-kpi-label">Perlu Konfirmasi</div>
                <div class="dashboard-kpi-value">{{ $needConfirmation }}</div>
            </div>
        </div>

        <div class="dashboard-kpi">
            <span class="dashboard-kpi-icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
            <div class="dashboard-kpi-content">
                <div class="dashboard-kpi-label">Selesai Bulan Ini</div>
                <div class="dashboard-kpi-value">{{ $completedThisMonth }}</div>
            </div>
        </div>
    </div>

    <section class="dashboard-panel recent-orders-panel">
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
.recent-table .status-pill {display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:8px!important;border:0!important;border-radius:999px!important;padding:8px 13px!important;font-weight:600!important;white-space:nowrap;}
.recent-table .status-pill::before {content:"";display:inline-block;width:7px;height:7px;min-width:7px;background:currentColor;border-radius:50%;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function setupChevron(buttonId, contentId) {
        const button = document.getElementById(buttonId);
        const content = document.getElementById(contentId);
        if (!button || !content) return;
        button.addEventListener('click', function () {
            const wasOpen = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!wasOpen));
            content.hidden = wasOpen;
        });
    }
    setupChevron('action-panel-toggle', 'action-panel-content');
    setupChevron('scrap-limit-toggle', 'scrap-limit-items');

    const form = document.getElementById('additional-box-form');
    if (!form) return;
    const submit = document.getElementById('additional-box-submit');
    const progress = document.getElementById('scrap-limit-progress');
    let rows = Array.from(form.querySelectorAll('[data-scrap-choice-row]'));
    const notifyEdited = function (input) { input.dispatchEvent(new Event('change', { bubbles: true })); };

    function updateButtons() {
        let selected = 0;
        let hasInvalidChoice = false;
        rows.forEach(function (row) {
            const decision = row.querySelector('[data-scrap-decision]').value;
            const reason = row.querySelector('[data-scrap-saved-reason]').value.trim();
            const isValid = decision === 'yes' || (decision === 'no' && reason.length > 0);
            // Only chosen products go to Laravel. An undecided product keeps its limit warning.
            row.querySelectorAll('input[type="hidden"]').forEach(function (input) {
                input.disabled = !isValid;
            });
            if (decision === 'no' && !reason) hasInvalidChoice = true;
            const orderButton = row.querySelector('[data-scrap-order]');
            orderButton.classList.toggle('is-selected', decision === 'yes');
            orderButton.setAttribute('aria-pressed', String(decision === 'yes'));
            orderButton.textContent = decision === 'yes' ? '✓' : 'Order';
            orderButton.setAttribute('aria-label', decision === 'yes' ? 'Batalkan pilihan Order' : 'Pilih Order');
            orderButton.title = decision === 'yes' ? 'Batalkan Order' : 'Order';
            row.querySelector('[data-scrap-no]').classList.toggle('is-selected', decision === 'no');
            row.querySelector('[data-scrap-no]').setAttribute('aria-pressed', String(decision === 'no'));
            if (isValid) selected++;
        });
        submit.disabled = selected === 0 || hasInvalidChoice;
        progress.textContent = selected ? selected + ' dipilih' : '';
    }
    function choose(row, choice) {
        const input = row.querySelector('[data-scrap-decision]');
        input.value = choice;
        notifyEdited(input); // Also protects unsaved choices from the existing auto-refresh.
        updateButtons();
    }
    function closeModal(modal) { modal.hidden = true; }

    rows.forEach(function (row) {
        const id = row.dataset.scrapChoiceRow;
        const reasonInput = row.querySelector('[data-scrap-saved-reason]');
        const modal = document.getElementById('scrap-reason-' + id);
        const textarea = modal.querySelector('[data-scrap-reason]');
        const saveReason = modal.querySelector('[data-scrap-reason-save]');
        const saveError = modal.querySelector('[data-scrap-save-error]');
        let saving = false;
        function updateReasonSave() { saveReason.disabled = saving || textarea.value.trim().length === 0; }
        function setSaveError(message) {
            saveError.textContent = message;
            saveError.hidden = !message;
        }
        row.querySelector('[data-scrap-order]').addEventListener('click', function () {
            const isAlreadyOrder = row.querySelector('[data-scrap-decision]').value === 'yes';
            choose(row, isAlreadyOrder ? '' : 'yes');
        });
        row.querySelector('[data-scrap-no]').addEventListener('click', function () {
            textarea.value = '';
            setSaveError('');
            updateReasonSave();
            modal.hidden = false;
            textarea.focus();
        });
        saveReason.addEventListener('click', async function () {
            const reason = textarea.value.trim();
            if (saving || !reason) return;
            saving = true;
            setSaveError('');
            updateReasonSave();
            const cancel = modal.querySelector('[data-scrap-modal-close]');
            cancel.disabled = true;
            try {
                const response = await fetch(modal.dataset.decisionUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ decision: 'no', reason: reason }),
                });
                const data = await response.json();
                if (!response.ok || data.success !== true) {
                    const validation = data.errors && Object.values(data.errors).flat()[0];
                    throw new Error(validation || data.message || 'Gagal menyimpan keputusan.');
                }
                // The server already recorded the No decision and reset this product's cycle.
                // Leave other checked Order products untouched until their own Submit.
                row.remove();
                modal.remove();
                rows = rows.filter(function (item) { return item !== row; });
                const count = document.querySelector('.scrap-limit-count');
                if (count) count.textContent = String(rows.length);
                if (!rows.length) document.querySelector('.scrap-limit-panel').remove();
                else updateButtons();
            } catch (error) {
                setSaveError(error.message || 'Koneksi gagal. Coba lagi.');
                saving = false;
                cancel.disabled = false;
                updateReasonSave();
            }
        });
        textarea.addEventListener('input', function () {
            textarea.setCustomValidity('');
            setSaveError('');
            updateReasonSave();
        });
        modal.querySelector('[data-scrap-modal-close]').addEventListener('click', function () {
            if (!saving) closeModal(modal);
        });
        modal.addEventListener('click', function (event) {
            if (!saving && event.target === modal) closeModal(modal);
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') document.querySelectorAll('.scrap-reason-modal').forEach(function (modal) {
            if (modal.querySelector('[data-scrap-modal-close]')?.disabled !== true) closeModal(modal);
        });
    });

    // Restore the submitted choices when Laravel returns validation errors.
    const oldDecisions = @json(old('decisions', []));
    Object.values(oldDecisions).forEach(function (item) {
        const row = form.querySelector('[data-scrap-choice-row="' + Number(item.cycle_id) + '"]');
        if (!row || !['yes', 'no'].includes(item.decision)) return;
        row.querySelector('[data-scrap-saved-reason]').value = item.reason || '';
        row.querySelector('[data-scrap-decision]').value = item.decision;
    });
    updateButtons();
    if (Object.keys(oldDecisions).length && document.getElementById('scrap-limit-toggle')) {
        document.getElementById('scrap-limit-toggle').setAttribute('aria-expanded', 'true');
        document.getElementById('scrap-limit-items').hidden = false;
    }
    form.addEventListener('submit', function (event) {
        updateButtons();
        if (submit.disabled) { event.preventDefault(); return; }
        submit.disabled = true;
        submit.textContent = 'Menyimpan...';
    });
    window.addEventListener('pageshow', updateButtons);
});
</script>
@endsection
