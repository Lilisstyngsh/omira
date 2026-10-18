@extends('layouts.app')
@section('title', 'Riwayat Scrap Limit')
@section('header', 'Riwayat Scrap Limit')
@section('content')
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;">
    <h2 style="margin:0 0 15px;font-size:18px">Riwayat Scrap Limit User</h2>
    <div style="overflow-x:auto">
        <table style="width:100%;min-width:1050px;border-collapse:collapse;font-size:12px">
            <thead>
                <tr style="background:#f3f7f4">
                    <th>User</th>
                    <th>Plant / Line</th>
                    <th>Model / Produk</th>
                    <th>Aktual / Limit</th>
                    <th>Keputusan</th>
                    <th>Alasan User</th>
                    <th>Reset / Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cycles as $cycle)
                <tr style="border-bottom:1px solid #e2e8f0">
                    <td style="padding:12px">{{ $cycle->user?->name }}</td>
                    <td>{{ $cycle->product?->masterModel?->line?->plant?->name }} / {{ $cycle->line?->name }}</td>
                    <td>{{ $cycle->product?->masterModel?->model }} / {{ $cycle->product?->name }}</td>
                    <td style="text-align:center">{{ $cycle->reached_qty }} / {{ $cycle->limit_qty }}</td>
                    <td style="text-align:center">{{ $cycle->decision === 'yes' ? 'Ya' : ($cycle->decision === 'no' ? 'Tidak' : 'Belum dipilih') }}</td>
                    <td>{{ $cycle->decision_reason ?: '—' }}</td>
                    <td>{{ $cycle->reset_at ? $cycle->reset_at->format('d/m/Y H:i').' • '.($cycle->resetter?->name ?? '-') : 'Belum reset' }} @if($cycle->reset_reason)<div style="color:#64748b">{{ $cycle->reset_reason }}</div>@endif</td>
                    <td style="padding:10px;min-width:190px">
                        @if(!$cycle->reset_at)
                        <form method="POST" action="{{ route('omd.scrap-cycles.reset',$cycle) }}">
                            @csrf
                            <input name="reason" required minlength="5" maxlength="1000" placeholder="Alasan reset" style="width:100%;padding:8px;border:1px solid #cbd5e1;border-radius:8px;margin-bottom:6px">
                            <button style="background:#347554;color:#fff;border:0;border-radius:8px;padding:9px 12px;cursor:pointer">Reset ke Default</button>
                        </form>
                        @else — @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:24px">Belum ada riwayat limit tercapai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px">{{ $cycles->links() }}</div>
</div>
@endsection