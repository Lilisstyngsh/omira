@extends('layouts.app')

@section('title', $plant->name . ' - Data Master')
@section('header', $plant->name . ' - Data Master')

@section('content')
<style>
    .breadcrumb {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 14px;
        font-size: 12px;
        color: #667085;
    }

    .breadcrumb a {
        color: #6d28d9;
        font-weight: 650;
    }

    .context-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .context-title h2 {
        margin: 0;
    }

    .context-title p {
        margin: 6px 0 0;
        color: #667085;
    }

    .area-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .area-card {
        display: block;
        background: #fff;
        border: 1px solid #e8e5f0;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 22px rgba(58, 35, 120, .06);
        transition: .2s ease;
    }

    .area-card:hover {
        transform: translateY(-3px);
        border-color: #c4b5fd;
        box-shadow: 0 12px 28px rgba(124, 58, 237, .12);
    }

    .area-card h3 {
        margin: 14px 0 6px;
    }

    .area-card p {
        margin: 0;
        color: #667085;
        font-size: 13px;
    }

    .area-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: #ede9fe;
        color: #6d28d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .area-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .badge-soft {
        padding: 5px 9px;
        border-radius: 999px;
        background: #f5f3ff;
        color: #6d28d9;
        font-size: 11px;
        font-weight: 700;
    }
</style>

<div class="breadcrumb">
    <a href="{{ route('omd.master.index', ['scope' => $scope]) }}">Data Master {{ $scopeLabel }}</a>
    <span>›</span>
    <strong>{{ $plant->name }}</strong>
</div>

<div class="context-head">
    <div class="context-title">
        <h2>{{ $plant->name }}</h2>
        <p>Pilih Area / Line untuk mengelola Model dan Produk.</p>
    </div>
    <a class="btn btn-secondary" href="{{ route('omd.master.index', ['scope' => $scope]) }}">← Kembali ke Plant</a>
</div>

<div class="card" style="margin-bottom:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
        <div>
            <h3 style="margin:0;">Tambah Area / Line</h3>
            <div class="muted" style="margin-top:5px;">Area akan otomatis terhubung ke Plant {{ $plant->name }}.</div>
        </div>
        <form method="POST" action="{{ route('omd.master.area.store', ['scope' => $scope, 'plant' => $plant]) }}" style="display:flex;gap:8px;align-items:end;flex-wrap:wrap;">
            @csrf
            <div class="field" style="margin:0;min-width:220px;">
                <label for="area_name">Nama Area / Line</label>
                <input id="area_name" name="name" value="{{ old('name') }}" placeholder="Contoh: DC" required maxlength="100">
            </div>
            <button class="btn btn-primary">Tambah Area</button>
        </form>
    </div>
</div>

@if ($areas->isEmpty())
<div class="card">
    <strong>Belum ada Area / Line pada {{ $plant->name }}.</strong>
    <div class="muted" style="margin-top:6px;">Area dapat ditambahkan melalui Data Master sesuai kebutuhan.</div>
</div>
@else
<div class="area-grid">
    @foreach ($areas as $area)
    <a class="area-card" href="{{ route('omd.master.area.index', ['scope' => $scope, 'plant' => $plant, 'area' => $area]) }}">
        <div class="area-icon">⌁</div>
        <h3>{{ $area->name }}</h3>
        <p>{{ $area->master_models_count }} Model aktif</p>
        <div class="area-footer">
            <span class="badge-soft">{{ $plant->name }}</span>
            <span style="color:#6d28d9;font-weight:700;font-size:12px;">Lihat Model →</span>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection