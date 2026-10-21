<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 11px 10px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6.3px;
            color: #111;
        }

        .matrix {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-page {
            page-break-after: always;
        }

        .report-page:last-child {
            page-break-after: auto;
        }

        .matrix th,
        .matrix td {
            border: .45px solid #555;
            padding: 2px 1px;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .matrix th {
            font-weight: bold;
        }

        .matrix thead {
            display: table-header-group;
        }

        .matrix .plant,
        .matrix .line,
        .matrix .model {
            font-weight: bold;
        }

        .matrix .produk {
            text-align: left;
        }

        .matrix .total,
        .matrix .grand-label {
            font-weight: bold;
        }

        .matrix .grand-label {
            white-space: nowrap;
            font-size: 5.5px;
        }

        .matrix .grand-empty {
            border: none !important;
        }

        .matrix .plantcol {
            width: 9%;
        }

        .matrix .linecol {
            width: 9%;
        }

        .matrix .modelcol {
            width: 11%;
        }

        .matrix .productcol {
            width: 12%;
        }

        .matrix .jeniscol {
            width: 4.5%;
        }

        .matrix .grandcol {
            width: 5.5%;
        }

        .matrix .grandtypecol {
            width: 5%;
        }

        .matrix tr {
            page-break-inside: avoid;
        }

        .annual-report {
            width: 100%;
        }

        .annual {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8pt;
            margin: 0 0 4px;
        }

        .annual th,
        .annual td {
            border: .6px solid #9BAAA0;
            padding: 5px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .annual thead th {
            background: #E5EEE6;
            color: #173C32;
            font-weight: bold;
        }

        .annual .metric {
            width: 78px;
            font-weight: bold;
            text-align: left;
            background: #F1F6F0;
        }

        .annual-title {
            text-align: center;
            font-weight: bold;
            color: #173C32;
            font-size: 14px;
            margin: 0 0 4px;
        }

        .annual-period {
            text-align: center;
            font-size: 8px;
            color: #667A72;
            margin-bottom: 3px;
        }

        .annual-chart-wrap {
            width: 100%;
            margin: 1px 0 2px;
            text-align: center;
            page-break-inside: avoid;
        }

        .annual-chart {
            display: block;
            width: 100%;
            height: auto;
        }

        .abnormality-title {
            margin: 1px 0 3px;
            font-size: 9px;
            font-weight: bold;
            color: #173C32;
        }

        .abnormality {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7.5px;
        }

        .abnormality th,
        .abnormality td {
            border: .55px solid #64748B;
            padding: 3px 5px;
            vertical-align: middle;
        }

        .abnormality thead th {
            background: #E5EEE6;
            color: #173C32;
            text-align: center;
            font-weight: bold;
        }

        .abnormality td.number,
        .abnormality td.month {
            text-align: center;
        }

        .abnormality td.reason {
            text-align: left;
            overflow-wrap: anywhere;
        }

        .abnormality tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @if ($isYearly)
        <div class="annual-report">
            <div class="annual-title">DATA REKAP REPAIR BOX FY {{ $startDate->year }}</div>
            <div class="annual-period">Periode: April {{ $startDate->year }} - Maret {{ $endDate->year }}</div>
            <div class="annual-period">Line: {{ $selectedLineNames->isEmpty() ? 'Semua Line' : $selectedLineNames->implode(', ') }}</div>

            <table class="annual">
                <thead>
                    <tr>
                        <th class="metric">INDIKATOR</th>
                        @foreach ($annualSummary as $month)
                            <th>{{ strtoupper($month['label']) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (['target' => 'TARGET', 'order' => 'ORDER', 'finish' => 'FINISH', 'scrap' => 'SCRAP'] as $key => $label)
                        <tr>
                            <th class="metric">{{ $label }}</th>
                            @foreach ($annualSummary as $month)
                                <td>{{ $month[$key] === null ? '' : number_format($month[$key], 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="annual-chart-wrap">
                <img
                    class="annual-chart"
                    src="data:image/svg+xml;base64,{{ base64_encode($annualChartSvg) }}"
                    alt="Grafik Order Repair Box FY {{ $startDate->year }}"
                >
            </div>

            <div class="abnormality-title">TABEL ABNORMALITY</div>
            <table class="abnormality">
                <colgroup>
                    <col style="width: 18%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 37%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>ORDER/NG</th>
                        <th>TARGET FY</th>
                        <th>SELISIH</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($annualAbnormalities as $abnormality)
                        <tr>
                            <td class="month">{{ $abnormality['label'] }}</td>
                            <td class="number">{{ $abnormality['order'] === null ? '' : number_format($abnormality['order'], 0, ',', '.') }}</td>
                            <td class="number">{{ number_format($abnormality['target'], 0, ',', '.') }}</td>
                            <td class="number">{{ $abnormality['difference'] === null ? '' : number_format($abnormality['difference'], 0, ',', '.') }}</td>
                            <td class="reason">{{ $abnormality['reason'] }}</td>
                        </tr>
                    @empty
                        @for ($emptyRow = 0; $emptyRow < 3; $emptyRow++)
                            <tr>
                                <td>&nbsp;</td><td></td><td></td><td></td><td></td>
                            </tr>
                        @endfor
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        @foreach ($monthlyReports as $report)
            {{-- Layout mengikuti file DATA REKAP BULANAN.xlsx: B:AM, tanpa kolom total per produk. --}}
            @foreach ($report['pages'] as $pageIndex => $pageRows)
                <div class="report-page">
                    <table class="matrix">
                        <colgroup>
                            <col class="plantcol">
                            <col class="linecol">
                            <col class="modelcol">
                            <col class="productcol">
                            <col class="jeniscol">
                            @foreach ($report['days'] as $date)
                                <col>
                            @endforeach
                            <col class="grandcol">
                            <col class="grandtypecol">
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2">PLANT</th>
                                <th rowspan="2">LINE</th>
                                <th rowspan="2">MODEL</th>
                                <th rowspan="2">PRODUK</th>
                                <th rowspan="2"></th>
                                <th colspan="{{ count($report['days']) }}">Bulan : {{ $report['range_label'] }}</th>
                                <th rowspan="2" class="grand-empty"></th>
                                <th rowspan="2" class="grand-empty"></th>
                            </tr>
                            <tr>
                                @foreach ($report['days'] as $date)
                                    <th>{{ $date['header'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pageRows as $plant)
                                @foreach ($plant['lines'] as $lineIndex => $line)
                                    @foreach ($line['models'] as $modelIndex => $model)
                                        @foreach ($model['products'] as $productIndex => $product)
                                            @foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'Scrap'] as $key => $label)
                                                @php
                                                    $isLast =
                                                        $loop->last &&
                                                        $loop->parent->last &&
                                                        $loop->parent->parent->last &&
                                                        $loop->parent->parent->parent->last &&
                                                        $loop->parent->parent->parent->parent->last &&
                                                        $loop->parent->parent->parent->parent->parent->last;
                                                @endphp
                                                <tr>
                                                    @if ($lineIndex === 0 && $modelIndex === 0 && $productIndex === 0 && $key === 'order')
                                                        <td rowspan="{{ $plant['rowspan'] }}" class="plant">
                                                            {{ $plant['name'] }}</td>
                                                    @endif
                                                    @if ($modelIndex === 0 && $productIndex === 0 && $key === 'order')
                                                        <td rowspan="{{ $line['rowspan'] }}" class="line">
                                                            {{ $line['name'] }}</td>
                                                    @endif
                                                    @if ($productIndex === 0 && $key === 'order')
                                                        <td rowspan="{{ $model['rowspan'] }}" class="model">
                                                            {{ $model['name'] }}</td>
                                                    @endif
                                                    @if ($key === 'order')
                                                        <td rowspan="3" class="produk">{{ $product['produk'] }}</td>
                                                    @endif
                                                    <td>{{ $label }}</td>
                                                    @foreach ($report['days'] as $date)
                                                        @php $amount = (int) ($product['daily'][$date['key']][$key] ?? 0); @endphp
                                                        <td>{{ $amount > 0 ? number_format($amount, 0, ',', '.') : '' }}
                                                        </td>
                                                    @endforeach
                                                    @if ($isLast)
                                                        <td colspan="2" class="grand-label">GRAND TOTAL</td>
                                                    @else
                                                        <td class="grand-empty"></td>
                                                        <td class="grand-empty"></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5">Tidak ada produk</td>
                                    <td colspan="{{ count($report['days']) }}"></td>
                                    <td colspan="2" class="grand-label">GRAND TOTAL</td>
                                </tr>
                            @endforelse
                            @if ($loop->last)
                                @foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'Scrap'] as $key => $label)
                                    <tr class="total">
                                        @if ($loop->first)
                                            <td rowspan="3" colspan="4">TOTAL</td>
                                        @endif
                                        <td>{{ $label }}</td>
                                        @foreach ($report['days'] as $date)
                                            @php $amount = (int) ($report['grandDaily'][$date['key']][$key] ?? 0); @endphp
                                            <td>{{ $amount > 0 ? number_format($amount, 0, ',', '.') : '' }}</td>
                                        @endforeach
                                        <td>{{ number_format(collect($report['days'])->sum(fn($d) => (int) ($report['grandDaily'][$d['key']][$key] ?? 0)), 0, ',', '.') }}
                                        </td>
                                        <td>{{ $label }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @endforeach
        @endforeach
    @endif
</body>

</html>
