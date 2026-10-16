@extends('layouts.app')

@section('title', 'Report')
@section('header', 'Report')

@section('content')
    @php
        $exportParams = [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
        if (!empty($selectedLineIds)) {
            $exportParams['line_ids'] = $selectedLineIds;
        }

        $selectedLineCount = count($selectedLineIds);
        $lineLabel =
            $selectedLineCount === 0
                ? 'Semua Line'
                : ($selectedLineCount === 1
                    ? optional($lines->firstWhere('id', $selectedLineIds[0]))->name ?? '1 Line dipilih'
                    : $selectedLineCount . ' Line dipilih');
    @endphp

    <style>
        .recap-page {
            display: grid;
            gap: 18px;
        }

        .recap-head h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
        }

        .recap-filter,
        .recap-card {
            background: var(--surface, #fff);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
        }

        .recap-filter {
            padding: 16px 18px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .recap-fields {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .recap-field {
            display: grid;
            gap: 6px;
            min-width: 190px;
        }

        .recap-field>label {
            color: var(--text-soft);
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .recap-period-select {
            width: 100%;
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--input-border, #dbe2ea);
            border-radius: 10px;
            background: var(--input-bg, #fff);
            color: var(--text);
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
        }

        .recap-period-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .recap-date-input {
            position: relative;
        }

        .recap-date-input>i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 12px;
            pointer-events: none;
            z-index: 2;
        }

        .recap-date-input input {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 34px;
            border: 1px solid var(--input-border, #dbe2ea);
            border-radius: 10px;
            background: var(--input-bg, #fff);
            color: var(--text);
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .recap-date-input input::placeholder {
            font-weight: 400;
            opacity: 1;
        }

        .recap-date-input input:focus,
        .recap-date-input.open input {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .recap-date-input.open .calendar-popup {
            display: block;
        }

        .recap-line-select {
            position: relative;
            min-width: 220px;
            width: 100%;
        }

        .recap-line-trigger {
            width: 100%;
            height: 38px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid var(--input-border, #dbe2ea);
            border-radius: 10px;
            background: var(--input-bg, #fff);
            color: var(--text);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
        }

        .recap-line-trigger:hover,
        .recap-line-select.open .recap-line-trigger {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .recap-line-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            max-width: 100%;
            max-height: min(56vh, 430px);
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface, #fff);
            box-shadow: var(--shadow);
            z-index: 10020;
        }

        .recap-line-select.open .recap-line-menu {
            display: block;
        }

        /* Checkbox rows: mengikuti referensi Settings checked */
        .recap-line-all,
        .recap-line-option {
            position: relative;
            min-height: 48px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 42px 10px 12px;
            margin: 0 0 6px;
            border: 0;
            border-radius: 24px;
            background: #fff;
            color: #171717;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.4;
            cursor: pointer;
            transition: background-color .16s ease, box-shadow .16s ease;
        }

        .recap-line-all:hover,
        .recap-line-option:hover {
            background: #f5faf5;
        }

        .recap-line-all:has(input:checked),
        .recap-line-option:has(input:checked) {
            background: #eaf6ec;
        }

        .recap-line-all {
            min-height: 48px;
            margin-bottom: 7px;
            border-bottom: 0;
            font-weight: 650;
        }

        .recap-line-option span,
        .recap-line-all span {
            display: block;
            min-width: 0;
            flex: 1;
        }

        .recap-line-option small {
            display: block;
            margin-left: 0;
            color: #5e626a;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.35;
        }

        .recap-line-option input,
        .recap-line-all input {
            appearance: none;
            -webkit-appearance: none;
            position: absolute;
            right: 14px;
            top: 50%;
            width: 22px !important;
            min-width: 22px !important;
            max-width: 22px !important;
            height: 22px !important;
            min-height: 22px !important;
            max-height: 22px !important;
            padding: 0 !important;
            margin: 0;
            flex: 0 0 22px;
            box-sizing: border-box;
            transform: translateY(-50%);
            border: 2px solid #92959b;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            display: grid;
            place-content: center;
            transition: background-color .16s ease, border-color .16s ease;
        }

        .recap-line-option input:checked,
        .recap-line-all input:checked {
            background: #38834b;
            border-color: #38834b;
        }

        .recap-line-option input:checked::after,
        .recap-line-all input:checked::after {
            content: '';
            width: 6px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) translate(-1px, -1px);
        }

        .recap-line-option input:focus-visible,
        .recap-line-all input:focus-visible {
            outline: 3px solid rgba(56, 131, 75, .22);
            outline-offset: 3px;
        }

        .recap-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .recap-btn {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
        }

        .recap-btn-primary {
            color: #fff;
            background: var(--primary);
        }

        .recap-btn-primary:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .recap-btn-excel {
            color: #166534;
            background: #ecfdf3;
            border-color: #bbf7d0;
        }

        .recap-btn-pdf {
            color: #b42318;
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .recap-card {
            overflow: hidden;
        }

        .recap-table-wrap {
            overflow: auto;
        }

        .recap-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 680px;
            background: #f8fafc;
        }

        .recap-table th,
        .recap-table td {
            padding: 14px 16px;
            border-right: 1px solid #e5eaf0;
            border-bottom: 1px solid #e5eaf0;
            text-align: left;
            white-space: nowrap;
            vertical-align: middle;
        }

        .recap-table th:first-child,
        .recap-table td:first-child {
            border-left: 1px solid #e5eaf0;
        }

        .recap-table thead th {
            border-top: 1px solid #e5eaf0;
        }

        .recap-table th {
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .recap-table td {
            background: #f8fafc;
            color: #111827;
            font-size: 12px;
            font-weight: 400;
        }

        .recap-table tbody tr:hover td {
            background: #f1f5f9;
        }

        .recap-table td strong,
        .recap-table td.total {
            font-weight: 700;
            color: var(--text);
        }

        .recap-product-model {
            display: grid;
            gap: 3px;
            white-space: normal;
            min-width: 150px;
        }

        .recap-product-model strong {
            font-size: 12px;
            line-height: 1.35;
            font-weight: 700;
            color: var(--text);
        }

        .recap-product-model small {
            font-size: 10px;
            line-height: 1.35;
            font-weight: 400;
            color: var(--text);
        }

        .recap-empty {
            padding: 38px 18px !important;
            text-align: center !important;
            color: var(--muted) !important;
        }

        .recap-pagination {
            padding: 14px 16px;
            border-top: 1px solid var(--border-soft, #edf0f4);
            margin-top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .recap-pagination-list {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .recap-pagination-btn {
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
            white-space: nowrap;
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }

        .recap-pagination-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .recap-pagination-btn.active {
            background: #334155;
            border-color: #334155;
            color: #fff;
        }

        .recap-pagination-btn.disabled {
            background: #f8fafc;
            border-color: #e5e7eb;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .recap-pagination-prev,
        .recap-pagination-next {
            min-width: 110px;
        }

        .recap-pagination-info {
            color: var(--text-soft, #64748b);
            font-size: 11px;
            white-space: nowrap;
        }

        .recap-continued {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #96999d;
            line-height: 1;
            vertical-align: middle;
        }

        .recap-continued svg {
            display: block;
            width: 16px;
            height: 16px;
        }

        @media (max-width: 700px) {
            .recap-pagination {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px 12px;
            }

            .recap-pagination-list {
                max-width: 100%;
            }

            .recap-pagination-prev,
            .recap-pagination-next {
                min-width: 82px;
            }
        }

        .annual-summary-table {
            min-width: 900px;
        }

        .annual-summary-table tbody th {
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .recap-table-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 14px 16px;
            background: var(--surface, #fff);
        }

        .recap-per-page-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .recap-per-page-form select {
            width: 66px;
            min-width: 66px;
            height: 34px;
            padding: 0 8px;
            border: 1px solid #d5ded8;
            border-radius: 8px;
            background: var(--surface, #fff);
            color: var(--text, #334155);
            font: inherit;
            font-size: 11px;
            font-weight: 700;
            outline: none;
            box-shadow: none;
        }

        .recap-year-input {
            display: block;
            width: 100%;
            height: 38px;
            box-sizing: border-box;
            padding: 0 12px;
            border: 1px solid var(--input-border, #dbe2ea);
            border-radius: 10px;
            background: var(--input-bg, #fff);
            color: var(--text);
            font-family: inherit;
            font-size: 11px;
            font-weight: 700;
            outline: none;
        }

        .recap-year-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .recap-ng-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-width: 260px;
        }

        .recap-ng-chip {
            display: inline-flex;
            justify-content: center;
            min-width: 54px;
            padding: 7px 10px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 750;
        }

        .recap-ng-p {
            color: #dc2626;
            background: #feecec;
        }

        .recap-ng-h {
            color: #d97706;
            background: #fff4dc;
        }

        .recap-ng-c {
            color: #0284c7;
            background: #e8f4ff;
        }

        .recap-ng-s {
            color: #7c3aed;
            background: #f2eaff;
        }

        .recap-continued {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #96999d;
            line-height: 1;
            vertical-align: middle;
        }

        .recap-continued svg {
            display: block;
            width: 16px;
            height: 16px;
        }

        .recap-order-number {
            font-weight: 700;
            color: #374151;
        }

        .recap-total-qty {
            display: inline-flex;
            min-width: 62px;
            justify-content: center;
            padding: 9px 12px;
            border-radius: 10px;
            color: #07834f;
            background: #eaf8f1;
            font-weight: 800;
        }

        @media (max-width: 700px) {
            .recap-table-toolbar {
                padding: 12px;
            }

            .recap-table th,
            .recap-table td {
                padding: 12px;
            }

            .recap-per-page-form select {
                height: 34px;
            }
        }

        /* Calendar mengikuti visual History */
        .calendar-popup {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 300px;
            padding: 16px;
            background: var(--surface, #fff);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            z-index: 10030;
            animation: recapCalendarShow .16s ease;
        }

        @keyframes recapCalendarShow {
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
            color: var(--text);
            text-transform: capitalize;
        }

        .calendar-nav {
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 50%;
            background: var(--surface-soft, #EAF3EC);
            color: var(--primary);
            font-weight: 900;
            cursor: pointer;
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
            color: var(--muted);
        }

        .calendar-day,
        .calendar-empty {
            height: 34px;
        }

        .calendar-day {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: var(--text);
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .calendar-day:hover:not(:disabled),
        .calendar-day.in-range {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .calendar-day.today {
            box-shadow: inset 0 0 0 2px rgba(95, 146, 120, .24);
        }

        .calendar-day.active {
            background: var(--primary);
            color: #fff;
        }

        .calendar-day:disabled {
            color: var(--muted);
            opacity: .45;
            cursor: not-allowed;
        }

        .calendar-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-soft, #f1f5f9);
        }

        .calendar-reset {
            border: 0;
            border-radius: 9px;
            padding: 7px 14px;
            background: var(--surface-soft, #f1f5f9);
            color: var(--text-soft);
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        /* Tablet-first: ruang filter lebih lega untuk perangkat operasional */
        @media (min-width: 701px) and (max-width: 1100px) {
            .recap-filter {
                display: block;
                padding: 18px;
            }

            .recap-fields {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 14px;
                align-items: end;
            }

            .recap-field {
                min-width: 0;
                width: 100%;
            }

            .recap-line-select {
                min-width: 0;
                width: 100%;
            }

            .recap-line-trigger,
            .recap-date-input input,
            .recap-period-select,
            .recap-btn {
                min-height: 48px;
                height: 48px;
                font-size: 12px;
            }

            .recap-line-menu {
                width: 100%;
                max-width: 100%;
                max-height: min(58vh, 460px);
            }

            .recap-actions {
                margin-top: 14px;
            }
        }

        @media (max-width: 700px) {
            .recap-filter {
                display: block;
                padding: 14px;
            }

            .recap-fields,
            .recap-actions {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
            }

            .recap-field,
            .recap-line-select {
                min-width: 0;
                width: 100%;
            }

            .recap-line-trigger,
            .recap-date-input input,
            .recap-period-select,
            .recap-btn {
                min-height: 46px;
                height: 46px;
                font-size: 12px;
            }

            .recap-line-menu {
                width: 100%;
                max-width: calc(100vw - 60px);
                max-height: 55vh;
                padding: 9px;
            }

        }
    </style>

    <div class="recap-page">
        <div class="recap-head">
            <h2>Report</h2>
        </div>

        <form method="GET" action="{{ route('omd.recap') }}" class="recap-filter" id="recapFilterForm">
            <div class="recap-fields">
                <div class="recap-field">
                    <label for="period_type">Jenis Rekap</label>
                    <select id="period_type" name="period_type" class="recap-period-select">
                        <option value="monthly" {{ request('period_type', 'monthly') === 'monthly' ? 'selected' : '' }}>
                            Bulanan</option>
                        <option value="yearly" {{ request('period_type') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="custom" {{ request('period_type') === 'custom' ? 'selected' : '' }}>Rentang Tanggal
                        </option>
                    </select>
                </div>

                <div class="recap-field" id="reportYearField">
                    <label for="report_year">Tahun</label>
                    <input type="number" id="report_year" name="report_year" class="recap-year-input"
                        min="2000" max="2100" step="1" value="{{ (int) request('report_year', $startDate->year) }}">
                </div>

                <div class="recap-field" id="reportMonthField">
                    <label for="report_month">Bulan</label>
                    <select id="report_month" name="report_month" class="recap-period-select">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}"
                                {{ (int) request('report_month', $startDate->month) === $month ? 'selected' : '' }}>
                                {{ \Illuminate\Support\Carbon::create(null, $month, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="recap-field recap-custom-date"
                    style="{{ request('period_type', 'monthly') !== 'custom' ? 'display:none' : '' }}">
                    <label for="start_date">Dari Tanggal</label>
                    <div class="recap-date-input" data-recap-datepicker="start">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}"
                            placeholder="Pilih tanggal" readonly autocomplete="off">
                        <div class="calendar-popup"></div>
                    </div>
                </div>

                <div class="recap-field recap-custom-date"
                    style="{{ request('period_type', 'monthly') !== 'custom' ? 'display:none' : '' }}">
                    <label for="end_date">Sampai Tanggal</label>
                    <div class="recap-date-input" data-recap-datepicker="end">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}"
                            placeholder="Pilih tanggal" readonly autocomplete="off">
                        <div class="calendar-popup"></div>
                    </div>
                </div>

                <div class="recap-field">
                    <label>Line</label>
                    <div class="recap-line-select" id="recapLineSelect">
                        <button type="button" class="recap-line-trigger" id="recapLineTrigger" aria-expanded="false">
                            <span id="recapLineLabel">{{ $lineLabel }}</span>
                            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                        </button>
                        <div class="recap-line-menu" id="recapLineMenu">
                            <label class="recap-line-all">
                                <input type="checkbox" id="recapLineAll" {{ empty($selectedLineIds) ? 'checked' : '' }}>
                                <span>Semua Line</span>
                            </label>
                            @foreach ($lines as $line)
                                <label class="recap-line-option">
                                    <input type="checkbox" name="line_ids[]" value="{{ $line->id }}"
                                        {{ in_array((int) $line->id, $selectedLineIds, true) ? 'checked' : '' }}>
                                    <span>{{ $line->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="recap-btn recap-btn-primary">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    Tampilkan
                </button>
            </div>

            <div class="recap-actions">
                <a class="recap-btn recap-btn-pdf" href="{{ route('omd.recap.pdf', $exportParams) }}">
                    <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                    PDF
                </a>
                <button type="button" class="recap-btn recap-btn-excel" data-export-form="omiraChartExport">
                    <i class="fa-solid fa-file-excel" aria-hidden="true"></i>
                    Excel
                </button>
            </div>
        </form>

        @if (request('period_type', 'monthly') === 'yearly')
            <div class="recap-table-wrap" style="overflow-x:auto">
                <table class="recap-table annual-summary-table">
                    <thead>
                        <tr>
                            <th>Indikator</th>
                            @foreach ($annualSummary as $month)
                                <th>{{ $month['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([['key' => 'target', 'label' => 'Target'], ['key' => 'order', 'label' => 'Order'], ['key' => 'finish', 'label' => 'Finish'], ['key' => 'scrap', 'label' => 'Scrap']] as $metric)
                            <tr>
                                <th scope="row">{{ $metric['label'] }}</th>
                                @foreach ($annualSummary as $month)
                                    <td style="text-align:right">{{ $month[$metric['key']] === null ? '' : number_format($month[$metric['key']], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if (request('period_type', 'monthly') !== 'yearly')
        <div class="recap-card">
            <div class="recap-table-toolbar">
                <form method="GET" action="{{ route('omd.recap') }}" class="recap-per-page-form">
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="recapPerPage" class="sr-only">Data per halaman</label>
                    <select id="recapPerPage" name="per_page" aria-label="Data per halaman"
                        onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>
                                {{ $size }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="recap-table-wrap">
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Line</th>
                            <th>No. Order</th>
                            <th>Produk / Model</th>
                            <th>Rincian NG</th>
                            <th>Total Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $previousDateLine = null;
                            $previousOrderNumber = null;
                        @endphp
                        @forelse ($rows as $row)
                            @php
                                $currentDateLine = $row->tanggal . '|' . $row->line;
                                $sameDateLine = $previousDateLine === $currentDateLine;
                                $sameOrder = $sameDateLine && $previousOrderNumber === $row->order_number;
                                $previousDateLine = $currentDateLine;
                                $previousOrderNumber = $row->order_number;
                            @endphp
                            <tr>
                                <td>{{ $sameOrder ? '' : \Illuminate\Support\Carbon::parse($row->tanggal)->format('d-m-Y') }}
                                </td>
                                <td>{{ $sameOrder ? '' : $row->line }}</td>
                                <td>
                                    @if ($sameOrder)
                                        <span class="recap-continued" aria-label="Lanjutan nomor order"><svg viewBox="0 0 64 64" width="16" height="16" aria-hidden="true" focusable="false"><path d="M16 12v19a8 8 0 0 0 8 8h25M38 24l12 15-12 13" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                    @else
                                        <span class="recap-order-number">{{ $row->order_number }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="recap-product-model">
                                        <strong>{{ $row->produk ?? '-' }}</strong>
                                        <small>{{ $row->model ?? '-' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="recap-ng-list">
                                        <span class="recap-ng-chip recap-ng-p">P:
                                            {{ number_format($row->qty_p, 0, ',', '.') }}</span>
                                        <span class="recap-ng-chip recap-ng-h">H:
                                            {{ number_format($row->qty_h, 0, ',', '.') }}</span>
                                        <span class="recap-ng-chip recap-ng-c">C:
                                            {{ number_format($row->qty_c, 0, ',', '.') }}</span>
                                        <span class="recap-ng-chip recap-ng-s">S:
                                            {{ number_format($row->qty_s, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="total"><span
                                        class="recap-total-qty">{{ number_format($row->total, 0, ',', '.') }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="recap-empty">Tidak ada order yang divalidasi OMD pada periode
                                    dan Line yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rows->hasPages())
                <div class="recap-pagination">
                    <div class="recap-pagination-list">
                        @if ($rows->onFirstPage())
                            <span class="recap-pagination-btn recap-pagination-prev disabled">Previous</span>
                        @else
                            <a href="{{ $rows->previousPageUrl() }}"
                                class="recap-pagination-btn recap-pagination-prev">Previous</a>
                        @endif

                        <a href="{{ $rows->url(1) }}"
                            class="recap-pagination-btn {{ $rows->currentPage() === 1 ? 'active' : '' }}">1</a>

                        @if ($rows->currentPage() > 3)
                            <span class="recap-pagination-btn disabled">...</span>
                        @endif

                        @for ($page = max(2, $rows->currentPage() - 1); $page <= min($rows->lastPage() - 1, $rows->currentPage() + 1); $page++)
                            <a href="{{ $rows->url($page) }}"
                                class="recap-pagination-btn {{ $rows->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                        @endfor

                        @if ($rows->currentPage() < $rows->lastPage() - 2)
                            <span class="recap-pagination-btn disabled">...</span>
                        @endif

                        @if ($rows->lastPage() > 1)
                            <a href="{{ $rows->url($rows->lastPage()) }}"
                                class="recap-pagination-btn {{ $rows->currentPage() === $rows->lastPage() ? 'active' : '' }}">{{ $rows->lastPage() }}</a>
                        @endif

                        @if ($rows->hasMorePages())
                            <a href="{{ $rows->nextPageUrl() }}"
                                class="recap-pagination-btn recap-pagination-next">Next</a>
                        @else
                            <span class="recap-pagination-btn recap-pagination-next disabled">Next</span>
                        @endif
                    </div>
                    <div class="recap-pagination-info">
                        Showing {{ $rows->firstItem() ?? 0 }} to {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }}
                        results
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>

    <script>
        (function() {
            const type = document.getElementById('period_type');
            const yearField = document.getElementById('reportYearField');
            const monthField = document.getElementById('reportMonthField');
            const customDates = document.querySelectorAll('.recap-custom-date');

            function syncPeriodFields() {
                const value = type.value;
                yearField.style.display = value === 'custom' ? 'none' : '';
                monthField.style.display = value === 'yearly' || value === 'custom' ? 'none' : '';
                customDates.forEach(field => {
                    field.style.display = value === 'custom' ? '' : 'none';
                });
            }

            type.addEventListener('change', syncPeriodFields);
            syncPeriodFields();
        })();
    </script>

    <script>
        (function() {
            const pad = n => String(n).padStart(2, '0');
            const toValue = (y, m, d) => y + '-' + pad(m + 1) + '-' + pad(d);
            const parseValue = str => {
                if (!str) return null;
                const p = str.split('-').map(Number);
                return new Date(p[0], p[1] - 1, p[2]);
            };

            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            const pickers = document.querySelectorAll('[data-recap-datepicker]');

            function closeCalendars() {
                pickers.forEach(p => p.classList.remove('open'));
            }

            pickers.forEach(wrap => {
                const role = wrap.getAttribute('data-recap-datepicker');
                const input = wrap.querySelector('input');
                const popup = wrap.querySelector('.calendar-popup');
                const base = parseValue(input.value) || new Date();
                let view = new Date(base.getFullYear(), base.getMonth(), 1);

                function render() {
                    const year = view.getFullYear();
                    const month = view.getMonth();
                    let firstDay = new Date(year, month, 1).getDay();
                    firstDay = firstDay === 0 ? 6 : firstDay - 1;
                    const totalDays = new Date(year, month + 1, 0).getDate();
                    const title = view.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                    const start = startInput.value;
                    const end = endInput.value;
                    const selected = input.value;
                    const today = new Date();
                    const todayValue = toValue(today.getFullYear(), today.getMonth(), today.getDate());

                    popup.innerHTML =
                        '<div class="calendar-header">' +
                        '<button type="button" class="calendar-nav" data-nav="-1"><i class="fa-solid fa-chevron-left"></i></button>' +
                        '<strong>' + title + '</strong>' +
                        '<button type="button" class="calendar-nav" data-nav="1"><i class="fa-solid fa-chevron-right"></i></button>' +
                        '</div>' +
                        '<div class="calendar-week"><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span><span>M</span></div>' +
                        '<div class="calendar-days"></div>' +
                        '<div class="calendar-footer"><button type="button" class="calendar-reset">Reset</button></div>';

                    const days = popup.querySelector('.calendar-days');
                    for (let i = 0; i < firstDay; i++) {
                        const empty = document.createElement('div');
                        empty.className = 'calendar-empty';
                        days.appendChild(empty);
                    }

                    for (let d = 1; d <= totalDays; d++) {
                        const value = toValue(year, month, d);
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'calendar-day';
                        btn.textContent = d;
                        btn.dataset.value = value;
                        if (value === todayValue) btn.classList.add('today');
                        if (start && end && value > start && value < end) btn.classList.add('in-range');
                        if (value === selected) btn.classList.add('active');
                        if (role === 'start' && end && value > end) btn.disabled = true;
                        if (role === 'end' && start && value < start) btn.disabled = true;
                        days.appendChild(btn);
                    }
                }

                input.addEventListener('click', e => {
                    e.stopPropagation();
                    const isOpen = wrap.classList.contains('open');
                    closeCalendars();
                    if (!isOpen) {
                        const current = parseValue(input.value);
                        if (current) view = new Date(current.getFullYear(), current.getMonth(), 1);
                        render();
                        wrap.classList.add('open');
                    }
                });

                popup.addEventListener('click', e => {
                    e.stopPropagation();
                    const nav = e.target.closest('[data-nav]');
                    const day = e.target.closest('.calendar-day');
                    const reset = e.target.closest('.calendar-reset');

                    if (nav) {
                        view.setMonth(view.getMonth() + Number(nav.dataset.nav));
                        render();
                        return;
                    }
                    if (day && !day.disabled) {
                        input.value = day.dataset.value;
                        closeCalendars();
                        return;
                    }
                    if (reset) {
                        input.value = '';
                        closeCalendars();
                    }
                });
            });

            const lineSelect = document.getElementById('recapLineSelect');
            const lineTrigger = document.getElementById('recapLineTrigger');
            const lineLabel = document.getElementById('recapLineLabel');
            const lineAll = document.getElementById('recapLineAll');
            const lineCheckboxes = Array.from(document.querySelectorAll('input[name="line_ids[]"]'));

            function refreshLineLabel() {
                const checked = lineCheckboxes.filter(c => c.checked);
                if (checked.length === 0 || checked.length === lineCheckboxes.length) {
                    lineLabel.textContent = 'Semua Line';
                    lineAll.checked = true;
                    if (checked.length === lineCheckboxes.length) {
                        lineCheckboxes.forEach(c => c.checked = false);
                    }
                } else if (checked.length === 1) {
                    lineAll.checked = false;
                    lineLabel.textContent = checked[0].closest('.recap-line-option').querySelector('span').textContent
                        .trim();
                } else {
                    lineAll.checked = false;
                    lineLabel.textContent = checked.length + ' Line dipilih';
                }
            }

            lineTrigger.addEventListener('click', e => {
                e.stopPropagation();
                const open = lineSelect.classList.toggle('open');
                lineTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
                closeCalendars();
            });

            lineAll.addEventListener('change', () => {
                lineCheckboxes.forEach(c => c.checked = false);
                lineAll.checked = true;
                refreshLineLabel();
            });

            lineCheckboxes.forEach(c => c.addEventListener('change', () => {
                lineAll.checked = false;
                refreshLineLabel();
            }));

            document.addEventListener('click', e => {
                if (!e.target.closest('.recap-date-input')) closeCalendars();
                if (!e.target.closest('.recap-line-select')) {
                    lineSelect.classList.remove('open');
                    lineTrigger.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    closeCalendars();
                    lineSelect.classList.remove('open');
                    lineTrigger.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>

    <form id="omiraChartExport" method="POST" action="{{ route('omd.recap.excel') }}"
        data-preview-url="{{ route('omd.recap.chart-preview') }}" style="display:none">
        @csrf
        @foreach ($exportParams as $key => $value)
            @if (is_array($value))
                @foreach ($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        <input type="hidden" name="chart_image" value="">
    </form>
    <script src="{{ asset('vendor/html2canvas.min.js') }}"></script>
    <script src="{{ asset('js/omira-excel-capture.js') }}"></script>
@endsection
