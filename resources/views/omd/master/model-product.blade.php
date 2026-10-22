@extends('layouts.app')

@section('title', 'Model Box')
@section('header', 'Model Box')

@section('content')
@php
    $activeAt = now();
    $defaultAddLineId = $selectedLineId ?: $lines->first(
        fn ($line) => !$selectedPlantId || (int) $line->plant_id === (int) $selectedPlantId
    )?->id;
    $catalogRoute = route('omd.master.model-product');
@endphp
<style>
    .mp-page{display:grid;gap:20px;color:#334155;font-size:12px;background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:28px 30px;box-shadow:0 8px 30px rgba(15,23,42,.04)}
    .mp-page *{box-sizing:border-box}
    .mp-top{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
    .mp-top h2{font-size:19px;font-weight:800;margin:0;color:#203044}
    .mp-card{background:#fff;border:1px solid #dfe6ef;border-radius:13px;box-shadow:0 2px 8px rgba(15,23,42,.035)}
    .mp-filters{padding:18px 20px;display:block;background:#f8fafc;border-color:#e4eaf1}
    .mp-filter-controls{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap}
    .mp-filter-controls>.mp-field{flex:0 1 215px}
    .mp-filter-actions{display:flex;gap:8px;align-items:center;flex-shrink:0}
    .mp-filter-controls>.mp-filter-search{margin-left:auto;flex:1 1 245px;max-width:365px}
    .mp-field{display:grid;gap:5px;min-width:0}
    .mp-label{font-size:10px;font-weight:800;letter-spacing:.035em;color:#566780}
    .mp-input,.mp-select{height:38px;width:100%;min-width:0;border:1px solid #dbe2ea;border-radius:10px;background:#fff;padding:0 12px;font:inherit;font-size:12px;color:#334155;outline:none}
    .mp-input:focus,.mp-select:focus,.mp-textarea:focus{border-color:#5f9278;box-shadow:0 0 0 3px rgba(95,146,120,.12)}
    .mp-input::placeholder{color:#9aa8bb;font-weight:400}
    .mp-btn{border:1px solid transparent;height:38px;border-radius:10px;padding:0 15px;background:#f0f4f1;color:#345747;font:inherit;font-size:12px;font-weight:700;display:inline-flex;gap:7px;align-items:center;justify-content:center;text-decoration:none;cursor:pointer;white-space:nowrap}
    .mp-btn:hover{filter:brightness(.96);text-decoration:none}
    .mp-btn-primary{color:#fff;background:#5f9278;border-color:#5f9278}
    .mp-btn-outline{color:#42594e;border-color:#dce6e0;background:#fff}
    .mp-btn-muted{background:#f4f6f5;color:#56665e}
    .mp-btn-icon{height:30px;min-width:30px;padding:0 7px;background:#fff;border:1px solid #dce4df;color:#617568}
    .mp-summary{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin:0 1px}
    .mp-summary-text{color:#334155;font-size:12px;font-weight:700}
    .mp-summary-right{display:flex;align-items:center;gap:8px}
    .mp-summary-right .mp-select{height:36px;width:82px;padding:0 10px;font-weight:700}
    .mp-table-scroll{width:100%;overflow-x:auto}
    .mp-table{border-collapse:collapse;width:100%;min-width:710px;table-layout:fixed}
    .mp-table th{background:#f9fafb;text-align:center;font-size:11px;letter-spacing:.035em;font-weight:800;color:#667085;padding:13px 14px;border-bottom:1px solid #dfe6ef}
    .mp-table thead th:not(:last-child){border-right:1px solid #dfe6ef}
    .mp-table tbody tr:not(.mp-limit-row):not(.mp-history-row) td:not(:last-child){border-right:1px solid #dfe6ef}
    .mp-table th:nth-child(2),.mp-table td:nth-child(2){width:185px;text-align:center}
    .mp-table th:nth-child(3),.mp-table td:nth-child(3){width:135px}
    .mp-table td:nth-child(3){text-align:center}
    .mp-table td{padding:12px 14px;border-bottom:1px solid #dfe6ef;vertical-align:middle;font-size:12px;color:#334155}
    .mp-table tbody:last-of-type tr:last-child td{border-bottom:0}
    .mp-model-row{background:#fff}
    .mp-model-row:hover{background:#f8fbf9}
    .mp-model-row{cursor:pointer}
    .mp-model-row .mp-inline-trigger,.mp-model-row button,.mp-model-row summary,.mp-model-row input{cursor:pointer}
    .mp-model-row .mp-inline-form input{cursor:text}
    .mp-model-row:focus-visible{outline:2px solid #5f9278;outline-offset:-2px}
    .mp-model-wrap{display:flex;align-items:center;gap:9px;min-width:0}
    .mp-caret{width:32px;height:32px;border:1px solid #c5d5ca;border-radius:50%;background:#fff;color:#4a8063;cursor:pointer;flex-shrink:0;display:grid;place-items:center}
    .mp-caret:hover{background:#f2f7f3;border-color:#91b19d}
    .mp-caret .mp-chevron{width:16px;height:16px;stroke-width:1.7;transition:transform .15s ease}
    .mp-group[data-expanded="true"] .mp-caret .mp-chevron{transform:rotate(90deg)}
    .mp-model-title{font-weight:800;color:#26392d;font-size:13px;overflow-wrap:anywhere}
    .mp-crumb{font-size:11px;color:#536678;margin-top:4px}
    .mp-product-row{background:#fcfdfc}
    .mp-product-row:hover{background:#f7faf8}
    .mp-prod-name{padding-left:38px;display:flex;gap:9px;align-items:center;min-width:0;overflow-wrap:anywhere}
    .mp-prod-name .mp-hierarchy-icon{color:#72877a;display:inline-block;width:17px;height:17px;stroke-width:1.7;flex-shrink:0}
    .mp-limit{font-weight:800;color:#2e7350;font-variant-numeric:tabular-nums}
    .mp-limit-empty{color:#6b786e;font-size:11px}
    .mp-actions{display:flex;align-items:center;justify-content:center;gap:6px}
    .mp-actions form{margin:0}
    .mp-delete-button{display:inline-grid;place-items:center;cursor:pointer;color:#627a6c}
    .mp-delete-button .mp-delete-icon{width:17px;height:17px;stroke-width:1.7;flex-shrink:0}
    .mp-delete-button:hover{background:#f4f8f5;border-color:#a9c2b3;color:#4b6d59}
    /* SweetAlert seragam dengan konfirmasi penghapusan Plant/Line. */
    .swal-plant-popup{width:320px!important;padding:20px!important;border-radius:18px!important;box-shadow:0 20px 60px rgba(0,0,0,.18)!important}
    .swal-plant-popup .swal2-icon{width:55px!important;height:55px!important;margin:0 auto 12px!important}
    .swal-plant-title{font-size:18px!important;font-weight:800!important;color:#172033!important}
    .swal-plant-text{font-size:12px!important;color:#64748b!important}
    .swal-plant-popup .swal2-actions{gap:10px!important;margin-top:18px!important}
    .swal-plant-confirm{background:#dc5b68!important;color:white!important;border:none!important;border-radius:9px!important;height:36px!important;padding:0 18px!important;font-size:12px!important;font-weight:700!important;cursor:pointer!important}
    .swal-plant-confirm:hover{background:#c94d5a!important}
    .swal-plant-cancel{background:#f1f5f9!important;color:#475569!important;border:none!important;border-radius:9px!important;height:36px!important;padding:0 18px!important;font-size:12px!important;font-weight:700!important;cursor:pointer!important}
    .swal-plant-cancel:hover{background:#e2e8f0!important}
    .mp-inline-trigger{font:inherit;color:inherit;border:0;background:transparent;padding:3px 5px;margin:-3px -5px;cursor:text;text-align:left;border-radius:5px;max-width:100%}
    .mp-inline-trigger:hover,.mp-inline-trigger:focus-visible{text-decoration:underline;text-underline-offset:3px;text-decoration-thickness:1px;background:transparent}
    .mp-inline-trigger.mp-model-title{font-size:13px;font-weight:800;color:#26392d}
    .mp-inline-trigger.mp-product-title{font-size:12px;font-weight:500;color:#334155;overflow-wrap:anywhere}
    .mp-inline-trigger.mp-limit,.mp-inline-trigger.mp-limit-empty{cursor:text}
    .mp-inline-form{display:none;align-items:center;gap:5px;min-width:0;max-width:100%}
    .mp-inline-form.is-open{display:flex}
    .mp-inline-form .mp-input{height:32px;min-width:0;padding:0 8px}
    .mp-model-edit{flex-wrap:wrap}
    .mp-model-edit .mp-input{width:min(220px,100%);flex:1 1 135px}
    .mp-product-edit .mp-input{width:min(290px,100%)}
    .mp-limit-edit .mp-input{width:76px;text-align:center}
    .mp-inline-form .mp-btn-icon{height:30px;min-width:30px}
    .mp-inline-form .mp-inline-save{background:#397e58;border-color:#397e58;color:#fff}
    .mp-inline-form .mp-inline-save:hover{background:#2b6948;border-color:#2b6948;filter:none}
    .mp-inline-form .mp-inline-cancel{background:#fff0f0;border-color:#d8a3a3;color:#a73131}
    .mp-inline-form .mp-inline-cancel:hover{background:#fbe1e1;border-color:#c77d7d;filter:none}
    .mp-limit-row td{padding:0 14px 11px!important;background:#fcfdfc}
    .mp-limit-fields{margin:0 0 0 37px;padding:11px;border:1px solid #cbd9ce;border-radius:9px;display:flex;gap:9px;align-items:end;flex-wrap:wrap}
    .mp-limit-fields .mp-field{min-width:145px;flex:0 1 190px}
    .mp-limit-fields .mp-note-field{flex:1 1 190px}
    .mp-limit-fields .mp-input{height:34px}
    .mp-limit-fields .mp-btn{height:34px}
    .mp-inline-trigger:focus-visible,.mp-btn:focus-visible{outline:2px solid #5f9278;outline-offset:2px}
    .mp-history-row td{padding:0 14px 12px!important;background:#f9fcfa}
    .mp-history{margin:0 0 0 37px;padding:9px 11px;border:1px solid #cbd9ce;border-radius:9px}
    .mp-history h5{font-size:12px;margin:0 0 6px;font-weight:800;color:#587663}
    .mp-history-line{padding:7px 0;display:flex;gap:12px;align-items:center;justify-content:space-between;border-top:1px solid #d5e0d6;flex-wrap:wrap;color:#3b5847;font-size:11px}
    .mp-history-line strong{color:#2b704e;font-size:12px}
    .mp-nodata{text-align:center!important;padding:34px!important;color:#8b988f}
    .mp-pagination{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;padding:12px 14px;border-top:1px solid #dfe6ef;color:#52627a;font-size:12px}
    .mp-pages{display:flex;gap:4px;align-items:center}
    .mp-page-link{display:grid;place-items:center;min-width:31px;height:31px;padding:0 7px;border-radius:7px;border:1px solid #e5eae5;color:#526c5e;text-decoration:none;background:#fff}
    .mp-page-link.active{background:#5f9278;color:#fff;border-color:#5f9278}
    .mp-page-link.disabled{opacity:.35;pointer-events:none}
    .mp-alert{padding:11px 13px;border-radius:10px;border:1px solid #d4e7da;background:#f0f9f3;color:#347451}
    .mp-alert.error{border-color:#efcccc;background:#fff6f6;color:#a54444}
    .mp-overlay{position:fixed;inset:0;z-index:10000;background:rgba(18,32,26,.5);display:none;align-items:center;justify-content:center;padding:15px}
    .mp-overlay.is-open{display:flex}
    .mp-modal{background:#fff;border:1px solid #e1eae4;border-radius:15px;width:100%;max-width:465px;max-height:90vh;overflow-y:auto;box-shadow:0 25px 75px rgba(0,0,0,.15)}
    .mp-modal-head{padding:16px 19px;border-bottom:1px solid #edf1ed;display:flex;justify-content:space-between;align-items:center;gap:12px}
    .mp-modal-title{font-weight:800;font-size:16px;margin:0;color:#23432d}
    .mp-modal-body{padding:16px 19px;display:grid;gap:13px}
    .mp-textarea{width:100%;min-height:75px;border:1px solid #dbe3de;border-radius:9px;padding:9px 11px;font:inherit;resize:vertical;outline:none}
    .mp-modal-foot{padding:13px 19px;border-top:1px solid #edf1ed;display:flex;justify-content:flex-end;gap:8px}
    .mp-modal .mp-field{gap:6px}
    .mp-hint{font-size:11px;color:#4e6556}
    body.mp-modal-active{overflow:hidden}
    .mp-mobile-only{display:none}
    [hidden]{display:none!important}
    @media(max-width:840px){.mp-page{padding:20px}.mp-filter-controls>.mp-field{flex:1 1 170px}.mp-filter-controls>.mp-filter-search{flex:1 1 100%;max-width:none}}
    @media(max-width:550px){.mp-page{padding:14px;gap:15px}.mp-filters{padding:13px}.mp-filter-controls{gap:10px}.mp-filter-controls>.mp-field{flex:1 1 115px}.mp-filter-actions{order:2}.mp-filter-controls>.mp-filter-search{order:3;flex-basis:100%}.mp-limit-fields{margin-left:0}.mp-limit-fields .mp-field{flex:1 1 100%}.mp-inline-form .mp-input{max-width:170px}.mp-top .mp-btn{width:auto}.mp-history{margin-left:0}.mp-table{min-width:580px}.mp-table th,.mp-table td{padding:9px 10px}.mp-prod-name{padding-left:21px}.mp-pagination{justify-content:center;text-align:center}}
</style>

<div class="mp-page" id="mpCatalog">
    <div class="mp-top">
        <h2>Daftar Model Box</h2>
        <button type="button" class="mp-btn mp-btn-primary" data-open-modal="mp-add-model">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah Model
        </button>
    </div>

    @if($errors->any()) <div class="mp-alert error" role="alert">{{ $errors->first() }}</div> @endif

    <form method="GET" action="{{ $catalogRoute }}" id="mpFilters" class="mp-card mp-filters">
        <div class="mp-filter-controls">
            <div class="mp-field">
                <label class="mp-label" for="mpPlant">PLANT</label>
                <select class="mp-select" id="mpPlant" name="plant_id">
                    <option value="">Semua Plant</option>
                    @foreach($plants as $plant)
                        <option value="{{ $plant->id }}" @selected((string)$selectedPlantId === (string)$plant->id)>{{ $plant->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mp-field">
                <label class="mp-label" for="mpLine">LINE</label>
                <select class="mp-select" id="mpLine" name="line_id">
                    <option value="">Semua Line</option>
                    @foreach($lines as $line)
                        <option value="{{ $line->id }}" data-plant="{{ $line->plant_id }}"
                            @selected((string)$selectedLineId === (string)$line->id)>{{ $line->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mp-filter-actions">
                <button class="mp-btn mp-btn-primary" type="submit" title="Terapkan filter"><i class="fa-solid fa-filter" aria-hidden="true"></i> Filter</button>
                @if($selectedPlantId || $selectedLineId)
                    <a class="mp-btn mp-btn-outline" href="{{ route('omd.master.model-product', ['q' => $search, 'per_page' => $perPage]) }}" title="Reset Plant dan Line"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i> Reset</a>
                @endif
            </div>
            <div class="mp-field mp-filter-search">
                <label class="mp-label" for="mpSearch">PENCARIAN</label>
                <input class="mp-input" id="mpSearch" type="search" name="q" maxlength="150"
                    placeholder="Cari model / produk..." value="{{ $search }}" autocomplete="off">
            </div>
        </div>
        <input type="hidden" name="per_page" value="{{ $perPage }}" id="mpFilterPerPage">
    </form>

    <div class="mp-summary">
        <div class="mp-summary-right">
            <select class="mp-select" id="mpPerPage" aria-label="Jumlah model per halaman">
                @foreach([10,25,50,100] as $count)
                    <option value="{{ $count }}" @selected($perPage === $count)>{{ $count }}</option>
                @endforeach
            </select>
        </div>
        <span class="mp-summary-text"><strong>{{ number_format($models->total()) }}</strong> Model</span>
    </div>

    <div class="mp-card">
        <div class="mp-table-scroll">
            <table class="mp-table" aria-label="Daftar Model Box, Produk dan Scrap Limit">
                <thead><tr>
                    <th scope="col">MODEL / PRODUK</th>
                    <th scope="col">SCRAP LIMIT</th>
                    <th scope="col">AKSI</th>
                </tr></thead>
                @forelse($models as $model)
                    @php
                        $plantName = $model->line?->plant?->name ?? '-';
                        $lineName = $model->line?->name ?? '-';
                        $searchMatchesModel = $search === '' || stripos($model->model, $search) !== false
                            || stripos($lineName, $search) !== false || stripos($plantName, $search) !== false;
                        $visibleProducts = $searchMatchesModel ? $model->products : $model->products->filter(
                            fn ($product) => stripos($product->name, $search) !== false
                        );
                        $modelOpen = $search !== '';
                    @endphp
                    <tbody id="model-{{ $model->id }}" class="mp-group" data-model-id="{{ $model->id }}" data-expanded="{{ $modelOpen ? 'true' : 'false' }}">
                        <tr class="mp-model-row" tabindex="0" title="Klik area baris untuk melihat produk" data-toggle-row="model">
                            <td>
                                <div class="mp-model-wrap">
                                    <button type="button" class="mp-caret js-toggle-model" aria-label="{{ $modelOpen ? 'Tutup' : 'Lihat produk' }} {{ $model->model }}"
                                        aria-expanded="{{ $modelOpen ? 'true' : 'false' }}">
                                        <svg class="mp-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m9 5 7 7-7 7" />
                                        </svg>
                                    </button>
                                    <div style="min-width:0;flex:1">
                                        <button type="button" class="mp-inline-trigger mp-model-title js-start-edit" data-edit="model" title="Klik untuk edit Model">{{ $model->model }}</button>
                                        <form class="mp-inline-form mp-model-edit js-inline-form" method="POST" action="{{ route('omd.master.model.update', $model) }}" data-edit-form="model">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="line_id" value="{{ $model->line_id }}">
                                            <input class="mp-input js-inline-input" name="model" maxlength="100" required value="{{ $model->model }}" data-original="{{ $model->model }}" aria-label="Nama Model">
                                            <button type="submit" class="mp-btn mp-btn-primary mp-btn-icon mp-inline-save" title="Simpan Model" aria-label="Simpan Model"><i class="fa-solid fa-check"></i></button>
                                            <button type="button" class="mp-btn mp-btn-icon mp-inline-cancel js-cancel-inline" title="Batal" aria-label="Batal"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                        <div class="mp-crumb">{{ $plantName }} / {{ $lineName }} · {{ $model->products->count() }} produk</div>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="mp-actions">
                                    <button type="button" class="mp-btn mp-btn-icon js-add-product"
                                        data-model-id="{{ $model->id }}" data-model-name="{{ $model->model }}"
                                        title="Tambah Produk" aria-label="Tambah Produk ke {{ $model->model }}">
                                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                    </button>
                                    <form method="POST" action="{{ route('omd.master.model.destroy', $model) }}" class="js-delete-form"
                                        data-delete-type="Model" data-confirm="Hapus model {{ $model->model }} beserta produknya? Pastikan tidak ada data transaksi yang masih menggunakan model ini.">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="mp-btn mp-btn-icon mp-delete-button" title="Hapus Model" aria-label="Hapus Model {{ $model->model }}">
                                            <svg class="mp-delete-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4.5h10a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3H9L2 12Z"/><path d="m12 9 6 6m0-6-6 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @forelse($visibleProducts as $product)
                            @php
                                $activeLimit = $product->scrapLimits->first(fn ($limit) => $limit->effective_from->lte($activeAt)
                                    && ($limit->effective_to === null || $limit->effective_to->gte($activeAt)));
                            @endphp
                            <tr class="mp-product-row" @if(!$modelOpen) hidden @endif>
                                <td>
                                    <div class="mp-prod-name"><svg class="mp-hierarchy-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 4v7a4 4 0 0 0 4 4h10" />
                                        <path d="m15 11 4 4-4 4" />
                                    </svg>
                                        <div style="min-width:0;flex:1">
                                            <button type="button" class="mp-inline-trigger mp-product-title js-start-edit" data-edit="product" title="Klik untuk edit Produk">{{ $product->name }}</button>
                                            <form class="mp-inline-form mp-product-edit js-inline-form" data-edit-form="product" method="POST" action="{{ route('omd.master.product.update', $product) }}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="master_model_id" value="{{ $model->id }}">
                                                <input class="mp-input js-inline-input" name="name" maxlength="150" required value="{{ $product->name }}" data-original="{{ $product->name }}" aria-label="Nama Produk">
                                                <button type="submit" class="mp-btn mp-btn-primary mp-btn-icon mp-inline-save" title="Simpan Produk" aria-label="Simpan Produk"><i class="fa-solid fa-check"></i></button>
                                                <button type="button" class="mp-btn mp-btn-icon mp-inline-cancel js-cancel-inline" title="Batal" aria-label="Batal"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="mp-inline-trigger js-start-edit js-limit-value {{ $activeLimit ? 'mp-limit' : 'mp-limit-empty' }}" data-edit="limit" title="Klik untuk edit Scrap Limit">{{ $activeLimit ? number_format($activeLimit->limit_qty) : 'Belum diatur' }}</button>
                                    <form class="mp-inline-form mp-limit-edit js-inline-form" id="mpLimitForm{{ $product->id }}" data-edit-form="limit" method="POST" action="{{ route('omd.scrap-limits.store') }}">
                                        @csrf
                                        <input type="hidden" name="master_model_id" value="{{ $model->id }}">
                                        <input type="hidden" name="return_to" value="model-product">
                                        <input type="hidden" name="return_plant" value="{{ $selectedPlantId }}">
                                        <input type="hidden" name="return_line_id" value="{{ $selectedLineId }}">
                                        <input type="hidden" name="return_q" value="{{ $search }}">
                                        <input type="hidden" name="return_per_page" value="{{ $perPage }}">
                                        <input class="mp-input js-inline-input" type="number" inputmode="numeric" name="limits[{{ $product->id }}]" min="0" step="1" required value="{{ $activeLimit?->limit_qty }}" data-original="{{ $activeLimit?->limit_qty }}" aria-label="Qty Scrap Limit {{ $product->name }}">
                                        <button type="submit" class="mp-btn mp-btn-primary mp-btn-icon mp-inline-save" title="Simpan Limit" aria-label="Simpan Limit"><i class="fa-solid fa-check"></i></button>
                                        <button type="button" class="mp-btn mp-btn-icon mp-inline-cancel js-cancel-inline" title="Batal" aria-label="Batal"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <div class="mp-actions">
                                        <button type="button" class="mp-btn mp-btn-icon js-history" aria-expanded="false"
                                            title="Riwayat Scrap Limit" aria-label="Riwayat Scrap Limit {{ $product->name }}">
                                            <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                                        </button>
                                        <form method="POST" action="{{ route('omd.master.product.destroy', $product) }}" class="js-delete-form"
                                            data-delete-type="Produk" data-confirm="Hapus produk {{ $product->name }}? Pastikan produk ini tidak lagi digunakan transaksi.">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="mp-btn mp-btn-icon mp-delete-button" title="Hapus Produk" aria-label="Hapus Produk {{ $product->name }}">
                                                <svg class="mp-delete-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4.5h10a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3H9L2 12Z"/><path d="m12 9 6 6m0-6-6 6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr class="mp-limit-row" hidden>
                                <td colspan="3">
                                    <div class="mp-limit-fields">
                                        <div class="mp-field">
                                            <label class="mp-label" for="mpDate{{ $product->id }}">MULAI BERLAKU</label>
                                            <input class="mp-input" type="date" id="mpDate{{ $product->id }}" name="effective_from" form="mpLimitForm{{ $product->id }}" value="{{ now()->toDateString() }}" required>
                                        </div>
                                        <div class="mp-field mp-note-field">
                                            <label class="mp-label" for="mpNote{{ $product->id }}">CATATAN (OPSIONAL)</label>
                                            <input class="mp-input" type="text" id="mpNote{{ $product->id }}" name="note" form="mpLimitForm{{ $product->id }}" maxlength="1000" placeholder="Catatan...">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="mp-history-row" hidden>
                                <td colspan="3">
                                    <div class="mp-history">
                                        <h5>Riwayat Scrap Limit · {{ $product->name }}</h5>
                                        @forelse($product->scrapLimits as $entry)
                                            <div class="mp-history-line">
                                                <strong>{{ number_format($entry->limit_qty) }}</strong>
                                                <span>
                                                    @if($entry->source === \App\Models\ProductScrapLimit::SOURCE_SEED)
                                                        Baseline awal
                                                    @else
                                                        {{ $entry->effective_from->format('d/m/Y H:i') }}
                                                        @if($entry->effective_to) — {{ $entry->effective_to->format('d/m/Y H:i') }} @endif
                                                    @endif
                                                </span>
                                                <span>{{ $entry->source === \App\Models\ProductScrapLimit::SOURCE_SEED ? 'Seeder' : ($entry->creator?->name ?? 'OMD') }}</span>
                                                @if($entry->note)<span title="Catatan" style="width:100%;text-align:right">{{ $entry->note }}</span>@endif
                                            </div>
                                        @empty
                                            <div class="mp-hint">Belum ada perubahan limit tercatat.</div>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="mp-product-row" @if(!$modelOpen) hidden @endif><td colspan="3" class="mp-nodata">Belum ada produk. Klik tombol + pada model untuk menambahkan.</td></tr>
                        @endforelse
                    </tbody>
                @empty
                    <tbody><tr><td colspan="3" class="mp-nodata">Model atau produk tidak ditemukan. Coba kata kunci atau filter lain.</td></tr></tbody>
                @endforelse
            </table>
        </div>
        @if($models->hasPages())
            <div class="mp-pagination">
                <span>Menampilkan {{ $models->firstItem() }}–{{ $models->lastItem() }} dari {{ $models->total() }} model</span>
                <div class="mp-pages">
                    <a class="mp-page-link {{ $models->onFirstPage() ? 'disabled' : '' }}" href="{{ $models->previousPageUrl() ?: '#' }}" aria-label="Sebelumnya">‹</a>
                    @foreach($models->getUrlRange(max(1, $models->currentPage()-2), min($models->lastPage(), $models->currentPage()+2)) as $page => $url)
                        <a class="mp-page-link {{ $models->currentPage() === $page ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                    @endforeach
                    <a class="mp-page-link {{ !$models->hasMorePages() ? 'disabled' : '' }}" href="{{ $models->nextPageUrl() ?: '#' }}" aria-label="Selanjutnya">›</a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal CRUD tetap menggunakan endpoint aplikasi lama. --}}
<div class="mp-overlay" id="mp-add-model" aria-hidden="true">
    <div class="mp-modal" role="dialog" aria-modal="true" aria-labelledby="mpAddModelTitle">
        <div class="mp-modal-head"><div><h3 class="mp-modal-title" id="mpAddModelTitle">Tambah Model Box</h3></div><button type="button" class="mp-btn mp-btn-icon js-close-modal" aria-label="Tutup">×</button></div>
        <form method="POST" action="{{ route('omd.master.model.store') }}">@csrf
            <div class="mp-modal-body">
                <div class="mp-field"><label class="mp-label" for="mpNewLine">LINE</label>
                    <select class="mp-select" id="mpNewLine" name="line_id" required>
                        @foreach($lines as $line)<option value="{{ $line->id }}" @selected((string)$defaultAddLineId === (string)$line->id)>{{ $line->plant?->name }} / {{ $line->name }}</option>@endforeach
                    </select>
                </div>
                <div class="mp-field"><label class="mp-label" for="mpNewModel">NAMA MODEL</label><input class="mp-input" id="mpNewModel" name="model" maxlength="100" placeholder="Nama Model Box" required></div>
            </div>
            <div class="mp-modal-foot"><button type="button" class="mp-btn mp-btn-muted js-close-modal">Batal</button><button type="submit" class="mp-btn mp-btn-primary">Simpan</button></div>
        </form>
    </div>
</div>
<div class="mp-overlay" id="mp-add-product" aria-hidden="true">
    <div class="mp-modal" role="dialog" aria-modal="true" aria-labelledby="mpAddProductTitle">
        <div class="mp-modal-head"><div><h3 class="mp-modal-title" id="mpAddProductTitle">Tambah Produk</h3></div><button type="button" class="mp-btn mp-btn-icon js-close-modal" aria-label="Tutup">×</button></div>
        <form method="POST" action="{{ route('omd.master.product.store') }}">@csrf
            <input type="hidden" name="master_model_id" id="mpAddProductModel">
            <div class="mp-modal-body"><div class="mp-field"><label class="mp-label" for="mpNewProduct">NAMA PRODUK</label><input class="mp-input" id="mpNewProduct" name="name" maxlength="150" required placeholder="Nama Produk"></div></div>
            <div class="mp-modal-foot"><button type="button" class="mp-btn mp-btn-muted js-close-modal">Batal</button><button type="submit" class="mp-btn mp-btn-primary">Simpan</button></div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    const root = document.getElementById('mpCatalog');
    const search = document.getElementById('mpSearch');
    const filters = document.getElementById('mpFilters');
    const plant = document.getElementById('mpPlant');
    const line = document.getElementById('mpLine');
    const perPage = document.getElementById('mpPerPage');
    const modalMap = document.querySelectorAll('.mp-overlay');
    let searchTimer;
    let lastFocus = null;
    let activeEditor = null;

    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        lastFocus = document.activeElement;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('mp-modal-active');
        const focusable = modal.querySelector('input:not([type=hidden]), select, textarea, button');
        if (focusable) focusable.focus();
    }
    function closeModal(modal) {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        if (!document.querySelector('.mp-overlay.is-open')) document.body.classList.remove('mp-modal-active');
        if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    }
    function updateLineOptions() {
        for (const option of line.options) {
            if (!option.value) continue;
            option.hidden = !!plant.value && option.dataset.plant !== plant.value;
            option.disabled = option.hidden;
        }
        if (line.selectedOptions[0] && line.selectedOptions[0].disabled) line.value = '';
    }
    function cancelEditor() {
        if (!activeEditor) return;
        const {form, trigger, detailsRow} = activeEditor;
        form.classList.remove('is-open');
        trigger.hidden = false;
        if (detailsRow) detailsRow.hidden = true;
        form.reset();
        activeEditor = null;
    }
    function startEditor(trigger) {
        if (activeEditor) cancelEditor();
        const type = trigger.dataset.edit;
        const row = trigger.closest('tr');
        const form = row.querySelector('.js-inline-form[data-edit-form="' + type + '"]');
        if (!form) return;
        const detailsRow = type === 'limit' ? row.nextElementSibling : null;
        trigger.hidden = true;
        form.classList.add('is-open');
        if (detailsRow) detailsRow.hidden = false;
        activeEditor = {form, trigger, detailsRow};
        const input = form.querySelector('.js-inline-input');
        if (input) {
            input.focus({preventScroll: true});
            // Tempatkan caret di akhir, tanpa menyeleksi isi awal.
            if (input.type !== 'number' && typeof input.setSelectionRange === 'function') {
                input.setSelectionRange(input.value.length, input.value.length);
            }
        }
    }
    updateLineOptions();
    // Plant dan Line baru diterapkan setelah tombol Filter ditekan.
    plant.addEventListener('change', updateLineOptions);
    filters.addEventListener('submit', () => clearTimeout(searchTimer));
    function navigateCatalog(overrides) {
        // Pencarian dan jumlah data memakai filter yang sudah diterapkan, bukan pilihan draft.
        const url = new URL(filters.action, window.location.href);
        const current = new URLSearchParams(window.location.search);
        for (const key of ['plant_id', 'line_id', 'q', 'per_page']) {
            const value = current.get(key);
            if (value) url.searchParams.set(key, value);
        }
        for (const [key, value] of Object.entries(overrides)) {
            if (value === '' || value === null) url.searchParams.delete(key);
            else url.searchParams.set(key, value);
        }
        window.location.assign(url.toString());
    }
    search.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => navigateCatalog({q: search.value.trim()}), 650);
    });
    search.addEventListener('keydown', event => {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(searchTimer);
            navigateCatalog({q: search.value.trim()});
        }
    });
    perPage.addEventListener('change', () => navigateCatalog({per_page: perPage.value}));
    document.querySelectorAll('[data-open-modal]').forEach(button => button.addEventListener('click', () => openModal(button.dataset.openModal)));
    modalMap.forEach(modal => {
        modal.addEventListener('click', event => {if (event.target === modal || event.target.closest('.js-close-modal')) closeModal(modal);});
    });
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        if (activeEditor) { event.preventDefault(); cancelEditor(); }
        document.querySelectorAll('.mp-overlay.is-open').forEach(closeModal);
    });
    function toggleModel(group) {
        if (!group) return;
        cancelEditor();
        const toggle = group.querySelector('.js-toggle-model');
        const expanded = group.dataset.expanded !== 'true';
        group.dataset.expanded = String(expanded);
        toggle.setAttribute('aria-expanded', String(expanded));
        const modelName = group.querySelector('.mp-model-title').textContent.trim();
        toggle.setAttribute('aria-label', (expanded ? 'Tutup' : 'Lihat produk') + ' ' + modelName);
        for (const row of group.querySelectorAll('.mp-product-row')) row.hidden = !expanded;
        for (const row of group.querySelectorAll('.mp-history-row,.mp-limit-row')) row.hidden = true;
        for (const button of group.querySelectorAll('.js-history')) button.setAttribute('aria-expanded', 'false');
    }
    root.addEventListener('keydown', event => {
        if (event.key !== 'Enter' && event.key !== ' ') return;
        if (!event.target.matches('.mp-model-row')) return;
        event.preventDefault();
        toggleModel(event.target.closest('.mp-group'));
    });
    root.addEventListener('click', event => {
        const trigger = event.target.closest('.js-start-edit');
        if (trigger) {startEditor(trigger);return;}
        const cancel = event.target.closest('.js-cancel-inline');
        if (cancel) {cancelEditor();return;}
        const toggle = event.target.closest('.js-toggle-model');
        if (toggle) {toggleModel(toggle.closest('.mp-group'));return;}
        // Area kosong pada baris Model: buka/tutup. Kontrol form/menu tetap mandiri.
        const modelRow = event.target.closest('.mp-model-row');
        if (modelRow && !event.target.closest('button, a, input, select, textarea, form, details, summary, label, [contenteditable], .mp-inline-form')) {
            toggleModel(modelRow.closest('.mp-group'));
            return;
        }
        const addProduct = event.target.closest('.js-add-product');
        if (addProduct) {
            document.getElementById('mpAddProductModel').value = addProduct.dataset.modelId;
            document.getElementById('mpNewProduct').value = '';
            openModal('mp-add-product');
            return;
        }
        const history = event.target.closest('.js-history');
        if (history) {
            const row = history.closest('tr').nextElementSibling.nextElementSibling;
            row.hidden = !row.hidden;
            history.setAttribute('aria-expanded', String(!row.hidden));
        }
    });
    root.addEventListener('submit', event => {
        const deleteForm = event.target.closest('.js-delete-form');
        if (deleteForm) {
            event.preventDefault();
            const type = deleteForm.dataset.deleteType || 'Data';
            const message = deleteForm.dataset.confirm || 'Data yang dihapus tidak dapat dikembalikan.';
            if (typeof Swal === 'undefined') {
                if (window.confirm(message)) HTMLFormElement.prototype.submit.call(deleteForm);
                return;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Hapus ' + type + '?',
                text: message,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'swal-plant-popup',
                    title: 'swal-plant-title',
                    htmlContainer: 'swal-plant-text',
                    confirmButton: 'swal-plant-confirm',
                    cancelButton: 'swal-plant-cancel'
                }
            }).then(result => {
                if (result.isConfirmed) HTMLFormElement.prototype.submit.call(deleteForm);
            });
            return;
        }
        const form = event.target.closest('.js-inline-form');
        if (!form) return;
        const input = form.querySelector('.js-inline-input');
        if (input && (!input.reportValidity() || (input.type !== 'number' && !input.value.trim()))) {
            event.preventDefault();
            input.focus();
            return;
        }
        // Tanggal berlaku dan catatan terkait menggunakan atribut form HTML5.
        if (form.dataset.editForm === 'limit') {
            const date = document.querySelector('input[form="' + form.id + '"][name="effective_from"]');
            if (!date || !date.value || !date.reportValidity()) {
                event.preventDefault();
                if (date) date.focus();
            }
        }
    });
});
</script>
@endsection
