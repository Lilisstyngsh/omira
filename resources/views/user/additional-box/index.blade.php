@extends('layouts.app')
@section('title', 'Riwayat Request Additional Box')
@section('content')
<div style="background:white;padding:24px;border-radius:12px;overflow-x:auto">
    <h2>Riwayat Request Additional Box</h2>
    <p><a href="{{ route('dashboard') }}">Kembali ke Dashboard</a></p>
    <table style="width:100%;border-collapse:collapse" cellpadding="12">
        <thead><tr><th align="left">Nomor</th><th align="left">Tanggal</th><th>Produk</th><th>Form</th></tr></thead>
        <tbody>@forelse($requests as $document)
        <tr style="border-top:1px solid #ddd"><td>{{ $document->document_number }}</td><td>{{ $document->created_at->format('d-m-Y H:i') }}</td><td align="center">{{ $document->items_count }}</td><td align="center"><a href="{{ route('user.additional-box.show', $document) }}">Lihat / Download</a></td></tr>
        @empty<tr><td colspan="4">Belum ada permintaan additional box.</td></tr>@endforelse</tbody>
    </table>
    {{ $requests->links() }}
</div>
@endsection
