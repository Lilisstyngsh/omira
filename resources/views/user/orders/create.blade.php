@extends('layouts.app')

@section('title', 'Buat Order Repair Box')
@section('header', 'Buat Order Repair Box')

@section('content')

    <style>
        .order-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .section-helper {
            margin: 0 0 16px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.55;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .info-box span {
            display: block;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .info-box strong {
            font-size: 14px;
            color: #111827;
            font-weight: 650;
        }

        .model-list {
            display: grid;
            gap: 12px;
        }

        .model-card {
            border: 1px solid #dfe5ed;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .model-card-header {
            width: 100%;
            min-height: 66px;
            border: 0;
            background: #fff;
            padding: 13px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            text-align: left;
            cursor: pointer;
            color: inherit;
        }

        .model-card-header:hover {
            background: #fff;
        }

        .model-card-header:focus-visible {
            outline: 3px solid rgba(79, 70, 229, .15);
            outline-offset: -3px;
        }

        .model-main {
            min-width: 0;
        }

        .model-title {
            display: block;
            color: #111827;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.3;
        }

        .model-subtitle {
            display: block;
            margin-top: 4px;
            color: #64748b;
            font-size: 11px;
            font-weight: 400;
        }

        .model-header-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            flex-shrink: 0;
        }

        .model-live-summary {
            text-align: right;
            min-width: 126px;
        }

        .model-live-total {
            display: block;
            color: #4f46e5;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.25;
        }

        .model-live-meta {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 10px;
            font-weight: 400;
        }

        .model-toggle-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 118px;
            min-height: 36px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 7px 10px;
            color: #334155;
            font-size: 11px;
            font-weight: 650;
            white-space: nowrap;
            background: #fff;
        }

        .model-card.is-open .model-toggle-label {
            border-color: #a5b4fc;
            color: #4338ca;
        }

        .model-card-body {
            border-top: 1px solid #e2e8f0;
            padding: 0;
            background: #fff;
        }

        .product-grid-head,
        .product-row,
        .model-total-row {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) repeat(4, 68px) 84px;
            align-items: stretch;
        }

        .product-grid-head > div,
        .product-row > div,
        .model-total-row > div {
            min-width: 0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-grid-head > div:last-child,
        .product-row > div:last-child,
        .model-total-row > div:last-child {
            border-right: 0;
        }

        .product-grid-head > div {
            padding: 9px 8px;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: .025em;
            background: #fff;
        }

        .product-grid-head .product-head {
            text-align: left;
            padding-left: 13px;
        }

        .ng-name {
            display: block;
            margin-top: 2px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0;
        }

        .product-row > div {
            min-height: 56px;
            padding: 7px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            font-size: 12px;
            font-weight: 400;
            background: #fff;
        }

        .product-row .product-cell {
            justify-content: flex-start;
            padding-left: 13px;
            line-height: 1.35;
        }

        .product-name {
            color: #334155;
            font-weight: 500;
        }

        .qty-input {
            width: 52px;
            max-width: 100%;
            height: 42px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            outline: none;
            box-sizing: border-box;
            transition: border-color .16s ease, box-shadow .16s ease;
        }

        .qty-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
        }

        .product-total-value {
            color: #475569;
            font-size: 12px;
            font-weight: 600;
        }

        .model-total-row > div {
            min-height: 45px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #4338ca;
            font-size: 12px;
            font-weight: 650;
        }

        .model-total-row .model-total-label {
            justify-content: flex-end;
            padding-right: 13px;
            color: #334155;
        }

        .model-total-row > div {
            border-bottom: 0;
        }

        .order-summary {
            margin-top: 18px;
            border: 1px solid #dfe5ed;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .summary-row {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) repeat(4, 68px) 84px;
            align-items: stretch;
        }

        .summary-row > div {
            min-height: 44px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
        }

        .summary-row > div:last-child {
            border-right: 0;
        }

        .summary-row:last-child > div {
            border-bottom: 0;
        }

        .summary-label {
            justify-content: flex-end !important;
            padding-right: 13px !important;
            color: #475569 !important;
        }

        .grand-summary-label {
            grid-column: 1 / 6;
            justify-content: flex-end !important;
            padding-right: 13px !important;
            color: #475569 !important;
        }

        .grand-summary-value {
            color: #4f46e5 !important;
            font-size: 15px !important;
            font-weight: 750 !important;
        }

        .field {
            margin-top: 24px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }

        .field textarea {
            width: 100%;
            min-height: 110px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            padding: 11px 12px;
            font-size: 13px;
            resize: vertical;
            outline: none;
            box-sizing: border-box;
        }

        .field textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
        }

        .form-notice {
            display: none;
            margin-top: 18px;
            padding: 11px 13px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #b42318;
            font-size: 12px;
            line-height: 1.45;
            background: #fff;
        }

        .form-notice.show {
            display: block;
        }

        .bottom-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-reset,
        .btn-submit {
            min-height: 42px;
            border-radius: 9px;
            padding: 10px 18px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .btn-reset {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
        }

        .btn-submit {
            border: 1px solid #4f46e5;
            background: #4f46e5;
            color: #fff;
        }

        .btn-submit:hover:not(:disabled) {
            background: #4338ca;
            border-color: #4338ca;
        }

        .btn-submit:disabled {
            cursor: not-allowed;
            opacity: .48;
        }

        .empty-state {
            text-align: center;
            padding: 35px;
            color: #94a3b8;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #fecdd3;
            color: #b42318;
            font-size: 11px;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 900px) {
            .order-card {
                padding: 18px;
            }

            .product-grid-head,
            .product-row,
            .model-total-row,
            .summary-row {
                grid-template-columns: minmax(150px, 1fr) repeat(4, 62px) 78px;
            }
        }

        @media (max-width: 700px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .model-card-header {
                align-items: flex-start;
            }

            .model-header-right {
                flex-direction: column;
                align-items: flex-end;
                gap: 7px;
            }

            .model-live-summary {
                min-width: 0;
            }

            .product-grid-head {
                display: none;
            }

            .product-row {
                grid-template-columns: repeat(4, 1fr);
                padding: 12px;
                gap: 8px;
                border-bottom: 1px solid #e2e8f0;
            }

            .product-row > div {
                min-height: 0;
                padding: 0;
                border: 0;
            }

            .product-row .product-cell {
                grid-column: 1 / -1;
                padding: 0 0 5px;
            }

            .product-row .qty-cell {
                position: relative;
                display: block;
            }

            .product-row .qty-cell::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 5px;
                color: #64748b;
                font-size: 9px;
                font-weight: 700;
                text-align: center;
            }

            .qty-input {
                width: 100%;
            }

            .product-row .product-total-cell {
                grid-column: 1 / -1;
                justify-content: flex-end;
                padding-top: 5px;
            }

            .product-row .product-total-cell::before {
                content: 'Total Produk: ';
                color: #64748b;
                font-size: 11px;
                font-weight: 500;
                margin-right: 6px;
            }

            .model-total-row {
                grid-template-columns: repeat(4, 1fr);
                padding: 11px 12px;
                gap: 6px;
            }

            .model-total-row > div {
                min-height: auto;
                padding: 0;
                border: 0;
            }

            .model-total-row .model-total-label {
                grid-column: 1 / -1;
                justify-content: flex-start;
                padding: 0 0 5px;
            }

            .model-total-row .model-grand-total-cell {
                grid-column: 1 / -1;
                justify-content: flex-end;
                padding-top: 4px;
            }

            .model-total-row .model-grand-total-cell::before {
                content: 'Total Model: ';
                color: #64748b;
                font-weight: 500;
                margin-right: 6px;
            }

            .summary-row {
                grid-template-columns: repeat(4, 1fr);
                padding: 11px 12px;
                gap: 6px;
            }

            .summary-row > div {
                min-height: auto;
                padding: 0;
                border: 0;
            }

            .summary-label {
                grid-column: 1 / -1;
                justify-content: flex-start !important;
                padding: 0 0 5px !important;
            }

            .grand-summary-label {
                grid-column: 1 / 4;
                justify-content: flex-start !important;
                padding: 0 !important;
            }

            .grand-summary-value {
                grid-column: 4;
                justify-content: flex-end !important;
            }

            .bottom-actions {
                flex-direction: column-reverse;
            }

            .btn-reset,
            .btn-submit {
                width: 100%;
            }
        }
    </style>


    <div class="order-card">

        @if ($errors->any())
            <div class="error-box">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-title">Informasi Order</div>

        <div class="info-grid">
            <div class="info-box">
                <span>Plant</span>
                <strong>{{ $line->plant?->name ?? '-' }}</strong>
            </div>

            <div class="info-box">
                <span>Line</span>
                <strong>{{ $line->name }}</strong>
            </div>

            <div class="info-box">
                <span>Tanggal</span>
                <strong>{{ now()->format('d-m-Y H:i') }}</strong>
            </div>
        </div>

        <div class="section-title">Detail Produk & Quantity NG</div>
        <p class="section-helper">
            Buka hanya Model yang akan diorder. Seluruh Produk pada Model tersebut akan langsung tersedia untuk input Quantity NG.
            Kolom yang tidak memiliki Quantity dapat dibiarkan kosong.
        </p>

        @php
            $ngMap = $ngTypes->keyBy(function ($ngType) {
                return strtoupper($ngType->code);
            });

            $ngCodes = ['P', 'H', 'C', 'S'];
            $hasProducts = $models->sum(fn($model) => $model->products->count()) > 0;
        @endphp

        <form method="POST" action="{{ route('user.orders.store') }}" id="orderForm">
            @csrf

            @if ($hasProducts)
                <div class="model-list" id="modelList">
                    @php $rowIndex = 0; @endphp

                    @foreach ($models as $model)
                        @php
                            $products = $model->products;
                            $productCount = $products->count();
                        @endphp

                        <section class="model-card" data-model-card="{{ $model->id }}">
                            <button type="button"
                                class="model-card-header"
                                data-model-toggle="{{ $model->id }}"
                                aria-expanded="false"
                                aria-controls="model-body-{{ $model->id }}">

                                <span class="model-main">
                                    <span class="model-title">MODEL {{ $model->model }}</span>
                                    <span class="model-subtitle">{{ $productCount }} Produk tersedia</span>
                                </span>

                                <span class="model-header-right">
                                    <span class="model-live-summary">
                                        <span class="model-live-total" data-model-header-total="{{ $model->id }}">Belum ada Qty</span>
                                        <span class="model-live-meta" data-model-header-meta="{{ $model->id }}">Belum ada Produk terisi</span>
                                    </span>

                                    <span class="model-toggle-label" data-model-toggle-label="{{ $model->id }}">＋ Input NG</span>
                                </span>
                            </button>

                            <div class="model-card-body"
                                id="model-body-{{ $model->id }}"
                                data-model-body="{{ $model->id }}"
                                hidden>

                                <div class="product-grid-head">
                                    <div class="product-head">Produk</div>
                                    @foreach ($ngCodes as $code)
                                        <div>
                                            {{ $code }}
                                            @if (isset($ngMap[$code]))
                                                <span class="ng-name">{{ $ngMap[$code]->name }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div>Total</div>
                                </div>

                                @foreach ($products as $product)
                                    <div class="product-row"
                                        data-model-id="{{ $model->id }}"
                                        data-row-index="{{ $rowIndex }}">

                                        <div class="product-cell">
                                            <span class="product-name">{{ $product->name }}</span>

                                            <input type="hidden"
                                                name="items[{{ $rowIndex }}][master_model_id]"
                                                value="{{ $model->id }}">

                                            <input type="hidden"
                                                name="items[{{ $rowIndex }}][product_id]"
                                                value="{{ $product->id }}">
                                        </div>

                                        @foreach ($ngCodes as $code)
                                            @php $ng = $ngMap[$code] ?? null; @endphp
                                            <div class="qty-cell" data-label="{{ $code }}">
                                                @if ($ng)
                                                    <input type="number"
                                                        class="qty-input"
                                                        data-ng-code="{{ $code }}"
                                                        data-model-id="{{ $model->id }}"
                                                        data-row-index="{{ $rowIndex }}"
                                                        name="items[{{ $rowIndex }}][qty][{{ $ng->id }}]"
                                                        min="0"
                                                        step="1"
                                                        value="{{ old('items.' . $rowIndex . '.qty.' . $ng->id, '') }}"
                                                        inputmode="numeric"
                                                        autocomplete="off"
                                                        placeholder="">
                                                @endif
                                            </div>
                                        @endforeach

                                        <div class="product-total-cell">
                                            <span class="product-total-value" data-product-total="{{ $rowIndex }}"></span>
                                        </div>
                                    </div>

                                    @php $rowIndex++; @endphp
                                @endforeach

                                <div class="model-total-row" data-model-total-row="{{ $model->id }}">
                                    <div class="model-total-label">Total Model {{ $model->model }}</div>

                                    @foreach ($ngCodes as $code)
                                        <div data-model-total-ng="{{ $model->id }}:{{ $code }}"></div>
                                    @endforeach

                                    <div class="model-grand-total-cell" data-model-grand-total="{{ $model->id }}"></div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>

                <div class="order-summary">
                    <div class="summary-row">
                        <div class="summary-label">Total Jenis NG</div>

                        @foreach ($ngCodes as $code)
                            <div data-total-ng="{{ $code }}"></div>
                        @endforeach

                        <div></div>
                    </div>

                    <div class="summary-row">
                        <div class="grand-summary-label">Grand Total Order</div>
                        <div class="grand-summary-value" id="grandTotal"></div>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    Belum ada Model & Produk untuk Line Anda.
                </div>
            @endif

            <div class="field">
                <label>Keterangan</label>
                <textarea name="description"
                    placeholder="Tambahkan keterangan jika diperlukan...">{{ old('description') }}</textarea>
            </div>

            <div class="form-notice" id="formNotice">
                Masukkan minimal satu Quantity NG sebelum mengirim order.
            </div>

            <div class="bottom-actions">
                <button type="button"
                    class="btn-reset"
                    onclick="resetOrderForm()"
                    title="Kosongkan seluruh input Quantity NG">
                    Kosongkan Form
                </button>

                <button type="submit"
                    class="btn-submit"
                    id="submitOrderButton"
                    title="Kirim Order Repair Box ke OMD Workshop"
                    {{ $hasProducts ? 'disabled' : 'disabled' }}>
                    <span id="submitOrderLabel">Kirim Order Repair</span>
                </button>
            </div>
        </form>
    </div>


    <script>
        function numberValue(input) {
            var value = parseInt(input.value, 10);
            return Number.isFinite(value) && value > 0 ? value : 0;
        }

        function setOptionalNumber(target, value, suffix) {
            if (!target) return;
            target.textContent = value > 0 ? String(value) + (suffix || '') : '';
        }

        function setModelOpen(modelId, shouldOpen) {
            var card = document.querySelector('[data-model-card="' + modelId + '"]');
            var toggle = document.querySelector('[data-model-toggle="' + modelId + '"]');
            var body = document.querySelector('[data-model-body="' + modelId + '"]');
            var label = document.querySelector('[data-model-toggle-label="' + modelId + '"]');

            if (!card || !toggle || !body || !label) return;

            card.classList.toggle('is-open', shouldOpen);
            toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
            body.hidden = !shouldOpen;
            label.textContent = shouldOpen ? '− Tutup' : '＋ Input NG';
        }

        document.querySelectorAll('[data-model-toggle]').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                var modelId = this.dataset.modelToggle;
                var isOpen = this.getAttribute('aria-expanded') === 'true';
                setModelOpen(modelId, !isOpen);

                if (isOpen) return;

                window.setTimeout(function() {
                    var firstInput = document.querySelector(
                        '[data-model-body="' + modelId + '"] .qty-input'
                    );
                    if (firstInput) firstInput.focus();
                }, 0);
            });
        });

        function calculateTotals() {
            var codes = ['P', 'H', 'C', 'S'];
            var orderTotals = { P: 0, H: 0, C: 0, S: 0 };
            var modelTotals = {};
            var modelFilledProducts = {};
            var productTotals = {};

            document.querySelectorAll('.qty-input').forEach(function(input) {
                var code = input.dataset.ngCode;
                var modelId = input.dataset.modelId;
                var rowIndex = input.dataset.rowIndex;
                var value = numberValue(input);

                if (!codes.includes(code)) return;

                orderTotals[code] += value;

                if (!modelTotals[modelId]) {
                    modelTotals[modelId] = { P: 0, H: 0, C: 0, S: 0 };
                }

                modelTotals[modelId][code] += value;
                productTotals[rowIndex] = (productTotals[rowIndex] || 0) + value;
            });

            document.querySelectorAll('[data-product-total]').forEach(function(cell) {
                var rowIndex = cell.dataset.productTotal;
                var value = productTotals[rowIndex] || 0;
                setOptionalNumber(cell, value);

                if (value > 0) {
                    var row = cell.closest('.product-row');
                    var modelId = row ? row.dataset.modelId : null;
                    if (modelId) {
                        modelFilledProducts[modelId] = (modelFilledProducts[modelId] || 0) + 1;
                    }
                }
            });

            document.querySelectorAll('[data-model-total-row]').forEach(function(row) {
                var modelId = row.dataset.modelTotalRow;
                var totals = modelTotals[modelId] || { P: 0, H: 0, C: 0, S: 0 };
                var modelGrandTotal = 0;

                codes.forEach(function(code) {
                    var value = totals[code] || 0;
                    modelGrandTotal += value;

                    setOptionalNumber(
                        document.querySelector('[data-model-total-ng="' + modelId + ':' + code + '"]'),
                        value
                    );
                });

                setOptionalNumber(
                    document.querySelector('[data-model-grand-total="' + modelId + '"]'),
                    modelGrandTotal
                );

                var headerTotal = document.querySelector('[data-model-header-total="' + modelId + '"]');
                var headerMeta = document.querySelector('[data-model-header-meta="' + modelId + '"]');
                var filledProducts = modelFilledProducts[modelId] || 0;

                if (headerTotal) {
                    headerTotal.textContent = modelGrandTotal > 0
                        ? modelGrandTotal + ' NG'
                        : 'Belum ada Qty';
                }

                if (headerMeta) {
                    headerMeta.textContent = filledProducts > 0
                        ? filledProducts + ' Produk terisi'
                        : 'Belum ada Produk terisi';
                }
            });

            var grandTotal = 0;

            codes.forEach(function(code) {
                var value = orderTotals[code];
                grandTotal += value;

                setOptionalNumber(
                    document.querySelector('[data-total-ng="' + code + '"]'),
                    value
                );
            });

            setOptionalNumber(
                document.getElementById('grandTotal'),
                grandTotal,
                ' NG'
            );

            var submitButton = document.getElementById('submitOrderButton');
            var submitLabel = document.getElementById('submitOrderLabel');
            var formNotice = document.getElementById('formNotice');

            if (submitButton) {
                submitButton.disabled = grandTotal <= 0;
            }

            if (submitLabel) {
                submitLabel.textContent = grandTotal > 0
                    ? 'Kirim Order Repair · ' + grandTotal + ' NG'
                    : 'Kirim Order Repair';
            }

            if (formNotice && grandTotal > 0) {
                formNotice.classList.remove('show');
            }

            return grandTotal;
        }

        document.querySelectorAll('.qty-input').forEach(function(input) {
            input.addEventListener('input', calculateTotals);

            input.addEventListener('keydown', function(event) {
                if (event.key !== 'Enter') return;

                event.preventDefault();

                var inputs = Array.from(document.querySelectorAll('.qty-input'));
                var currentIndex = inputs.indexOf(this);

                if (inputs[currentIndex + 1]) {
                    var next = inputs[currentIndex + 1];
                    var nextModelId = next.dataset.modelId;
                    if (nextModelId) setModelOpen(nextModelId, true);
                    next.focus();
                    next.select();
                }
            });
        });

        function resetOrderForm() {
            document.querySelectorAll('.qty-input').forEach(function(input) {
                input.value = '';
            });

            var description = document.querySelector('textarea[name="description"]');
            if (description) description.value = '';

            document.querySelectorAll('[data-model-card]').forEach(function(card) {
                setModelOpen(card.dataset.modelCard, false);
            });

            calculateTotals();
        }

        var orderForm = document.getElementById('orderForm');
        if (orderForm) {
            orderForm.addEventListener('submit', function(event) {
                var grandTotal = calculateTotals();

                if (grandTotal > 0) return;

                event.preventDefault();

                var notice = document.getElementById('formNotice');
                if (notice) notice.classList.add('show');

                var firstCard = document.querySelector('[data-model-card]');
                if (firstCard) {
                    setModelOpen(firstCard.dataset.modelCard, true);
                    window.setTimeout(function() {
                        var firstInput = firstCard.querySelector('.qty-input');
                        if (firstInput) firstInput.focus();
                    }, 0);
                }
            });
        }

        calculateTotals();

        document.querySelectorAll('[data-model-card]').forEach(function(card) {
            var modelId = card.dataset.modelCard;
            var hasOldValue = Array.from(card.querySelectorAll('.qty-input')).some(function(input) {
                return numberValue(input) > 0;
            });

            if (hasOldValue) {
                setModelOpen(modelId, true);
            }
        });
    </script>

@endsection
