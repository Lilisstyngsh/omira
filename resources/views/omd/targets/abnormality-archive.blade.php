@extends('layouts.app')
@section('title','Arsip Abnormality Fiscal Year')
@section('content')
<div class="monitor-card" style="padding:24px;">
    <h2>Arsip Abnormality Fiscal Year {{ $year }}</h2>
    <form method="get" style="margin:16px 0"><select name="year" onchange="this.form.submit()">@foreach($years as $y)<option value="{{$y}}" @selected($year==$y)>FY {{$y}}</option>@endforeach</select></form>
    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Order</th>
                <th>Target FY</th>
                <th>Selisih</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>@forelse($abnormalities as $item)<tr>
                <td>{{$item->month}}</td>
                <td>{{$item->actual_qty}}</td>
                <td>{{$item->threshold_qty}}</td>
                <td>{{$item->actual_qty-$item->threshold_qty}}</td>
                <td>{{$item->reason ?: '-'}}</td>
            </tr>@empty<tr>
                <td colspan="5">Belum ada arsip.</td>
            </tr>@endforelse</tbody>
    </table>
</div>
@endsection