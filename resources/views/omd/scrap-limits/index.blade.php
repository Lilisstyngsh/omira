@extends('layouts.app')

@section('title', 'Scrap Limit Model')
@section('header', 'Scrap Limit Model')

@section('content')
    <style>
        .sl-page {
            --sl-border: #dbe3ee;
            --sl-text: #1e293b;
            --sl-muted: #64748b;
            --sl-primary: #5b4ce6;
            --sl-green: #16a34a;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sl-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .sl-heading h2 {
            margin: 0;
            color: var(--sl-text);
            font-size: 21px;
            font-weight: 750;
        }

        .sl-heading p {
            margin: 4px 0 0;
            color: var(--sl-muted);
            font-size: 12px;
        }

        .sl-toolbar {
            display: grid;
            grid-template-columns: minmax(190px, 240px) minmax(240px, 1fr) auto;
            gap: 10px;
            padding: 14px;
            background: #fff;
            border: 1px solid var(--sl-border);
            border-radius: 14px;
        }

        .sl-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 0;
        }

        .sl-field label {
            color: var(--sl-muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .035em;
        }

        .sl-field select,
        .sl-field input,
        .sl-field textarea {
            width: 100%;
            color: #334155;
            background: #fff;
            border: 1px solid #b9c5d4;
            border-radius: 9px;
            outline: 0;
            font-size: 12px;
        }

        .sl-field select,
        .sl-field input {
            height: 42px;
            padding: 0 11px;
        }

        .sl-field textarea {
            min-height: 84px;
            padding: 10px 11px;
            line-height: 1.5;
            resize: vertical;
        }

        .sl-field select:focus,
        .sl-field input:focus,
        .sl-field textarea:focus {
            border-color: var(--sl-primary);
            box-shadow: 0 0 0 3px rgba(91, 76, 230, .09);
        }

        .sl-btn {
            min-height: 42px;
            padding: 0 15px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 750;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .sl-btn-primary {
            background: var(--sl-primary);
            color: #fff;
        }

        .sl-btn-success {
            background: var(--sl-green);
            color: #fff;
        }

        .sl-btn-neutral {
            background: #fff;
            border-color: #cbd5e1;
            color: #475569;
        }

        .sl-btn-soft {
            background: #f8fafc;
            border-color: #dbe3ee;
            color: #475569;
        }

        .sl-alert {
            padding: 12px 14px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.5;
        }

        .sl-alert.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .sl-alert.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .sl-result-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            color: var(--sl-muted);
            font-size: 11px;
        }

        .sl-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 235px), 1fr));
            gap: 12px;
            align-items: stretch;
        }

        .sl-model-card {
            min-width: 0;
            min-height: 176px;
            display: flex;
            flex-direction: column;
            padding: 15px;
            background: #fff;
            border: 1px solid var(--sl-border);
            border-radius: 13px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, .035);
        }

        .sl-model-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .sl-model-name {
            color: #172033;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .sl-model-line {
            margin-top: 3px;
            color: var(--sl-muted);
            font-size: 10.5px;
            line-height: 1.35;
        }

        .sl-status {
            flex: 0 0 auto;
            padding: 4px 7px;
            border-radius: 999px;
            background: #f0fdf4;
            color: #15803d;
            font-size: 9px;
            font-weight: 750;
        }

        .sl-status.empty {
            background: #f1f5f9;
            color: #64748b;
        }

        .sl-limit-block {
            margin-top: 14px;
        }

        .sl-limit-label {
            color: var(--sl-muted);
            font-size: 10px;
        }

        .sl-limit-value {
            margin-top: 2px;
            color: #172033;
            font-size: 25px;
            font-weight: 800;
            line-height: 1.1;
        }

        .sl-limit-value small {
            font-size: 10px;
            color: var(--sl-muted);
            font-weight: 600;
        }

        .sl-limit-period {
            margin-top: 5px;
            min-height: 30px;
            color: var(--sl-muted);
            font-size: 10.5px;
            line-height: 1.45;
        }

        .sl-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px;
            margin-top: auto;
            padding-top: 13px;
        }

        .sl-card-actions .sl-btn {
            min-height: 36px;
            padding: 0 9px;
            font-size: 10px;
        }

        .sl-empty {
            padding: 22px;
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 13px;
            color: var(--sl-muted);
            text-align: center;
            font-size: 12px;
        }

        .sl-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, .48);
        }

        .sl-modal-backdrop.is-open {
            display: flex;
        }

        .sl-modal {
            width: min(100%, 500px);
            max-height: min(88vh, 720px);
            overflow: auto;
            background: #fff;
            border-radius: 17px;
            box-shadow: 0 22px 65px rgba(15, 23, 42, .22);
        }

        .sl-modal.wide {
            width: min(100%, 680px);
        }

        .sl-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 17px 18px 13px;
            border-bottom: 1px solid #e8edf4;
        }

        .sl-modal-title {
            margin: 0;
            color: #172033;
            font-size: 16px;
            font-weight: 800;
        }

        .sl-modal-subtitle {
            margin-top: 3px;
            color: var(--sl-muted);
            font-size: 11px;
        }

        .sl-modal-close {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 19px;
            cursor: pointer;
        }

        .sl-modal-body {
            padding: 16px 18px;
        }

        .sl-modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 11px;
        }

        .sl-modal-grid .full {
            grid-column: 1 / -1;
        }

        .sl-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 13px 18px 17px;
            border-top: 1px solid #e8edf4;
        }

        .sl-history-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sl-history-item {
            display: grid;
            grid-template-columns: minmax(70px, .7fr) minmax(170px, 1.4fr) minmax(130px, 1fr);
            gap: 10px;
            padding: 11px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #334155;
            font-size: 11px;
            line-height: 1.45;
        }

        .sl-history-limit {
            color: #172033;
            font-weight: 800;
        }

        .sl-history-note {
            color: var(--sl-muted);
        }

        .sl-history-empty {
            color: var(--sl-muted);
            font-size: 12px;
            text-align: center;
            padding: 18px 8px;
        }

        @media (max-width: 780px) {
            .sl-toolbar {
                grid-template-columns: 1fr 1fr;
            }

            .sl-toolbar .sl-btn {
                grid-column: 1 / -1;
            }

            .sl-modal-grid {
                grid-template-columns: 1fr;
            }

            .sl-modal-grid .full {
                grid-column: auto;
            }

            .sl-history-item {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 520px) {
            .sl-toolbar {
                grid-template-columns: 1fr;
            }

            .sl-toolbar .sl-btn {
                grid-column: auto;
            }

            .sl-grid {
                grid-template-columns: 1fr;
            }

            .sl-modal-backdrop {
                padding: 10px;
            }

            .sl-modal-actions {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div class="sl-page">
        <div class="sl-heading">
            <div>
                <h2>Scrap Limit Model</h2>
                <p>Atur limit aktif dan riwayatnya per model.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="sl-alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="sl-alert error">
                <strong>Data belum dapat disimpan.</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
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
                <label for="q">Cari Model / Line</label>
                <input id="q" type="search" name="q" value="{{ $search }}" placeholder="Contoh: 660 atau PPIC Body">
            </div>

            <button type="submit" class="sl-btn sl-btn-primary">Tampilkan</button>
        </form>

        <div class="sl-result-meta">
            <span>{{ number_format($models->count()) }} model</span>
            @if ($search !== '')
                <a class="sl-btn sl-btn-neutral" style="min-height:32px;padding:0 10px;" href="{{ route('omd.scrap-limits.index', ['line_id' => $lineFilter]) }}">Reset pencarian</a>
            @endif
        </div>

        @if ($models->isEmpty())
            <div class="sl-empty">
                Tidak ada model yang sesuai dengan filter.
            </div>
        @else
            <div class="sl-grid">
                @foreach ($models as $model)
                    @php
                        $activeLimit = $model->scrapLimits->first(function ($limit) use ($today) {
                            return $limit->effective_from->toDateString() <= $today
                                && ($limit->effective_to === null || $limit->effective_to->toDateString() >= $today);
                        });
                        $lineLabel = trim(($model->line?->plant?->name ? $model->line->plant->name . ' — ' : '') . ($model->line?->name ?? '-'));
                    @endphp

                    <article class="sl-model-card">
                        <div class="sl-model-top">
                            <div>
                                <div class="sl-model-name">{{ $model->model }}</div>
                                <div class="sl-model-line">{{ $lineLabel }}</div>
                            </div>
                            <span class="sl-status {{ $activeLimit ? '' : 'empty' }}">
                                {{ $activeLimit ? 'Aktif' : 'Belum diatur' }}
                            </span>
                        </div>

                        <div class="sl-limit-block">
                            <div class="sl-limit-label">Limit</div>
                            <div class="sl-limit-value">
                                {{ $activeLimit ? number_format($activeLimit->limit_qty) : '—' }}
                                @if ($activeLimit)
                                    <small>box</small>
                                @endif
                            </div>
                            <div class="sl-limit-period">
                                @if ($activeLimit)
                                    {{ $activeLimit->effective_from->format('d M Y') }}
                                    @if ($activeLimit->effective_to)
                                        – {{ $activeLimit->effective_to->format('d M Y') }}
                                    @else
                                        – sekarang
                                    @endif
                                @else
                                    Belum ada limit aktif.
                                @endif
                            </div>
                        </div>

                        <div class="sl-card-actions">
                            <button
                                type="button"
                                class="sl-btn sl-btn-primary js-open-limit"
                                data-model-id="{{ $model->id }}"
                                data-model="{{ $model->model }}"
                                data-line="{{ $lineLabel }}"
                                data-limit="{{ $activeLimit?->limit_qty }}"
                            >Atur Limit</button>

                            <button
                                type="button"
                                class="sl-btn sl-btn-soft js-open-history"
                                data-model="{{ $model->model }}"
                                data-history-id="sl-history-{{ $model->id }}"
                            >Riwayat</button>
                        </div>
                    </article>

                    <template id="sl-history-{{ $model->id }}">
                        @if ($model->scrapLimits->isEmpty())
                            <div class="sl-history-empty">Belum ada riwayat limit.</div>
                        @else
                            <div class="sl-history-list">
                                @foreach ($model->scrapLimits as $limit)
                                    <div class="sl-history-item">
                                        <div>
                                            <div class="sl-history-limit">{{ number_format($limit->limit_qty) }} box</div>
                                            <div>{{ $limit->creator?->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            {{ $limit->effective_from->format('d M Y') }}
                                            –
                                            {{ $limit->effective_to?->format('d M Y') ?? 'Sekarang' }}
                                        </div>
                                        <div class="sl-history-note">{{ $limit->note ?: '—' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                <input type="hidden" name="master_model_id" id="sl-master-model-id" value="{{ old('master_model_id') }}">
                <input type="hidden" name="return_line" value="{{ $lineFilter }}">
                <input type="hidden" name="return_q" value="{{ $search }}">

                <div class="sl-modal-body">
                    <div class="sl-modal-grid">
                        <div class="sl-field">
                            <label for="sl-limit-qty">Limit Qty</label>
                            <input id="sl-limit-qty" type="number" name="limit_qty" min="0" value="{{ old('limit_qty') }}" required>
                        </div>

                        <div class="sl-field">
                            <label for="sl-effective-from">Mulai Berlaku</label>
                            <input id="sl-effective-from" type="date" name="effective_from" value="{{ old('effective_from', $today) }}" required>
                        </div>

                        <div class="sl-field full">
                            <label for="sl-note">Keterangan</label>
                            <textarea id="sl-note" name="note" placeholder="Opsional">{{ old('note') }}</textarea>
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
        (() => {
            const limitModal = document.getElementById('sl-limit-modal');
            const historyModal = document.getElementById('sl-history-modal');
            const modelIdInput = document.getElementById('sl-master-model-id');
            const limitInput = document.getElementById('sl-limit-qty');
            const effectiveInput = document.getElementById('sl-effective-from');
            const noteInput = document.getElementById('sl-note');
            const limitContext = document.getElementById('sl-limit-context');
            const historyContext = document.getElementById('sl-history-context');
            const historyContent = document.getElementById('sl-history-content');
            const today = @json($today);
            const oldModelId = @json((string) old('master_model_id', ''));
            const hasErrors = @json($errors->any());

            function openModal(modal) {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                if (!document.querySelector('.sl-modal-backdrop.is-open')) {
                    document.body.style.overflow = '';
                }
            }

            function openLimit(button, keepOldValues = false) {
                modelIdInput.value = button.dataset.modelId;
                limitContext.textContent = `${button.dataset.model} · ${button.dataset.line}`;

                if (!keepOldValues) {
                    limitInput.value = button.dataset.limit || '';
                    effectiveInput.value = today;
                    noteInput.value = '';
                }

                openModal(limitModal);
                setTimeout(() => limitInput.focus(), 60);
            }

            document.querySelectorAll('.js-open-limit').forEach((button) => {
                button.addEventListener('click', () => openLimit(button));
            });

            document.querySelectorAll('.js-open-history').forEach((button) => {
                button.addEventListener('click', () => {
                    const template = document.getElementById(button.dataset.historyId);
                    historyContext.textContent = button.dataset.model;
                    historyContent.innerHTML = template ? template.innerHTML : '';
                    openModal(historyModal);
                });
            });

            document.querySelectorAll('.js-close-modal').forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.sl-modal-backdrop');
                    if (modal) closeModal(modal);
                });
            });

            [limitModal, historyModal].forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) closeModal(modal);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') return;
                document.querySelectorAll('.sl-modal-backdrop.is-open').forEach(closeModal);
            });

            if (hasErrors && oldModelId) {
                const button = document.querySelector(`.js-open-limit[data-model-id="${CSS.escape(oldModelId)}"]`);
                if (button) openLimit(button, true);
            }
        })();
    </script>
@endsection
