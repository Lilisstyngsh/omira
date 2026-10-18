<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><style>
@page { size:A4 portrait; margin:10mm; }
body { margin:0;font-family:DejaVu Sans,sans-serif;font-size:9pt;color:#000; }
table { width:100%;border-collapse:collapse;table-layout:fixed; }
td,th { border:0.5pt solid #111;padding:2pt 3pt;vertical-align:middle;word-wrap:break-word; }
.page { page-break-after:always; }.page:last-child { page-break-after:auto; }
.blue { background:#195f7b; }.gray { background:#c0c0c0; }.center { text-align:center; }
.header td { height:26mm; }.title { font-size:17pt;font-weight:bold;text-align:center; }
.department { font-size:8pt;font-weight:bold;text-align:center;line-height:1.6; }
.items th { height:9mm;font-size:11pt; }.items td { height:9mm;font-size:8pt; }
.blank { border:0; }.schedule td { height:6.5mm; }.schedule th { padding:4pt 1pt; }
.signature td { padding:2pt; }.signature .space td { height:21mm; }
</style></head><body>
@foreach($pages as $rows)
<div class="page" style="page-break-after:{{ $loop->last ? 'auto' : 'always' }}">
<table class="header"><tr>
<td style="width:25.6%;text-align:center">@if($logoData)<img src="{{ $logoData }}" style="width:34mm;max-height:20mm">@else<span style="font-size:25pt;font-style:italic;color:#082b6c;font-weight:bold">AISIN</span>@endif</td>
<td class="title" style="width:53.4%">FORM REQUEST ADDITIONAL<br>BOX</td>
<td class="department" style="width:21%">OPERATION<br>MANAGEMENT<br>DEVELOPMENT DEPT.</td></tr></table>
<table style="height:15mm"><tr><td class="blank" style="width:73%"></td><td class="blank" style="font-size:9pt">Date : {{ $document->created_at->format('d-m-Y') }}</td></tr></table>
<table class="items"><colgroup><col style="width:7.6%"><col style="width:18%"><col style="width:21%"><col style="width:37.6%"><col style="width:15.8%"></colgroup>
<thead><tr class="blue"><th>NO</th><th>TYPE BOX</th><th>MARKING</th><th>PURPOSE</th><th>QTY</th></tr></thead><tbody>
@foreach($rows as $line)
@php($entry = $line['item'])
<tr><td class="center" style="height:{{ 9 * $line['slots'] }}mm">{{ $line['number'] }}</td><td>{{ $entry ? $entry->model_name . ' — ' . $entry->product_name : '' }}</td><td></td><td>{{ $entry ? $document->purpose : '' }}</td><td class="center">{{ $entry?->quantity }}</td></tr>
@endforeach
</tbody></table>
<div style="height:10mm"></div>
<table><tr><td class="blank" style="width:52%"></td><td class="blank" style="padding:0;width:48%">
<table class="signature center"><tr><td>APPROVED</td><td>CHECKED</td><td>PREPARED</td></tr><tr class="space"><td></td><td></td><td></td></tr><tr><td>GM</td><td>Ka. Dept</td><td>Ka. Sie</td></tr></table>
</td></tr></table>
<div style="height:10mm"></div>
<table class="schedule center"><colgroup><col style="width:25.6%">@for($w=1;$w<=14;$w++)<col style="width:5.314%">@endfor</colgroup>
<tr class="blue"><th colspan="15" style="font-size:15pt;height:7mm">SCHEDULE ADDITIONAL BOX</th></tr>
<tr class="gray"><th rowspan="2" style="font-size:13pt">ACTIVITY</th><th colspan="14" style="font-size:13pt;height:7mm">LEAD TIME (IN WEEK)</th></tr>
<tr>@for($w=1;$w<=14;$w++)<td style="height:4mm">{{ $w }}</td>@endfor</tr>
@for($r=0;$r<5;$r++)<tr><td></td>@for($w=1;$w<=14;$w++)<td></td>@endfor</tr>@endfor
</table>
<div style="height:10mm"></div>
<table><tr><td style="width:46.6%;background:#98ddf1;vertical-align:top;height:19mm">Note:<br><b>LEAD TIME TERHITUNG SETELAH PENGAJUAN DISETUJUI</b></td><td class="blank" style="width:21%"></td><td class="blank" style="width:32.4%;padding:0;vertical-align:top">
<table class="signature center"><tr><td colspan="2">CONFIRMED</td></tr><tr class="space"><td></td><td></td></tr><tr><td>Ka. Dept OMC</td><td>Ka. Sie OMC</td></tr></table>
</td></tr></table>
</div>
@endforeach
</body></html>
