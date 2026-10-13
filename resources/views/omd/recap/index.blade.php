@extends('layouts.app')

@section('title', 'Rekap Data')
@section('header', 'Rekap Data')

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
    $lineLabel = $selectedLineCount === 0
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

    .recap-field > label {
        color: var(--text-soft);
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .recap-date-input {
        position: relative;
    }

    .recap-date-input > i {
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
        box-shadow: 0 0 0 3px rgba(95,146,120,.12);
    }

    .recap-line-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 290px;
        max-height: 320px;
        overflow: auto;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--surface, #fff);
        box-shadow: var(--shadow);
        z-index: 10020;
    }

    .recap-line-select.open .recap-line-menu {
        display: block;
    }

    .recap-line-all,
    .recap-line-option {
        min-height: 36px;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 9px;
        border-radius: 9px;
        color: var(--text);
        font-size: 11px;
        cursor: pointer;
    }

    .recap-line-all:hover,
    .recap-line-option:hover {
        background: var(--surface-hover, #F4F8F5);
    }

    .recap-line-all {
        border-bottom: 1px solid var(--border);
        margin-bottom: 6px;
        padding-bottom: 10px;
        font-weight: 800;
    }

    .recap-line-option small {
        margin-left: auto;
        color: var(--muted);
        font-size: 9px;
    }

    .recap-line-option input,
    .recap-line-all input {
        accent-color: var(--primary);
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
        border-collapse: collapse;
        min-width: 860px;
    }

    .recap-table th,
    .recap-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border-soft, #edf0f4);
        text-align: left;
        white-space: nowrap;
    }

    .recap-table th {
        background: var(--table-head-bg, #F4F8F5);
        color: var(--text-soft);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .recap-table td {
        color: var(--text);
        font-size: 12px;
        font-weight: 400;
    }

    .recap-table td strong,
    .recap-table td.total {
        font-weight: 700;
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
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
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

    @media (max-width: 900px) {
        .recap-filter {
            align-items: stretch;
        }

        .recap-fields {
            flex: 1 1 100%;
        }
    }

    @media (max-width: 700px) {
        .recap-filter,
        .recap-fields,
        .recap-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .recap-field,
        .recap-line-select {
            min-width: 0;
            width: 100%;
        }

        .recap-line-menu,
        .calendar-popup {
            width: min(300px, calc(100vw - 42px));
        }
    }
</style>

<div class="recap-page">
    <div class="recap-head">
        <h2>Rekap Data</h2>
    </div>

    <form method="GET" action="{{ route('omd.recap') }}" class="recap-filter" id="recapFilterForm">
        <div class="recap-fields">
            <div class="recap-field">
                <label for="start_date">Dari Tanggal</label>
                <div class="recap-date-input" data-recap-datepicker="start">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <input type="text" id="start_date" name="start_date" value="{{ $startDate->toDateString() }}" readonly autocomplete="off">
                    <div class="calendar-popup"></div>
                </div>
            </div>

            <div class="recap-field">
                <label for="end_date">Sampai Tanggal</label>
                <div class="recap-date-input" data-recap-datepicker="end">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <input type="text" id="end_date" name="end_date" value="{{ $endDate->toDateString() }}" readonly autocomplete="off">
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
                                <small>{{ $line->plant }}</small>
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

    <div class="recap-card">
        <div class="recap-table-wrap">
            <table class="recap-table">
                <thead>
                    <tr>
                        <th>Plant</th>
                        <th>Line</th>
                        <th>Model</th>
                        <th>Produk</th>
                        <th>Jenis NG</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->plant }}</td>
                            <td>{{ $row->line }}</td>
                            <td><strong>{{ $row->model ?? '-' }}</strong></td>
                            <td>{{ $row->produk ?? '-' }}</td>
                            <td>{{ $row->jenis_ng }}</td>
                            <td class="total">{{ number_format($row->total, 0, ',', '.') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="recap-empty">Tidak ada data confirmed pada periode dan Line yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rows->hasPages())
            <div class="recap-pagination">{{ $rows->links() }}</div>
        @endif
    </div>
</div>

<script>
(function () {
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
            const title = view.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
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
            lineLabel.textContent = checked[0].closest('.recap-line-option').querySelector('span').textContent.trim();
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

<form id="omiraChartExport" method="POST" action="{{ route('omd.recap.excel') }}" data-preview-url="{{ route('omd.recap.chart-preview') }}" style="display:none">
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
