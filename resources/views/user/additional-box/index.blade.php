@extends('layouts.app')
@section('title', 'Additional Box')
@section('header', 'Additional Box')
@section('content')
<style>
    .ab-page-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; }
    .ab-page-title { margin:0; font-size:21px; font-weight:700; color:#172033; }
    .ab-page-subtitle { margin:5px 0 0; font-size:12px; color:#64748b; }
    .ab-card { overflow:hidden; background:#fff; border:1px solid #e8edf4; border-radius:18px; box-shadow:0 10px 35px rgba(15,23,42,.06); }
    .ab-card-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 20px; border-bottom:1px solid #edf1f5; background:linear-gradient(135deg,#F4F8F5 0%,#fff 75%); }
    .ab-card-header h2 { margin:0; font-size:15px; font-weight:650; color:#172033; }
    .ab-counter { display:inline-flex; align-items:center; padding:8px 12px; border-radius:9px; font-size:11px; font-weight:700; background:#EAF3EC; color:#347554; white-space:nowrap; }
    .ab-table-wrap { padding:12px; overflow-x:auto; }
    .ab-table { width:100%; min-width:510px; border:1px solid #dfe6ef; border-radius:12px; border-spacing:0; border-collapse:separate; overflow:hidden; table-layout:fixed; }
    .ab-table th { padding:12px 14px; background:#f6f7fb; border-right:1px solid #dfe6ef; border-bottom:1px solid #dfe6ef; color:#64748b; font-size:10px; font-weight:800; letter-spacing:.025em; text-align:left; text-transform:uppercase; }
    .ab-table td { padding:13px 14px; border-right:1px solid #dfe6ef; border-bottom:1px solid #dfe6ef; vertical-align:middle; color:#334155; font-size:12px; overflow-wrap:anywhere; }
    .ab-table th:last-child,.ab-table td:last-child { border-right:0; }
    .ab-table tbody tr:last-child td { border-bottom:0; }
    .ab-table tbody tr[data-href] { cursor:pointer; transition:background .15s ease; }
    .ab-table tbody tr[data-href]:hover,.ab-table tbody tr[data-href]:focus-visible { background:#f0f7f2; }
    .ab-table tbody tr[data-href]:focus-visible { outline:2px solid #438866; outline-offset:-2px; }
    .ab-number { font-weight:750; color:#263e30; text-decoration:none; }
    .ab-date { color:#334155; font-weight:600; }
    .ab-time { display:block; font-size:10px; color:#94a3b8; margin-top:3px; }
    .ab-products { display:flex; flex-wrap:wrap; gap:5px; align-items:center; }
    .ab-product { background:#f3f6fa; border:1px solid #e9edf3; color:#334155; border-radius:7px; padding:5px 8px; font-size:11px; }
    .ab-extra { font-size:11px; font-weight:700; color:#438866; }
    .ab-empty { text-align:center; padding:40px 15px !important; color:#94a3b8 !important; }
    .ab-pagination { padding:0 16px 16px; }
    @media(max-width:600px) { .ab-page-title { font-size:19px; } .ab-card-header { padding:14px; } .ab-table-wrap { padding:8px; } }
</style>
<div class="ab-page-head">
    <div><h1 class="ab-page-title">Additional Box</h1><p class="ab-page-subtitle">Riwayat keputusan scrap limit</p></div>
</div>
<div class="ab-card">
    <div class="ab-card-header">
        <h2>Daftar Additional Box</h2>
        <span class="ab-counter">{{ $requests->total() }} Request</span>
    </div>
    <div class="ab-table-wrap">
        <table class="ab-table">
            <colgroup><col style="width:26%"><col style="width:23%"><col style="width:51%"></colgroup>
            <thead><tr><th scope="col">No Order</th><th scope="col">Tanggal</th><th scope="col">Model – Produk</th></tr></thead>
            <tbody>
                @forelse($requests as $document)
                    @php
                        // Older requests predate decision snapshots; retain their existing export items.
                        $products = $document->decisions->isNotEmpty() ? $document->decisions : $document->items;
                        $names = $products->map(fn ($item) => trim($item->model_name . ' – ' . $item->product_name))->unique()->values();
                    @endphp
                    <tr data-href="{{ route('user.additional-box.show', $document) }}" role="link" tabindex="0"
                        aria-label="Lihat detail {{ $document->document_number }}"
                        onclick="if (!event.target.closest('a,button,input')) window.location.assign(this.dataset.href)"
                        onkeydown="if (event.target === this && (event.key === 'Enter' || event.key === ' ')) { event.preventDefault(); window.location.assign(this.dataset.href); }">
                        <td><a class="ab-number" href="{{ route('user.additional-box.show', $document) }}">{{ $document->document_number }}</a></td>
                        <td><span class="ab-date">{{ $document->created_at->format('d/m/Y') }}</span><span class="ab-time">{{ $document->created_at->format('H:i') }}</span></td>
                        <td><div class="ab-products">
                            @foreach($names->take(2) as $name)<span class="ab-product">{{ $name }}</span>@endforeach
                            @if($names->count() > 2)<span class="ab-extra">+{{ $names->count() - 2 }} lainnya</span>@endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="ab-empty"><i class="fa-regular fa-folder-open" aria-hidden="true"></i> Belum ada riwayat Additional Box.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())<div class="ab-pagination">{{ $requests->links() }}</div>@endif
</div>
@endsection
