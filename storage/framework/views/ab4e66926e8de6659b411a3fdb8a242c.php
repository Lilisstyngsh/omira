<?php $__env->startSection('title', 'Buat Order Repair Box'); ?>
<?php $__env->startSection('header', 'Buat Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>
<style>
    :root {
        --border: #94a3b8;
        --border-soft: #cbd5e1;
        --text: #0f172a;
        --muted: #64748b;
        --primary: #4f46e5;
        --primary-dark: #4338ca
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
        border-color: #6366f1;
        box-shadow: 0 0 0 1px rgba(99, 102, 241, .12)
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
        color: #4338ca;
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
        display: grid;
        grid-template-columns: 42px 56px 42px;
        border: 2px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        width: 140px;
        margin: auto
    }

    .qty-input {
        width: 56px;
        height: 48px;
        border: 0 !important;
        border-left: 1px solid var(--border) !important;
        border-right: 1px solid var(--border) !important;
        outline: 0 !important;
        text-align: center;
        font-size: 15px;
        font-weight: 600;
        color: #0f172a;
        background: #fff;
        padding: 0 3px;
        -moz-appearance: textfield
    }

    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0
    }

    .qty-control:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12)
    }

    .qty-step {
        width: 42px;
        height: 48px;
        border: 0;
        background: #f8fafc;
        color: #0f172a;
        font-size: 16px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        touch-action: none;
        user-select: none
    }

    .qty-step:active {
        background: #e2e8f0
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
        font-weight: 600;
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
</style>

<div class="create-wrap">
    <?php if($errors->any()): ?>
    <div class="errors">
        <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <div class="top-info">
        <div class="top-info-item"><span>Plant</span><strong><?php echo e($line->plant?->name ?? '-'); ?></strong></div>
        <div class="top-info-item"><span>Line</span><strong><?php echo e($line->name); ?></strong></div>
        <div class="top-info-item"><span>Tanggal</span><strong><?php echo e(now()->format('d-m-Y H:i')); ?></strong></div>
    </div>

    <?php
    $ngMap = $ngTypes->keyBy(fn($ngType) => strtoupper($ngType->code));
    $ngCodes = ['P','H','C','S'];
    $hasProducts = $models->sum(fn($model) => $model->products->count()) > 0;
    $rowIndex = 0;
    ?>

    <form method="POST" action="<?php echo e(route('user.orders.store')); ?>" id="orderForm">
        <?php echo csrf_field(); ?>

        <div class="section-head">
            <div>
                <h3>Pilih Model</h3>
                <p>Tap card untuk membuka atau menutup input.</p>
            </div>
        </div>

        <?php if($hasProducts): ?>
        <div class="model-picker" id="modelPicker">
            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="model-card" data-model-card="<?php echo e($model->id); ?>" aria-expanded="false">
                <span class="model-card-main">
                    <span class="model-card-title">Model <?php echo e($model->model); ?></span>
                    <span class="model-card-meta"><?php echo e($model->products->count()); ?> produk</span>
                </span>
                <span style="display:flex;align-items:center;gap:8px">
                    <span class="model-card-total" data-card-total="<?php echo e($model->id); ?>"></span>
                    <span class="model-card-chevron">›</span>
                </span>
            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="editor-stack" id="editorStack">
            <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="model-editor" data-model-editor="<?php echo e($model->id); ?>" hidden>
                <div class="model-editor-head">
                    <strong>Model <?php echo e($model->model); ?></strong>
                    <span data-editor-meta="<?php echo e($model->id); ?>"><?php echo e($model->products->count()); ?> produk</span>
                </div>
                <div class="qty-table-wrap">
                    <table class="qty-table">
                        <thead>
                            <tr>
                                <th>Produk</th><?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($code); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $model->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-product-row="<?php echo e($rowIndex); ?>" data-model-id="<?php echo e($model->id); ?>" data-model-name="<?php echo e($model->model); ?>" data-product-name="<?php echo e($product->name); ?>">
                                <td>
                                    <span class="product-name"><?php echo e($product->name); ?></span>
                                    <input type="hidden" name="items[<?php echo e($rowIndex); ?>][master_model_id]" value="<?php echo e($model->id); ?>">
                                    <input type="hidden" name="items[<?php echo e($rowIndex); ?>][product_id]" value="<?php echo e($product->id); ?>">
                                </td>
                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $ng=$ngMap[$code]??null; ?>
                                <td>
                                    <?php if($ng): ?>
                                    <div class="qty-control">
                                        <button type="button" class="qty-step" data-step="-1" aria-label="Kurangi qty <?php echo e($code); ?>">▼</button>
                                        <input type="number" class="qty-input" min="0" step="1" inputmode="numeric" autocomplete="off"
                                            data-ng-code="<?php echo e($code); ?>" data-model-id="<?php echo e($model->id); ?>" data-row-index="<?php echo e($rowIndex); ?>"
                                            name="items[<?php echo e($rowIndex); ?>][qty][<?php echo e($ng->id); ?>]"
                                            value="<?php echo e(old('items.'.$rowIndex.'.qty.'.$ng->id, '')); ?>">
                                        <button type="button" class="qty-step" data-step="1" aria-label="Tambah qty <?php echo e($code); ?>">▲</button>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="total-cell" data-product-total="<?php echo e($rowIndex); ?>"></td>
                            </tr>
                            <?php $rowIndex++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total Model <?php echo e($model->model); ?></td>
                                <?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td style="text-align:center" data-model-total-ng="<?php echo e($model->id); ?>:<?php echo e($code); ?>"></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="total-cell" data-model-grand="<?php echo e($model->id); ?>"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="review-box">
            <div class="section-head">
                <div>
                    <h3>Ringkasan Order</h3>
                    <p>Hanya produk yang memiliki Qty NG yang ditampilkan.</p>
                </div>
            </div>
            <div id="reviewEmpty" class="review-empty">Belum ada Qty NG yang diinput.</div>
            <div class="qty-table-wrap" id="reviewTableWrap" hidden>
                <table class="review-table">
                    <thead>
                        <tr>
                            <th>Model</th>
                            <th>Produk</th><?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($code); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="reviewBody"></tbody>
                    <tfoot>
                        <tr class="review-grand">
                            <td colspan="2">Total Jenis NG</td><?php $__currentLoopData = $ngCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td style="text-align:center" data-order-total="<?php echo e($code); ?>"></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><td></td>
                        </tr>
                        <tr class="review-grand">
                            <td colspan="6">Grand Total Order</td>
                            <td style="text-align:center" id="grandTotal"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="review-empty">Belum ada Model &amp; Produk untuk Line Anda.</div>
        <?php endif; ?>

        <div class="field"><label>Keterangan <span style="font-weight:400;color:#64748b">(opsional)</span></label><textarea name="description" placeholder="Tambahkan keterangan jika diperlukan..."><?php echo e(old('description')); ?></textarea></div>
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
        const editorStack = document.getElementById('editorStack');
        const submitButton = document.getElementById('submitButton');
        const notice = document.getElementById('formNotice');
        const reviewBody = document.getElementById('reviewBody');
        const reviewWrap = document.getElementById('reviewTableWrap');
        const reviewEmpty = document.getElementById('reviewEmpty');

        function valueOf(input) {
            const n = parseInt(input.value, 10);
            return Number.isFinite(n) && n > 0 ? n : 0
        }

        function display(el, n, suffix = '') {
            if (el) el.textContent = n > 0 ? `${n}${suffix}` : ''
        }

        function setEditor(modelId, open, bringToTop = true) {
            const card = document.querySelector(`[data-model-card="${modelId}"]`);
            const editor = document.querySelector(`[data-model-editor="${modelId}"]`);
            if (!card || !editor) return;
            card.classList.toggle('is-open', open);
            card.setAttribute('aria-expanded', open ? 'true' : 'false');
            editor.hidden = !open;
            if (open && bringToTop && editorStack) editorStack.prepend(editor);
        }

        document.querySelectorAll('[data-model-card]').forEach(card => {
            card.addEventListener('click', () => {
                const id = card.dataset.modelCard;
                const open = card.getAttribute('aria-expanded') === 'true';
                setEditor(id, !open, true)
            });
        });

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
            ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev => btn.addEventListener(ev, stopHold));
            btn.addEventListener('contextmenu', e => e.preventDefault());
        });

        function calculate() {
            const orderTotals = {
                P: 0,
                H: 0,
                C: 0,
                S: 0
            };
            const modelTotals = {};
            const rows = [];
            document.querySelectorAll('[data-product-row]').forEach(row => {
                const modelId = row.dataset.modelId,
                    modelName = row.dataset.modelName,
                    productName = row.dataset.productName;
                const vals = {
                    P: 0,
                    H: 0,
                    C: 0,
                    S: 0
                };
                row.querySelectorAll('.qty-input').forEach(input => {
                    const code = input.dataset.ngCode;
                    const n = valueOf(input);
                    vals[code] = n;
                    orderTotals[code] += n;
                    if (!modelTotals[modelId]) modelTotals[modelId] = {
                        name: modelName,
                        P: 0,
                        H: 0,
                        C: 0,
                        S: 0,
                        total: 0,
                        filled: 0
                    };
                    modelTotals[modelId][code] += n
                });
                const total = codes.reduce((sum, c) => sum + vals[c], 0);
                display(document.querySelector(`[data-product-total="${row.dataset.productRow}"]`), total);
                if (total > 0) {
                    rows.push({
                        modelId,
                        modelName,
                        productName,
                        vals,
                        total
                    });
                    modelTotals[modelId].filled++
                }
                if (modelTotals[modelId]) modelTotals[modelId].total += total;
            });
            Object.keys(modelTotals).forEach(id => {
                const m = modelTotals[id];
                codes.forEach(c => display(document.querySelector(`[data-model-total-ng="${id}:${c}"]`), m[c]));
                display(document.querySelector(`[data-model-grand="${id}"]`), m.total);
                display(document.querySelector(`[data-card-total="${id}"]`), m.total, ' NG');
                const meta = document.querySelector(`[data-editor-meta="${id}"]`);
                if (meta) meta.textContent = m.filled > 0 ? `${m.filled} produk terisi · ${m.total} NG` : `${document.querySelectorAll(`[data-product-row][data-model-id="${id}"]`).length} produk`;
            });
            document.querySelectorAll('[data-model-card]').forEach(card => {
                if (!modelTotals[card.dataset.modelCard]) {
                    const t = card.querySelector('[data-card-total]');
                    if (t) t.textContent = ''
                }
            });
            const grand = codes.reduce((sum, c) => sum + orderTotals[c], 0);
            codes.forEach(c => display(document.querySelector(`[data-order-total="${c}"]`), orderTotals[c]));
            display(document.getElementById('grandTotal'), grand, ' NG');
            if (reviewBody) {
                reviewBody.innerHTML = '';
                const grouped = {};
                rows.forEach(r => {
                    if (!grouped[r.modelId]) grouped[r.modelId] = [];
                    grouped[r.modelId].push(r)
                });
                Object.keys(grouped).forEach(modelId => {
                    const group = grouped[modelId];
                    const m = modelTotals[modelId];
                    const head = document.createElement('tr');
                    head.className = 'review-model';
                    head.innerHTML = `<td colspan="7">Model ${escapeHtml(m.name)}</td>`;
                    reviewBody.appendChild(head);
                    group.forEach(r => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td></td><td>${escapeHtml(r.productName)}</td>${codes.map(c=>`<td style="text-align:center">${r.vals[c]>0?r.vals[c]:''}</td>`).join('')}<td style="text-align:center;font-weight:550">${r.total}</td>`;
                        reviewBody.appendChild(tr)
                    });
                    const totalRow = document.createElement('tr');
                    totalRow.className = 'review-model-total';
                    totalRow.innerHTML = `<td colspan="2">Total Model ${escapeHtml(m.name)}</td>${codes.map(c=>`<td style="text-align:center">${m[c]>0?m[c]:''}</td>`).join('')}<td style="text-align:center">${m.total>0?m.total:''}</td>`;
                    reviewBody.appendChild(totalRow);
                });
            }
            if (reviewWrap) reviewWrap.hidden = grand <= 0;
            if (reviewEmpty) reviewEmpty.hidden = grand > 0;
            if (submitButton) {
                submitButton.disabled = grand <= 0;
                submitButton.textContent = grand > 0 ? `Kirim Order Repair · ${grand} NG` : 'Kirim Order Repair'
            }
            if (grand > 0 && notice) notice.classList.remove('show');
        }

        function escapeHtml(s) {
            return String(s ?? '').replace(/[&<>'"]/g, ch => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#039;',
                '"': '&quot;'
            } [ch]))
        }
        document.querySelectorAll('.qty-input').forEach(input => input.addEventListener('input', () => {
            if (input.value !== '' && parseInt(input.value, 10) < 0) input.value = '';
            calculate()
        }));
        document.getElementById('resetButton')?.addEventListener('click', () => {
            document.querySelectorAll('.qty-input').forEach(i => i.value = '');
            document.querySelectorAll('[data-model-card]').forEach(c => setEditor(c.dataset.modelCard, false, false));
            calculate()
        });
        form?.addEventListener('submit', e => {
            const total = Array.from(document.querySelectorAll('.qty-input')).reduce((s, i) => s + valueOf(i), 0);
            if (total <= 0) {
                e.preventDefault();
                notice?.classList.add('show')
            }
        });
        calculate();
        document.querySelectorAll('[data-model-card]').forEach(card => {
            const id = card.dataset.modelCard;
            const hasOld = Array.from(document.querySelectorAll(`[data-product-row][data-model-id="${id}"] .qty-input`)).some(i => valueOf(i) > 0);
            if (hasOld) setEditor(id, true, true)
        });
    })();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/create.blade.php ENDPATH**/ ?>