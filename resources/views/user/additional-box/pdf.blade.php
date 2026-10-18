<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 8.5pt; color: #000; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td, th { border: .6pt solid #111; padding: 1.5pt 2pt; vertical-align: middle; word-wrap: break-word; }
        .page { page-break-inside: avoid; }
        .page-break { page-break-after: always; }
        .blank { border: 0; padding: 0; }
        .center { text-align: center; }
        .blue { background: #195f7b; color: #fff; }
        .gray { background: #c0c0c0; }

        .header td { height: 19mm; }
        .header .logo { text-align: center; padding: 1pt; }
        .header .logo img { width: 39mm; max-height: 15mm; }
        .header .title { text-align: center; font-size: 15.5pt; font-weight: bold; line-height: 1.1; }
        .header .department { text-align: center; font-size: 8pt; font-weight: bold; line-height: 1.25; }
        .date td { height: 4mm; font-size: 8pt; padding: 0 2pt; }

        .items th { text-align: center; height: 5.7mm; font-size: 9pt; padding: 1pt 2pt; }
        .items td { text-align: left; height: 5mm; font-size: 8pt; line-height: 1.15; padding: 1pt 2pt; }
        .items tr { page-break-inside: avoid; }

        .signature td { text-align: center; font-size: 8pt; height: 3.2mm; padding: 0 1pt; }
        .signature .space td { height: 15mm; }

        /* Satu tabel induk untuk menyamakan seluruh garis horizontal jadwal. */
        .schedule th, .schedule td { border: .6pt solid #111; text-align: center; padding: 0; vertical-align: middle; }
        .schedule .schedule-title th { height: 6mm; font-size: 13pt; }
        .schedule .activity-heading { width: 23.5%; font-size: 10pt; white-space: nowrap; word-wrap: normal; }
        .schedule .schedule-heading { height: 5.5mm; font-size: 11pt; }
        .schedule .week-cell { width: 76.5%; padding: 0; }
        .schedule .week-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .schedule .week-grid td { width: 7.142857%; height: 3.6mm; font-size: 7.5pt; border: 0; border-left: .6pt solid #111; padding: 0; }
        .schedule .week-grid td:first-child { border-left: 0; }
        .schedule .activity-cell { width: 23.5%; height: 3.6mm; }

        .note td { background: #98ddf1; vertical-align: top; font-size: 8.5pt; line-height: 1.18; height: 15.5mm; padding: 2pt; }
        .confirmation td { text-align: center; font-size: 8pt; padding: 0 1pt; height: 3.2mm; }
        .confirmation .space td { height: 18mm; }
    </style>
</head>
<body>
@foreach($pages as $rows)
<div class="page {{ $loop->last ? '' : 'page-break' }}">
    <table class="header">
        <tr>
            <td class="logo" style="width:23%">
                @if($logoData)
                    <img src="{{ $logoData }}" alt="AISIN">
                @else
                    <strong style="font-size:22pt;font-style:italic;color:#082b6c">AISIN</strong>
                @endif
            </td>
            <td class="title" style="width:55%">FORM REQUEST ADDITIONAL<br>BOX</td>
            <td class="department" style="width:22%">OPERATION<br>MANAGEMENT<br>DEVELOPMENT DEPT.</td>
        </tr>
    </table>

    <div style="height:3mm"></div>
    <table class="date">
        <tr>
            <td class="blank" style="width:77%"></td>
            <td style="width:23%">Date : {{ $document->created_at->copy()->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
    </table>
    <div style="height:4mm"></div>

    <table class="items">
        <colgroup>
            <col style="width:4.5%"><col style="width:30.5%"><col style="width:19%"><col style="width:37%"><col style="width:9%">
        </colgroup>
        <thead>
            <tr class="blue">
                <th width="4.5%" style="width:4.5%">NO</th>
                <th width="30.5%" style="width:30.5%">TYPE BOX</th>
                <th width="19%" style="width:19%">MARKING</th>
                <th width="37%" style="width:37%">PURPOSE</th>
                <th width="9%" style="width:9%">QTY</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $line)
                @php($entry = $line['item'])
                <tr>
                    <td width="4.5%" style="width:4.5%;text-align:center">{{ $line['number'] }}</td>
                    <td width="30.5%" style="width:30.5%">{{ trim($entry->model_name . ' - ' . $entry->product_name, ' -') }}</td>
                    <td width="19%" style="width:19%"></td>
                    <td width="37%" style="width:37%">{{ $document->purpose }}</td>
                    <td width="9%" style="width:9%">{{ $entry->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="height:4mm"></div>
    <table>
        <tr>
            <td class="blank" style="width:51%"></td>
            <td class="blank" style="width:49%">
                <table class="signature">
                    <tr><td>APPROVED</td><td>CHECKED</td><td>PREPARED</td></tr>
                    <tr class="space"><td></td><td></td><td></td></tr>
                    <tr><td>GM</td><td>Ka. Dept</td><td>Ka. Sie</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="height:4mm"></div>
    <table class="schedule">
        <colgroup><col style="width:23.5%"><col style="width:76.5%"></colgroup>
        <tr class="blue schedule-title"><th colspan="2">SCHEDULE ADDITIONAL BOX</th></tr>
        <tr class="gray">
            <th rowspan="2" width="23.5%" class="activity-heading" style="width:23.5%">ACTIVITY</th>
            <th width="76.5%" class="schedule-heading" style="width:76.5%">LEAD TIME (IN WEEK)</th>
        </tr>
        <tr>
            <td class="week-cell" width="76.5%" style="width:76.5%">
                <table class="week-grid"><tr>@for($w = 1; $w <= 14; $w++)<td>{{ $w }}</td>@endfor</tr></table>
            </td>
        </tr>
        @for($r = 0; $r < 5; $r++)
            <tr>
                <td class="activity-cell" width="23.5%" style="width:23.5%"></td>
                <td class="week-cell" width="76.5%" style="width:76.5%">
                    <table class="week-grid"><tr>@for($w = 1; $w <= 14; $w++)<td></td>@endfor</tr></table>
                </td>
            </tr>
        @endfor
    </table>

    <div style="height:7.5mm"></div>
    <table>
        <tr>
            <td class="blank" style="width:45.5%;vertical-align:top">
                <table class="note"><tr><td>Note:<br><strong>LEAD TIME TERHITUNG SETELAH PENGAJUAN DISETUJUI</strong></td></tr></table>
            </td>
            <td class="blank" style="width:21.5%"></td>
            <td class="blank" style="width:33%;vertical-align:top">
                <table class="confirmation">
                    <tr><td colspan="2">CONFIRMED</td></tr>
                    <tr class="space"><td></td><td></td></tr>
                    <tr><td>Ka. Dept OMC</td><td>Ka. Sie OMC</td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>
@endforeach
</body>
</html>
