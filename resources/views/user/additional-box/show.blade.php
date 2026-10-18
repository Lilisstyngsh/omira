@extends('layouts.app')
@section('title', 'Request Additional Box')
@section('content')
<div style="background:white;padding:24px;border-radius:12px;overflow-x:auto">
    <h2>Form Request Additional Box</h2>
    <p>{{ $document->document_number }} · {{ $document->created_at->format('d-m-Y H:i') }}</p>
    <p><a class="btn btn-primary" href="{{ route('user.additional-box.export', [$document, 'pdf']) }}">Download PDF</a>
    <a class="btn btn-primary" href="{{ route('user.additional-box.export', [$document, 'excel']) }}">Download Excel</a>
    <a href="{{ route('user.additional-box.index') }}">Riwayat Permintaan</a> · <a href="{{ route('dashboard') }}">Dashboard</a></p>
    <table style="width:100%;border-collapse:collapse" border="1" cellpadding="12">
        <thead style="background:#195f7b;color:white"><tr><th>No.</th><th>Type Box</th><th>Marking</th><th>Purpose</th><th>Qty</th></tr></thead>
        <tbody>@foreach($document->items as $item)<tr><td>{{ $loop->iteration }}</td><td>{{ $item->model_name }} — {{ $item->product_name }}</td><td></td><td>{{ $document->purpose }}</td><td>{{ $item->quantity }}</td></tr>@endforeach</tbody>
    </table>
</div>
@endsection
