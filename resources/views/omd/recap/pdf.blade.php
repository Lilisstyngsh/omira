<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 15px 15px 18px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7px; color: #24332b; }
        .title { text-align: center; font-size: 13px; font-weight: bold; margin: 0 0 3px; }
        .period { text-align: center; font-size: 8px; margin-bottom: 10px; }
        .month-heading { text-align: center; font-weight: bold; font-size: 10px; margin: 8px 0; }
        .month-section { page-break-after: always; }
        .month-section:last-child { page-break-after: auto; }
        .matrix { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .matrix th, .matrix td { border: 0.5px solid #748379; padding: 3px 2px; text-align: center; vertical-align: middle; overflow-wrap: break-word; }
        .matrix thead th { background: #e5eee8; font-weight: bold; }
        .matrix .group { font-weight: bold; background: #fff; }
        .matrix .product { text-align: left; padding-left: 5px; }
        .matrix .status { font-weight: normal; background: #fff; }
        .matrix .grandrow td { background: #d9e9df; font-weight: bold; }
        .matrix .empty { color: #64748b; padding: 15px; }
        .matrix .daycol { width: auto; }
        .matrix .totalcol { width: 45px; font-weight: bold; }
        .matrix .plantcol { width: 55px; }
        .matrix .linecol { width: 56px; }
        .matrix .modelcol { width: 57px; }
        .matrix .productcol { width: 74px; }
        .matrix .statuscol { width: 35px; }
        .matrix tbody tr { page-break-inside: avoid; }
    </style>
</head>
<body>
@foreach ($monthlyReports as $report)
    <div class="month-section">
        <div class="title">DATA REKAP REPAIR BOX ALL PLANT</div>
        <div class="period">Periode: {{ $startDate->format('d-m-Y') }} s.d. {{ $endDate->format('d-m-Y') }} | Line: {{ $selectedLineNames->implode(', ') }}</div>
        <div class="month-heading">Bulan: {{ $report['label'] }}</div>
        <table class="matrix">
            <thead>
                <tr>
                    <th rowspan="2" class="plantcol">PLANT</th>
                    <th rowspan="2" class="linecol">LINE</th>
                    <th rowspan="2" class="modelcol">MODEL</th>
                    <th rowspan="2" class="productcol">PRODUK</th>
                    <th rowspan="2" class="statuscol">JENIS</th>
                    <th colspan="{{ count($report['days']) }}">TANGGAL — {{ $report['label'] }}</th>
                    <th rowspan="2" class="totalcol">GRAND TOTAL</th>
                </tr>
                <tr>
                    @foreach ($report['days'] as $day)
                        <th class="daycol">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @forelse ($report['plants'] as $plant)
                @foreach ($plant['lines'] as $lineIndex => $line)
                    @foreach ($line['models'] as $modelIndex => $model)
                        @foreach ($model['products'] as $productIndex => $product)
                            @foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'Scrap'] as $key => $label)
                            <tr>
                                @if ($lineIndex === 0 && $modelIndex === 0 && $productIndex === 0 && $key === 'order')
                                    <td rowspan="{{ $plant['rowspan'] }}" class="group">{{ $plant['name'] }}</td>
                                @endif
                                @if ($modelIndex === 0 && $productIndex === 0 && $key === 'order')
                                    <td rowspan="{{ $line['rowspan'] }}" class="group">{{ $line['name'] }}</td>
                                @endif
                                @if ($productIndex === 0 && $key === 'order')
                                    <td rowspan="{{ $model['rowspan'] }}" class="group">{{ $model['name'] }}</td>
                                @endif
                                @if ($key === 'order')
                                    <td rowspan="3" class="product">{{ $product['produk'] }}</td>
                                @endif
                                <td class="status">{{ $label }}</td>
                                @foreach ($report['days'] as $day)
                                    @php $value = (int) ($product['daily'][$day][$key] ?? 0); @endphp
                                    <td>{{ $value > 0 ? number_format($value, 0, ',', '.') : '' }}</td>
                                @endforeach
                                <td>{{ number_format($product['totals'][$key], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @empty
                <tr><td class="empty" colspan="{{ count($report['days']) + 6 }}">Tidak ada data Repair Box terkonfirmasi pada periode dan Line yang dipilih.</td></tr>
            @endforelse
            @foreach (['order' => 'NG', 'ok' => 'OK', 'scrap' => 'Scrap'] as $key => $label)
                <tr class="grandrow">
                    @if ($key === 'order')
                        <td colspan="4" rowspan="3">TOTAL</td>
                    @endif
                    <td>{{ $label }}</td>
                    @foreach ($report['days'] as $day)
                        @php $value = (int) ($report['grandDaily'][$day][$key] ?? 0); @endphp
                        <td>{{ $value > 0 ? number_format($value, 0, ',', '.') : '' }}</td>
                    @endforeach
                    <td>{{ number_format($report['grandTotals'][$key], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach
</body>
</html>
