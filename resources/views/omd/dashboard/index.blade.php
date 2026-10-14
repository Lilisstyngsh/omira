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

        // Pastikan 12 bulan selalu dirender dalam grafik tanpa menambah realisasi palsu.
        $chartMonthLabels = collect(range(1, 12))->map(fn ($m) => \Illuminate\Support\Carbon::create($year, $m, 1)->locale('id')->translatedFormat('M'));

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
        .monitor-page {
            display: grid;
            gap: 18px;
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
            background: #fff;
            border: 1px solid #e6ebf2;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .monitor-filter {
            display: grid;
            grid-template-columns: repeat(3, minmax(150px, 1fr)) auto;
            gap: 12px;
            align-items: end;
            padding: 16px;
        }

        /* Soft-green abstract cloud pattern, decorative only. */
        .monitor-filter {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            border-color: #d5e9dc;
            background:
                radial-gradient(ellipse 165px 82px at 6% 112%, rgba(157, 210, 171, .25) 0 65%, transparent 66%),
                radial-gradient(ellipse 185px 105px at 27% 116%, rgba(204, 232, 204, .53) 0 65%, transparent 66%),
                radial-gradient(ellipse 160px 90px at 61% 115%, rgba(139, 197, 163, .24) 0 65%, transparent 66%),
                radial-gradient(ellipse 205px 95px at 92% 111%, rgba(187, 227, 198, .5) 0 65%, transparent 66%),
                radial-gradient(circle at 18% 23%, rgba(122, 180, 147, .30) 0 2px, transparent 3px),
                radial-gradient(circle at 68% 17%, rgba(122, 180, 147, .20) 0 2px, transparent 3px),
                linear-gradient(112deg, #f0f8f2 0%, #e5f2e9 54%, #f5faf5 100%);
        }
        .monitor-filter::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(circle at 37% 18%, rgba(255,255,255,.84) 0 1.5px, transparent 2.5px),
                radial-gradient(circle at 79% 55%, rgba(255,255,255,.88) 0 2px, transparent 3px);
        }
        .monitor-filter .monitor-field label { color: #365c44; }
        .monitor-filter .monitor-field select,
        .monitor-filter .monitor-field input {
            background: rgba(255,255,255,.94);
            border-color: #d2e5d8;
        }

        /* Color-tinted KPI cards; values and card hierarchy stay unchanged. */
        .monitor-kpis .monitor-kpi { position: relative; overflow: hidden; }
        .monitor-kpis .monitor-kpi:nth-child(1) {
            background: linear-gradient(135deg, #f2f6ff, #e8efff);
            border-color: #d4e1ff;
        }
        .monitor-kpis .monitor-kpi:nth-child(2) {
            background: linear-gradient(135deg, #f4fbed, #e9f6df);
            border-color: #d9ebcc;
        }
        .monitor-kpis .monitor-kpi:nth-child(3) {
            background: linear-gradient(135deg, #fff9ef, #fbf0dd);
            border-color: #f0e1c6;
        }
        .monitor-kpis .monitor-kpi:nth-child(4) {
            background: linear-gradient(135deg, #fff5f5, #fde9eb);
            border-color: #f1d4d8;
        }
        .monitor-kpis .kpi-label { color: #34413b; }
        .monitor-kpis .kpi-value { color: #1a2922; }

        .monitor-field label {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .monitor-field select,
        .monitor-field input,
        .abnormality-form textarea {
            width: 100%;
            min-height: 44px;
            padding: 0 12px;
            border: 1px solid #dbe3ee;
            border-radius: 11px;
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

        .monitor-filter .btn {
            min-height: 44px;
            justify-content: center;
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
            width: min(280px, calc(100vw - 28px));
            padding: 12px 13px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 14px 34px rgba(15, 23, 42, .16);
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: opacity .14s ease, transform .14s ease, visibility .14s ease;
        }

        .chart-abnormality-popover.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .chart-abnormality-title {
            margin: 0;
            color: #172033;
            font-size: 11px;
            font-weight: 900;
        }

        .chart-abnormality-meta {
            margin-top: 4px;
            color: #64748b;
            font-size: 9px;
            font-weight: 700;
        }

        .chart-abnormality-reason {
            margin-top: 8px;
            color: #334155;
            font-size: 10px;
            line-height: 1.55;
            overflow-wrap: anywhere;
        }

        .chart-card {
            padding: 18px;
            overflow: hidden;
        }

        .chart-card { padding:24px 30px 28px; }
        .axis-caption { text-align:center; font-size:12px; font-weight:600; color:#475569; padding:13px 0 2px; }
        .fy-chart-shell { position:relative; padding-left:14px; padding-right:8px; }
        .axis-y-label { position:absolute; left:0; top:50%; transform:translate(-50%,-50%) rotate(-90deg); font-size:11px; font-weight:600; color:#475569; white-space:nowrap; }
        .ongoing-bubble { position:absolute; z-index:8; bottom:var(--ongoing-bottom, 64px); left:50%; transform:translateX(-50%); background:#8F6227; color:white; border-radius:999px; padding:5px 8px; white-space:nowrap; font-size:9px; font-weight:900; box-shadow:0 3px 9px #0002; pointer-events:none; }
        .ongoing-bubble:after { content:''; position:absolute; top:100%; left:50%; margin-left:-5px; border:5px solid transparent; border-top-color:#8F6227; }
        .month-group { position:relative; }
        .month-group.is-future .chart-bar { height:7px; min-height:7px; background:#DDE2E9; opacity:.8; border-radius:3px 3px 1px 1px; }
        .month-group.is-future .month-label { color:#94A3B8; }
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
            color: #94a3b8;
            font-size: 10px;
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0 10px 27px 0;
            color: #94a3b8;
            font-size: 9px;
            text-align: right;
        }

        .chart-plot {
            position: relative;
            min-width: 0;
        }

        /*
         * Grid dan garis Target FY harus memakai area data yang SAMA dengan
         * tinggi batang (area chart dikurangi 27px untuk label bulan).
         * Sebelumnya target memakai persentase terhadap tinggi chart penuh,
         * sehingga garis bisa tampak lebih tinggi dari batang walaupun nilai
         * batang sebenarnya sudah melewati target.
         */
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

        /*
         * Target FY dibuat sebagai overlay TERPISAH di atas batang.
         * Dengan begitu garis tetap terlihat di depan ketika batang Order / OK / Scrap
         * sudah melewati target, seperti grafik kombinasi bar + target line.
         */
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
            position: absolute;
            left: 50%;
            top: -23px;
            transform: translateX(-50%);
            padding: 1px 4px;
            border-radius: 5px;
            background: rgba(255, 255, 255, .92);
            color: #B84650;
            font-size: 8px;
            font-weight: 900;
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
            min-height: 2px;
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
            color: #334155;
            font-size: 8px;
            font-weight: 900;
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
            display: flex;
            align-items: end;
            justify-content: center;
            padding-bottom: 2px;
            color: #64748b;
            font-size: 9px;
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
            font-size: 8px;
            font-weight: 900;
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

            .monitor-filter {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .monitor-filter .btn {
                grid-column: 1 / -1;
            }

            .chart-card,
            .table-card,
            .scrap-card {
                padding: 15px;
            }
        }

        @media (max-width: 560px) {
            .monitor-filter {
                grid-template-columns: 1fr;
            }

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
                    {{ $monthNames[$month] }} {{ $year }}
                </span>
            </div>
        </div>

        <form class="monitor-card monitor-filter" method="GET" action="{{ route('dashboard') }}">
            <div class="monitor-field">
                <label>Bulan</label>
                <select name="month">
                    @foreach ($monthNames as $number => $name)
                        <option value="{{ $number }}" @selected($month === $number)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="monitor-field">
                <label>Tahun</label>
                <input type="number" name="year" value="{{ $year }}" min="2020" max="2100">
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

            <button type="submit" class="btn btn-primary">Tampilkan</button>
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
                    <input type="hidden" name="year" value="{{ $year }}">
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
            <div class="monitor-card alert-card scrap-alert">
                <div class="alert-icon">!</div>
                <div style="flex:1;min-width:0;">
                    <h3 class="alert-title">Peringatan Scrap Limit Produk</h3>
                    <p class="alert-copy">{{ $productScrapAlerts->count() }} produk mencapai/melewati Scrap Limit.</p>
                </div>
            </div>
        @endif

        <div class="monitor-card chart-card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Grafik Monitoring Repair Box {{ $year }}</h3>
                    <p class="card-subtitle">Januari – Desember {{ $year }}</p>
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
                                    $monthAbnormality = $abnormalitiesByMonth->get($index + 1);
                                @endphp

                                @php
                                    $futureMonth = $year > now()->year || ($year === now()->year && ($index + 1) > now()->month);
                                    $ongoingMonth = $year === now()->year && ($index + 1) === now()->month;
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
                                        <div class="chart-bar order {{ $monthAbnormality ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($orderVisualHeight, 2, '.', '') }}%;"
                                            @if ($monthAbnormality)
                                                role="button" tabindex="0"
                                                data-abnormality-month="{{ $monthNames[$index + 1] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $year }}"
                                                data-abnormality-actual="{{ (int) $monthAbnormality->actual_qty }}"
                                                data-abnormality-target="{{ (int) $monthAbnormality->threshold_qty }}"
                                                data-abnormality-reason="{{ $monthAbnormality->reason }}"
                                            @endif>
                                            @if ($orderValue > 0)<span class="chart-bar-value">{{ number_format($orderValue) }}</span>@endif
                                        </div>
                                        <div class="chart-bar ok {{ $monthAbnormality ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($okVisualHeight, 2, '.', '') }}%;"
                                            @if ($monthAbnormality)
                                                role="button" tabindex="0"
                                                data-abnormality-month="{{ $monthNames[$index + 1] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $year }}"
                                                data-abnormality-actual="{{ (int) $monthAbnormality->actual_qty }}"
                                                data-abnormality-target="{{ (int) $monthAbnormality->threshold_qty }}"
                                                data-abnormality-reason="{{ $monthAbnormality->reason }}"
                                            @endif>
                                            @if ($okValue > 0)<span class="chart-bar-value">{{ number_format($okValue) }}</span>@endif
                                        </div>
                                        <div class="chart-bar scrap {{ $monthAbnormality ? 'has-abnormality' : '' }}"
                                            style="--bar-height: {{ number_format($scrapVisualHeight, 2, '.', '') }}%;"
                                            @if ($monthAbnormality)
                                                role="button" tabindex="0"
                                                data-abnormality-month="{{ $monthNames[$index + 1] ?? $monthLabel }}"
                                                data-abnormality-year="{{ $year }}"
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
            @if ($orderSeries->sum() === 0 && $finishSeries->sum() === 0 && $scrapSeries->sum() === 0)
                <div class="empty-state" style="margin-top:12px;">
                    Grafik sudah aktif, tetapi belum ada order yang sudah dikonfirmasi User pada periode/filter ini.
                    Batang akan muncul otomatis setelah User mengonfirmasi barang sesuai.
                </div>
            @endif
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
                                <td><strong>{{ $monthNames[(int) $abMonth] ?? $abMonth }}</strong></td>
                                <td>{{ number_format((int) $item->actual_qty) }}</td>
                                <td>{{ number_format((int) $item->threshold_qty) }}</td>
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

        <div class="chart-abnormality-popover" id="chartAbnormalityPopover" role="dialog" aria-hidden="true">
            <h4 class="chart-abnormality-title" id="chartAbnormalityTitle">Abnormality Global</h4>
            <div class="chart-abnormality-meta" id="chartAbnormalityMeta"></div>
            <div class="chart-abnormality-reason" id="chartAbnormalityReason"></div>
        </div>

    </div>

    <script>
        (() => {
            const popover = document.getElementById('chartAbnormalityPopover');
            const title = document.getElementById('chartAbnormalityTitle');
            const meta = document.getElementById('chartAbnormalityMeta');
            const reason = document.getElementById('chartAbnormalityReason');
            const bars = document.querySelectorAll('.chart-bar.has-abnormality');

            if (!popover || bars.length === 0) return;

            const formatNumber = value => new Intl.NumberFormat('id-ID').format(Number(value || 0));

            const closePopover = () => {
                popover.classList.remove('show');
                popover.setAttribute('aria-hidden', 'true');
            };

            const openPopover = bar => {
                const actual = Number(bar.dataset.abnormalityActual || 0);
                const target = Number(bar.dataset.abnormalityTarget || 0);
                const difference = Math.max(actual - target, 0);

                title.textContent = `Abnormality Global · ${bar.dataset.abnormalityMonth} ${bar.dataset.abnormalityYear}`;
                meta.textContent = `Order ${formatNumber(actual)} · Target ${formatNumber(target)} · Selisih +${formatNumber(difference)}`;
                reason.textContent = bar.dataset.abnormalityReason || '-';

                popover.classList.add('show');
                popover.setAttribute('aria-hidden', 'false');

                const rect = bar.getBoundingClientRect();
                const popupRect = popover.getBoundingClientRect();
                const gap = 10;
                let left = rect.left + (rect.width / 2) - (popupRect.width / 2);
                let top = rect.top - popupRect.height - gap;

                left = Math.max(12, Math.min(left, window.innerWidth - popupRect.width - 12));
                if (top < 12) top = Math.min(window.innerHeight - popupRect.height - 12, rect.bottom + gap);

                popover.style.left = `${left}px`;
                popover.style.top = `${Math.max(12, top)}px`;
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

            document.addEventListener('click', event => {
                if (!popover.contains(event.target)) closePopover();
            });
            window.addEventListener('resize', closePopover);
            window.addEventListener('scroll', closePopover, true);
        })();
    </script>
@endsection

<style>
.modern-table-wrap{border:1px solid #e8edf0;border-radius:16px;overflow:hidden;background:#fff;}
.modern-abnormality-table{width:100%;border-collapse:collapse;}
.modern-abnormality-table thead{background:#f7f9fa;}
.modern-abnormality-table th{padding:14px 16px;color:#64748b;font-size:12px;text-transform:uppercase;text-align:left;}
.modern-abnormality-table td{padding:15px 16px;border-top:1px solid #eef2f3;color:#334155;}
.modern-abnormality-table tbody tr:hover{background:#f8faf9;}
.abnormality-diff{display:inline-flex;padding:5px 10px;border-radius:999px;font-weight:700;font-size:13px;}
.abnormality-diff.danger{background:#feecec;color:#dc2626;}
.abnormality-diff.safe{background:#eaf7ef;color:#15803d;}
.reason-cell{max-width:420px;}
</style>
