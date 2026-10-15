<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<style>
@page { size: A4 portrait; margin: 8mm; }
* { box-sizing: border-box; }
body { font-family: Arial, DejaVu Sans, sans-serif; color:#111; font-size:9px; margin:0; }
table { border-collapse:collapse; table-layout:fixed; }
.w-full { width:100%; }
.header td { border:1px solid #222; height:54px; vertical-align:middle; padding:4px 7px; }
.header-logo { width:25%; text-align:center; }
.header-title { width:50%; text-align:center; font-size:20px; font-weight:bold; }
.header-order { width:25%; text-align:center; }
.logo { max-width:95%; max-height:42px; }
.order-label { font-weight:bold; font-size:14px; padding:0 0 5px; border-bottom:1px solid #222; margin:0 -7px 5px; }
.order-number { font-size:12px; }
.metadata { margin:17px 0 35px; font-weight:bold; line-height:1.55; font-size:10px; }
.panels { width:100%; }
.panels > tbody > tr > td { vertical-align:top; width:49%; }
.panels .gutter { width:1.4%; border:0; }
.grid { width:100%; font-size:7.5px; }
.grid th,.grid td { border:1px solid #222; padding:1px 2px; height:14px; vertical-align:middle; }
.grid th { text-align:center; font-weight:bold; }
.grid .section { height:17px; font-size:8px; }
.grid .model,.grid .qty { text-align:center; }
.grid .product,.grid .note-cell { text-align:left; }
.grid .model,.grid .product { font-size:7px; white-space:nowrap; overflow:hidden; }
.grid .qty { white-space:nowrap; }
.grid .subtotal td,.grid .grandtotal td { font-weight:bold; background:#f1f3f5; }
.grid .grandtotal td { height:15px; }
.left-grid col.model { width:22%; } .left-grid col.product { width:36%; } .left-grid col.qty { width:10.5%; }
.right-grid col.model { width:22%; } .right-grid col.product { width:36%; } .right-grid col.qty { width:10.5%; }
.bottom-row { width:100%; margin-top:40px; }
.bottom-row td { vertical-align:top; }
.legend { width:49%; font-size:9px; }
.legend td { border:1px solid #222; padding:3px 5px; height:20px; }
.legend .legend-title { font-weight:bold; }
.legend table { width:58%; }
.legend td:first-child { width:7%; text-align:center; padding-left:2px; padding-right:2px; white-space:nowrap; }
.notes-wrap { width:49%; }
.notes { width:100%; }
.notes td { border:1px solid #222; padding:5px 7px; }
.notes .notes-title { font-weight:bold; height:23px; }
.notes .notes-content { height:105px; vertical-align:top; line-height:1.45; white-space:pre-line; }
.bottom-spacer { width:1.4%; }
.signatures { width:100%; margin-top:48px; }
.signatures td { border:1px solid #222; text-align:center; padding:4px; }
.signatures .sig-title { font-weight:bold; height:23px; }
.signatures .sig-body { height:66px; line-height:1.55; vertical-align:middle; }
.signatures .sig-role { font-weight:bold; height:22px; }
.signature-gap { height:0; border:0!important; }
.form-row { width:27%; margin-top:23px; }
.form-row td { border:1px solid #222; text-align:center; height:23px; vertical-align:middle; }
</style>
</head>
<body>
<table class="header w-full">
<tr>
<td class="header-logo">
@if($logoData)<img class="logo" src="{{ $logoData }}">@else <b style="font-size:22px">AISIN</b><br><span>PT. AISIN INDONESIA AUTOMOTIVE</span>@endif
</td>
<td class="header-title">FORM ORDER REPAIR BOX NG</td>
<td class="header-order"><div class="order-label">PSD - OMD</div><div class="order-number">{{ $order->order_number }}</div></td>
</tr>
</table>
<div class="metadata">
Nama User : {{ $order->user?->name ?? '-' }} / {{ $order->line?->name ?? '-' }}<br>
Hari/Tanggal Order : {{ $order->created_at?->locale('id')->translatedFormat('l, d F Y') ?? '-' }}
</div>
@php
$categories = ['P','H','C','S'];
$beforeTotals = array_fill_keys($categories, 0);
$afterTotals = array_fill_keys($categories, 0);
$byModel = collect($entries)->groupBy('model');
$productNotes = [];
foreach ($entries as $entry) {
    $entryNote = trim((string) ($entry['mismatch_note'] ?? ''));
    if ($entryNote !== '') $productNotes[] = ($entry['model'] ?? '-') . ' - ' . ($entry['product'] ?? '-') . ': ' . $entryNote;
}
$noteText = implode("\n", array_values(array_unique($productNotes)));
@endphp
<table class="panels">
<tr>
<td>
<table class="grid left-grid">
<colgroup><col class="model"><col class="product"><col class="qty"><col class="qty"><col class="qty"><col class="qty"></colgroup>
<thead>
<tr><th colspan="6" class="section">DI ISI OLEH USER (SEBELUM REPAIR)</th></tr>
<tr><th rowspan="2">MODEL</th><th rowspan="2">PRODUK</th><th colspan="4">JENIS NG &amp; QTY</th></tr>
<tr>@foreach($categories as $ng)<th>{{ $ng }}</th>@endforeach</tr>
</thead>
<tbody>
@php $index = 0; @endphp
@forelse($byModel as $model => $modelEntries)
@foreach($modelEntries as $entryIndex => $entry)
@php $index++; @endphp
<tr>
@if($entryIndex === 0)<td class="model" rowspan="{{ count($modelEntries) }}">{{ $entry['model'] }}</td>@endif
<td class="product">{{ $entry['product'] }}</td>
@foreach($categories as $ng) @php $qty=(int)($entry['before'][$ng]??0); $beforeTotals[$ng]+=$qty; @endphp<td class="qty">{{ $qty > 0 ? $qty : '' }}</td>@endforeach
</tr>
@endforeach
@empty
<tr><td colspan="6" style="text-align:center">Tidak ada item.</td></tr>
@endforelse
<tr class="subtotal"><td colspan="2">SUB TOTAL</td>@foreach($categories as $ng)<td class="qty">{{ $beforeTotals[$ng] > 0 ? $beforeTotals[$ng] : '' }}</td>@endforeach</tr>
<tr class="grandtotal"><td colspan="2">GRAND TOTAL</td><td colspan="4" class="qty">{{ array_sum($beforeTotals) > 0 ? array_sum($beforeTotals) : '' }}</td></tr>
</tbody>
</table>
</td>
<td class="gutter"></td>
<td>
<table class="grid right-grid">
<colgroup><col class="model"><col class="product"><col class="qty"><col class="qty"><col class="qty"><col class="qty"></colgroup>
<thead>
<tr><th colspan="6" class="section">DI ISI OLEH OMD (SETELAH REPAIR)</th></tr>
<tr><th rowspan="2">MODEL</th><th rowspan="2">PRODUK</th><th colspan="4">JENIS NG &amp; QTY</th></tr>
<tr>@foreach($categories as $ng)<th>{{ $ng }}</th>@endforeach</tr>
</thead>
<tbody>
@php $index = 0; @endphp
@forelse($byModel as $model => $modelEntries)
@foreach($modelEntries as $entryIndex => $entry)
@php $index++; @endphp
<tr>
@if($entryIndex === 0)<td class="model" rowspan="{{ count($modelEntries) }}">{{ $entry['model'] }}</td>@endif
<td class="product">{{ $entry['product'] }}</td>
@foreach($categories as $ng) @php $qty=(int)($entry['after'][$ng]??0); $afterTotals[$ng]+=$qty; @endphp<td class="qty">{{ $qty > 0 ? $qty : '' }}</td>@endforeach

</tr>
@endforeach
@empty
<tr><td colspan="6" style="text-align:center">Tidak ada item.</td></tr>
@endforelse
<tr class="subtotal"><td colspan="2">SUB TOTAL</td>@foreach($categories as $ng)<td class="qty">{{ $afterTotals[$ng] > 0 ? $afterTotals[$ng] : '' }}</td>@endforeach</tr>
<tr class="grandtotal"><td colspan="2">GRAND TOTAL</td><td colspan="4" class="qty">{{ array_sum($afterTotals) > 0 ? array_sum($afterTotals) : '' }}</td></tr>
</tbody>
</table>
</td>
</tr>
</table>
<table class="bottom-row">
<tr>
<td class="legend">
<table class="w-full">
<tr><td colspan="2" class="legend-title">LEGEND :</td></tr>
<tr><td style="width:12%">P</td><td>NG Partisi</td></tr>
<tr><td>H</td><td>NG Holder Kanban</td></tr>
<tr><td>C</td><td>NG Cover</td></tr>
<tr><td>S</td><td>Box Scrap/Pecah</td></tr>
</table>
</td>
<td class="bottom-spacer"></td>
<td class="notes-wrap">
<table class="notes">
<tr><td class="notes-title">Catatan Ketidaksesuaian</td></tr>
<tr><td class="notes-content">{{ $noteText }}</td></tr>
</table>
</td>
</tr>
</table>
<table class="signatures">
<tr>
<td colspan="2" class="sig-title">Diserahkan oleh:</td><td colspan="2" class="sig-title">Diterima oleh:</td>
<td style="width:2%;border:0"></td>
<td colspan="2" class="sig-title">Diserahkan oleh:</td><td colspan="2" class="sig-title">Diterima oleh:</td>
</tr>
<tr>
<td colspan="2" class="sig-body">@if($isClosed && $userApproval)DIKONFIRMASI ELEKTRONIK<br>{{ $userApproval }}<br>{{ $approvedAt }}@endif</td>
<td colspan="2" class="sig-body">@if($isClosed && $omdApproval)DIKONFIRMASI ELEKTRONIK<br>{{ $omdApproval }}<br>{{ $approvedAt }}@endif</td>
<td style="border:0"></td>
<td colspan="2" class="sig-body">@if($isClosed && $omdApproval)DIKONFIRMASI ELEKTRONIK<br>{{ $omdApproval }}<br>{{ $approvedAt }}@endif</td>
<td colspan="2" class="sig-body">@if($isClosed && $userApproval)DIKONFIRMASI ELEKTRONIK<br>{{ $userApproval }}<br>{{ $approvedAt }}@endif</td>
</tr>
<tr>
<td colspan="2" class="sig-role">PPIC</td><td colspan="2" class="sig-role">OMD</td><td style="border:0"></td><td colspan="2" class="sig-role">OMD</td><td colspan="2" class="sig-role">PPIC</td>
</tr>
</table>
<table class="form-row"><tr><td>FRM-PSD-P3-057-00</td></tr></table>
</body>
</html>
