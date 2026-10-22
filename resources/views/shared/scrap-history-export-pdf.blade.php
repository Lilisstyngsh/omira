<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 14mm 12mm 13mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 9px; }
        .report-head { margin-bottom: 16px; }
        .report-title { margin: 0 0 5px; text-align: center; color: #263e30; font-size: 16px; font-weight: bold; }
        .report-meta { text-align: right; color: #64748b; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #e5eaf0; padding: 7px 8px; vertical-align: top; overflow-wrap: break-word; }
        th { background: #f9fafb; color: #64748b; font-size: 8px; font-weight: bold; text-transform: uppercase; text-align: left; }
        td { background: #fff; color: #111827; font-size: 8.5px; line-height: 1.45; }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        .center { text-align: center; }
        .decision { font-weight: bold; color: #263e30; }
        .reason { margin-top: 4px; white-space: pre-wrap; overflow-wrap: break-word; }
        .empty { text-align: center; color: #64748b; padding: 16px; }
        .col-order { width: 17%; }
        .col-date { width: 16%; }
        .col-product { width: 27%; }
        .col-qty { width: 13%; }
        .col-decision { width: 27%; }
    </style>
</head>
<body>
    <div class="report-head">
        <h1 class="report-title">{{ $title }}</h1>
        <div class="report-meta">Diekspor: {{ $generatedAt->format('d-m-Y H:i') }}</div>
    </div>
    <table>
        <colgroup>
            <col class="col-order"><col class="col-date"><col class="col-product"><col class="col-qty"><col class="col-decision">
        </colgroup>
        <thead>
            <tr>
                <th>No Order</th>
                <th>Tanggal</th>
                <th>Model – Produk</th>
                <th class="center">Scrap / Limit</th>
                <th>Keputusan dan Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['order_number'] ?: '—' }}</td>
                    <td class="center">{{ $row['date'] ?: '—' }}</td>
                    <td>{{ $row['model_product'] ?: '—' }}</td>
                    <td class="center">{{ $row['scrap_limit'] ?: '—' }}</td>
                    <td>
                        <div class="decision">{{ $row['decision'] ?: '—' }}</div>
                        @if (trim((string) ($row['reason'] ?? '')) !== '')
                            <div class="reason">Alasan: {{ $row['reason'] }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Tidak ada data untuk filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
