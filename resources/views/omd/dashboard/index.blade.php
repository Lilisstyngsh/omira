@extends('layouts.app')

@section('title', 'Monitoring Repair Box')
@section('header', 'Monitoring Repair Box')

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
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
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

        .kpi-dot.order { background: #4f46e5; }
        .kpi-dot.ok { background: #16a34a; }
        .kpi-dot.scrap { background: #dc2626; }
        .kpi-dot.target { background: #f97316; }

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

        .alert-card.target-alert {
            border-color: #fdba74;
            background: #fff7ed;
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

        .target-alert .alert-icon {
            background: #ffedd5;
            color: #ea580c;
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

        .abnormality-meta {
            margin-top: 8px;
            color: #9a3412;
            font-size: 10px;
            font-weight: 700;
        }

        .chart-card {
            padding: 18px;
            overflow: hidden;
        }

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

        .legend-color.order { background: #4f46e5; }
        .legend-color.ok { background: #16a34a; }
        .legend-color.scrap { background: #dc2626; }
        .legend-color.target { background: #f97316; height: 3px; }

        .chart-scroll {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .bar-chart {
            display: grid;
            grid-template-columns: 58px minmax(650px, 1fr);
            min-width: 710px;
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

        .chart-grid {
            position: absolute;
            inset: 0 0 27px;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent calc(25% - 1px),
                #eef2f7 calc(25% - 1px),
                #eef2f7 25%
            );
            border-bottom: 1px solid #cbd5e1;
        }

        .target-line {
            position: absolute;
            left: 0;
            right: 0;
            bottom: calc(27px + var(--target-height));
            z-index: 3;
            border-top: 2px dashed #f97316;
            pointer-events: none;
        }

        .target-line span {
            position: absolute;
            right: 3px;
            top: -21px;
            padding: 2px 6px;
            border-radius: 999px;
            background: #fff7ed;
            color: #c2410c;
            font-size: 9px;
            font-weight: 900;
        }

        .chart-months {
            position: absolute;
            inset: 0 0 0;
            display: grid;
            grid-template-columns: repeat(var(--month-count), minmax(48px, 1fr));
            gap: 7px;
            z-index: 2;
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
            width: min(18px, 28%);
            min-height: 2px;
            height: var(--bar-height);
            border-radius: 6px 6px 2px 2px;
            transition: opacity .15s ease, transform .15s ease;
        }

        .chart-bar:hover {
            opacity: .82;
            transform: translateY(-2px);
        }

        .chart-bar.order { background: #4f46e5; }
        .chart-bar.ok { background: #16a34a; }
        .chart-bar.scrap { background: #dc2626; }

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

        .summary-order td:first-child { color: #4f46e5; }
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
        }
    </style>

    <div class="monitor-page">
        <div class="monitor-head">
            <div>
                <h2>Monitoring Repair Box</h2>
                <div class="monitor-muted">
                    Data realtime hasil repair yang sudah disubmit OMD. Grafik menampilkan Januari sampai bulan filter.
                </div>
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

        <div class="monitor-kpis">
            <div class="monitor-card monitor-kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Order</span>
                    <span class="kpi-dot order"></span>
                </div>
                <div class="kpi-value">{{ number_format($totalOrders) }}</div>
                <div class="kpi-note">Grand total NG P + H + C + S hasil OMD bulan terpilih.</div>
            </div>

            <div class="monitor-card monitor-kpi">
                <div class="kpi-top">
                    <span class="kpi-label">OK</span>
                    <span class="kpi-dot ok"></span>
                </div>
                <div class="kpi-value">{{ number_format($finishedOrders) }}</div>
                <div class="kpi-note">Qty hasil repair yang dinyatakan OK.</div>
            </div>

            <div class="monitor-card monitor-kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Scrap</span>
                    <span class="kpi-dot scrap"></span>
                </div>
                <div class="kpi-value">{{ number_format($scrap) }}</div>
                <div class="kpi-note">Order dikurangi qty OK hasil repair.</div>
            </div>

            <div class="monitor-card monitor-kpi">
                <div class="kpi-top">
                    <span class="kpi-label">Target FY</span>
                    <span class="kpi-dot target"></span>
                </div>
                <div class="kpi-value">{{ number_format($target) }}</div>
                <div class="kpi-note">{{ $targetScopeLabel }} untuk tahun {{ $year }}.</div>
            </div>
        </div>

        @if ($targetExceeded)
            <div class="monitor-card alert-card target-alert">
                <div class="alert-icon">!</div>
                <div style="flex:1;min-width:0;">
                    <h3 class="alert-title">Target FY terlampaui</h3>
                    <p class="alert-copy">
                        Order {{ number_format($totalOrders) }} melewati target {{ number_format($target) }}
                        sebanyak <strong>{{ number_format($targetDifference) }}</strong> box.
                        OMD wajib mencatat alasan abnormality.
                    </p>

                    <form class="abnormality-form" method="POST" action="{{ route('omd.dashboard.abnormality.store') }}">
                        @csrf
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="month" value="{{ $month }}">
                        @if ($lineId)
                            <input type="hidden" name="line_id" value="{{ $lineId }}">
                        @endif

                        <textarea name="reason" placeholder="Tuliskan penyebab Order melewati Target FY..." required>{{ old('reason', $abnormality?->reason) }}</textarea>

                        @error('reason')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div>
                            <button type="submit" class="btn btn-primary">
                                {{ $abnormality ? 'Perbarui Alasan' : 'Simpan Alasan' }}
                            </button>
                        </div>
                    </form>

                    @if ($abnormality)
                        <div class="abnormality-meta">
                            Alasan terakhir tersimpan {{ optional($abnormality->updated_at)->format('d/m/Y H:i') }}.
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($productScrapAlerts->isNotEmpty())
            <div class="monitor-card alert-card scrap-alert">
                <div class="alert-icon">!</div>
                <div style="flex:1;min-width:0;">
                    <h3 class="alert-title">Peringatan Scrap Limit Produk</h3>
                    <p class="alert-copy">
                        {{ $productScrapAlerts->count() }} produk mencapai atau melewati Scrap Limit pada periode filter.
                    </p>
                </div>
            </div>
        @endif

        <div class="monitor-card chart-card">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Grafik Monitoring Repair Box {{ $year }}</h3>
                    <p class="card-subtitle">
                        Januari s.d. {{ $monthNames[$month] }} · data berdasarkan tanggal OMD submit hasil repair.
                    </p>
                </div>

                <div class="chart-legend">
                    <span class="legend-item"><i class="legend-color order"></i>Order</span>
                    <span class="legend-item"><i class="legend-color ok"></i>OK</span>
                    <span class="legend-item"><i class="legend-color scrap"></i>Scrap</span>
                    <span class="legend-item"><i class="legend-color target"></i>Target FY</span>
                </div>
            </div>

            <div class="chart-scroll">
                <div class="bar-chart">
                    <div class="chart-axis">
                        @foreach ($scaleValues as $scale)
                            <span>{{ number_format($scale) }}</span>
                        @endforeach
                    </div>

                    <div class="chart-plot">
                        <div class="chart-grid"></div>

                        @if ($target > 0)
                            <div class="target-line" style="--target-height: {{ number_format($targetPercent, 2, '.', '') }}%;">
                                <span>Target {{ number_format($target) }}</span>
                            </div>
                        @endif

                        <div class="chart-months" style="--month-count: {{ max($months->count(), 1) }};">
                            @foreach ($months as $index => $monthLabel)
                                @php
                                    $orderValue = (int) ($orderSeries[$index] ?? 0);
                                    $okValue = (int) ($finishSeries[$index] ?? 0);
                                    $scrapValue = (int) ($scrapSeries[$index] ?? 0);

                                    $orderHeight = $chartMax > 0 ? ($orderValue / $chartMax) * 100 : 0;
                                    $okHeight = $chartMax > 0 ? ($okValue / $chartMax) * 100 : 0;
                                    $scrapHeight = $chartMax > 0 ? ($scrapValue / $chartMax) * 100 : 0;
                                @endphp

                                <div class="month-group">
                                    <div class="month-bars">
                                        <div class="chart-bar order"
                                            title="{{ $monthLabel }} · Order {{ number_format($orderValue) }}"
                                            style="--bar-height: {{ number_format($orderHeight, 2, '.', '') }}%;"></div>
                                        <div class="chart-bar ok"
                                            title="{{ $monthLabel }} · OK {{ number_format($okValue) }}"
                                            style="--bar-height: {{ number_format($okHeight, 2, '.', '') }}%;"></div>
                                        <div class="chart-bar scrap"
                                            title="{{ $monthLabel }} · Scrap {{ number_format($scrapValue) }}"
                                            style="--bar-height: {{ number_format($scrapHeight, 2, '.', '') }}%;"></div>
                                    </div>
                                    <div class="month-label">{{ $monthLabel }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if ($orderSeries->sum() === 0 && $finishSeries->sum() === 0 && $scrapSeries->sum() === 0)
                <div class="empty-state" style="margin-top:12px;">
                    Grafik sudah aktif, tetapi belum ada hasil repair OMD pada periode/filter ini.
                    Batang akan muncul otomatis setelah OMD submit hasil repair.
                </div>
            @endif
        </div>

        <div class="monitor-grid">
            <div class="monitor-card table-card">
                <div class="card-head">
                    <div>
                        <h3 class="card-title">Rekap Realtime</h3>
                        <p class="card-subtitle">Angka yang menjadi sumber grafik Dashboard.</p>
                    </div>
                </div>

                <div class="summary-scroll">
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th>Summary</th>
                                @foreach ($months as $monthLabel)
                                    <th>{{ $monthLabel }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="summary-target">
                                <td>Target FY</td>
                                @foreach ($targetSeries as $value)
                                    <td>{{ number_format($value) }}</td>
                                @endforeach
                            </tr>
                            <tr class="summary-order">
                                <td>Order</td>
                                @foreach ($orderSeries as $value)
                                    <td>{{ number_format($value) }}</td>
                                @endforeach
                            </tr>
                            <tr class="summary-ok">
                                <td>OK</td>
                                @foreach ($finishSeries as $value)
                                    <td>{{ number_format($value) }}</td>
                                @endforeach
                            </tr>
                            <tr class="summary-scrap">
                                <td>Scrap</td>
                                @foreach ($scrapSeries as $value)
                                    <td>{{ number_format($value) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="monitor-card scrap-card">
                <div class="card-head">
                    <div>
                        <h3 class="card-title">Scrap Limit per Produk</h3>
                        <p class="card-subtitle">Alert berdasarkan limit produk yang aktif pada periode filter.</p>
                    </div>
                </div>

                @if ($productScrapAlerts->isEmpty())
                    <div class="empty-state">
                        Tidak ada produk yang mencapai Scrap Limit pada periode ini.
                    </div>
                @else
                    <div class="scrap-list">
                        @foreach ($productScrapAlerts as $alert)
                            <div class="scrap-item {{ $alert['status'] }}">
                                <div class="scrap-item-top">
                                    <div class="scrap-model">{{ $alert['product'] }}</div>
                                    <div class="scrap-status">
                                        {{ $alert['status'] === 'exceeded' ? 'Exceeded' : 'Limit' }}
                                    </div>
                                </div>
                                <div class="scrap-line">Model {{ $alert['model'] }} · Line {{ $alert['line'] }}</div>
                                <div class="scrap-values">
                                    Scrap <strong>{{ number_format($alert['scrap_qty']) }}</strong> ·
                                    Limit <strong>{{ number_format($alert['limit_qty']) }}</strong>
                                    @if ($alert['difference'] > 0)
                                        · Melewati <strong>{{ number_format($alert['difference']) }}</strong>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
