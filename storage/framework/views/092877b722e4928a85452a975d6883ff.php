<?php $__env->startSection('title', 'History Order Repair Box'); ?>
<?php $__env->startSection('header', 'History Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .page-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .page-head {
            margin-bottom: 18px;
        }

        .page-head h2 {
            margin: 0 0 6px;
            font-size: 20px;
            font-weight: 800;
            color: #172033;
        }

        .page-head p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        /* =================================================
                   FILTER
                ================================================== */

        .history-filter-box {
            margin-bottom: 18px;
            padding: 16px 18px;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            background: #f8fafc;
        }

        .history-filter-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .history-filter-field {
            min-width: 190px;
        }

        .history-filter-field>label {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .history-date-input {
            position: relative;
        }

        .history-date-input>i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b7cf6;
            font-size: 12px;
            pointer-events: none;
            z-index: 2;
        }

        .history-date-input input {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 34px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .history-date-input input::placeholder {
            color: #a3adba;
            font-weight: 500;
        }

        .history-date-input input:hover {
            border-color: #cbd5e1;
        }

        .history-date-input input:focus,
        .history-date-input.open input {
            border-color: #7c6cf4;
            box-shadow: 0 0 0 3px rgba(124, 108, 244, .12);
        }

        .history-filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .history-filter-btn {
            height: 38px;
            padding: 0 16px;
            border: none;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: transform .16s ease, background .16s ease, box-shadow .16s ease;
        }

        .history-filter-btn:hover {
            transform: translateY(-1px);
        }

        .history-filter-btn-primary {
            background: #6d5dfc;
            color: #fff;
            box-shadow: 0 6px 15px rgba(109, 93, 252, .20);
        }

        .history-filter-btn-primary:hover {
            background: #5d4df0;
            color: #fff;
        }

        .history-filter-btn-reset {
            background: #fff;
            color: #64748b;
            border: 1px solid #dbe2ea;
        }

        .history-filter-btn-reset:hover {
            background: #f1f5f9;
            color: #334155;
        }

        /* =================================================
                   CALENDAR POPUP
                ================================================== */

        .calendar-popup {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 300px;
            padding: 16px;
            box-sizing: border-box;
            background: #fff;
            border: 1px solid #e4e0ff;
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .18);
            z-index: 9999;
            animation: calendarShow .16s ease;
        }

        .history-date-input.open .calendar-popup {
            display: block;
        }

        @keyframes calendarShow {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .calendar-header strong {
            font-size: 13px;
            font-weight: 800;
            color: #172033;
            text-transform: capitalize;
        }

        .calendar-nav {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 50%;
            background: #f1efff;
            color: #6557dc;
            font-weight: 900;
            cursor: pointer;
            transition: background .15s ease;
        }

        .calendar-nav:hover {
            background: #e4e0ff;
        }

        .calendar-week,
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-week {
            margin-bottom: 6px;
        }

        .calendar-week span {
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            color: #94a3b8;
        }

        .calendar-day,
        .calendar-empty {
            height: 34px;
        }

        .calendar-day {
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 50%;
            background: transparent;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            transition: background .15s ease, color .15s ease;
        }

        .calendar-day:hover:not(:disabled) {
            background: #f1efff;
            color: #5d4df0;
        }

        .calendar-day.today {
            box-shadow: inset 0 0 0 2px #c9c2ff;
        }

        .calendar-day.in-range {
            background: #f1efff;
            color: #5d4df0;
            border-radius: 8px;
        }

        .calendar-day.active {
            background: #6d5dfc;
            color: #fff;
            border-radius: 50%;
        }

        .calendar-day:disabled {
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .calendar-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .calendar-reset {
            border: none;
            border-radius: 9px;
            padding: 7px 14px;
            background: #f1f5f9;
            color: #475569;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .calendar-reset:hover {
            background: #e2e8f0;
        }

        /* =================================================
                   TOOLBAR
                ================================================== */

        .history-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }

        .history-toolbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .history-per-page {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .history-per-page select {
            width: 72px;
            height: 34px;
            padding: 0 10px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            outline: none;
            cursor: pointer;
        }

        .history-per-page select:focus {
            border-color: #7c6cf4;
            box-shadow: 0 0 0 3px rgba(124, 108, 244, .12);
        }

        .history-info {
            font-size: 11px;
            color: #94a3b8;
        }

        .history-info strong {
            color: #475569;
            font-weight: 800;
        }

        .history-search {
            position: relative;
            width: 320px;
            max-width: 100%;
        }

        .history-search>i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
        }

        .history-search input {
            width: 100%;
            height: 36px;
            padding: 0 38px 0 34px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .history-search input::placeholder {
            color: #a3adba;
            font-weight: 500;
        }

        .history-search input:focus {
            border-color: #7c6cf4;
            box-shadow: 0 0 0 3px rgba(124, 108, 244, .12);
        }

        .history-search-clear {
            position: absolute;
            right: 7px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
        }

        .history-search-clear:hover {
            background: #f1f5f9;
            color: #475569;
        }

        /* =================================================
                   TABLE
                ================================================== */

        .orders-table-wrap {
            overflow-x: auto;
            border: 1px solid #e9eef4;
            border-radius: 12px;
        }

        .orders-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f9fafb;
            text-align: left;
            padding: 12px 14px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            white-space: nowrap;
            border-bottom: 1px solid #e9eef4;
        }

        .orders-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #374151;
            vertical-align: middle;
        }

        .orders-table tbody tr {
            cursor: pointer;
            transition: background .15s ease;
        }

        .orders-table tbody tr:hover {
            background: #f8f7ff;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 700;
            text-align: center;
        }

        .order-number {
            font-weight: 800;
            color: #111827;
            white-space: nowrap;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 76px;
            min-height: 28px;
            padding: 0 11px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            cursor: default;
        }

        .order-empty-text {
            font-size: 12px;
            color: #94a3b8;
        }

        /* =================================================
                   PAGINATION
                ================================================== */

        .history-pagination {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
        }

        .history-pagination-list {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .history-pagination-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            background: #fff;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }

        .history-pagination-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .history-pagination-btn.active {
            background: #6d5dfc;
            border-color: #6d5dfc;
            color: #fff;
        }

        .history-pagination-btn.disabled {
            background: #f8fafc;
            border-color: #e5e7eb;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .history-pagination-prev,
        .history-pagination-next {
            min-width: 78px;
        }

        .history-pagination-dots {
            min-width: 28px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
        }

        /* =================================================
                   RESPONSIVE
                ================================================== */

        @media (max-width: 900px) {
            .history-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .history-search {
                width: 100%;
            }

            .history-filter-field {
                flex: 1 1 180px;
            }

            .history-filter-actions {
                width: 100%;
            }

            .history-filter-btn {
                flex: 1;
            }
        }

        @media (max-width: 600px) {
            .page-card {
                padding: 16px;
            }

            .history-filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .history-filter-field {
                width: 100%;
            }

            .calendar-popup {
                width: calc(100vw - 72px);
                max-width: 320px;
            }

            .history-pagination {
                justify-content: center;
            }

            .history-pagination-list {
                gap: 3px;
            }

            .history-pagination-prev,
            .history-pagination-next {
                min-width: 34px;
                padding: 0 8px;
                font-size: 0;
            }

            .history-pagination-prev::before {
                content: '‹';
                font-size: 14px;
            }

            .history-pagination-next::before {
                content: '›';
                font-size: 14px;
            }
        }
    </style>


    <?php
        $orders->appends(request()->query());

        $currentPage = $orders->currentPage();
        $lastPage = $orders->lastPage();

        $hasFilter = request('search') || request('start_date') || request('end_date');
    ?>


    <div class="page-card">

        

        <div class="page-head">
            <h2>History Order Repair Box</h2>
            <p>Riwayat seluruh order Repair Box yang sudah selesai.</p>
        </div>


        <form action="<?php echo e(route('user.orders.history')); ?>" method="GET" id="historyForm">

            

            <div class="history-filter-box">

                <div class="history-filter-form">

                    <div class="history-filter-field">
                        <label>Dari Tanggal</label>

                        <div class="history-date-input" data-datepicker="start">
                            <i class="fa-regular fa-calendar"></i>

                            <input type="text" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                                placeholder="Pilih tanggal" readonly autocomplete="off">

                            <div class="calendar-popup"></div>
                        </div>
                    </div>


                    <div class="history-filter-field">
                        <label>Sampai Tanggal</label>

                        <div class="history-date-input" data-datepicker="end">
                            <i class="fa-regular fa-calendar"></i>

                            <input type="text" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                                placeholder="Pilih tanggal" readonly autocomplete="off">

                            <div class="calendar-popup"></div>
                        </div>
                    </div>


                    <div class="history-filter-actions">

                        <button type="submit" class="history-filter-btn history-filter-btn-primary">
                            <i class="fa-solid fa-filter"></i>
                            Terapkan Filter
                        </button>

                        <?php if(request('start_date') || request('end_date')): ?>
                            <a href="<?php echo e(route('user.orders.history', array_filter(['search' => request('search'), 'per_page' => request('per_page')]))); ?>"
                                class="history-filter-btn history-filter-btn-reset">
                                <i class="fa-solid fa-rotate-left"></i>
                                Reset
                            </a>
                        <?php endif; ?>

                    </div>

                </div>

            </div>


            

            <div class="history-toolbar">

                <div class="history-toolbar-left">

                    <label class="history-per-page">

                        <select name="per_page" id="per_page" onchange="this.form.submit()">
                            <?php $__currentLoopData = [10, 25, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($size); ?>"
                                    <?php echo e((int) request('per_page', 10) === $size ? 'selected' : ''); ?>>
                                    <?php echo e($size); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>


                </div>


                <div class="history-search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        placeholder="Cari No Order, User, atau Line..." autocomplete="off">

                    <?php if(request('search')): ?>
                        <button type="button" class="history-search-clear" title="Hapus pencarian"
                            onclick="this.form.querySelector('input[name=search]').value=''; this.form.submit();">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    <?php endif; ?>

                </div>

            </div>

        </form>


        

        <div class="orders-table-wrap">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th style="width:55px;text-align:center;">No</th>
                        <th>Tanggal</th>
                        <th>No Order</th>
                        <th>Jenis Order</th>
                        <th>Line</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="window.location='<?php echo e(route('user.orders.history.show', $order)); ?>'"
                            title="Klik untuk melihat detail history order">

                            <td class="order-no"><?php echo e($orders->firstItem() + $index); ?></td>

                            <td><?php echo e($order->created_at?->format('d-m-Y H:i') ?? '-'); ?></td>

                            <td class="order-number"><?php echo e($order->order_number); ?></td>

                            <td>Repair Box</td>

                            <td><?php echo e($order->line?->name ?? '-'); ?></td>

                            <td><?php echo e($order->quantity); ?></td>

                            <td>
                                <span class="status status-confirmed">Selesai</span>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr style="cursor:default;">
                            <td colspan="7" class="order-empty">
                                <div class="order-empty-text">
                                    <?php echo e($hasFilter ? 'Tidak ada order yang sesuai dengan filter.' : 'Belum ada history Order Repair Box.'); ?>

                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        

        <?php if($lastPage > 1): ?>

            <div class="history-pagination">

                <div class="history-pagination-list">

                    <?php if($orders->onFirstPage()): ?>
                        <span class="history-pagination-btn history-pagination-prev disabled">Previous</span>
                    <?php else: ?>
                        <a href="<?php echo e($orders->previousPageUrl()); ?>"
                            class="history-pagination-btn history-pagination-prev">Previous</a>
                    <?php endif; ?>


                    <a href="<?php echo e($orders->url(1)); ?>"
                        class="history-pagination-btn <?php echo e($currentPage == 1 ? 'active' : ''); ?>">1</a>


                    <?php if($currentPage > 3): ?>
                        <span class="history-pagination-dots">...</span>
                    <?php endif; ?>


                    <?php for($page = max(2, $currentPage - 1); $page <= min($lastPage - 1, $currentPage + 1); $page++): ?>
                        <a href="<?php echo e($orders->url($page)); ?>"
                            class="history-pagination-btn <?php echo e($currentPage == $page ? 'active' : ''); ?>"><?php echo e($page); ?></a>
                    <?php endfor; ?>


                    <?php if($currentPage < $lastPage - 2): ?>
                        <span class="history-pagination-dots">...</span>
                    <?php endif; ?>


                    <a href="<?php echo e($orders->url($lastPage)); ?>"
                        class="history-pagination-btn <?php echo e($currentPage == $lastPage ? 'active' : ''); ?>"><?php echo e($lastPage); ?></a>


                    <?php if($orders->hasMorePages()): ?>
                        <a href="<?php echo e($orders->nextPageUrl()); ?>"
                            class="history-pagination-btn history-pagination-next">Next</a>
                    <?php else: ?>
                        <span class="history-pagination-btn history-pagination-next disabled">Next</span>
                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>

    </div>


    <script>
        (function() {

            var pad = function(n) {
                return String(n).padStart(2, '0');
            };

            var toValue = function(y, m, d) {
                return y + '-' + pad(m + 1) + '-' + pad(d);
            };

            var parseValue = function(str) {
                if (!str) return null;
                var p = str.split('-').map(Number);
                return new Date(p[0], p[1] - 1, p[2]);
            };

            var startInput = document.getElementById('start_date');
            var endInput = document.getElementById('end_date');
            var pickers = document.querySelectorAll('[data-datepicker]');

            function closeAll() {
                pickers.forEach(function(p) {
                    p.classList.remove('open');
                });
            }

            pickers.forEach(function(wrap) {

                var role = wrap.getAttribute('data-datepicker');
                var input = wrap.querySelector('input');
                var popup = wrap.querySelector('.calendar-popup');

                var base = parseValue(input.value) || new Date();
                var view = new Date(base.getFullYear(), base.getMonth(), 1);

                function render() {

                    var year = view.getFullYear();
                    var month = view.getMonth();

                    // Senin sebagai awal minggu
                    var firstDay = new Date(year, month, 1).getDay();
                    firstDay = firstDay === 0 ? 6 : firstDay - 1;

                    var totalDays = new Date(year, month + 1, 0).getDate();

                    var title = view.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });

                    var start = startInput.value;
                    var end = endInput.value;
                    var selected = input.value;

                    var today = new Date();
                    var todayValue = toValue(today.getFullYear(), today.getMonth(), today.getDate());

                    popup.innerHTML =
                        '<div class="calendar-header">' +
                        '<button type="button" class="calendar-nav" data-nav="-1">←</button>' +
                        '<strong>' + title + '</strong>' +
                        '<button type="button" class="calendar-nav" data-nav="1">→</button>' +
                        '</div>' +
                        '<div class="calendar-week">' +
                        '<span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span><span>M</span>' +
                        '</div>' +
                        '<div class="calendar-days"></div>' +
                        '<div class="calendar-footer">' +
                        '<button type="button" class="calendar-reset">Reset</button>' +
                        '</div>';

                    var days = popup.querySelector('.calendar-days');

                    for (var i = 0; i < firstDay; i++) {
                        var empty = document.createElement('div');
                        empty.className = 'calendar-empty';
                        days.appendChild(empty);
                    }

                    for (var d = 1; d <= totalDays; d++) {

                        var value = toValue(year, month, d);
                        var btn = document.createElement('button');

                        btn.type = 'button';
                        btn.className = 'calendar-day';
                        btn.textContent = d;
                        btn.setAttribute('data-value', value);

                        if (value === todayValue) btn.classList.add('today');
                        if (start && end && value > start && value < end) btn.classList.add('in-range');
                        if (value === selected) btn.classList.add('active');

                        // Tanggal awal tidak boleh melewati tanggal akhir, dan sebaliknya
                        if (role === 'start' && end && value > end) btn.disabled = true;
                        if (role === 'end' && start && value < start) btn.disabled = true;

                        days.appendChild(btn);
                    }
                }

                input.addEventListener('click', function() {
                    var isOpen = wrap.classList.contains('open');

                    closeAll();

                    if (!isOpen) {
                        var current = parseValue(input.value);
                        if (current) {
                            view = new Date(current.getFullYear(), current.getMonth(), 1);
                        }

                        render();
                        wrap.classList.add('open');
                    }
                });

                popup.addEventListener('click', function(e) {
                    e.stopPropagation();

                    var nav = e.target.closest('[data-nav]');
                    var day = e.target.closest('.calendar-day');
                    var reset = e.target.closest('.calendar-reset');

                    if (nav) {
                        view.setMonth(view.getMonth() + Number(nav.getAttribute('data-nav')));
                        render();
                        return;
                    }

                    if (day && !day.disabled) {
                        input.value = day.getAttribute('data-value');
                        closeAll();
                        return;
                    }

                    if (reset) {
                        input.value = '';
                        closeAll();
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.history-date-input')) {
                    closeAll();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAll();
                }
            });

        })();
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/user/orders/history.blade.php ENDPATH**/ ?>