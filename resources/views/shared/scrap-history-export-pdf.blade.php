<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 12mm 10mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #000; font-size: 7px; }
        .report-head { margin-bottom: 9px; color: #000; }
        .report-title { margin: 0 0 4px; text-align: center; color: #000; font-size: 13px; font-weight: bold; }
        .report-meta { text-align: right; color: #000; font-size: 7px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 4px 3px; vertical-align: top; overflow-wrap: break-word; word-wrap: break-word; color: #000; }
        th { background: transparent; font-size: 7px; font-weight: bold; text-align: left; }
        td { background: transparent; font-size: 7px; line-height: 1.25; }
        .center { text-align: center; }
        .empty { text-align: center; padding: 8px; }
        .col-date { width: 12%; }
        .col-order { width: 13%; }
        .col-line { width: 10%; }
        .col-model { width: 12%; }
        .col-product { width: 14%; }
        .col-scrap { width: 11%; }
        .col-decision { width: 12%; }
        .col-reason { width: 16%; }
    </style>
</head>
<body>
    <div class="report-head">
        <h1 class="report-title">{{ $title }}</h1>
        <div class="report-meta">Diekspor: {{ $generatedAt->format('d-m-Y H:i') }}</div>
    </div>
    <table>
        <colgroup>
            <col class="col-date"><col class="col-order"><col class="col-line"><col class="col-model">
            <col class="col-product"><col class="col-scrap"><col class="col-decision"><col class="col-reason">
        </colgroup>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No Order</th>
                <th>Line</th>
                <th>Model</th>
                <th>Produk</th>
                <th class="center">Scrap / Limit</th>
                <th>Keputusan</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td class="center">{{ $row['date'] ?: '—' }}</td>
                    <td>{{ $row['order_number'] ?: '—' }}</td>
                    <td>{{ $row['line'] ?: '—' }}</td>
                    <td>{{ $row['model'] ?: '—' }}</td>
                    <td>{{ $row['product'] ?: '—' }}</td>
                    <td class="center">{{ $row['scrap_limit'] ?: '—' }}</td>
                    <td>{{ $row['decision'] ?: '—' }}</td>
                    <td>{{ $row['reason'] ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">Tidak ada data untuk filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
