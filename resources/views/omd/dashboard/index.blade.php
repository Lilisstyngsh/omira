@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    @php
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Label grafik mengikuti Fiscal Year April–Maret.
        $chartMonthLabels = collect($fiscalMonths)->map(fn ($item) => \Illuminate\Support\Carbon::create($item['year'], $item['month'], 1)->locale('id')->translatedFormat('M'));

        $targetPercent = $chartMax > 0
            ? min(100, ($target / $chartMax) * 100)
            : 0;

        $scaleValues = [
            $chartMax,
            (int) round($chartMax * .75),
            (int) round($chartMax * .50),
            (int) round($chartMax * .25),
            0,
        ];
    @endphp

    <style>
        /* Dashboard mengikuti surface solid dari tema OMIRA. */
        .monitor-page {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
            isolation: isolate;
            margin: -14px;
            padding: 14px;
            border-radius: 24px;
            background: transparent;
        }

        .monitor-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .monitor-head h2 {
            margin: 0;
            color: #172033;
            font-size: 23px;
            font-weight: 900;
        }

        .monitor-muted {
            margin-top: 5px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.6;
        }

        .monitor-pills {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .monitor-pill {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 0 12px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #fff;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
        }

        .monitor-card {
            background: var(--surface, #fff);
            border: 1px solid var(--border, #DCE8DE);
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(31, 67, 44, .05);
        }

        .monitor-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
            padding: 16px;
        }

        .monitor-filter .monitor-field {
            min-width: 0;
            max-width: 100%;
        }

        .monitor-filter input[name="year"] {
            width: 94px;
            max-width: 100%;
        }

        .monitor-filter select[name="line_id"] {
            width: var(--line-filter-width, 165px);
            max-width: 100%;
        }

        .monitor-filter .monitor-field label { color: #365c44; }
        .monitor-filter .monitor-field select,
        .monitor-filter .monitor-field input {
            background: var(--surface, #fff);
            border-color: #d2e5d8;
        }

        .monitor-kpis .monitor-kpi {
            position: relative;
            overflow: hidden;
            background: var(--surface, #fff);
            border-color: var(--border, #DCE8DE);
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }
        .monitor-kpis .monitor-kpi:hover {
            transform: translateY(-2px);
            border-color: #a4c5ae;
            box-shadow: 0 8px 22px rgba(40,78,51,.08);
        }
        .monitor-kpis .kpi-label { color: #34413b; }
        .monitor-kpis .kpi-value { color: #1a2922; }

        .monitor-field label {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .monitor-field select,
        .monitor-field input,
        .abnormality-form textarea {
            width: 100%;
            height: 38px;
            min-height: 38px;
            box-sizing: border-box;
            padding: 0 12px;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #172033;
            font-size: 12px;
            outline: none;
        }

        .monitor-field select:focus,
        .monitor-field input:focus,
        .abnormality-form textarea:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .10);
        }

        .monitor-filter .monitor-filter-action {
            flex: 0 0 auto;
        }

        .monitor-filter .btn {
            height: 38px;
            min-height: 38px;
            justify-content: center;
        }

        .monitor-filter .dashboard-filter-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 18px;
            border: 1px solid #5f967e;
            border-radius: 9px;
            background: #5f967e;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            box-shadow: none;
        }
        .monitor-filter .dashboard-filter-button:hover,
        .monitor-filter .dashboard-filter-button:focus-visible {
            background: #4f856d;
            color: #fff;
        }

        .monitor-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .monitor-kpi {
            padding: 17px;
        }

        .kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .kpi-label {
            color: #64748b;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .kpi-dot {
            width: 11px;
            height: 11px;
            border-radius: 999px;
        }

        .kpi-dot.order { background: #578EF5; }
        .kpi-dot.ok { background: #76C457; }
        .kpi-dot.scrap { background: #D39A53; }
        .kpi-dot.target { background: #D75B62; }

        .kpi-value {
            margin-top: 13px;
            color: #172033;
            font-size: clamp(25px, 3vw, 34px);
            font-weight: 900;
            line-height: 1;
        }

        .kpi-note {
            margin-top: 7px;
            color: #94a3b8;
            font-size: 10px;
            line-height: 1.5;
        }

        .alert-card {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 15px 16px;
        }

        .target-reason-card {
            padding: 16px;
            border-color: #f3cf7a;
            background: #fffaf0;
            box-shadow: 0 10px 28px rgba(180, 120, 20, .07);
        }

        .target-reason-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .target-reason-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .target-reason-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            color: #d97706;
            font-size: 17px;
            line-height: 1;
        }

        .target-reason-title {
            margin: 0;
            color: #172033;
            font-size: 13px;
            font-weight: 900;
        }

        .target-reason-meta {
            color: #92400e;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .target-reason-card .abnormality-form textarea {
            background: #fff;
            border-color: #e9c46a;
        }

        .target-reason-card .abnormality-form textarea:focus {
            border-color: #d9a514;
            box-shadow: 0 0 0 3px rgba(217, 165, 20, .12);
        }

        .alert-card.scrap-alert {
            border-color: #fecaca;
            background: #fff7f7;
        }

        .alert-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            font-weight: 900;
        }

        .scrap-alert .alert-icon {
            background: #fee2e2;
            color: #dc2626;
        }

        .alert-title {
            margin: 0 0 4px;
            color: #172033;
            font-size: 12px;
            font-weight: 900;
        }

        .alert-copy {
            margin: 0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .abnormality-form {
            display: grid;
            gap: 9px;
            margin-top: 12px;
        }

        .abnormality-form textarea {
            min-height: 88px;
            padding: 10px 12px;
            resize: vertical;
        }

        .chart-abnormality-popover {
            position: fixed;
            z-index: 1400;
            width: min(340px, calc(100vw - 28px));
            padding: 12px 13px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #FEFAF3;
            box-shadow: 0 14px 34px rgba(15, 23, 42, .16);
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: opacity .14s ease, transform .14s ease, visibility .14s ease;
        }

        #chartAbnormalityPopover {
            background: #FEFAF3 !important;
        }

        .chart-abnormality-popover.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #chartAbnormalityTitle {
            margin: 0;
            color: #172033 !important;
            font-size: 11px;
            font-weight: 700;
        }

        #chartAbnormalityReason {
            margin-top: 8px;
            background: transparent !important;
            color: #334155 !important;
            font-size: 10px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .chart-abnormality-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 12px;
        }
        .chart-abnormality-actions button {
            font-size: 11px;
            border-radius: 8px;
            padding: 7px 12px;
            font-weight: 700;
            cursor: pointer;
        }
        .chart-abnormality-actions .edit-button,
        .chart-abnormality-actions .save-button {
            border: 1px solid #147d77;
            background: #147d77;
            color: #fff;
        }
        .chart-abnormality-actions .cancel-button {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
        }
        .chart-abnormality-actions .save-button:disabled {
            cursor: not-allowed;
            border-color: #cbd5e1;
            background: #e2e8f0;
            color: #64748b;
        }
        #chartAbnormalityEditForm[hidden],
        #chartAbnormalityDisplay[hidden] { display: none !important; }
        #chartAbnormalityEditForm textarea {
            box-sizing: border-box;
            width: 100%;
            min-height: 100px;
            margin-top: 9px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 9px 10px;
            color: #334155;
            font: inherit;
            font-size: 12px;
            line-height: 1.5;
            resize: vertical;
        }
        #chartAbnormalityEditForm textarea:focus {
            outline: 2px solid #147d77;
            outline-offset: 1px;
        }

        .chart-card {
            padding: 18px;
            overflow: hidden;
        }

        .chart-card { padding:24px 30px 28px; }
        .axis-caption { text-align:center; font-size:11px; font-weight:600; color:#000000; padding:13px 0 2px; }
        .fy-chart-shell { position:relative; padding-left:14px; padding-right:8px; }
        .axis-y-label { position:absolute; left:0; top:50%; transform:translate(-50%,-50%) rotate(-90deg); font-size:11px; font-weight:600; color:#000000; white-space:nowrap; }
        .ongoing-bubble { position:absolute; z-index:8; bottom:var(--ongoing-bottom, 64px); left:50%; transform:translateX(-50%); background:#FFF449; color:#000000; border-radius:999px; padding:6px 10px; white-space:nowrap; font-size:11px; font-weight:800; box-shadow:0 3px 9px #0002; pointer-events:none; }
        .ongoing-bubble:after { content:''; position:absolute; top:100%; left:50%; margin-left:-5px; border:5px solid transparent; border-top-color:#FFF449; }
        .month-group { position:relative; }
        .month-group.is-future .chart-bar,
        .chart-bar.is-empty { height:0; min-height:0; visibility:hidden; pointer-events:none; }
        .month-group.is-future .month-label { color:#94a3b8; }
        .abnormality-summary { margin-top:18px; overflow-x:auto; }
        .abnormality-summary table { width:100%; border-collapse:collapse; font-size:11px; }
        .abnormality-summary th,.abnormality-summary td { padding:10px 12px; border:1px solid #e2e8f0; text-align:left; vertical-align:top; }
        .abnormality-summary th { background:#f1f5f9; font-weight:800; }


        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .card-title {
            margin: 0;
            color: #172033;
            font-size: 14px;
            font-weight: 900;
        }

        .card-subtitle {
            margin: 5px 0 0;
            color: #000000;
            font-size: 10px;
            font-weight: 600;
            line-height: 1.5;
        }

        .chart-legend {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 10px;
            height: 10px;
            border-radius: 3px;
        }

        .legend-color.order { background: #578EF5; }
        .legend-color.ok { background: #76C457; }
        .legend-color.scrap { background: #D39A53; }
        .legend-color.target { background: #D75B62; height: 3px; }

        .chart-scroll {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .bar-chart {
            display: grid;
            grid-template-columns: 46px minmax(650px, 1fr);
            min-width: 696px;
            height: 360px;
        }

        .chart-axis {
            color: #000000;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0 10px 27px 0;
            font-size: 9px;
            text-align: right;
        }

        .chart-plot {
            position: relative;
            min-width: 0;
        }
        
        .chart-data-layer {
            position: absolute;
            inset: 0 0 27px;
            z-index: 1;
            pointer-events: none;
        }

        .chart-grid {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent calc(25% - 1px),
                #eef2f7 calc(25% - 1px),
                #eef2f7 25%
            );
            border-bottom: 1px solid #cbd5e1;
        }

        .target-overlay {
            position: absolute;
            inset: 0 0 27px;
            z-index: 6;
            pointer-events: none;
        }

        .target-line {
            position: absolute;
            left: 0;
            right: 0;
            bottom: var(--target-height);
            border-top: 3px solid #D75B62;
            filter: drop-shadow(0 1px 0 rgba(255, 255, 255, .55));
        }

        .target-points {
            position: absolute;
            left: 0;
            right: 0;
            bottom: var(--target-height);
            height: 0;
            display: grid;
            grid-template-columns: repeat(var(--month-count), minmax(48px, 1fr));
            gap: 7px;
        }

        .target-point {
            position: relative;
            height: 0;
            min-width: 0;
        }

        .target-marker {
            position: absolute;
            left: 50%;
            top: 0;
            width: 9px;
            height: 9px;
            border: 2px solid #D75B62;
            border-radius: 999px;
            background: #fff;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 0 2px rgba(199, 110, 110, .10);
        }

        .target-value {
            color: #000000;
            font-size: 9px;
            font-weight: 600;
            position: absolute;
            left: 50%;
            top: -23px;
            transform: translateX(-50%);
            padding: 1px 4px;
            border-radius: 5px;
            background: rgba(255, 255, 255, .92);
            color: #000000;
            font-size: 9px;
            font-weight: 600;
            line-height: 1.3;
            white-space: nowrap;
        }

        .chart-months {
            position: absolute;
            inset: 0 0 0;
            display: grid;
            grid-template-columns: repeat(var(--month-count), minmax(48px, 1fr));
            gap: 7px;
            z-index: 3;
        }

        .month-group {
            min-width: 0;
            display: grid;
            grid-template-rows: 1fr 27px;
        }

        .month-bars {
            display: flex;
            justify-content: center;
            align-items: end;
            gap: 4px;
            min-height: 0;
            padding: 0 2px;
        }

        .chart-bar {
            position: relative;
            z-index: 3;
            width: min(18px, 28%);
            min-height: 0;
            height: var(--bar-height);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            overflow: visible;
            border-radius: 6px 6px 2px 2px;
            transition: opacity .15s ease, transform .15s ease;
        }

        .chart-bar-value {
            position: absolute;
            left: 50%;
            top: -18px;
            z-index: 5;
            transform: translateX(-50%);
            color: #000000;
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
            pointer-events: none;
        }

        .chart-bar:hover {
            opacity: .88;
        }

        .chart-bar.has-abnormality {
            cursor: pointer;
        }

        .chart-bar.has-abnormality:hover {
            transform: translateY(-2px);
        }

        .chart-bar.has-abnormality:focus-visible {
            outline: 2px solid rgba(95, 146, 120, .28);
            outline-offset: 3px;
        }

        .chart-bar.order { background: #578EF5; }
        .chart-bar.ok { background: #76C457; }
        .chart-bar.scrap { background: #D39A53; }

        .month-label {
            color: #000000;
            display: flex;
            align-items: end;
            justify-content: center;
            padding-bottom: 2px;
            font-size: 10px;
            font-weight: 800;
        }

        .monitor-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(300px, .8fr);
            gap: 18px;
        }

        .table-card,
        .scrap-card {
            padding: 18px;
        }

        .summary-scroll {
            overflow-x: auto;
        }

        .summary-table {
            width: 100%;
            min-width: 650px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .summary-table th,
        .summary-table td {
            padding: 9px 10px;
            border-right: 1px solid #e5eaf1;
            border-bottom: 1px solid #e5eaf1;
            text-align: center;
            white-space: nowrap;
            font-size: 10px;
        }

        .summary-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 900;
        }

        .summary-table th:first-child,
        .summary-table td:first-child {
            position: sticky;
            left: 0;
            z-index: 1;
            background: #fff;
            text-align: left;
            font-weight: 900;
        }

        .summary-table tr:last-child td { border-bottom: 0; }
        .summary-table th:last-child,
        .summary-table td:last-child { border-right: 0; }

        .summary-order td:first-child { color: #5F9278; }
        .summary-ok td:first-child { color: #16a34a; }
        .summary-scrap td:first-child { color: #dc2626; }
        .summary-target td { background: #fff7ed; color: #c2410c; }
        .summary-target td:first-child { background: #fff7ed; }

        .scrap-list {
            display: grid;
            gap: 10px;
        }

        .scrap-item {
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            background: #fff;
        }

        .scrap-item.warning {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .scrap-item.exceeded {
            border-color: #fecaca;
            background: #fff7f7;
        }

        .scrap-item-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .scrap-model {
            color: #172033;
            font-size: 11px;
            font-weight: 900;
        }

        .scrap-status {
            padding: 3px 7px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .warning .scrap-status {
            background: #fef3c7;
            color: #92400e;
        }

        .exceeded .scrap-status {
            background: #fee2e2;
            color: #991b1b;
        }

        .scrap-line,
        .scrap-values {
            margin-top: 5px;
            color: #64748b;
            font-size: 10px;
            line-height: 1.5;
        }

        .empty-state {
            padding: 22px 12px;
            border: 1px dashed #dbe3ee;
            border-radius: 13px;
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.6;
            text-align: center;
        }

        .field-error {
            color: #dc2626;
            font-size: 10px;
            font-weight: 700;
        }

        @media (max-width: 1050px) {
            .monitor-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .monitor-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 820px) {
            .monitor-head {
                flex-direction: column;
            }

            .monitor-pills {
                justify-content: flex-start;
            }

            .chart-card,
            .table-card,
            .scrap-card {
                padding: 15px;
            }
        }

        @media (max-width: 560px) {
            .monitor-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .monitor-kpi {
                padding: 14px;
            }

            .card-head {
                flex-direction: column;
            }

            .chart-legend {
                justify-content: flex-start;
            }

            .alert-card {
                padding: 13px;
            }

            .target-reason-head {
                align-items: flex-start;
                flex-direction: column;
                gap: 6px;
            }

            .target-reason-meta {
                white-space: normal;
            }
        }
        /* Dark mode memakai surface solid dari tema utama. */
        html[data-theme="dark"] body .monitor-page .monitor-card:not(.target-reason-card):not(.scrap-alert) {
            background: var(--surface, #18231D) !important;
            border-color: var(--border, #34453B) !important;
            box-shadow: 0 6px 18px rgba(0,0,0,.14) !important;
        }
        html[data-theme="dark"] .monitor-page :is(.kpi-label,.kpi-value,.card-title,.monitor-head h2,.alert-title) { color: var(--text) !important; }
        html[data-theme="dark"] .monitor-page :is(.kpi-note,.monitor-muted,.chart-legend,.monitor-pill) { color: var(--text-soft) !important; }
        html[data-theme="dark"] .monitor-page :is(.chart-axis,.axis-caption,.axis-y-label,.month-label) { color: var(--text-soft) !important; }
        html[data-theme="dark"] .monitor-page .monitor-pill {
            background: var(--surface-soft, #202E26);
            border-color: var(--border, #34453B);
            color: var(--text-soft) !important;
        }
        html[data-theme="dark"] .monitor-page .monitor-field label {
            color: var(--text-soft) !important;
        }
        @media (max-width: 680px) {
            .monitor-page { margin: 0; padding: 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .monitor-kpis .monitor-kpi { transition: none; }
            .monitor-kpis .monitor-kpi:hover { transform: none; }
        }
    </style>

    <div class="monitor-page">
        <div class="monitor-head">
            <div>
                <h2>Monitoring Repair Box</h2>
            </div>

            <div class="monitor-pills">
                <span class="monitor-pill">
                    {{ $selectedLine?->name ?? 'Semua Line' }}
                </span>
                <span class="monitor-pill">
                    FY {{ $year }}
                </span>
            </div>
        </div>

        <form class="monitor-card monitor-filter" method="GET" action="{{ route('dashboard') }}">
            <div class="monitor-field">
                <label for="dashboard-year">Tahun</label>
                <input
                    id="dashboard-year"
                    type="number"
                    name="year"
                    min="2020"
                    max="2100"
                    value="{{ $year }}"
                    required
                >
            </div>

            <div class="monitor-field">
                <label>Line</label>
                <select name="line_id">
                    <option value="">Semua Line</option>
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}" @selected((string) $lineId === (string) $line->id)>
                            {{ $line->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="monitor-field monitor-filter-action">
                <label aria-hidden="true">&nbsp;</label>
                <button type="submit" class="btn btn-primary dashboard-filter-button"><i class="fa-solid fa-filter" aria-hidden="true"></i><span>Filter</span></button>
            </div>
        </form>


        @if ($targetExceeded && ! $abnormality)
            <div class="monitor-card target-reason-card">
                <div class="target-reason-head">
                    <div class="target-reason-heading">
                        <span class="target-reason-icon" aria-hidden="true">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                        <h3 class="target-reason-title">Alasan Target FY</h3>
                    </div>
                    <span class="target-reason-meta">
                        {{ $monthNames[$month] }} · Order {{ number_format($globalOrder) }} · Target {{ number_format($target) }} · +{{ number_format($targetDifference) }}
                    </span>
                </div>

                <form class="abnormality-form" method="POST" action="{{ route('omd.dashboard.abnormality.store') }}">
                    @csrf
                    <input type="hidden" name="year" value="{{ now()->year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    @if ($lineId)
                        <input type="hidden" name="line_id" value="{{ $lineId }}">
                    @endif

                    <textarea name="reason" placeholder="Tuliskan penyebab..." required>{{ old('reason') }}</textarea>

                    @error('reason')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        @endif

        @if ($productScrapAlerts->isNotEmpty())
            <a href="{{ route('omd.scrap-cycles.index') }}" class="monitor-card alert-card scrap-alert" style="text-decoration:none;color:inherit;cursor:pointer;">
                <div class="alert-icon">!</div>
                <div style="flex:1;min-width:0;">
                    <h3 class="alert-title">Peringatan Scrap Limit Produk</h3>
                    <p class="alert-copy">{{ $productScrapAlerts->count() }} produk mencapai/melewati Scrap Limit.</p>
                </div>
            </a>
        @endif

        <div class="monitor-card chart-card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Grafik Monitoring Repair Box FY {{ $year }}</h3>
                    <p class="card-subtitle">April {{ $year }} – Maret {{ $year + 1 }}</p>
                </div>

                <div class="chart-legend">
                    <span class="legend-item"><i class="legend-color order"></i>Order</span>
                    <span class="legend-item"><i class="legend-color ok"></i>OK</span>
                    <span class="legend-item"><i class="legend-color scrap"></i>Scrap</span>
                    <span class="legend-item"><i class="legend-color target"></i>Target FY {{ $year }}</span>
                </div>
            </div>

            <div class="fy-chart-shell">
            <div class="axis-y-label">Jumlah Box</div>
            <div class="chart-scroll">
                <div class="bar-chart">
                    <div class="chart-axis">
                        @foreach ($scaleValues as $scale)
                            <span>{{ number_format($scale) }}</span>
                        @endforeach
                    </div>

                    <div class="chart-plot">
                        <div class="chart-data-layer">
                            <div class="chart-grid"></div>
                        </div>

                        @if ($target > 0)
                            <div class="target-overlay"
                                style="--target-height: {{ number_format($targetPercent, 2, '.', '') }}%; --month-count: {{ 12 }};">
                                <div class="target-line"></div>
                                <div class="target-points">
                                    @foreach ($chartMonthLabels as $targetMonth)
                                        <span class="target-point" aria-hidden="true">
                                            <i class="target-marker"></i>
                                            <span class="target-value">{{ number_format($target) }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="chart-months" style="--month-count: {{ 12 }};">
                            @foreach ($chartMonthLabels as $index => $monthLabel)
                                @php
                                    $orderValue = (int) ($orderSeries[$index] ?? 0);
                                    $okValue = (int) ($finishSeries[$index] ?? 0);
                                    $scrapValue = (int) ($scrapSeries[$index] ?? 0);

                                    $orderHeight = $chartMax > 0 ? ($orderValue / $chartMax) * 100 : 0;
                                    $okHeight = $chartMax > 0 ? ($okValue / $chartMax) * 100 : 0;
                                    $scrapHeight = $chartMax > 0 ? ($scrapValue / $chartMax) * 100 : 0;

                                    // Batang kecil tetap diberi tinggi minimum visual, tanpa mengubah nilai aslinya.
                                    $orderVisualHeight = $orderValue > 0 ? max($orderHeight, 2) : 0;
                                    $okVisualHeight = $okValue > 0 ? max($okHeight, 2) : 0;
                                    $scrapVisualHeight = $scrapValue > 0 ? max($scrapHeight, 2) : 0;
                                    $monthAbnormality = $abnormalitiesByMonth->get($fiscalMonths[$index]['year'] . '-' . str_pad((string) $fiscalMonths[$index]['month'], 2, '0', STR_PAD_LEFT));
                                    $globalMonthOrder = (int) ($globalOrderSeries[$index] ?? 0);
                                    $canExplainOrder = $orderValue > 0 && ($monthAbnormality || ($target > 0 && $globalMonthOrder > $target));
                                @endphp

                                @php
                                    $chartCalendarYear = (int) $fiscalMonths[$index]['year'];
                                    $chartCalendarMonth = (int) $fiscalMonths[$index]['month'];
                                    $futureMonth = \Illuminate\Support\Carbon::create($chartCalendarYear, $chartCalendarMonth, 1)->gt(now()->startOfMonth());
                                    $ongoingMonth = $chartCalendarYear === now()->year && $chartCalendarMonth === now()->month;
                                    // Align the badge with the tallest bar, but keep it clear of the FY target line.
                                    $barTop = max($orderHeight, $okHeight, $scrapHeight);
                                    $bubbleBottom = 27 + (int) round(min($barTop, 92) * 3.33) + 24;
                                    $targetBottom = 27 + ($targetPercent * 3.33);
                                    if (abs($bubbleBottom - $targetBottom) < 25) {
                                        $bubbleBottom = max(47, $bubbleBottom - 36);
                                    }
                                    $bubbleBottom = min(331, max(47, $bubbleBottom));
                                @endphp
                                <div class="month-group {{ $futureMonth ? 'is-future' : '' }}">
                                    @if ($ongoingMonth)
                                        <span class="ongoing-bubble" style="--ongoing-bottom:{{ $bubbleBottom }}px">ON GOING</span>
                                    @endif
                                    <div class="month-bars">
                                        <div class="chart-bar order {{ $orderValue <= 0 ? 'is-empty' : '' }} {{ $canExplainOrder ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($orderVisualHeight, 2, '.', '') }}%;"
                                            @if ($canExplainOrder)
                                                role="button" tabindex="0"
                                                aria-label="{{ $monthAbnormality ? 'Lihat atau edit' : 'Tambah' }} alasan abnormality {{ $monthNames[$fiscalMonths[$index]['month']] ?? $monthLabel }} {{ $fiscalMonths[$index]['year'] }}"
                                                data-abnormality-month="{{ $monthNames[$fiscalMonths[$index]['month']] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $fiscalMonths[$index]['year'] }}"
                                                data-abnormality-month-number="{{ $fiscalMonths[$index]['month'] }}"
                                                data-abnormality-actual="{{ (int) ($monthAbnormality?->actual_qty ?? $globalMonthOrder) }}"
                                                data-abnormality-target="{{ (int) ($monthAbnormality?->threshold_qty ?? $target) }}"
                                                data-abnormality-reason="{{ $monthAbnormality?->reason ?? '' }}"
                                                data-abnormality-existing="{{ $monthAbnormality ? '1' : '0' }}"
                                            @endif>
                                            @if ($orderValue > 0)<span class="chart-bar-value">{{ number_format($orderValue) }}</span>@endif
                                        </div>
                                        <div class="chart-bar ok {{ $okValue <= 0 ? 'is-empty' : '' }} {{ $monthAbnormality ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($okVisualHeight, 2, '.', '') }}%;"
                                            @if ($monthAbnormality)
                                                role="button" tabindex="0"
                                                data-abnormality-month="{{ $monthNames[$fiscalMonths[$index]['month']] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $fiscalMonths[$index]['year'] }}"
                                                data-abnormality-month-number="{{ $fiscalMonths[$index]['month'] }}"
                                                data-abnormality-actual="{{ (int) $monthAbnormality->actual_qty }}"
                                                data-abnormality-target="{{ (int) $monthAbnormality->threshold_qty }}"
                                                data-abnormality-reason="{{ $monthAbnormality->reason }}"
                                            @endif>
                                            @if ($okValue > 0)<span class="chart-bar-value">{{ number_format($okValue) }}</span>@endif
                                        </div>
                                        <div class="chart-bar scrap {{ $scrapValue <= 0 ? 'is-empty' : '' }} {{ $monthAbnormality ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($scrapVisualHeight, 2, '.', '') }}%;"
                                            @if ($monthAbnormality)
                                                role="button" tabindex="0"
                                                data-abnormality-month="{{ $monthNames[$fiscalMonths[$index]['month']] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $fiscalMonths[$index]['year'] }}"
                                                data-abnormality-month-number="{{ $fiscalMonths[$index]['month'] }}"
                                                data-abnormality-actual="{{ (int) $monthAbnormality->actual_qty }}"
                                                data-abnormality-target="{{ (int) $monthAbnormality->threshold_qty }}"
                                                data-abnormality-reason="{{ $monthAbnormality->reason }}"
                                            @endif>
                                            @if ($scrapValue > 0)<span class="chart-bar-value">{{ number_format($scrapValue) }}</span>@endif
                                        </div>
                                    </div>
                                    <div class="month-label">{{ $monthLabel }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            </div>
            <div class="axis-caption">FY {{ $year }}</div>
        </div>

        <section class="monitor-card abnormality-card" style="padding:18px;">
            <h3 class="card-title">Daftar Abnormality Fiscal Year {{ $year }}</h3>
            <div class="abnormality-summary modern-table-wrap">
                <table class="modern-abnormality-table">
                    <thead><tr><th>Bulan</th><th>Order</th><th>Target FY</th><th>Selisih</th><th>Alasan</th></tr></thead>
                    <tbody>
                        @forelse ($abnormalitiesByMonth as $abMonth => $item)
                            @php $diff=(int)$item->actual_qty-(int)$item->threshold_qty; @endphp
                            <tr>
                                <td><strong>{{ ($monthNames[(int) $item->month] ?? $item->month) . ' ' . $item->year }}</strong></td>
                                <td class="abnormality-qty">{{ number_format((int) $item->actual_qty) }}</td>
                                <td class="abnormality-qty">{{ number_format((int) $item->threshold_qty) }}</td>
                                <td><span class="abnormality-diff {{ $diff > 0 ? 'danger' : 'safe' }}">{{ $diff > 0 ? '+' : '' }}{{ number_format($diff) }}</span></td>
                                <td class="reason-cell">{{ $item->reason ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;color:#64748b;">Belum ada data abnormality tersimpan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="chart-abnormality-popover" id="chartAbnormalityPopover" role="dialog" aria-modal="false" aria-labelledby="chartAbnormalityTitle" aria-hidden="true">
            <h4 class="chart-abnormality-title" id="chartAbnormalityTitle">Abnormality</h4>
            <div id="chartAbnormalityDisplay">
                <div class="chart-abnormality-reason" id="chartAbnormalityReason"></div>
                <div class="chart-abnormality-actions">
                    <button type="button" class="edit-button" id="chartAbnormalityEditButton">Edit</button>
                </div>
            </div>
            <form id="chartAbnormalityEditForm" method="POST" action="{{ route('omd.dashboard.abnormality.store') }}" hidden>
                @csrf
                <input type="hidden" name="year" id="chartAbnormalityYear">
                <input type="hidden" name="month" id="chartAbnormalityMonth">
                @if ($lineId)
                    <input type="hidden" name="line_id" value="{{ $lineId }}">
                @endif
                <textarea id="chartAbnormalityReasonInput" name="reason" required minlength="5" maxlength="2000" aria-label="Alasan abnormality"></textarea>
                <div class="chart-abnormality-actions">
                    <button type="button" class="cancel-button" id="chartAbnormalityCancelButton">Batal</button>
                    <button type="submit" class="save-button" id="chartAbnormalitySaveButton">Simpan</button>
                </div>
            </form>
        </div>

    </div>

    <script>
        (() => {
            const lineSelect = document.querySelector('.monitor-filter select[name="line_id"]');
            if (!lineSelect) return;
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            if (!context) return;

            const fitLineFilter = () => {
                const style = window.getComputedStyle(lineSelect);
                context.font = `${style.fontWeight} ${style.fontSize} ${style.fontFamily}`;
                const text = lineSelect.selectedOptions[0]?.textContent.trim() || 'Semua Line';
                const padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight);
                const borders = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
                const width = Math.ceil(context.measureText(text).width + padding + borders + 40);
                lineSelect.style.setProperty('--line-filter-width', `${Math.max(100, width)}px`);
            };

            lineSelect.addEventListener('change', fitLineFilter);
            window.addEventListener('resize', fitLineFilter);
            fitLineFilter();
            if (document.fonts?.ready) document.fonts.ready.then(fitLineFilter);
        })();
    </script>

    <script>
        (() => {
            const popover = document.getElementById('chartAbnormalityPopover');
            const title = document.getElementById('chartAbnormalityTitle');
            const reason = document.getElementById('chartAbnormalityReason');
            const display = document.getElementById('chartAbnormalityDisplay');
            const form = document.getElementById('chartAbnormalityEditForm');
            const editButton = document.getElementById('chartAbnormalityEditButton');
            const cancelButton = document.getElementById('chartAbnormalityCancelButton');
            const input = document.getElementById('chartAbnormalityReasonInput');
            const saveButton = document.getElementById('chartAbnormalitySaveButton');
            const yearInput = document.getElementById('chartAbnormalityYear');
            const monthInput = document.getElementById('chartAbnormalityMonth');
            const bars = document.querySelectorAll('.chart-bar.has-abnormality');
            if (!popover || bars.length === 0) return;

            let activeBar = null;

            const positionPopover = () => {
                if (!activeBar) return;
                const rect = activeBar.getBoundingClientRect();
                const popupRect = popover.getBoundingClientRect();
                const gap = 10;
                let left = rect.left + rect.width / 2 - popupRect.width / 2;
                let top = rect.top - popupRect.height - gap;
                left = Math.max(12, Math.min(left, window.innerWidth - popupRect.width - 12));
                if (top < 12) top = Math.min(window.innerHeight - popupRect.height - 12, rect.bottom + gap);
                popover.style.left = `${left}px`;
                popover.style.top = `${Math.max(12, top)}px`;
            };

            const setEditMode = editing => {
                display.hidden = editing;
                form.hidden = !editing;
                if (editing) {
                    input.value = activeBar?.dataset.abnormalityReason || '';
                    yearInput.value = activeBar?.dataset.abnormalityYear || '';
                    monthInput.value = activeBar?.dataset.abnormalityMonthNumber || '';
                    saveButton.disabled = input.value.trim().length < 5;
                }
                positionPopover();
                if (editing) input.focus();
            };

            const closePopover = () => {
                popover.classList.remove('show');
                popover.setAttribute('aria-hidden', 'true');
                activeBar = null;
                form.hidden = true;
                display.hidden = false;
            };

            const openPopover = bar => {
                activeBar = bar;
                const existing = bar.dataset.abnormalityExisting !== '0';
                title.textContent = `${existing ? 'Abnormality' : 'Tambah Alasan'} – ${bar.dataset.abnormalityMonth || ''} ${bar.dataset.abnormalityYear || ''}`;
                reason.textContent = bar.dataset.abnormalityReason || '-';
                popover.classList.add('show');
                popover.setAttribute('aria-hidden', 'false');
                // Bulan di atas target tanpa record langsung membuka form input.
                // Bulan yang sudah memiliki alasan tetap menampilkan tombol Edit.
                setEditMode(!existing);
            };

            bars.forEach(bar => {
                bar.addEventListener('click', event => {
                    event.stopPropagation();
                    openPopover(bar);
                });
                bar.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openPopover(bar);
                    }
                });
            });
            editButton.addEventListener('click', () => setEditMode(true));
            cancelButton.addEventListener('click', () => {
                if (activeBar?.dataset.abnormalityExisting === '0') {
                    closePopover();
                } else {
                    setEditMode(false);
                }
            });
            input.addEventListener('input', () => {
                saveButton.disabled = input.value.trim().length < 5;
            });
            form.addEventListener('submit', event => {
                if (input.value.trim().length < 5) {
                    event.preventDefault();
                    return;
                }
                saveButton.disabled = true;
            });
            document.addEventListener('click', event => {
                if (!popover.contains(event.target)) closePopover();
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && popover.classList.contains('show')) closePopover();
            });
            window.addEventListener('resize', closePopover);
            window.addEventListener('scroll', event => {
                if (!popover.contains(event.target)) closePopover();
            }, true);
        })();
    </script>
<style>
/* Daftar Abnormality FY: konsisten dengan tabel History (tanpa pemisah vertikal). */
.abnormality-summary.modern-table-wrap {
    border: 1px solid var(--border, #e8edf0);
    border-radius: 14px;
    overflow-x: auto;
    background: var(--surface, #fff);
}
.modern-abnormality-table { width: 100%; min-width: 650px; border-collapse: separate; border-spacing: 0; }
.modern-abnormality-table thead { background: var(--table-head-bg, #f7f9fa); }
body .modern-abnormality-table.modern-abnormality-table th {
    padding: 12px 14px;
    border: 0;
    border-bottom: 1px solid var(--border, #e3e9f0);
    color: #000000;
    font-size: 11px !important;
    font-weight: 700;
    text-transform: uppercase;
    text-align: left;
    white-space: nowrap;
}
body .modern-abnormality-table.modern-abnormality-table td {
    padding: 12px 14px;
    border: 0;
    border-bottom: 1px solid var(--border-soft, #edf1f5);
    color: #334155;
    font: inherit;
    font-size: 11px !important;
    font-weight: 400;
    vertical-align: middle;
}
.modern-abnormality-table tbody tr:last-child td { border-bottom: 0; }
.modern-abnormality-table tbody tr:hover { background: var(--table-hover-bg, #f8faf9); }
.modern-abnormality-table td strong { font-size: 11px; font-weight: 700; }
.modern-abnormality-table .abnormality-qty { font-weight: 600 !important; }
body .modern-abnormality-table.modern-abnormality-table td.reason-cell { max-width: 420px; font-size: 11px !important; line-height: 1.5; }
.modern-abnormality-table .abnormality-diff {
    display: inline-flex;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 600;
}
.abnormality-diff.danger { background: #feecec; color: #dc2626; }
.abnormality-diff.safe { background: #eaf7ef; color: #15803d; }
html[data-theme="dark"] .modern-abnormality-table tbody tr:hover { background: var(--table-hover-bg) !important; }
html[data-theme="dark"] .modern-abnormality-table th { color: #d4e1d8 !important; }
html[data-theme="dark"] .modern-abnormality-table td { color: var(--text) !important; }
</style>
@endsection
