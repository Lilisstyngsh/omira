<?php $__env->startSection('title', 'History Order Repair Box'); ?>
<?php $__env->startSection('header', 'History Order Repair Box'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .page-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-head h2 {
            margin: 0;
            font-size: 17px;
            color: #111827;
        }

        .orders-table-wrap {
            overflow-x: auto;
            border: 1px solid #dfe6ef;
            border-radius: 12px;
        }

        .orders-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .orders-table th {
            background: #f9fafb;
            text-align: center;
            padding: 12px 14px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            white-space: nowrap;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
        }

        .orders-table td {
            padding: 13px 14px;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
            font-size: 12px;
            color: #374151;
            vertical-align: middle;
        }

        .orders-table th:last-child,
        .orders-table td:last-child {
            border-right: 0;
        }

        .orders-table tbody tr {
            cursor: pointer;
            transition: .18s ease;
        }

        .orders-table tbody tr:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .orders-table tbody tr:active {
            background: #f1f5f9;
        }

        .orders-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-no {
            width: 55px;
            color: #94a3b8 !important;
            font-weight: 400;
            text-align: center;
        }

        .order-number {
            font-weight: 500;
            color: #111827;
            white-space: nowrap;
        }

        .order-user {
            color: #475569;
            white-space: nowrap;
        }

        .order-line-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3478c5;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-type-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            background: #f5f3ff;
            color: #6659df;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-qty {
            font-size: 13px !important;
            font-weight: 600;
            color: #172033 !important;
            text-align: center;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        /* =========================================================
                                                                                                                       DETAIL REPAIR BOX
                                                                                                                    ========================================================== */

        .repair-detail {
            min-width: 280px;
            max-width: 390px;
        }

        .repair-detail-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eef2f6;
        }

        .repair-detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .repair-detail-item:first-child {
            padding-top: 0;
        }

        .repair-detail-main {
            min-width: 0;
        }

        .repair-model {
            color: #172033;
            font-size: 10px;
            font-weight: 800;
            line-height: 1.4;
        }

        .repair-product {
            margin-top: 2px;
            color: #64748b;
            font-size: 9px;
            line-height: 1.4;
        }

        .repair-ng {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: center;
            min-width: 27px;
            height: 23px;
            padding: 0 7px;
            border-radius: 7px;
            background: #f5f3ff;
            color: #6557dc;
            font-size: 9px;
            font-weight: 800;
        }

        .repair-more {
            display: inline-block;
            margin-top: 7px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 700;
        }

        .repair-empty {
            color: #94a3b8;
            font-size: 10px;
        }

        .order-empty {
            padding: 55px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .order-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }

        @media (max-width: 900px) {
            .history-pagination {
                align-items: stretch;
                flex-direction: column;
            }

            .history-line-filter {
                width: 100%;
                justify-content: flex-end;
            }

            .history-line-filter select {
                min-width: 0;
                flex: 1;
            }

            .orders-table-wrap {
                overflow-x: auto;
            }

            .orders-table {
                min-width: 1250px;
            }
        }

        /* =========================================================
                                                                                               HISTORY FILTER
                                                                                            ========================================================= */

        .history-filter-box {
            margin-top: 18px;
            margin-bottom: 20px;

            padding: 16px 18px;

            border: 1px solid #e7ebf0;
            border-radius: 14px;

            background: linear-gradient(180deg,
                    #fbfcfd 0%,
                    #f8fafc 100%);

            box-shadow: 0 4px 16px rgba(15, 23, 42, .03);
        }

        .history-filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 13px;
        }

        .history-filter-title {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .history-filter-icon {
            width: 30px;
            height: 30px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #eef2ff;
            color: #5b5bd6;

            font-size: 12px;
        }

        .history-filter-title strong {
            display: block;

            color: #334155;

            font-size: 11px;
            font-weight: 800;
        }

        .history-filter-title span {
            display: block;

            margin-top: 2px;

            color: #94a3b8;

            font-size: 9px;
            line-height: 1.4;
        }

        .history-filter-active {
            display: inline-flex;
            align-items: center;

            min-height: 26px;

            padding: 0 9px;

            border-radius: 7px;

            background: #ecfdf3;
            color: #15803d;

            font-size: 9px;
            font-weight: 800;
        }

        .history-filter-form {
            display: flex;
            align-items: flex-end;

            gap: 10px;

            flex-wrap: wrap;
        }

        .history-filter-field {
            min-width: 180px;
        }

        .history-filter-field label {
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

            display: flex;
            align-items: center;
        }

        .history-date-input i {
            position: absolute;
            left: 11px;

            color: #94a3b8;

            font-size: 11px;

            pointer-events: none;
        }

        .history-date-input input {
            width: 100%;
            height: 38px;

            padding: 0 34px 0 32px;

            box-sizing: border-box;

            border: 1px solid #dbe2ea;
            border-radius: 9px;

            background: #fff;

            color: #334155;

            font-family: inherit;
            font-size: 11px;
            font-weight: 600;

            outline: none;

            transition:
                border-color .18s ease,
                box-shadow .18s ease,
                background .18s ease;
        }

        .history-date-input input:hover {
            border-color: #cbd5e1;
        }

        .history-date-input input:focus {
            border-color: #818cf8;

            background: #fff;

            box-shadow:
                0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-filter-actions {
            display: flex;
            align-items: center;

            gap: 7px;
        }

        .history-filter-btn {
            height: 38px;

            padding: 0 15px;

            border: none;
            border-radius: 9px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            font-family: inherit;
            font-size: 10px;
            font-weight: 800;

            cursor: pointer;

            text-decoration: none;

            transition:
                transform .16s ease,
                background .16s ease,
                box-shadow .16s ease;
        }

        .history-filter-btn:hover {
            transform: translateY(-1px);
        }

        .history-filter-btn-primary {
            background: #334155;
            color: #fff;

            box-shadow: 0 5px 12px rgba(51, 65, 85, .14);
        }

        .history-filter-btn-primary:hover {
            background: #1e293b;
            color: #fff;

            box-shadow: 0 7px 16px rgba(51, 65, 85, .18);
        }

        .history-filter-btn-reset {
            background: #fff;
            color: #64748b;

            border: 1px solid #dbe2ea;
        }

        .history-filter-btn-reset:hover {
            background: #f8fafc;
            color: #334155;
        }

        .history-filter-result {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 12px;
            padding-top: 11px;

            border-top: 1px dashed #e2e8f0;

            color: #64748b;

            font-size: 10px;
        }

        .history-filter-result strong {
            color: #334155;
            font-weight: 800;
        }


        @media (max-width: 900px) {

            .history-filter-form {
                align-items: stretch;
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

            .history-filter-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .history-filter-form {
                flex-direction: column;
            }

            .history-filter-field {
                width: 100%;
            }

            .history-filter-actions {
                width: 100%;
            }
        }

        

        .history-per-page {
            display: flex;
            align-items: center;
            justify-content: flex-start;

            margin-bottom: 17px;
        }

        .history-per-page-label {
            display: flex;
            align-items: center;
            gap: 10px;

            color: #94a3b8;

            font-size: 10px;
            font-weight: 600;
        }

        .history-per-page-label select {
            width: 72px;
            min-width: 72px;
            height: 34px;

            padding: 0 24px 0 10px;

            border: 1px solid #dbe2ea;
            border-radius: 8px;

            background: #fff;

            color: #334155;

            font-family: inherit;
            font-size: 10px;
            font-weight: 800;

            outline: none;
            cursor: pointer;

            box-sizing: border-box;

            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .history-per-page-label select:hover {
            border-color: #cbd5e1;
        }

        .history-per-page-label select:focus {
            border-color: #818cf8;

            box-shadow:
                0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-per-page-label span:last-child {
            color: #94a3b8;
        }

        
        .history-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
            margin-bottom: 17px;
        }

        .history-search {
            width: 320px;
            max-width: 100%;
        }

        .history-search-form {
            width: 100%;
        }

        .history-search-input {
            position: relative;

            display: flex;
            align-items: center;
        }

        .history-search-input i {
            position: absolute;
            left: 12px;

            color: #94a3b8;

            font-size: 11px;

            pointer-events: none;
        }

        .history-search-input input {
            width: 100%;
            height: 34px;

            padding: 0 38px 0 32px;

            box-sizing: border-box;

            border: 1px solid #dbe2ea;
            border-radius: 8px;

            background: #fff;

            color: #334155;

            font-family: inherit;
            font-size: 10px;
            font-weight: 600;

            outline: none;

            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .history-search-input input::placeholder {
            color: #a3adba;
            font-weight: 500;
        }

        .history-search-input input:hover {
            border-color: #cbd5e1;
        }

        .history-search-input input:focus {
            border-color: #818cf8;

            box-shadow:
                0 0 0 3px rgba(129, 140, 248, .10);
        }

        .history-search-clear {
            position: absolute;
            right: 10px;

            width: 22px;
            height: 22px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 6px;

            background: transparent;
            color: #94a3b8;

            cursor: pointer;

            transition:
                background .15s ease,
                color .15s ease;
        }

        .history-search-clear:hover {
            background: #f1f5f9;
            color: #475569;
        }


        /* =========================================================
                                                                       RESPONSIVE TOOLBAR
                                                                    ========================================================= */

        @media (max-width: 900px) {

            .history-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .history-search {
                width: 100%;
            }
        }

        /* =========================================================
                                               PAGINATION
                                            ========================================================= */

        .history-pagination {
            margin-top: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;

            width: 100%;
        }


        .history-line-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .history-line-filter label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            white-space: nowrap;
        }

        .history-line-filter select {
            min-width: 170px;
            height: 34px;
            padding: 0 30px 0 10px;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-size: 10px;
            font-weight: 700;
            outline: none;
        }

        .history-line-filter select:focus {
            border-color: #7c6cf2;
            box-shadow: 0 0 0 3px rgba(124, 108, 242, .10);
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
            border-radius: 7px;

            background: #fff;

            color: #64748b;

            font-size: 10px;
            font-weight: 700;
            line-height: 1;

            text-decoration: none;

            transition:
                background .15s ease,
                border-color .15s ease,
                color .15s ease;
        }

        .history-pagination-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .history-pagination-btn.active {
            background: #334155;
            border-color: #334155;
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
            min-width: 110px;
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


        /* =========================================================
                                                           RESPONSIVE
                                                        ========================================================= */

        @media (max-width: 600px) {

            .history-pagination nav>div:first-child {
                flex-direction: column;
                align-items: flex-start;
            }

            .history-pagination nav>div:first-child>div:last-child {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 3px;
            }
        }

        .history-date-picker {
            position: relative;
            display: flex;
            align-items: center;
        }


        .history-date-picker i {

            position: absolute;

            left: 12px;

            color: #94a3b8;

            font-size: 12px;

        }



        .history-date-picker input {

            height: 38px;

            width: 100%;

            padding-left: 35px;

            border-radius: 10px;

            border: 1px solid #dbe7df;

            background: white;

            font-size: 11px;

            font-weight: 700;

            color: #334155;

            cursor: pointer;

        }



        .history-date-picker input:focus {

            outline: none;

            border-color: #52b788;

            box-shadow:
                0 0 0 3px rgba(82, 183, 136, .15);

        }




        /* =====================
                                   CUSTOM CALENDAR
                                ===================== */


        .calendar-popup {

            display: none;

            position: absolute;

            margin-top: 8px;

            width: 300px;

            background: white;

            border-radius: 18px;

            padding: 18px;

            z-index: 9999;


            box-shadow:

                0 20px 45px rgba(0, 0, 0, .15);


            border:

                1px solid #dcfce7;

        }




        .calendar-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 18px;

        }



        .calendar-header button {


            width: 32px;

            height: 32px;

            border: none;

            border-radius: 50%;


            background: #ecfdf5;

            color: #15803d;


            cursor: pointer;

            font-weight: 900;


        }



        .calendar-header strong {

            font-size: 15px;

            font-weight: 900;

            color: #172033;

        }





        .calendar-week,
        .calendar-days {


            display: grid;

            grid-template-columns: repeat(7, 1fr);

            gap: 6px;


        }



        .calendar-week span {

            text-align: center;

            font-size: 10px;

            color: #94a3b8;

            font-weight: 800;

        }




        .calendar-day {


            height: 34px;


            display: flex;

            align-items: center;

            justify-content: center;


            border-radius: 50%;


            cursor: pointer;


            font-size: 12px;

            font-weight: 700;


            color: #334155;

        }



        .calendar-day:hover {


            background: #d8f3dc;

            color: #166534;


        }



        .calendar-day.active {


            background: #52b788;

            color: white;


        }



        .calendar-day.range {


            background: #d8f3dc;

            border-radius: 8px;

        }


        .calendar-empty {

            height: 34px;

        }

        /* =========================
                           CALENDAR IMPROVEMENT
                        ========================= */


        .calendar-popup {

            animation:
                calendarShow .18s ease;

        }



        @keyframes calendarShow {


            from {

                opacity: 0;

                transform:
                    translateY(-8px) scale(.96);

            }


            to {

                opacity: 1;

                transform:
                    translateY(0) scale(1);

            }


        }





        .calendar-day.today {


            border:

                2px solid #52b788;


            color: #15803d;


        }






        .calendar-day.selected-range {


            background: #d8f3dc;


            border-radius: 8px;


        }






        .calendar-footer {


            display: flex;


            justify-content: space-between;


            margin-top: 15px;


            padding-top: 12px;


            border-top:

                1px solid #ecfdf5;


        }





        .calendar-reset {


            border: none;


            background: #f0fdf4;


            color: #15803d;


            border-radius: 10px;


            padding: 7px 14px;


            font-size: 11px;


            font-weight: 800;


            cursor: pointer;


        }








        @media(max-width:768px) {



            .calendar-popup {


                width:

                    calc(100vw - 40px);


                max-width: 320px;


            }




        }

        .history-search-form{display:flex;align-items:center;gap:10px}
        .history-line-filter-inline select{min-height:40px;border:1px solid #cbd5e1;border-radius:9px;background:#fff;color:#334155;padding:0 32px 0 11px;font-size:12px;outline:none;max-width:190px}
        .history-line-filter-inline select:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1)}
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        @media(max-width:760px){.history-search-form{align-items:stretch;flex-direction:column}.history-line-filter-inline,.history-line-filter-inline select,.history-search-input{width:100%;max-width:none}}

        .history-search{width:auto;max-width:100%}
        .history-search-input{width:320px;max-width:100%}
    </style>

    <div class="page-card">

        <div class="page-head">
            <h2>
                History Order Repair Box
            </h2>
        </div>


        

        <div class="history-filter-box">
            <form action="<?php echo e(route('omd.orders.history')); ?>" method="GET" class="history-filter-form">
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
                <input type="hidden" name="line_id" value="<?php echo e(request('line_id')); ?>">

                
                <div class="history-filter-field">

                    <label for="start_date">
                        Dari Tanggal
                    </label>

                    <div class="history-date-input">

                        <i class="fa-regular fa-calendar"></i>

                        <div class="history-date-picker">

                            <i class="fa-regular fa-calendar"></i>

                            <input type="text" id="start_date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                                placeholder="Pilih tanggal" readonly onclick="openCalendar('start_date')">


                        </div>


                        <div class="calendar-popup" id="calendar-start_date">

                            <div class="calendar-header">

                                <button type="button" onclick="prevMonth()">
                                    ←
                                </button>


                                <strong id="calendarTitle">
                                    September 2026
                                </strong>


                                <button type="button" onclick="nextMonth()">
                                    →
                                </button>


                            </div>



                            <div class="calendar-week">


                                <span>M</span>
                                <span>T</span>
                                <span>W</span>
                                <span>T</span>
                                <span>F</span>
                                <span>S</span>
                                <span>S</span>


                            </div>



                            <div class="calendar-days" id="calendarDays">
                                <div class="calendar-footer">

                                    <button type="button" class="calendar-reset" onclick="resetDate('start_date')">

                                        Reset

                                    </button>

                                </div>

                            </div>



                        </div>

                    </div>

                </div>


                
                <div class="history-filter-field">

                    <label for="end_date">
                        Sampai Tanggal
                    </label>

                    <div class="history-date-input">

                        <i class="fa-regular fa-calendar"></i>

                        <div class="history-date-picker">

                            <i class="fa-regular fa-calendar"></i>

                            <input type="text" id="end_date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                                placeholder="Pilih tanggal" readonly onclick="openCalendar('end_date')">

                        </div>


                        <div class="calendar-popup" id="calendar-end_date">


                            <div class="calendar-header">


                                <button type="button" onclick="prevMonth()">
                                    ←
                                </button>


                                <strong id="calendarTitle2">

                                </strong>


                                <button type="button" onclick="nextMonth()">
                                    →

                                </button>


                            </div>


                            <div class="calendar-week">


                                <span>M</span>
                                <span>T</span>
                                <span>W</span>
                                <span>T</span>
                                <span>F</span>
                                <span>S</span>
                                <span>S</span>


                            </div>


                            <div class="calendar-days" id="calendarDays2">
                                <div class="calendar-footer">

                                    <button type="button" class="calendar-reset" onclick="resetDate('end_date')">

                                        Reset

                                    </button>

                                </div>


                            </div>


                        </div>
                    </div>

                </div>


                
                <div class="history-filter-actions">

                    <button type="submit" class="history-filter-btn history-filter-btn-primary">
                        <i class="fa-solid fa-filter"></i>

                        Terapkan Filter
                    </button>


                    <?php if(request('start_date') || request('end_date')): ?>
                        <a href="<?php echo e(route('omd.orders.history', array_filter(['search' => request('search'), 'per_page' => request('per_page'), 'line_id' => request('line_id')]))); ?>" class="history-filter-btn history-filter-btn-reset">
                            <i class="fa-solid fa-rotate-left"></i>

                            Reset
                        </a>
                    <?php endif; ?>

                </div>

            </form>

        </div>

        <div class="history-toolbar">

            

            <div class="history-per-page">

                <div class="history-per-page-label">

                    <select name="per_page" id="per_page"
                        onchange="
                        document.getElementById('perPageHidden').value = this.value;
                        document.getElementById('perPageForm').submit();
                    ">

                        <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>
                            10
                        </option>

                        <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>
                            25
                        </option>

                        <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>
                            50
                        </option>

                        <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>
                            100
                        </option>

                    </select>

                </div>


                <form id="perPageForm" action="<?php echo e(route('omd.orders.history')); ?>" method="GET">

                    <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">

                    <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">

                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">

                    <input type="hidden" name="line_id" value="<?php echo e(request('line_id')); ?>">

                    <input type="hidden" name="per_page" id="perPageHidden" value="<?php echo e(request('per_page', 10)); ?>">

                </form>

            </div>

            

            <div class="history-search">

                <form action="<?php echo e(route('omd.orders.history')); ?>" method="GET" class="history-search-form">

                    <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">

                    <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">

                    <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">

                    <div class="history-line-filter history-line-filter-inline">
                        <label for="history_line_id" class="sr-only">Filter Line</label>
                        <select id="history_line_id" name="line_id" onchange="this.form.submit()">
                            <option value="">Semua Line</option>
                            <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($line->id); ?>" <?php if((string) request('line_id') === (string) $line->id): echo 'selected'; endif; ?>>
                                    <?php echo e($line->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="history-search-input">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Cari No Order, User, atau Line..." autocomplete="off">


                        <?php if(request('search')): ?>
                            <button type="button" class="history-search-clear"
                                onclick="
                            this.closest('form').querySelector('input[name=search]').value = '';
                            this.closest('form').submit();
                        "
                                title="Hapus pencarian">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        <?php endif; ?>

                    </div>

                </form>

            </div>

        </div>

        <div class="orders-table-wrap">

            <table class="orders-table">

                <thead>

                    <tr>

                        <th style="width:55px;">
                            No
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            No Order
                        </th>

                        <th>
                            User
                        </th>

                        <th>
                            Jenis Order
                        </th>

                        <th>
                            Line
                        </th>

                        <th>
                            Qty
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="window.location='<?php echo e(route('omd.orders.history.show', $order)); ?>'"
                            title="Klik untuk melihat detail riwayat order">

                            <td class="order-no">
                                <?php echo e($orders->firstItem() + $index); ?>

                            </td>

                            <td>
                                <?php echo e($order->created_at ? $order->created_at->format('d-m-Y H:i') : '-'); ?>

                            </td>

                            <td class="order-number">
                                <?php echo e($order->order_number); ?>

                            </td>

                            <td class="order-user">
                                <?php echo e($order->user?->name ?? '-'); ?>

                            </td>

                            <td>
                                Repair Box
                            </td>

                            <td>
                                <?php echo e($order->line?->name ?? ($order->area?->name ?? '-')); ?>

                            </td>

                            <td>
                                <?php echo e(in_array($order->status, ['completed', 'confirmed'], true) ? (int) ($order->after_qty_sum ?? 0) : (int) ($order->before_qty_sum ?? $order->quantity)); ?>

                            </td>

                            <td>
                                <span class="status status-confirmed">
                                    Selesai
                                </span>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="9" class="order-empty">

                                <div class="order-empty-text">
                                    Belum ada history Order Repair Box.
                                </div>

                            </td>

                        </tr>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        

        <?php
            $orders->appends(request()->query());

            $currentPage = $orders->currentPage();
            $lastPage = $orders->lastPage();
        ?>

        <div class="history-pagination">

            <div class="history-pagination-list">

                <?php if($lastPage > 1): ?>

                    
                    <?php if($orders->onFirstPage()): ?>
                        <span class="history-pagination-btn history-pagination-prev disabled">
                            Previous
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($orders->previousPageUrl()); ?>"
                            class="history-pagination-btn history-pagination-prev">
                            Previous
                        </a>
                    <?php endif; ?>


                    
                    <a href="<?php echo e($orders->url(1)); ?>"
                        class="history-pagination-btn <?php echo e($currentPage == 1 ? 'active' : ''); ?>">
                        1
                    </a>


                    
                    <?php if($currentPage > 3): ?>
                        <span class="history-pagination-dots">
                            ...
                        </span>
                    <?php endif; ?>


                    
                    <?php for($page = max(2, $currentPage - 1); $page <= min($lastPage - 1, $currentPage + 1); $page++): ?>
                        <a href="<?php echo e($orders->url($page)); ?>"
                            class="history-pagination-btn <?php echo e($currentPage == $page ? 'active' : ''); ?>">
                            <?php echo e($page); ?>

                        </a>
                    <?php endfor; ?>


                    
                    <?php if($currentPage < $lastPage - 2): ?>
                        <span class="history-pagination-dots">
                            ...
                        </span>
                    <?php endif; ?>


                    
                    <?php if($lastPage > 1): ?>
                        <a href="<?php echo e($orders->url($lastPage)); ?>"
                            class="history-pagination-btn <?php echo e($currentPage == $lastPage ? 'active' : ''); ?>">
                            <?php echo e($lastPage); ?>

                        </a>
                    <?php endif; ?>


                    
                    <?php if($orders->hasMorePages()): ?>
                        <a href="<?php echo e($orders->nextPageUrl()); ?>" class="history-pagination-btn history-pagination-next">
                            Next
                        </a>
                    <?php else: ?>
                        <span class="history-pagination-btn history-pagination-next disabled">
                            Next
                        </span>
                    <?php endif; ?>

                <?php endif; ?>
            </div>



        </div>

    </div>

    <script>
        let calendarTarget = null;


        let currentDate = new Date();





        function openCalendar(target) {


            calendarTarget = target;



            let popup = document.getElementById(
                'calendar-' + target
            );



            document
                .querySelectorAll('.calendar-popup')
                .forEach(item => {

                    item.style.display = 'none';

                });



            popup.style.display = 'block';



            renderCalendar();



        }









        function renderCalendar() {


            let year = currentDate.getFullYear();

            let month = currentDate.getMonth();



            let firstDay = new Date(
                year,
                month,
                1
            ).getDay();



            let days = new Date(
                year,
                month + 1,
                0
            ).getDate();




            /*
                Ubah Minggu menjadi kolom terakhir
            */

            firstDay = firstDay === 0 ?
                6 :
                firstDay - 1;





            let title =
                currentDate.toLocaleDateString(
                    'id-ID', {
                        month: 'long',
                        year: 'numeric'
                    }
                );



            document
                .querySelectorAll('#calendarTitle,#calendarTitle2')
                .forEach(el => {

                    el.innerHTML =
                        title;

                });





            let container;



            if (calendarTarget === 'start_date') {

                container =
                    document.getElementById(
                        'calendarDays'
                    );

            } else {

                container =
                    document.getElementById(
                        'calendarDays2'
                    );

            }





            container.innerHTML = '';







            for (
                let i = 0; i < firstDay; i++
            ) {

                let empty =
                    document.createElement('div');


                empty.className =
                    'calendar-empty';


                container.appendChild(empty);

            }







            for (
                let day = 1; day <= days; day++
            ) {



                let button =
                    document.createElement('div');



                button.className =
                    'calendar-day';



                button.innerHTML =
                    day;





                let value =
                    `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;



                let selected =
                    document
                    .getElementById(calendarTarget)
                    .value;





                if (selected === value) {

                    button.classList.add(
                        'active'
                    );

                }




                let start =
                    document.getElementById('start_date').value;



                let end =
                    document.getElementById('end_date').value;




                if (start && end) {


                    if (value > start && value < end) {

                        button.classList.add(
                            'selected-range'
                        );

                    }


                }





                button.onclick = function() {


                    document
                        .getElementById(calendarTarget)
                        .value = value;



                    closeCalendar();



                };



                container.appendChild(button);


            }



        }









        function prevMonth() {


            currentDate.setMonth(
                currentDate.getMonth() - 1
            );



            renderCalendar();


        }









        function nextMonth() {


            currentDate.setMonth(
                currentDate.getMonth() + 1
            );


            renderCalendar();


        }









        function closeCalendar() {


            document
                .querySelectorAll('.calendar-popup')
                .forEach(item => {


                    item.style.display = 'none';


                });


        }









        document.addEventListener(
            'click',
            function(e) {


                let calendar =
                    e.target.closest(
                        '.calendar-popup'
                    );



                let input =
                    e.target.closest(
                        '.history-date-picker'
                    );



                if (!calendar && !input) {

                    closeCalendar();

                }


            }
        );

        function resetDate(target) {


            document
                .getElementById(target)
                .value = '';



            closeCalendar();


        }
    </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/orders/history.blade.php ENDPATH**/ ?>