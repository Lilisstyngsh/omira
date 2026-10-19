<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 9mm 7mm;
            size: A4 portrait;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #161616;
            font-size: 7px;
        }

        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }

        .header td {
            border: 1px solid #444;
            height: 49px;
            vertical-align: middle;
            padding: 5px;
        }

        .header .logo-side {
            width: 24%;
            text-align: center;
        }

        .header .title {
            width: 52%;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .header .document-side {
            width: 24%;
            text-align: center;
            padding: 0;
        }

        .document-meta {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
        }

        .document-meta td {
            border: 0;
            text-align: center;
            vertical-align: middle;
            padding: 3px 2px;
        }

        .document-meta .document-label {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #444;
        }

        .document-meta .document-number {
            font-size: 10px;
            font-weight: bold;
        }

        .logo {
            max-width: 100%;
            max-height: 38px;
        }

        .metadata {
            margin: 0;
            padding: 8px 7px 10px;
            line-height: 1.7;
        }

        .outer-frame {
            border: 1.2px solid #333;
            width: 100%;
            border-collapse: collapse;
        }

        .outer-frame>tbody>tr>td {
            border: 0;
            padding: 4px;
            vertical-align: top;
        }

        .form-frame {
            border: 0;
            margin-top: 5px;
        }

        .grid {
            border: 0;
            width: 100%;
        }

        .grid th,
        .grid td {
            border: 1px solid #555;
            padding: 2px 0.5px;
            word-wrap: break-word;
        }

        .grid th {
            text-align: center;
            font-weight: bold;
        }

        .grid .section {
            font-size: 9px;
        }

        .grid .sep {
            border: none !important;
            background: white !important;
            width: 2%;
            padding: 0;
        }

        .grid td.model,
        .grid td.num {
            text-align: center;
        }

        .grid td.num {
            white-space: nowrap;
            font-size: 6.5px;
            padding-left: 0;
            padding-right: 0;
        }

        .grid td.product {
            text-align: left;
        }

        .grid .summary td {
            font-weight: bold;
        }

        .grid .grand td {}

        .legend-notes {
            width: 100%;
            margin-top: 9px;
            border-collapse: collapse;
        }

        .legend-wrap {
            width: 49%;
            vertical-align: top;
            padding: 0;
            border: 0;
        }

        .notes-wrap {
            width: 49%;
            vertical-align: top;
            padding: 0;
            border: 0;
        }

        .legend-notes .gap {
            width: 2%;
            border: 0;
            padding: 0;
        }

        .legend-table {
            /* Align the legend table's right border with the center divider
               of the main User/OMD form above. */
            width: 100%;
            border-collapse: collapse;
        }

        .notes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .legend-table td,
        .notes-table td {
            border: 1px solid #555;
            padding: 3px 5px;
        }

        .legend-table td {
            height: 14px;
        }

        .legend-title {
            text-align: left;
            font-weight: bold;
        }

        .legend-code {
            width: 33.333%;
            text-align: center;
        }

        .legend-description {
            width: 66.667%;
        }

        .notes-title {
            font-weight: bold;
            text-align: left;
        }

        .notes-content {
            height: 76px;
            vertical-align: top;
            white-space: pre-line;
            line-height: 1.5;
            overflow-wrap: break-word;
        }

        .signature-pair {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-pair>tbody>tr>td {
            border: 0;
            padding: 0;
            vertical-align: top;
        }

        .signature-gap {
            width: 2%;
        }

        .signature {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .signature td {
            border: 1px solid #555;
            text-align: center;
            vertical-align: middle;
            padding: 3px 2px;
            height: 18px;
        }

        .signature .blank {
            height: 43px;
            line-height: 1.55;
        }

        .form-number {
            width: 49%;
            /* Leave one extra line of breathing room below the main frame. */
            margin-top: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .form-number td {
            border: 1px solid #555;
            text-align: center;
            padding: 3px 4px;
            font-size: 7px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table class="outer-frame">
        <tr>
            <td>
                <table class="header">
                    <tr>
                        <td class="logo-side" rowspan="2">
                            @if ($logoData)
                            <img class="logo" src="{{ $logoData }}">
                            @else
                            <b>AISIN</b>
                            @endif
                        </td>
                        <td class="title" rowspan="2">FORM ORDER REPAIR BOX NG</td>
                        <td class="document-side" style="font-size:11px;font-weight:bold;height:24px">PSD - OMD</td>
                    </tr>
                    <tr>
                        <td class="document-side" style="font-size:8px;font-weight:bold;height:25px">{{ $order->order_number }}</td>
                    </tr>
                </table>
                <div class="form-frame">
                    <div class="metadata"><b>Diserahkan oleh:</b> {{ $handedBy }}<br><b>Hari, tanggal:</b>
                        {{ $date }}
                    </div>
                    @php
                    $categories = ['P', 'H', 'C', 'S'];
                    $grandBefore = array_fill_keys($categories, 0);
                    $grandAfter = array_fill_keys($categories, 0);
                    $byModel = collect($entries)->groupBy('model');
                    $notes = collect($entries)->map(function ($entry) {
                    $note = trim((string) ($entry['mismatch_note'] ?? ''));
                    if ($note === '') {
                    return null;
                    }
                    $model = trim((string) ($entry['model'] ?? ''));
                    $product = trim((string) ($entry['product'] ?? ''));
                    $label = trim($model . ($product !== '' ? ' - ' . $product : ''), ' -');
                    return ($label !== '' ? $label . ': ' : '') . $note;
                    })->filter()->unique()->values()->implode("\n");
                    @endphp
                    <table class="grid">
                        <colgroup>
                            <col style="width:12%">
                            <col style="width:29%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:12%">
                            <col style="width:29%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:2%">
                            <col style="width:2%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th colspan="6" class="section">PENYERAHAN USER</th>
                                <th class="sep" rowspan="3"></th>
                                <th colspan="6" class="section">KONFIRMASI PENGEMBALIAN OMD</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Model</th>
                                <th rowspan="2">Produk</th>
                                <th colspan="4">Jenis NG &amp; Qty</th>
                                <th rowspan="2">Model</th>
                                <th rowspan="2">Produk</th>
                                <th colspan="4">Jenis NG &amp; Qty</th>
                            </tr>
                            <tr>
                                @foreach ($categories as $ng)
                                <th>{{ $ng }}</th>
                                @endforeach @foreach ($categories as $ng)
                                <th>{{ $ng }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byModel as $model => $modelEntries)
                            @php @endphp
                            @foreach ($modelEntries as $entryIndex => $entry)
                            <tr>
                                @if ($entryIndex === 0)
                                <td class="model" rowspan="{{ count($modelEntries) }}">
                                    {{ $entry['model'] }}
                                </td>
                                @endif
                                <td class="product">
                                    {{ $entry['product'] }}
                                </td>
                                @foreach ($categories as $ng)
                                @php
                                $n = (int) ($entry['before'][$ng] ?? 0);
                                $grandBefore[$ng] += $n;
                                @endphp
                                <td class="num">{{ $n ?: '' }}</td>
                                @endforeach
                                <td class="sep"></td>
                                @if ($entryIndex === 0)
                                <td class="model" rowspan="{{ count($modelEntries) }}">
                                    {{ $entry['model'] }}
                                </td>
                                @endif
                                <td class="product">
                                    {{ $entry['product'] }}
                                </td>
                                @foreach ($categories as $ng)
                                @php
                                $n = (int) ($entry['after'][$ng] ?? 0);
                                $grandAfter[$ng] += $n;
                                @endphp
                                <td class="num">{{ $n ?: '' }}</td>
                                @endforeach
                            </tr>
                            @endforeach

                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center">Tidak ada item Repair Order.</td>
                                <td class="sep"></td>
                                <td colspan="6" style="text-align:center">Tidak ada item Repair Order.</td>
                            </tr>
                            @endforelse
                            <tr class="summary">
                                <td colspan="2">SUB TOTAL</td>
                                @foreach ($categories as $ng)
                                <td class="num">{{ $grandBefore[$ng] ?: '' }}</td>
                                @endforeach
                                <td class="sep">
                                </td>
                                <td colspan="2">SUB TOTAL</td>
                                @foreach ($categories as $ng)
                                <td class="num">{{ $grandAfter[$ng] ?: '' }}</td>
                                @endforeach
                            </tr>
                            <tr class="summary grand">
                                <td colspan="2">GRAND TOTAL</td>
                                <td class="num" colspan="4">{{ array_sum($grandBefore) ?: '' }}</td>
                                <td class="sep"></td>
                                <td colspan="2">GRAND TOTAL</td>
                                <td class="num" colspan="4">{{ array_sum($grandAfter) ?: '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <table class="legend-notes">
                    <tr>
                        <td class="legend-wrap">
                            <table class="legend-table">
                                <tr>
                                    <td colspan="2" class="legend-title">LEGEND :</td>
                                </tr>
                                <tr>
                                    <td class="legend-code">P</td>
                                    <td class="legend-description">NG Partisi</td>
                                </tr>
                                <tr>
                                    <td class="legend-code">H</td>
                                    <td class="legend-description">NG Holder</td>
                                </tr>
                                <tr>
                                    <td class="legend-code">C</td>
                                    <td class="legend-description">NG Cover</td>
                                </tr>
                                <tr>
                                    <td class="legend-code">S</td>
                                    <td class="legend-description">Box Scrap/Pecah</td>
                                </tr>
                            </table>
                        </td>
                        <td class="gap"></td>
                        <td class="notes-wrap">
                            <table class="notes-table">
                                <tr>
                                    <td class="notes-title">Catatan Ketidaksesuaian</td>
                                </tr>
                                <tr>
                                    <td class="notes-content">{{ $notes }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="signature-pair">
                    <colgroup>
                        <col style="width:49%">
                        <col style="width:2%">
                        <col style="width:49%">
                    </colgroup>
                    <tr>
                        <td>
                            <table class="signature">
                                <colgroup>
                                    <col style="width:50%">
                                    <col style="width:50%">
                                </colgroup>
                                <tr>
                                    <td>Diserahkan oleh:</td>
                                    <td>Diterima oleh:</td>
                                </tr>
                                <tr>
                                    <td class="blank">
                                        @if ($isClosed)
                                        <b>DIKONFIRMASI ELEKTRONIK</b><br>{{ $userApproval }}<br>{{ $approvedAt }}
                                        @endif
                                    </td>
                                    <td class="blank">
                                        @if ($isClosed && $omdApproval)
                                        <b>DIKONFIRMASI ELEKTRONIK</b><br>{{ $omdApproval }}<br>{{ $approvedAt }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ $order->line?->name ?? '-' }}</b></td>
                                    <td><b>OMD</b></td>
                                </tr>
                            </table>
                        </td>
                        <td class="signature-gap"></td>
                        <td>
                            <table class="signature">
                                <colgroup>
                                    <col style="width:50%">
                                    <col style="width:50%">
                                </colgroup>
                                <tr>
                                    <td>Diserahkan oleh:</td>
                                    <td>Diterima oleh:</td>
                                </tr>
                                <tr>
                                    <td class="blank">
                                        @if ($isClosed && $omdApproval)
                                        <b>DIKONFIRMASI ELEKTRONIK</b><br>{{ $omdApproval }}<br>{{ $approvedAt }}
                                        @endif
                                    </td>
                                    <td class="blank">
                                        @if ($isClosed)
                                        <b>DIKONFIRMASI ELEKTRONIK</b><br>{{ $userApproval }}<br>{{ $approvedAt }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>OMD</b></td>
                                    <td><b>{{ $order->line?->name ?? '-' }}</b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="form-number">
        <tr>
            <td>{{ \App\Models\FormNumber::activeNumber() }}</td>
        </tr>
    </table>
</body>

</html>