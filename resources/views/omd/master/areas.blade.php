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
        color: #376B52;
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
        box-shadow: 0 12px 28px rgba(95, 146, 120, .12);
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
        background: #E7F1E9;
        color: #376B52;
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
        background: #F2F7F2;
        color: #376B52;
        font-size: 11px;
        font-weight: 700;
    }

/* Modern industrial master directory — flat hierarchy, no ornamental icon tiles */
.master-hero{background:#fff!important;color:#233b2d!important;border:1px solid #e2ebe4!important;box-shadow:none!important;border-radius:14px!important}
.master-hero p{color:#6a7c70!important}
.master-card,.area-card{border:1px solid #e0e9e2!important;border-radius:12px!important;box-shadow:none!important;padding:18px!important;transition:border-color .15s ease,background .15s ease!important}
.master-card:hover,.area-card:hover{transform:none!important;box-shadow:none!important;border-color:#93b69f!important;background:#fbfdfb!important}
.master-icon,.area-icon{display:none!important}
.master-card h3,.area-card h3{margin-top:0!important;font-weight:700!important;color:#263b30!important}
.master-card-footer,.area-footer{border-top:1px solid #edf1ed;padding-top:12px;margin-top:15px!important}

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
                <input id="area_name" name="name" value="{{ old('name') }}" placeholder="Contoh: Die Casting" required maxlength="100">
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
            <span style="color:#376B52;font-weight:700;font-size:12px;">Lihat Model →</span>
        </div>
    </a>
    @endforeach
</div>
@endif
@endsection