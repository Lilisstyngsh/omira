@extends('layouts.app')
@section('title', 'History Scrap')
@section('header', 'History Scrap')
@section('content')
    <style>
        .ab-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .ab-filter {
            margin-bottom: 18px;
            padding: 16px 18px;
            border: 1px solid #e7ebf0;
            border-radius: 14px;
            background: #f8fafc;
        }

        .ab-filter-fields {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ab-date-field {
            width: 160px;
            min-width: 0;
            max-width: 100%;
        }

        .ab-date-field label {
            display: block;
            margin-bottom: 6px;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .ab-date-control {
            position: relative;
        }

        .ab-date-control>i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b7cf6;
            font-size: 12px;
            pointer-events: none;
        }

        .ab-date-control>input {
            width: 100%;
            height: 38px;
            box-sizing: border-box;
            padding: 0 12px 0 34px;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            outline: none;
        }

        .ab-date-control>input::placeholder {
            color: #a3adba;
            font-weight: 500;
            opacity: 1;
        }

        .ab-date-control>input:focus,
        .ab-date-control.is-open>input {
            border-color: #5F9278;
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .ab-filter-button {
            height: 38px;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 0;
            border-radius: 10px;
            background: #5F9278;
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .ab-filter-button:hover {
            background: #4c8167;
        }

        .ab-reset {
            height: 38px;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            color: #64748b;
            font-size: 11px;
            font-weight: 650;
            text-decoration: none;
        }

        .ab-reset:hover {
            background: #f1f5f9;
        }

        .ab-calendar {
            display: none;
            position: absolute;
            z-index: 100;
            top: calc(100% + 8px);
            left: 0;
            width: 292px;
            max-width: calc(100vw - 48px);
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #dfe6ef;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .15);
        }

        .ab-date-control.is-open .ab-calendar {
            display: block;
        }

        .ab-calendar-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 9px;
        }

        .ab-calendar-heading strong {
            font-size: 14px;
            font-weight: 750;
            color: #263b30;
            text-transform: capitalize;
        }

        .ab-calendar-nav {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border: 0;
            border-radius: 50%;
            background: #f0f6f1;
            color: #5f947a;
            font-size: 18px;
            cursor: pointer;
        }

        .ab-calendar-week,
        .ab-calendar-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 2px;
        }

        .ab-calendar-week span {
            text-align: center;
            padding: 4px 0;
            font-size: 11px;
            color: #819489;
            font-weight: 750;
        }

        .ab-calendar-day,
        .ab-calendar-empty {
            height: 36px;
        }

        .ab-calendar-day {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border: 0;
            border-radius: 50%;
            background: none;
            color: #293d32;
            font-size: 12px;
            font-weight: 650;
            cursor: pointer;
        }

        .ab-calendar-day:hover:not(:disabled) {
            background: #edf5ef;
            color: #326a4d;
        }

        .ab-calendar-day.today:not(.active) {
            box-shadow: inset 0 0 0 1px #9cbea8;
        }

        .ab-calendar-day.in-range {
            background: #e4f0e7;
            color: #34684e;
        }

        .ab-calendar-day.active {
            background: #5F9278;
            color: #fff;
        }

        .ab-calendar-day:disabled {
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .ab-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 24px 0 22px;
        }

        .ab-page-size {
            width: 72px;
            height: 34px;
            box-sizing: border-box;
            padding: 0 10px;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .ab-search {
            position: relative;
            width: 320px;
            max-width: 100%;
        }

        .ab-toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .ab-export-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .ab-export-btn {
            height: 36px;
            padding: 0 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }

        .ab-export-btn.pdf {
            color: #b42318;
            background: #fff1f2;
            border-color: #fecdd3;
        }

        .ab-export-btn.excel {
            color: #166534;
            background: #ecfdf3;
            border-color: #bbf7d0;
        }

        .ab-export-btn:hover { filter: brightness(.97); }

        .ab-search>i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .ab-search>input {
            width: 100%;
            height: 36px;
            box-sizing: border-box;
            padding: 0 38px 0 34px;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            outline: none;
        }

        .ab-search>input::placeholder {
            color: #a3adba;
            font-weight: 500;
        }

        .ab-search>input:focus {
            border-color: #5F9278;
            box-shadow: 0 0 0 3px rgba(95, 146, 120, .12);
        }

        .ab-search-clear {
            position: absolute;
            top: 50%;
            right: 7px;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
        }

        .ab-search-clear:hover {
            background: #f1f5f9;
        }

        .ab-table-wrap {
            overflow-x: auto;
            border: 1px solid #dfe6ef;
            border-radius: 12px;
        }

        .ab-table {
            width: 100%;
            min-width: 880px;
            border-spacing: 0;
            border-collapse: separate;
            table-layout: fixed;
            font-size: 11px;
        }

        .ab-table th {
            padding: 12px 14px;
            border-bottom: 1px solid #e3e9f0;
            background: #f9fafb;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .ab-table td {
            padding: 13px 14px;
            border-bottom: 1px solid #edf1f5;
            color: #334155;
            font-size: 11px;
            font-weight: 400;
            vertical-align: middle;
        }

        .ab-table th:nth-child(2),
        .ab-table th:nth-child(4),
        .ab-table th:nth-child(5) {
            text-align: center;
        }

        .ab-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .ab-table tbody tr[data-href] {
            cursor: pointer;
            transition: background .15s ease;
        }

        .ab-table tbody tr[data-href]:hover,
        .ab-table tbody tr[data-href]:focus-visible {
            background: #F4F8F5;
        }

        .ab-table tbody tr[data-href]:focus-visible {
            outline: 2px solid #5F9278;
            outline-offset: -2px;
        }

        .ab-order-number {
            color: #172033;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .ab-date {
            text-align: center;
            white-space: nowrap;
        }

        .ab-date-main {
            display: block;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.35;
            color: inherit;
        }

        .ab-date-time {
            display: block;
            margin-top: 4px;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.35;
        }

        .ab-model-products {
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .ab-products-stack,
        .ab-values-stack,
        .ab-decisions-stack {
            display: grid;
            gap: 9px;
        }

        .ab-product-item,
        .ab-value-item,
        .ab-decision-item {
            min-height: 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .ab-model-name {
            color: #172033;
            font-size: 11px;
            font-weight: 700;
        }

        .ab-product-name {
            margin-top: 3px;
            color: #475569;
            font-size: 11px;
            font-weight: 500;
            line-height: 1.45;
        }

        .ab-value-item,
        .ab-decision-item {
            align-items: center;
            text-align: center;
        }

        .ab-value-item {
            color: inherit;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .ab-muted {
            color: #94a3b8;
        }

        .ab-decision-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .ab-decision-pill.yes {
            background: #eaf6ed;
            color: #277047;
        }

        .ab-decision-pill.no {
            background: #fff1f2;
            color: #b24355;
        }

        .ab-decision-pill.reset {
            background: #eaf2ff;
            color: #245ba8;
        }

        .ab-table tbody tr[data-href]:hover .ab-decision-pill.yes,
        .ab-table tbody tr[data-href]:focus-visible .ab-decision-pill.yes {
            background: #dff0e4;
        }

        .ab-table tbody tr[data-href]:hover .ab-decision-pill.reset,
        .ab-table tbody tr[data-href]:focus-visible .ab-decision-pill.reset {
            background: #dbeafe;
        }

        .ab-table tbody tr[data-href]:hover .ab-decision-pill.no,
        .ab-table tbody tr[data-href]:focus-visible .ab-decision-pill.no {
            background: #ffe4e6;
        }

        .ab-reset-meta,
        .ab-reset-reason {
            align-self: stretch;
            max-width: 100%;
            color: #475569;
            font-size: 9px;
            font-weight: 400;
            line-height: 1.4;
            text-align: left;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .ab-reset-reason {
            margin-top: 3px;
        }

        html[data-theme="dark"] .ab-model-name {
            color: var(--text);
        }

        html[data-theme="dark"] .ab-product-name,
        html[data-theme="dark"] .ab-date-time {
            color: var(--text-soft);
        }

        .ab-extra {
            color: #5F9278;
            font-size: 10px;
            font-weight: 400;
            white-space: nowrap;
        }

        .ab-empty {
            padding: 46px 16px !important;
            text-align: center;
            color: #94a3b8 !important;
        }

        .ab-pagination {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .ab-page-link {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            background: #fff;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }

        .ab-page-link:hover {
            background: #f8fafc;
        }

        .ab-page-link.active {
            background: #5F9278;
            border-color: #5F9278;
            color: #fff;
        }

        .ab-page-link.disabled {
            background: #f8fafc;
            color: #cbd5e1;
        }

        .ab-page-ellipsis {
            padding: 0 5px;
            color: #94a3b8;
        }

        @media(max-width:720px) {
            .ab-card {
                padding: 15px;
            }

            .ab-toolbar {
                gap: 10px;
            }

            .ab-toolbar-actions {
                flex-wrap: wrap;
            }

            .ab-search {
                width: min(320px, 70%);
            }
        }

        @media(max-width:480px) {
            .ab-filter-fields {
                align-items: stretch;
            }

            .ab-date-field {
                width: 100%;
            }

            .ab-filter-button {
                flex: 1;
            }

            .ab-toolbar {
                align-items: stretch;
            }

            .ab-page-size {
                width: 72px;
            }

            .ab-toolbar-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .ab-search {
                width: 100%;
                max-width: none;
            }

            .ab-export-actions {
                width: 100%;
            }

            .ab-export-btn {
                flex: 1;
            }
        }
        .ab-history-modal[hidden] {
            display: none !important;
        }

        .ab-history-modal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .ab-history-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .48);
            -webkit-backdrop-filter: blur(3px);
            backdrop-filter: blur(3px);
        }

        .ab-history-modal-dialog {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            width: min(1160px, calc(100vw - 40px));
            max-height: calc(100vh - 40px);
            max-height: calc(100dvh - 40px);
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .24);
            overflow: hidden;
        }

        .ab-history-modal-dialog.is-reset-detail {
            width: min(900px, calc(100vw - 40px));
        }

        .ab-history-modal-dialog.is-reset-detail .ab-history-modal-body {
            padding: 16px;
        }

        .ab-history-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 16px 20px;
            border-bottom: 1px solid #e8edf4;
            background: linear-gradient(135deg, #F4F8F5 0%, #fff 75%);
            flex: 0 0 auto;
        }

        .ab-history-modal-title {
            margin: 0;
            color: #172033;
            font-size: 16px;
            line-height: 1.35;
            font-weight: 750;
        }

        .ab-history-modal-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease, color .15s ease;
        }

        .ab-history-modal-close:hover,
        .ab-history-modal-close:focus-visible {
            background: #F4F8F5;
            border-color: #b8d0c0;
            color: #245f42;
            outline: none;
        }

        .ab-history-modal-body {
            min-height: 0;
            padding: 18px;
            overflow: auto;
            display: grid;
            align-content: start;
            gap: 14px;
            color: #334155;
        }

        .ab-history-modal-content {
            display: grid;
            gap: 18px;
            min-width: 0;
        }

        .ab-modal-card {
            min-width: 0;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 14px;
            overflow: hidden;
        }

        .ab-modal-card-header {
            padding: 13px 16px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(135deg, #F4F8F5 0%, #fff 75%);
        }

        .ab-modal-card-header h3 {
            margin: 0;
            color: #172033;
            font-size: 13px;
            font-weight: 700;
        }

        .ab-modal-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ab-modal-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .ab-modal-table {
            width: 100%;
            min-width: 820px;
            border-spacing: 0;
            border-collapse: separate;
            table-layout: fixed;
            font-size: 11px;
        }

        .ab-modal-table-reset {
            min-width: 0;
        }

        .ab-history-modal-dialog.is-reset-detail .ab-modal-table-wrap {
            overflow-x: visible;
        }

        .ab-modal-table th {
            padding: 12px 13px;
            border-bottom: 1px solid #e3e9f0;
            background: #f9fafb;
            color: #111111;
            font-size: 10px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .035em;
        }

        .ab-modal-table td {
            padding: 12px 13px;
            border-bottom: 1px solid #edf1f5;
            color: #111111;
            font-size: 11px;
            font-weight: 400;
            line-height: 1.5;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .ab-modal-table tr:last-child td {
            border-bottom: 0;
        }

        .ab-modal-table th:not(:last-child),
        .ab-modal-table td:not(:last-child) {
            border-right: 1px solid #edf1f5;
        }

        .ab-modal-table .ab-modal-model {
            color: #000000;
            font-weight: 700;
        }

        .ab-modal-table .ab-modal-reason {
            white-space: pre-wrap;
            text-align: left;
            overflow-wrap: anywhere;
        }

        .ab-modal-table .ab-modal-center {
            text-align: center;
            white-space: nowrap;
        }

        .ab-modal-preview {
            padding: 12px;
        }

        .ab-modal-preview-frame {
            display: block;
            width: 100%;
            height: 58vh;
            min-height: 300px;
            border: 1px solid #dfe6ef;
            border-radius: 10px;
            background: #f8fafc;
        }

        .ab-modal-preview-fallback {
            margin: 9px 2px 0;
            color: #64748b;
            font-size: 11px;
        }

        .ab-modal-preview-fallback a {
            color: #347554;
            font-weight: 700;
        }

        .ab-modal-loading,
        .ab-modal-error {
            padding: 34px 16px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }

        .ab-modal-error a {
            display: inline-flex;
            margin-top: 10px;
            color: #347554;
            font-weight: 700;
        }

        @media (max-width: 640px) {
            .ab-history-modal {
                padding: 8px;
            }

            .ab-history-modal-dialog {
                width: calc(100vw - 16px);
                max-height: calc(100vh - 16px);
                max-height: calc(100dvh - 16px);
                border-radius: 14px;
            }

            .ab-history-modal-dialog.is-reset-detail {
                width: calc(100vw - 16px);
            }

            .ab-history-modal-dialog.is-reset-detail .ab-modal-table-wrap {
                overflow-x: auto;
            }

            .ab-history-modal-dialog.is-reset-detail .ab-modal-table-reset {
                min-width: 680px;
            }

            .ab-history-modal-header {
                padding: 13px 14px;
            }

            .ab-history-modal-body {
                padding: 12px;
                gap: 12px;
            }

            .ab-modal-preview-frame {
                height: 48vh;
                min-height: 260px;
            }
        }

    </style>

    @php
        $hasFilters = request()->filled('start_date') || request()->filled('end_date') || request()->filled('search');
    @endphp

    <div class="ab-card">
        <form method="GET" action="{{ route('user.additional-box.index') }}" id="additionalBoxFilterForm">
            <div class="ab-filter">
                <div class="ab-filter-fields">
                    <div class="ab-date-field">
                        <label for="ab_start_date">Dari Tanggal</label>
                        <div class="ab-date-control" data-ab-date="start">
                            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                            <input type="text" id="ab_start_date" name="start_date" value="{{ request('start_date') }}"
                                placeholder="Pilih tanggal" autocomplete="off" readonly aria-label="Dari Tanggal">
                            <div class="ab-calendar"></div>
                        </div>
                    </div>
                    <div class="ab-date-field">
                        <label for="ab_end_date">Sampai Tanggal</label>
                        <div class="ab-date-control" data-ab-date="end">
                            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                            <input type="text" id="ab_end_date" name="end_date" value="{{ request('end_date') }}"
                                placeholder="Pilih tanggal" autocomplete="off" readonly aria-label="Sampai Tanggal">
                            <div class="ab-calendar"></div>
                        </div>
                    </div>
                    <button type="submit" class="ab-filter-button"><i class="fa-solid fa-filter" aria-hidden="true"></i>
                        Filter</button>
                    @if (request()->filled('start_date') || request()->filled('end_date'))
                        <a class="ab-reset"
                            href="{{ route('user.additional-box.index', array_filter(['search' => request('search'), 'per_page' => request('per_page')])) }}">Reset</a>
                    @endif
                </div>
            </div>

            <div class="ab-toolbar">
                <select name="per_page" class="ab-page-size" aria-label="Jumlah data per halaman"
                    onchange="this.form.submit()">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
                <div class="ab-toolbar-actions">
                    <div class="ab-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari No Order atau Produk..." autocomplete="off"
                            aria-label="Cari No Order, Model, atau Produk">
                        @if (request()->filled('search'))
                            <button type="button" class="ab-search-clear" aria-label="Hapus pencarian"
                                onclick="this.form.querySelector('input[name=search]').value=''; this.form.submit();"><i
                                    class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                        @endif
                    </div>
                    <div class="ab-export-actions">
                        <a class="ab-export-btn pdf" href="{{ route('user.additional-box.history-export', array_merge(request()->except('page'), ['format' => 'pdf'])) }}">
                            <i class="fa-solid fa-file-pdf" aria-hidden="true"></i> PDF
                        </a>
                        <a class="ab-export-btn excel" href="{{ route('user.additional-box.history-export', array_merge(request()->except('page'), ['format' => 'excel'])) }}">
                            <i class="fa-solid fa-file-excel" aria-hidden="true"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="ab-table-wrap">
            <table class="ab-table" id="abHistoryTable">
                <colgroup>
                    <col style="width:20%">
                    <col style="width:17%">
                    <col style="width:29%">
                    <col style="width:16%">
                    <col style="width:18%">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">No Order</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Model – Produk</th>
                        <th scope="col">Scrap / Limit</th>
                        <th scope="col">Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $historyItem)
                        @if ($historyItem->type === 'reset')
                            @php $cycle = $historyItem->record; @endphp
                            <tr class="ab-manual-reset-row"
                                data-href="{{ route('user.additional-box.reset-show', $cycle) }}"
                                role="link" tabindex="0" aria-haspopup="dialog"
                                aria-label="Lihat detail reset oleh OMD tanggal {{ $cycle->reset_at->format('d-m-Y H:i') }}">
                                <td><span class="ab-muted">—</span></td>
                                <td class="ab-date">
                                    <time datetime="{{ $cycle->reset_at->toIso8601String() }}">
                                        <span class="ab-date-main">{{ $cycle->reset_at->format('d-m-Y') }}</span>
                                        <span class="ab-date-time">{{ $cycle->reset_at->format('H:i') }}</span>
                                    </time>
                                </td>
                                <td class="ab-model-products">
                                    <div class="ab-products-stack">
                                        <div class="ab-product-item">
                                            <div class="ab-model-name">{{ $cycle->product?->masterModel?->model ?: '—' }}</div>
                                            <div class="ab-product-name">{{ $cycle->product?->name ?: '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ab-values-stack">
                                        <div class="ab-value-item">{{ $cycle->reached_qty ?? 0 }} / {{ $cycle->limit_qty }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ab-decisions-stack">
                                        <div class="ab-decision-item">
                                            <span class="ab-decision-pill reset">Reset oleh OMD</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @php
                                $document = $historyItem->record;
                                // Snapshot keputusan tersedia untuk data baru; data lama fallback ke items.
                                $hasSnapshots = $document->decisions->isNotEmpty();
                                $products = $hasSnapshots ? $document->decisions : $document->items;
                                $visibleProducts = $products->take(2);
                            @endphp
                            <tr data-href="{{ route('user.additional-box.show', $document) }}" role="link" tabindex="0"
                                aria-haspopup="dialog" aria-label="Lihat detail {{ $document->document_number }}">
                            <td><a class="ab-order-number"
                                    href="{{ route('user.additional-box.show', $document) }}">{{ $document->document_number }}</a>
                            </td>
                            <td class="ab-date">
                                <time datetime="{{ $document->created_at->toIso8601String() }}">
                                    <span class="ab-date-main">{{ $document->created_at->format('d-m-Y') }}</span>
                                    <span class="ab-date-time">{{ $document->created_at->format('H:i') }}</span>
                                </time>
                            </td>
                            <td class="ab-model-products">
                                <div class="ab-products-stack">
                                    @foreach ($visibleProducts as $product)
                                        <div class="ab-product-item">
                                            <div class="ab-model-name">{{ $product->model_name ?: '—' }}</div>
                                            <div class="ab-product-name">{{ $product->product_name ?: '—' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($products->count() > 2)
                                    <span class="ab-extra">+{{ $products->count() - 2 }} lainnya</span>
                                @endif
                            </td>
                            <td>
                                <div class="ab-values-stack">
                                    @foreach ($visibleProducts as $product)
                                        <div class="ab-value-item">
                                            @if ($hasSnapshots && $product->scrap_qty !== null && $product->limit_qty !== null)
                                                {{ $product->scrap_qty }} / {{ $product->limit_qty }}
                                            @else
                                                <span class="ab-muted">—</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="ab-decisions-stack">
                                    @foreach ($visibleProducts as $product)
                                        <div class="ab-decision-item">
                                            @if ($hasSnapshots && $product->decision === 'yes')
                                                <span class="ab-decision-pill yes">Order</span>
                                            @elseif($hasSnapshots && $product->decision === 'no')
                                                <span class="ab-decision-pill no">Tidak</span>
                                            @else
                                                <span class="ab-muted">—</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="ab-empty">
                                {{ $hasFilters ? 'Tidak ada data yang sesuai filter.' : 'Belum ada riwayat Additional Box.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($requests->hasPages())
            <nav class="ab-pagination" aria-label="Halaman Additional Box">
                @if ($requests->onFirstPage())
                    <span class="ab-page-link disabled">Previous</span>
                @else
                    <a class="ab-page-link" href="{{ $requests->previousPageUrl() }}">Previous</a>
                @endif
                @foreach ($requests->getUrlRange(max(1, $requests->currentPage() - 2), min($requests->lastPage(), $requests->currentPage() + 2)) as $page => $url)
                    <a class="ab-page-link {{ $requests->currentPage() === $page ? 'active' : '' }}"
                        href="{{ $url }}"
                        @if ($requests->currentPage() === $page) aria-current="page" @endif>{{ $page }}</a>
                @endforeach
                @if ($requests->hasMorePages())
                    <a class="ab-page-link" href="{{ $requests->nextPageUrl() }}">Next</a>
                @else
                    <span class="ab-page-link disabled">Next</span>
                @endif
            </nav>
        @endif
    </div>

    <div class="ab-history-modal" id="abHistoryModal" hidden aria-hidden="true">
        <div class="ab-history-modal-backdrop" data-ab-modal-close></div>
        <section class="ab-history-modal-dialog" role="dialog" aria-modal="true"
            aria-labelledby="abHistoryModalTitle" tabindex="-1">
            <header class="ab-history-modal-header">
                <h2 class="ab-history-modal-title" id="abHistoryModalTitle">Detail History Scrap</h2>
                <button type="button" class="ab-history-modal-close" data-ab-modal-close
                    aria-label="Tutup detail">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </header>
            <div class="ab-history-modal-body" id="abHistoryModalBody" aria-live="polite">
                <div class="ab-modal-loading">Memuat detail...</div>
            </div>
        </section>
    </div>

    <script>
        (function() {
            'use strict';
            const table = document.getElementById('abHistoryTable');
            const modal = document.getElementById('abHistoryModal');
            const dialog = modal?.querySelector('.ab-history-modal-dialog');
            const modalTitle = document.getElementById('abHistoryModalTitle');
            const modalBody = document.getElementById('abHistoryModalBody');
            const closeButton = modal?.querySelector('.ab-history-modal-close');
            if (!table || !modal || !dialog || !modalTitle || !modalBody || !closeButton) return;

            let lastTrigger = null;
            let priorBodyOverflow = '';
            let requestVersion = 0;

            function closeModal() {
                requestVersion++;
                modal.hidden = true;
                modal.setAttribute('aria-hidden', 'true');
                dialog.classList.remove('is-reset-detail');
                modalBody.replaceChildren();
                document.body.style.overflow = priorBodyOverflow;
                if (lastTrigger && typeof lastTrigger.focus === 'function') lastTrigger.focus();
            }

            async function openModal(url, trigger) {
                lastTrigger = trigger || document.activeElement;
                priorBodyOverflow = document.body.style.overflow;
                const currentRequest = ++requestVersion;
                const target = new URL(url, window.location.href);
                target.searchParams.set('modal', '1');

                modal.hidden = false;
                modal.setAttribute('aria-hidden', 'false');
                dialog.classList.remove('is-reset-detail');
                document.body.style.overflow = 'hidden';
                modalTitle.textContent = 'Detail History Scrap';
                modalBody.innerHTML = '<div class="ab-modal-loading">Memuat detail...</div>';
                closeButton.focus();

                try {
                    const response = await fetch(target.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });
                    if (!response.ok) throw new Error('Detail tidak tersedia.');

                    const html = await response.text();
                    if (currentRequest !== requestVersion) return;

                    const template = document.createElement('template');
                    template.innerHTML = html;
                    const content = template.content.querySelector('[data-modal-title]');
                    if (!content) throw new Error('Respons detail tidak valid.');

                    modalTitle.textContent = content.dataset.modalTitle || 'Detail History Scrap';
                    dialog.classList.toggle('is-reset-detail', content.dataset.modalTitle === 'Detail Reset Scrap');
                    modalBody.replaceChildren(content);
                } catch (error) {
                    if (currentRequest !== requestVersion) return;
                    modalBody.replaceChildren();
                    const message = document.createElement('div');
                    message.className = 'ab-modal-error';
                    message.textContent = 'Detail tidak dapat dimuat.';
                    const fallback = document.createElement('a');
                    fallback.href = url;
                    fallback.textContent = 'Buka halaman detail';
                    message.appendChild(document.createElement('br'));
                    message.appendChild(fallback);
                    modalBody.appendChild(message);
                }
            }

            table.addEventListener('click', function(event) {
                const row = event.target.closest('tr[data-href]');
                if (!row || !table.contains(row)) return;
                if (event.target.closest('button,input,select,textarea,[data-no-modal]')) return;

                const link = event.target.closest('a');
                if (link) event.preventDefault();
                openModal(row.dataset.href, row);
            });

            table.addEventListener('keydown', function(event) {
                const row = event.target.closest('tr[data-href]');
                if (!row || !table.contains(row) || event.target !== row) return;
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openModal(row.dataset.href, row);
                }
            });

            modal.addEventListener('click', function(event) {
                if (event.target.closest('[data-ab-modal-close]')) closeModal();
            });

            document.addEventListener('keydown', function(event) {
                if (modal.hidden) return;
                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeModal();
                }
                if (event.key === 'Tab') {
                    const focusable = modal.querySelectorAll('a[href], button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])');
                    if (!focusable.length) {
                        event.preventDefault();
                        dialog.focus();
                        return;
                    }
                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];
                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            });
        })();
    </script>

    <script>
        (function() {
            'use strict';
            const controls = document.querySelectorAll('[data-ab-date]');
            const startInput = document.getElementById('ab_start_date');
            const endInput = document.getElementById('ab_end_date');
            const pad = n => String(n).padStart(2, '0');
            const format = (y, m, d) => y + '-' + pad(m + 1) + '-' + pad(d);
            const parse = value => {
                if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) return null;
                const parts = value.split('-').map(Number);
                const date = new Date(parts[0], parts[1] - 1, parts[2]);
                return date.getFullYear() === parts[0] && date.getMonth() === parts[1] - 1 && date.getDate() ===
                    parts[2] ? date : null;
            };
            const closeAll = () => controls.forEach(control => control.classList.remove('is-open'));

            controls.forEach(control => {
                const input = control.querySelector('input');
                const calendar = control.querySelector('.ab-calendar');
                const role = control.dataset.abDate;
                const chosen = parse(input.value) || new Date();
                let view = new Date(chosen.getFullYear(), chosen.getMonth(), 1);

                function render() {
                    const year = view.getFullYear();
                    const month = view.getMonth();
                    const label = view.toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                    calendar.innerHTML =
                        '<div class="ab-calendar-heading"><button type="button" class="ab-calendar-nav" data-nav="-1" aria-label="Bulan sebelumnya">←</button><strong></strong><button type="button" class="ab-calendar-nav" data-nav="1" aria-label="Bulan berikutnya">→</button></div><div class="ab-calendar-week"><span>Sn</span><span>Sl</span><span>Rb</span><span>Km</span><span>Jm</span><span>Sb</span><span>Mg</span></div><div class="ab-calendar-days"></div>';
                    calendar.querySelector('strong').textContent = label;
                    const grid = calendar.querySelector('.ab-calendar-days');
                    const first = (new Date(year, month, 1).getDay() + 6) % 7;
                    for (let i = 0; i < first; i++) {
                        const blank = document.createElement('span');
                        blank.className = 'ab-calendar-empty';
                        grid.appendChild(blank);
                    }
                    const start = startInput.value;
                    const end = endInput.value;
                    const today = new Date();
                    const todayValue = format(today.getFullYear(), today.getMonth(), today.getDate());
                    for (let day = 1; day <= new Date(year, month + 1, 0).getDate(); day++) {
                        const value = format(year, month, day);
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'ab-calendar-day';
                        btn.textContent = day;
                        btn.dataset.value = value;
                        btn.setAttribute('aria-label', value);
                        if (value === input.value) btn.classList.add('active');
                        if (value === todayValue) btn.classList.add('today');
                        if (start && end && value > start && value < end) btn.classList.add('in-range');
                        if ((role === 'start' && end && value > end) || (role === 'end' && start && value <
                                start)) btn.disabled = true;
                        grid.appendChild(btn);
                    }
                }

                input.addEventListener('click', () => {
                    const wasOpen = control.classList.contains('is-open');
                    closeAll();
                    if (!wasOpen) {
                        const current = parse(input.value);
                        if (current) view = new Date(current.getFullYear(), current.getMonth(), 1);
                        render();
                        control.classList.add('is-open');
                    }
                });
                calendar.addEventListener('click', event => {
                    const nav = event.target.closest('[data-nav]');
                    const day = event.target.closest('.ab-calendar-day');
                    if (nav) {
                        view.setMonth(view.getMonth() + Number(nav.dataset.nav));
                        render();
                    } else if (day && !day.disabled) {
                        input.value = day.dataset.value;
                        closeAll();
                    }
                });
            });
            document.addEventListener('click', event => {
                if (!event.target.closest('[data-ab-date]')) closeAll();
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') closeAll();
            });
        })();
    </script>
@endsection
