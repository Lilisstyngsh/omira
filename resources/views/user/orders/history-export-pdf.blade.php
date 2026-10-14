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

        .header .side {
            width: 24%;
            text-align: center;
        }

        .header .title {
            width: 52%;
            text-align: center;
            font-size: 14px;
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

        .note {
            margin-top: 10px
        }

        .note-box {
            border: 1px solid #555;
            height: 40px;
            margin-top: 4px;
        }

        .signature {
            margin-top: 10px
        }

        .signature td {
            border: 1px solid #555;
            text-align: center;
            height: 20px
        }

        .signature .blank {
            height: 45px;
            vertical-align: middle;
            line-height: 1.6
        }
    </style>
</head>

<body>
    <table class="outer-frame">
        <tr>
            <td>
                <table class="header">
                    <tr>
                        <td class="side">
                            @if ($logoData)
                                <img class="logo" src="{{ $logoData }}">
                            @else
                                <b>AISIN</b>
                            @endif
                        </td>
                        <td class="title">FORM ORDER REPAIR BOX NG</td>
                        <td class="side" style="font-size:10px;font-weight:bold;overflow-wrap:anywhere">
                            {{ $order->order_number }}</td>
                    </tr>
                </table>
                <div class="form-frame">
                    <div class="metadata"><b>Diserahkan oleh:</b> {{ $handedBy }}<br><b>Hari, tanggal:</b>
                        {{ $date }}</div>
                    @php
                        $categories = ['P', 'H', 'C', 'S'];
                        $grandBefore = array_fill_keys($categories, 0);
                        $grandAfter = array_fill_keys($categories, 0);
                        $byModel = collect($entries)->groupBy('model');
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
                                @php  @endphp
                                @foreach ($modelEntries as $entryIndex => $entry)
                                    <tr>
                                        @if ($entryIndex === 0)
                                            <td class="model" rowspan="{{ count($modelEntries) }}">
                                                {{ $entry['model'] }}</td>
                                        @endif
                                        <td class="product">
                                            {{ $entry['product'] }}</td>
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
                                                {{ $entry['model'] }}</td>
                                        @endif
                                        <td class="product">
                                            {{ $entry['product'] }}</td>
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
                <div class="note"><b>Catatan ketidaksesuaian:</b>
                    <div class="note-box"></div>
                </div>
                <table class="signature">
                    <tr>
                        <td>Diterima oleh (OMD)</td>
                        <td>Diserahkan oleh (User / Line)</td>
                    </tr>
                    <tr>
                        <td class="blank">
                            @if ($isClosed && $omdApproval)
                                <b>Diproses oleh OMD</b><br>{{ $omdApproval }}
                            @endif
                        </td>
                        <td class="blank">
                            @if ($isClosed)
                                <b>Dikonfirmasi elektronik</b><br>{{ $userApproval }}<br>{{ $approvedAt }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
