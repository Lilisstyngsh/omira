@extends('layouts.app')

@section('title', 'Buat Order Repair Box')
@section('header', 'Buat Order Repair Box')

@section('content')
    <style>
        :root {
            --border: #94a3b8;
            --border-soft: #cbd5e1;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #5F9278;
            --primary-dark: #376B52
        }

        .create-wrap {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 26px rgba(15, 23, 42, .04)
        }

        .top-info {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 20px
        }

        .top-info-item {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 11px 13px;
            background: #fff
        }

        .top-info-item span {
            display: block;
            font-size: 10px;
            color: var(--muted);
            margin-bottom: 3px
        }

        .top-info-item strong {
            font-size: 13px;
            font-weight: 600;
            color: var(--text)
        }

        .section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            margin: 18px 0 10px
        }

        .section-head h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 650;
            color: var(--text)
        }

        .section-head p {
            margin: 0;
            font-size: 11px;
            color: var(--muted)
        }

        .model-picker {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 16px
        }

        .model-card {
            appearance: none;
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 11px;
            background: #fff;
            padding: 12px 13px;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 60px;
            transition: .15s ease
        }

        .model-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .05)
        }

        .model-card.is-open {
            border-color: #5F9278;
            box-shadow: 0 0 0 1px rgba(95, 146, 120, .12)
        }

        .model-card-main {
            min-width: 0
        }

        .model-card-title {
            display: block;
            font-size: 13px;
            font-weight: 650;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .model-card-meta {
            display: block;
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px
        }

        .model-card-total {
            font-size: 12px;
            font-weight: 600;
            color: #376B52;
            white-space: nowrap
        }

        .model-card-chevron {
            font-size: 20px;
            color: #64748b;
            transition: transform .15s
        }

        .model-card.is-open .model-card-chevron {
            transform: rotate(90deg)
        }

        .editor-stack {
            display: grid;
            gap: 12px
        }

        .model-editor {
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            overflow: hidden
        }

        .model-editor[hidden] {
            display: none !important
        }

        .model-editor-head {
            min-height: 46px;
            padding: 10px 13px;
            border-bottom: 1px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .model-editor-head strong {
            font-size: 13px;
            font-weight: 650;
            color: var(--text)
        }

        .model-editor-head span {
            font-size: 11px;
            color: var(--muted)
        }

        .qty-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .qty-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 840px;
            font-size: 12px
        }

        .qty-table th,
        .qty-table td {
            border: 1px solid #cbd5e1;
            padding: 7px;
            vertical-align: middle;
            font-weight: 400
        }

        .qty-table thead th {
            text-align: center;
            font-weight: 650;
            color: #0f172a;
            background: #fff
        }

        .qty-table th:first-child,
        .qty-table td:first-child {
            text-align: left
        }

        .qty-table .product-name {
            font-weight: 450;
            color: #1f2937
        }

        .qty-table .total-cell {
            text-align: center;
            font-weight: 550;
            color: #334155;
            min-width: 70px
        }

        .qty-table tfoot td {
            font-weight: 550;
            background: #fff
        }

        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            width: max-content;
            margin: auto
        }

        .qty-input {
            width: 48px;
            height: 40px;
            border: 1px solid #94a3b8 !important;
            border-radius: 9px;
            outline: 0 !important;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            background: #fff;
            padding: 0 3px;
            -moz-appearance: textfield;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .06)
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0
        }

        .qty-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12)
        }

        .qty-step{width:34px;height:40px;border:0;background:transparent;font-size:23px;font-weight:400;line-height:1;cursor:pointer;touch-action:none;user-select:none;padding:0;display:inline-flex;align-items:center;justify-content:center}.qty-step[data-step="-1"],
        .qty-step[data-step="1"] {
            color: #374151;
        }

        .qty-step:active {
            transform: scale(.94);
            opacity: .75
        }

        .review-box {
            margin-top: 18px
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            background: #fff
        }

        .review-table th,
        .review-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 9px;
            font-weight: 400
        }

        .review-table thead th {
            text-align: center;
            font-weight: 650;
            color: #0f172a
        }

        .review-table .review-model td {
            font-weight: 700;
            color: #334155
        }

        .review-table .review-model-total td {
            font-weight: 550
        }

        .review-table .review-grand td {
            font-weight: 600;
            background: #fff
        }

        .review-empty {
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            color: #64748b;
            font-size: 11px
        }

        .field {
            margin-top: 18px
        }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px
        }

        .field textarea {
            width: 100%;
            min-height: 84px;
            border: 1.5px solid #94a3b8;
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 13px;
            resize: vertical;
            outline: none
        }

        .field textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1)
        }

        .form-notice {
            display: none;
            margin-top: 12px;
            padding: 10px 12px;
            border: 1px solid #fecaca;
            border-radius: 9px;
            color: #b91c1c;
            background: #fff;
            font-size: 11px
        }

        .form-notice.show {
            display: block
        }

        .errors {
            margin-bottom: 14px;
            padding: 10px 12px;
            border: 1px solid #fecaca;
            border-radius: 9px;
            color: #b91c1c;
            font-size: 11px
        }

        .errors ul {
            margin: 0;
            padding-left: 18px
        }

        .actions {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px
        }

        .btn-reset,
        .btn-submit {
            min-height: 44px;
            border-radius: 9px;
            padding: 0 16px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer
        }

        .btn-reset {
            border: 1px solid #94a3b8;
            background: #fff;
            color: #334155
        }

        .btn-submit {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--primary-dark)
        }

        .btn-submit:disabled {
            opacity: .45;
            cursor: not-allowed
        }

        @media(max-width:1100px) {
            .model-picker {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:700px) {
            .create-wrap {
                padding: 14px
            }

            .top-info {
                grid-template-columns: 1fr
            }

            .model-picker {
                grid-template-columns: 1fr
            }

            .section-head {
                align-items: flex-start;
                flex-direction: column
            }

            .actions {
                flex-direction: column-reverse
            }

            .btn-reset,
            .btn-submit {
                width: 100%
            }
        }

        .all-model-table {
            min-width: 980px;
            table-layout: fixed
        }

        .all-model-table th,
        .all-model-table td {
            border: 1px solid #d1d5db;
            padding: 9px 7px
        }

        .all-model-table thead th {
            background: #f8fafc;
            text-align: center !important;
            font-size: 11px;
            letter-spacing: .015em;
            font-weight: 700;
            color: #243347
        }

        .all-model-table td.merged-model {
            text-align: center !important;
            vertical-align: middle;
            font-size: 12px;
            color: #172b22
        }

        .all-model-table .product-name {
            text-align: left;
            font-size: 12px;
            font-weight: 500
        }

        .all-model-table tfoot td {
            font-weight: 650;
            background: #f8fafc;
            text-align: center
        }

        .all-model-table tfoot td:first-child {
            text-align: left;
            padding-left: 14px
        }

        .all-model-table .grand-row td {
            background: #edf5ef;
            color: #214d36
        }

        .all-model-table .qty-input {
            width: 40px;
            height: 36px;
            font-size: 13px;
            border-radius: 7px
        }

        .all-model-table .qty-step {
            width: 27px;
            height: 36px;
            font-size: 20px
        }

        .all-model-table .qty-control {
            gap: 2px
        }

        /* Minimalist Create User improvements */
        .top-info-item:nth-child(1) {
            background: #edf5ef;
            border-color: #dbe8de
        }

        .top-info-item:nth-child(2) {
            background: #edf4f8;
            border-color: #dbe6eb
        }

        .top-info-item:nth-child(3) {
            background: #f8f3eb;
            border-color: #eee4d5
        }

        .all-model-table {
            min-width: 940px
        }

        .all-model-table thead th {
            position: sticky;
            z-index: 3;
            background: #f4f7f6;
            box-shadow: inset 0 -1px 0 #aebdb6
        }

        .all-model-table thead tr:first-child th {
            top: 0;
            height: 42px
        }

        .all-model-table thead tr:nth-child(2) th {
            top: 42px;
            height: 36px
        }

        .all-model-table .product-name {
            overflow-wrap: anywhere
        }

        .qty-table-wrap {
            max-height: min(68vh, 690px);
            overflow: auto;
            position: relative;
            border: 1px solid #d1d5db;
            border-radius: 9px
        }

        .all-model-table th,
        .all-model-table td {
            border-color: #d1d5db
        }
    </style>

    <div class="create-wrap">
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="top-info">
            <div class="top-info-item"><span>Plant</span><strong>{{ $line->plant?->name ?? '-' }}</strong></div>
            <div class="top-info-item"><span>Line</span><strong>{{ $line->name }}</strong></div>
            <div class="top-info-item"><span>Tanggal</span><strong>{{ now()->format('d-m-Y H:i') }}</strong></div>
        </div>

        @php
            $ngMap = $ngTypes->keyBy(fn($ngType) => strtoupper($ngType->code));
            $ngCodes = ['P', 'H', 'C', 'S'];
            $ngLabels = ['P' => 'Partisi', 'H' => 'Holder', 'C' => 'Cover', 'S' => 'Scrap'];
            $hasProducts = $models->sum(fn($model) => $model->products->count()) > 0;
            $rowIndex = 0;
        @endphp

        <form method="POST" action="{{ route('user.orders.store') }}" id="orderForm">
            @csrf

            <div class="section-head">
                <h3>Input Order Repair Box</h3>
            </div>

            @if ($hasProducts)
                <div class="qty-table-wrap">
                    <table class="qty-table all-model-table" aria-label="Input Jenis NG dan Qty">
                        <colgroup>
                            <col style="width:48px">
                            <col style="width:15%">
                            <col style="width:22%">
                            <col span="4" style="width:14%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2">NO</th>
                                <th rowspan="2">MODEL</th>
                                <th rowspan="2">PRODUK</th>
                                <th colspan="4">JENIS NG &amp; QTY</th>
                            </tr>
                            <tr>
                                @foreach ($ngCodes as $code)
                                    <th>{{ $ngLabels[$code] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($models as $model)
                                @if ($model->products->count() > 0)
                                    @foreach ($model->products as $product)
                                        <tr data-product-row="{{ $rowIndex }}" data-model-id="{{ $model->id }}"
                                            data-model-name="{{ $model->model }}" data-product-name="{{ $product->name }}">
                                            @if ($loop->first)
                                                <td rowspan="{{ $model->products->count() }}" class="merged-model">
                                                    {{ $loop->parent->iteration }}</td>
                                                <td rowspan="{{ $model->products->count() }}" class="merged-model">
                                                    <strong>{{ $model->model }}</strong>
                                                </td>
                                            @endif
                                            <td class="product-name">
                                                {{ $product->name }}
                                                <input type="hidden" name="items[{{ $rowIndex }}][master_model_id]"
                                                    value="{{ $model->id }}">
                                                <input type="hidden" name="items[{{ $rowIndex }}][product_id]"
                                                    value="{{ $product->id }}">
                                            </td>
                                            @foreach ($ngCodes as $code)
                                                @php $ng = $ngMap[$code] ?? null; @endphp
                                                <td>
                                                    @if ($ng)
                                                        <div class="qty-control">
                                                            <button type="button" class="qty-step" data-step="-1"
                                                                aria-label="Kurangi qty {{ $ngLabels[$code] }}">−</button>
                                                            <input type="number" class="qty-input" min="0"
                                                                step="1" inputmode="numeric" autocomplete="off"
                                                                data-ng-code="{{ $code }}"
                                                                data-model-id="{{ $model->id }}"
                                                                data-row-index="{{ $rowIndex }}"
                                                                name="items[{{ $rowIndex }}][qty][{{ $ng->id }}]"
                                                                value="{{ old('items.' . $rowIndex . '.qty.' . $ng->id, '') }}">
                                                            <button type="button" class="qty-step" data-step="1"
                                                                aria-label="Tambah qty {{ $ngLabels[$code] }}">+</button>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        @php $rowIndex++; @endphp
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="subtotal-row">
                                <td colspan="3">SUB TOTAL</td>
                                @foreach ($ngCodes as $code)
                                    <td data-order-total="{{ $code }}"></td>
                                @endforeach
                            </tr>
                            <tr class="grand-row">
                                <td colspan="3">GRAND TOTAL</td>
                                <td colspan="4" id="grandTotal"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="review-empty">Belum ada Model &amp; Produk untuk Line Anda.</div>
            @endif

            <div class="field">
                <label for="orderDescription">Catatan Umum</label>
                <textarea id="orderDescription" name="description" rows="3" maxlength="1000"
                    placeholder="Catatan untuk seluruh order (opsional)">{{ old('description') }}</textarea>
            </div>

            <div class="form-notice" id="formNotice">Masukkan minimal satu Quantity NG sebelum mengirim order.</div>
            <div class="actions">
                <button type="button" class="btn-reset" id="resetButton">Kosongkan Form</button>
                <button type="submit" class="btn-submit" id="submitButton" disabled>Kirim Order Repair</button>
            </div>
        </form>
    </div>

    <script>
        (function() {
            const codes = ['P', 'H', 'C', 'S'];
            const form = document.getElementById('orderForm');
            const submitButton = document.getElementById('submitButton');
            const notice = document.getElementById('formNotice');

            function valueOf(input) {
                const n = parseInt(input.value, 10);
                return Number.isFinite(n) && n > 0 ? n : 0
            }

            function display(el, n, suffix = '') {
                if (el) el.textContent = n > 0 ? `${n}${suffix}` : ''
            }

            let holdTimer = null,
                repeatTimer = null;

            function stopHold() {
                clearTimeout(holdTimer);
                clearInterval(repeatTimer);
                holdTimer = null;
                repeatTimer = null
            }

            function changeQty(btn) {
                const control = btn.closest('.qty-control');
                const input = control?.querySelector('.qty-input');
                if (!input) return;
                const step = parseInt(btn.dataset.step, 10) || 0;
                const current = valueOf(input);
                const next = Math.max(0, current + step);
                input.value = next > 0 ? String(next) : '';
                input.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            }
            document.querySelectorAll('.qty-step').forEach(btn => {
                btn.addEventListener('pointerdown', e => {
                    e.preventDefault();
                    stopHold();
                    changeQty(btn);
                    holdTimer = setTimeout(() => {
                        repeatTimer = setInterval(() => changeQty(btn), 120)
                    }, 500)
                });
                ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev => btn.addEventListener(ev,
                    stopHold));
                btn.addEventListener('contextmenu', e => e.preventDefault());
            });

            function calculate() {
                const orderTotals = {
                    P: 0,
                    H: 0,
                    C: 0,
                    S: 0
                };
                document.querySelectorAll('[data-product-row] .qty-input').forEach(input => {
                    const code = input.dataset.ngCode;
                    if (Object.prototype.hasOwnProperty.call(orderTotals, code)) orderTotals[code] += valueOf(
                        input);
                });
                const grand = codes.reduce((sum, c) => sum + orderTotals[c], 0);
                codes.forEach(code => display(document.querySelector(`[data-order-total="${code}"]`), orderTotals[
                    code]));
                display(document.getElementById('grandTotal'), grand);
                if (submitButton) {
                    submitButton.disabled = grand <= 0;
                    submitButton.textContent = grand > 0 ? `Kirim Order Repair · ${grand} NG` : 'Kirim Order Repair';
                }
                if (grand > 0 && notice) notice.classList.remove('show');
            }
            document.querySelectorAll('.qty-input').forEach(input => input.addEventListener('input', () => {
                if (input.value !== '' && parseInt(input.value, 10) < 0) input.value = '';
                calculate()
            }));
            document.getElementById('resetButton')?.addEventListener('click', () => {
                document.querySelectorAll('.qty-input').forEach(i => i.value = '');
                document.getElementById('orderDescription').value = '';
                calculate()
            });
            form?.addEventListener('submit', e => {
                const total = Array.from(document.querySelectorAll('.qty-input')).reduce((s, i) => s + valueOf(
                    i), 0);
                if (total <= 0) {
                    e.preventDefault();
                    notice?.classList.add('show')
                }
            });
            calculate();
        })();
    </script>
@endsection
