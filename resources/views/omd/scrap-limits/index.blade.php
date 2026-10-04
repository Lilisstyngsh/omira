@extends('layouts.app')

@section('title', 'Scrap Limit Produk')
@section('header', 'Scrap Limit Produk')

@section('content')
    @php
        $activeAt = function ($product) use ($now) {
            return $product->scrapLimits
                ->first(function ($limit) use ($now) {
                    return $limit->effective_from->lte($now)
                        && ($limit->effective_to === null || $limit->effective_to->gte($now));
                });
        };

        $modelPayload = $models->mapWithKeys(function ($model) use ($activeAt) {
            return [
                (string) $model->id => [
                    'id' => $model->id,
                    'model' => $model->model,
                    'line' => trim(($model->line?->plant?->name ? $model->line->plant->name . ' — ' : '') . ($model->line?->name ?? '-')),
                    'products' => $model->products->map(function ($product) use ($activeAt) {
                        $active = $activeAt($product);

                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'limit' => $active?->limit_qty,
                        ];
                    })->values(),
                ],
            ];
        });
    @endphp

    <style>
        .sl-page { display:grid; gap:16px; }
        .sl-head { display:flex; align-items:flex-end; justify-content:space-between; gap:12px; }
        .sl-head h2 { margin:0; font-size:22px; line-height:1.2; color:#172033; }
        .sl-count { font-size:13px; color:#748095; }
        .sl-toolbar { display:grid; grid-template-columns:minmax(180px,230px) minmax(220px,1fr) auto; gap:10px; align-items:end; padding:14px; border:1px solid #e4e8ef; background:#fff; border-radius:16px; }
        .sl-field { display:grid; gap:6px; }
        .sl-field label { font-size:12px; font-weight:700; color:#5e697b; }
        .sl-field input, .sl-field select, .sl-field textarea { width:100%; border:1px solid #d9dfe8; border-radius:10px; background:#fff; color:#1f2937; font:inherit; outline:none; }
        .sl-field input, .sl-field select { min-height:40px; padding:0 11px; }
        .sl-field textarea { min-height:76px; padding:10px 11px; resize:vertical; }
        .sl-field input:focus, .sl-field select:focus, .sl-field textarea:focus { border-color:#7cb596; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
        .sl-toolbar-actions { display:flex; gap:8px; }
        .sl-btn { min-height:40px; border:0; border-radius:10px; padding:0 14px; display:inline-flex; align-items:center; justify-content:center; gap:7px; font-weight:700; font-size:13px; cursor:pointer; text-decoration:none; white-space:nowrap; }
        .sl-btn-neutral { background:#eef1f5; color:#445065; }
        .sl-btn-soft { background:#eef8f2; color:#237044; }
        .sl-btn-success { background:#22a65a; color:#fff; }
        .sl-btn:hover { filter:brightness(.985); }
        .sl-alert { border:1px solid #d9efe0; background:#f3fbf5; color:#23633b; border-radius:12px; padding:11px 13px; font-size:13px; }
        .sl-alert.error { border-color:#f2d2d2; background:#fff7f7; color:#a63a3a; }
        .sl-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(285px,1fr)); gap:14px; align-items:stretch; }
        .sl-card { min-width:0; border:1px solid #e2e7ee; background:#fff; border-radius:16px; padding:15px; display:flex; flex-direction:column; gap:13px; box-shadow:0 2px 8px rgba(19,33,58,.035); }
        .sl-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; }
        .sl-model { min-width:0; }
        .sl-model-name { font-size:17px; line-height:1.25; font-weight:800; color:#172033; overflow-wrap:anywhere; }
        .sl-model-line { margin-top:3px; font-size:12px; color:#7a8596; overflow-wrap:anywhere; }
        .sl-badge { flex:0 0 auto; background:#f4f6f8; color:#657084; border-radius:999px; padding:5px 8px; font-size:11px; font-weight:700; }
        .sl-product-list { display:grid; border:1px solid #edf0f4; border-radius:12px; overflow:hidden; }
        .sl-product-row { display:grid; grid-template-columns:minmax(0,1fr) auto; gap:10px; align-items:center; padding:9px 10px; background:#fff; }
        .sl-product-row + .sl-product-row { border-top:1px solid #edf0f4; }
        .sl-product-name { font-size:13px; color:#374151; overflow-wrap:anywhere; }
        .sl-limit { min-width:44px; text-align:center; border-radius:8px; padding:5px 8px; background:#ecf8f0; color:#216a40; font-weight:800; font-size:12px; }
        .sl-limit.empty { background:#f3f5f7; color:#8a94a3; font-weight:700; }
        .sl-card-actions { margin-top:auto; display:flex; gap:8px; }
        .sl-card-actions .sl-btn { flex:1; min-width:0; }
        .sl-empty { border:1px dashed #d7dde7; background:#fafbfc; border-radius:16px; padding:30px 16px; text-align:center; color:#7b8798; }
        .sl-modal-backdrop { position:fixed; inset:0; z-index:1050; background:rgba(16,24,40,.42); padding:20px; display:none; align-items:center; justify-content:center; }
        .sl-modal-backdrop.is-open { display:flex; }
        .sl-modal { width:min(620px,100%); max-height:min(88vh,800px); background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 24px 70px rgba(15,23,42,.22); display:flex; flex-direction:column; }
        .sl-modal.wide { width:min(760px,100%); }
        .sl-modal-head { padding:17px 18px; border-bottom:1px solid #edf0f4; display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
        .sl-modal-title { margin:0; font-size:18px; color:#172033; }
        .sl-modal-subtitle { margin-top:3px; color:#7b8798; font-size:12px; }
        .sl-modal-close { border:0; background:#f2f4f7; width:34px; height:34px; border-radius:9px; color:#667085; font-size:20px; cursor:pointer; }
        .sl-modal-body { padding:17px 18px; overflow:auto; }
        .sl-modal-actions { padding:13px 18px 17px; border-top:1px solid #edf0f4; display:flex; justify-content:flex-end; gap:8px; }
        .sl-edit-products { border:1px solid #e6eaf0; border-radius:12px; overflow:hidden; margin-bottom:14px; }
        .sl-edit-row { display:grid; grid-template-columns:minmax(0,1fr) 92px; gap:10px; align-items:center; padding:10px 11px; }
        .sl-edit-row + .sl-edit-row { border-top:1px solid #edf0f4; }
        .sl-edit-name { font-size:13px; font-weight:650; color:#344054; overflow-wrap:anywhere; }
        .sl-edit-row input { width:100%; min-height:38px; border:1px solid #d8dee7; border-radius:9px; padding:0 8px; text-align:center; font-weight:800; outline:none; }
        .sl-edit-row input:focus { border-color:#79b793; box-shadow:0 0 0 3px rgba(34,197,94,.08); }
        .sl-form-grid { display:grid; grid-template-columns:180px minmax(0,1fr); gap:12px; }
        .sl-form-grid .full { grid-column:1 / -1; }
        .sl-history-product { border:1px solid #e7ebf1; border-radius:12px; overflow:hidden; }
        .sl-history-product + .sl-history-product { margin-top:10px; }
        .sl-history-name { padding:9px 11px; background:#f8f9fb; font-size:13px; font-weight:800; color:#303a4b; }
        .sl-history-row { display:grid; grid-template-columns:70px minmax(120px,1fr) minmax(95px,auto); gap:10px; align-items:center; padding:9px 11px; font-size:12px; color:#667085; }
        .sl-history-row + .sl-history-row { border-top:1px solid #edf0f4; }
        .sl-history-qty { font-size:13px; font-weight:800; color:#216a40; }
        .sl-history-source { text-align:right; color:#7f8998; }
        body.sl-modal-open { overflow:hidden; }
        @media (max-width:760px) {
            .sl-toolbar { grid-template-columns:1fr; }
            .sl-toolbar-actions { width:100%; }
            .sl-toolbar-actions .sl-btn { flex:1; }
            .sl-grid { grid-template-columns:repeat(auto-fit,minmax(min(100%,250px),1fr)); }
            .sl-form-grid { grid-template-columns:1fr; }
            .sl-form-grid .full { grid-column:auto; }
            .sl-modal-backdrop { padding:10px; align-items:flex-end; }
            .sl-modal { max-height:92vh; border-radius:18px 18px 12px 12px; }
            .sl-history-row { grid-template-columns:58px 1fr; }
            .sl-history-source { grid-column:1 / -1; text-align:left; }
        }
    </style>

    <div class="sl-page">
        <div class="sl-head">
            <div>
                <h2>Scrap Limit Produk</h2>
                <div class="sl-count">{{ $models->count() }} model</div>
            </div>
        </div>

        @if (session('success'))
            <div class="sl-alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="sl-alert error">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('omd.scrap-limits.index') }}" class="sl-toolbar">
            <div class="sl-field">
                <label for="line_id">Line</label>
                <select id="line_id" name="line_id">
                    <option value="all" @selected($lineFilter === 'all')>Semua Line</option>
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}" @selected((string) $lineId === (string) $line->id)>
                            {{ $line->plant?->name ? $line->plant->name . ' — ' : '' }}{{ $line->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sl-field">
                <label for="q">Cari Model / Produk</label>
                <input id="q" name="q" type="search" value="{{ $search }}" placeholder="Model atau produk...">
            </div>

            <div class="sl-toolbar-actions">
                <button class="sl-btn sl-btn-success" type="submit">Terapkan</button>
                @if ($search !== '' || $lineFilter !== 'all')
                    <a class="sl-btn sl-btn-neutral" href="{{ route('omd.scrap-limits.index') }}">Reset</a>
                @endif
            </div>
        </form>

        @if ($models->isEmpty())
            <div class="sl-empty">Model atau produk tidak ditemukan.</div>
        @else
            <div class="sl-grid">
                @foreach ($models as $model)
                    @php
                        $lineLabel = trim(($model->line?->plant?->name ? $model->line->plant->name . ' — ' : '') . ($model->line?->name ?? '-'));
                    @endphp
                    <article class="sl-card">
                        <div class="sl-card-head">
                            <div class="sl-model">
                                <div class="sl-model-name">{{ $model->model }}</div>
                                <div class="sl-model-line">{{ $lineLabel }}</div>
                            </div>
                            <span class="sl-badge">{{ $model->products->count() }} Produk</span>
                        </div>

                        <div class="sl-product-list">
                            @forelse ($model->products as $product)
                                @php($activeLimit = $activeAt($product))
                                <div class="sl-product-row">
                                    <div class="sl-product-name">{{ $product->name }}</div>
                                    <div class="sl-limit {{ $activeLimit ? '' : 'empty' }}">
                                        {{ $activeLimit ? number_format($activeLimit->limit_qty) : '—' }}
                                    </div>
                                </div>
                            @empty
                                <div class="sl-product-row">
                                    <div class="sl-product-name">Belum ada produk aktif.</div>
                                </div>
                            @endforelse
                        </div>

                        <div class="sl-card-actions">
                            <button type="button" class="sl-btn sl-btn-success js-open-limit" data-model-id="{{ $model->id }}" @disabled($model->products->isEmpty())>
                                Atur Limit
                            </button>
                            <button type="button" class="sl-btn sl-btn-neutral js-open-history" data-model-id="{{ $model->id }}" @disabled($model->products->isEmpty())>
                                Riwayat
                            </button>
                        </div>
                    </article>

                    <template id="sl-history-{{ $model->id }}">
                        @foreach ($model->products as $product)
                            <div class="sl-history-product">
                                <div class="sl-history-name">{{ $product->name }}</div>
                                @forelse ($product->scrapLimits as $limit)
                                    <div class="sl-history-row">
                                        <div class="sl-history-qty">{{ number_format($limit->limit_qty) }}</div>
                                        <div>
                                            @if ($limit->source === \App\Models\ProductScrapLimit::SOURCE_SEED)
                                                Baseline awal
                                            @else
                                                {{ $limit->effective_from->format('d/m/Y H:i') }}
                                                @if ($limit->effective_to)
                                                    – {{ $limit->effective_to->format('d/m/Y H:i') }}
                                                @endif
                                            @endif
                                        </div>
                                        <div class="sl-history-source">
                                            {{ $limit->source === \App\Models\ProductScrapLimit::SOURCE_SEED ? 'Seeder' : ($limit->creator?->name ?? 'OMD') }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="sl-history-row">
                                        <div class="sl-history-qty">—</div>
                                        <div>Belum ada riwayat.</div>
                                        <div></div>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </template>
                @endforeach
            </div>
        @endif
    </div>

    <div class="sl-modal-backdrop" id="sl-limit-modal" aria-hidden="true">
        <div class="sl-modal" role="dialog" aria-modal="true" aria-labelledby="sl-limit-title">
            <div class="sl-modal-head">
                <div>
                    <h3 class="sl-modal-title" id="sl-limit-title">Atur Scrap Limit</h3>
                    <div class="sl-modal-subtitle" id="sl-limit-context"></div>
                </div>
                <button type="button" class="sl-modal-close js-close-modal" aria-label="Tutup">×</button>
            </div>

            <form method="POST" action="{{ route('omd.scrap-limits.store') }}" id="sl-limit-form">
                @csrf
                <input type="hidden" name="master_model_id" id="sl-model-id" value="{{ old('master_model_id') }}">
                <input type="hidden" name="return_line" value="{{ $lineFilter }}">
                <input type="hidden" name="return_q" value="{{ $search }}">

                <div class="sl-modal-body">
                    <div class="sl-edit-products" id="sl-edit-products"></div>

                    <div class="sl-form-grid">
                        <div class="sl-field">
                            <label for="sl-effective-from">Mulai Berlaku</label>
                            <input id="sl-effective-from" name="effective_from" type="date" value="{{ old('effective_from', $today) }}" required>
                        </div>
                        <div class="sl-field full">
                            <label for="sl-note">Catatan <span style="font-weight:500;color:#98a2b3;">(opsional)</span></label>
                            <textarea id="sl-note" name="note" placeholder="Catatan perubahan...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="sl-modal-actions">
                    <button type="button" class="sl-btn sl-btn-neutral js-close-modal">Batal</button>
                    <button type="submit" class="sl-btn sl-btn-success">Simpan Limit</button>
                </div>
            </form>
        </div>
    </div>

    <div class="sl-modal-backdrop" id="sl-history-modal" aria-hidden="true">
        <div class="sl-modal wide" role="dialog" aria-modal="true" aria-labelledby="sl-history-title">
            <div class="sl-modal-head">
                <div>
                    <h3 class="sl-modal-title" id="sl-history-title">Riwayat Scrap Limit</h3>
                    <div class="sl-modal-subtitle" id="sl-history-context"></div>
                </div>
                <button type="button" class="sl-modal-close js-close-modal" aria-label="Tutup">×</button>
            </div>
            <div class="sl-modal-body" id="sl-history-content"></div>
            <div class="sl-modal-actions">
                <button type="button" class="sl-btn sl-btn-neutral js-close-modal">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const models = @json($modelPayload);
            const oldModelId = @json((string) old('master_model_id', ''));
            const oldLimits = @json(old('limits', []));
            const hasErrors = @json($errors->any());

            const limitModal = document.getElementById('sl-limit-modal');
            const historyModal = document.getElementById('sl-history-modal');
            const limitContext = document.getElementById('sl-limit-context');
            const modelIdInput = document.getElementById('sl-model-id');
            const productsBox = document.getElementById('sl-edit-products');
            const historyContext = document.getElementById('sl-history-context');
            const historyContent = document.getElementById('sl-history-content');

            function openModal(modal) {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('sl-modal-open');
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                if (!document.querySelector('.sl-modal-backdrop.is-open')) {
                    document.body.classList.remove('sl-modal-open');
                }
            }

            function esc(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function openLimit(modelId, keepOldValues = false) {
                const model = models[String(modelId)];
                if (!model) return;

                modelIdInput.value = model.id;
                limitContext.textContent = `${model.model} · ${model.line}`;

                productsBox.innerHTML = model.products.map(product => {
                    const oldValue = keepOldValues && Object.prototype.hasOwnProperty.call(oldLimits, String(product.id))
                        ? oldLimits[String(product.id)]
                        : (product.limit ?? '');

                    return `
                        <label class="sl-edit-row">
                            <span class="sl-edit-name">${esc(product.name)}</span>
                            <input
                                type="number"
                                min="0"
                                inputmode="numeric"
                                name="limits[${product.id}]"
                                value="${esc(oldValue)}"
                                aria-label="Limit ${esc(product.name)}"
                            >
                        </label>
                    `;
                }).join('');

                openModal(limitModal);
            }

            document.querySelectorAll('.js-open-limit').forEach(button => {
                button.addEventListener('click', () => openLimit(button.dataset.modelId));
            });

            document.querySelectorAll('.js-open-history').forEach(button => {
                button.addEventListener('click', () => {
                    const model = models[String(button.dataset.modelId)];
                    const template = document.getElementById(`sl-history-${button.dataset.modelId}`);
                    if (!model) return;

                    historyContext.textContent = `${model.model} · ${model.line}`;
                    historyContent.innerHTML = template ? template.innerHTML : '';
                    openModal(historyModal);
                });
            });

            document.querySelectorAll('.js-close-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.sl-modal-backdrop');
                    if (modal) closeModal(modal);
                });
            });

            [limitModal, historyModal].forEach(modal => {
                modal.addEventListener('click', event => {
                    if (event.target === modal) closeModal(modal);
                });
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    document.querySelectorAll('.sl-modal-backdrop.is-open').forEach(closeModal);
                }
            });

            if (hasErrors && oldModelId) {
                openLimit(oldModelId, true);
            }
        });
    </script>
@endsection
