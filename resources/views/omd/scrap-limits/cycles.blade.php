@extends('layouts.app')
@section('title', 'Riwayat Scrap Limit')
@section('header', 'Riwayat Scrap Limit')

@section('content')
<style>
    .scrap-history-card { background:#fff; border:1px solid #e8edf4; border-radius:18px; padding:20px; box-shadow:0 10px 35px rgba(15,23,42,.06); }
    .scrap-history-filter { padding:16px 18px; border:1px solid #e7ebf0; border-radius:14px; background:#f8fafc; margin-bottom:18px; }
    .scrap-history-filter-fields { display:flex; flex-wrap:wrap; align-items:flex-end; gap:12px; }
    .scrap-history-field { display:grid; gap:6px; width:160px; max-width:100%; }
    .scrap-history-field.line { width:205px; }
    .scrap-history-field label { font-size:10px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
    .scrap-history-field input, .scrap-history-field select { width:100%; height:38px; box-sizing:border-box; border:1px solid #dbe2ea; border-radius:10px; background:#fff; padding:0 10px; font-size:12px; font-weight:400; font-family:inherit; color:#334155; outline:none; }
    .scrap-history-field input:focus, .scrap-history-field select:focus { border-color:#5f9278; box-shadow:0 0 0 3px rgba(95,146,120,.12); }
    .scrap-history-filter-actions { display:flex; align-items:center; gap:8px; }
    .scrap-history-filter-btn { height:38px; padding:0 16px; display:inline-flex; gap:7px; align-items:center; justify-content:center; border-radius:10px; border:1px solid transparent; background:#5f9278; color:#fff; font-size:11px; font-weight:800; font-family:inherit; text-decoration:none; cursor:pointer; }
    .scrap-history-filter-btn:hover { background:#4c8167; color:#fff; }
    .scrap-history-filter-btn.clear { background:#fff; color:#64748b; border-color:#dbe2ea; }
    .scrap-history-filter-btn.clear:hover { background:#f1f5f9; color:#334155; }
    .scrap-history-toolbar { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:14px; }
    .scrap-history-per-page { width:72px; height:34px; padding:0 8px; background:#fff; border:1px solid #dbe2ea; border-radius:9px; color:#334155; font-size:11px; }
    .scrap-history-search { position:relative; width:min(320px,100%); }
    .scrap-history-search input { box-sizing:border-box; width:100%; height:36px; padding:0 34px; border:1px solid #dbe2ea; border-radius:10px; background:#fff; font-size:11px; font-weight:400; outline:none; color:#334155; }
    .scrap-history-search input::placeholder { color:#94a3b8; font-weight:400; }
    .scrap-history-search input:focus { border-color:#5f9278; box-shadow:0 0 0 3px rgba(95,146,120,.12); }
    .scrap-history-search > i { position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:12px; color:#94a3b8; pointer-events:none; }
    .scrap-history-clear-search { position:absolute; top:50%; right:6px; transform:translateY(-50%); border:0; border-radius:7px; width:25px; height:25px; background:transparent; color:#94a3b8; cursor:pointer; }
    .scrap-history-clear-search:hover { background:#f1f5f9; }
    .scrap-history-table-wrap { overflow-x:auto; border:1px solid #e3e9f0; border-radius:12px; }
    .scrap-history-table { width:100%; min-width:790px; border-spacing:0; border-collapse:separate; font-size:11px; }
    .scrap-history-table thead th { background:#f9fafb; border-bottom:1px solid #e3e9f0; padding:12px 14px; text-align:left; text-transform:uppercase; letter-spacing:.04em; color:#64748b; font-size:11px; font-weight:700; }
    .scrap-history-table tbody td { padding:13px 14px; border-bottom:1px solid #edf1f5; vertical-align:middle; color:#334155; font-size:11px; font-weight:400; }
    .scrap-history-table tbody tr:last-child td { border-bottom:0; }
    .scrap-history-row { cursor:pointer; }
    .scrap-history-row:hover, .scrap-history-row:focus-visible { background:#f8fafc; outline:none; }
    .scrap-history-row.is-open { background:#f7faf8; }
    .scrap-history-expand { display:flex; align-items:center; gap:9px; }
    .scrap-history-chevron { font-size:11px; display:inline-flex; width:24px; height:24px; align-items:center; justify-content:center; border:1px solid #dfe7e2; border-radius:7px; background:#fff; color:#64748b; cursor:pointer; flex-shrink:0; }
    .scrap-history-chevron i { transition:transform .15s; }
    .scrap-history-row.is-open .scrap-history-chevron i { transform:rotate(180deg); }
    .scrap-history-main { font-size:11px; font-weight:700; color:#172033; }
    .scrap-history-sub { margin-top:4px; font-size:10px; font-weight:400; color:#64748b; }
    .scrap-history-date { display:block; text-align:center; white-space:nowrap; }
    .scrap-history-date-main { display:block; font-size:11px; font-weight:700; line-height:1.35; color:inherit; }
    .scrap-history-date-time { display:block; margin-top:4px; font-size:10px; font-weight:400; line-height:1.35; color:#94a3b8; }
    html[data-theme="dark"] .scrap-history-date-time { color:var(--text-soft); }
    .scrap-history-pill { display:inline-flex; align-items:center; justify-content:center; padding:6px 10px; border-radius:8px; font-size:10px; font-weight:600; white-space:nowrap; }
    .scrap-history-pill.yes { background:#eaf6ed; color:#277047; }
    .scrap-history-pill.no { background:#fff1f2; color:#b24355; }
    .scrap-history-pill.pending { background:#f1f5f9; color:#64748b; }
    .scrap-history-detail-row[hidden] { display:none !important; }
    .scrap-history-detail-row td { background:#fbfdfb; padding:16px 20px 18px !important; }
    .scrap-history-detail-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .scrap-history-detail-box { padding:13px 15px; background:#fff; border:1px solid #e5eaf0; border-radius:10px; min-width:0; }
    .scrap-history-detail-label { display:block; color:#94a3b8; font-size:10px; font-weight:700; text-transform:uppercase; margin-bottom:7px; }
    .scrap-history-detail-content { color:#334155; font-size:11px; font-weight:400; line-height:1.5; white-space:pre-wrap; overflow-wrap:anywhere; }
    .scrap-history-detail-meta { font-size:10px; font-weight:400; color:#64748b; margin-top:7px; }
    .scrap-history-reset { margin-top:14px; display:flex; gap:9px; flex-wrap:wrap; align-items:flex-start; }
    .scrap-history-reset input { height:38px; box-sizing:border-box; padding:0 12px; border:1px solid #dbe2ea; border-radius:9px; background:#fff; min-width:240px; flex:1; font-size:11px; font-weight:400; font-family:inherit; }
    .scrap-history-reset button { border:0; border-radius:9px; background:#5f9278; color:#fff; padding:0 14px; height:38px; font-size:11px; font-weight:600; cursor:pointer; }
    .scrap-history-reset button:hover { background:#4c8167; }
    .scrap-history-empty { text-align:center; color:#94a3b8 !important; padding:30px 14px !important; }
    .scrap-history-pagination { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-top:18px; }
    .scrap-history-pagination-info { color:#64748b; font-size:11px; }
    .scrap-history-pagination-links { display:flex; gap:5px; flex-wrap:wrap; }
    .scrap-history-page { display:inline-flex; justify-content:center; align-items:center; min-width:34px; height:34px; box-sizing:border-box; padding:0 9px; border:1px solid #dbe2ea; border-radius:8px; color:#64748b; background:#fff; font-size:11px; font-weight:750; text-decoration:none; }
    .scrap-history-page:hover { background:#f1f5f9; color:#334155; }
    .scrap-history-page.active { background:#5f9278; color:#fff; border-color:#5f9278; }
    .scrap-history-page.disabled { color:#cbd5e1; background:#f8fafc; }
    @media(max-width:720px) {
        .scrap-history-card { padding:14px; }
        .scrap-history-field, .scrap-history-field.line { width:calc(50% - 6px); }
        .scrap-history-toolbar { align-items:stretch; }
        .scrap-history-search { width:100%; }
        .scrap-history-detail-grid { grid-template-columns:1fr; }
        .scrap-history-pagination { flex-direction:column; align-items:flex-start; }
    }
    @media(max-width:440px) { .scrap-history-field, .scrap-history-field.line { width:100%; } }

    /* Kalender mengikuti History Order, di-scope agar tidak memengaruhi halaman lain. */
    .scrap-date-picker { position:relative; }
    .scrap-date-picker > i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#5f947a; font-size:12px; pointer-events:none; z-index:1; }
    .scrap-history-field .scrap-date-picker > input { padding-left:34px; cursor:pointer; font-weight:400; }
    .scrap-history-field .scrap-date-picker > input::placeholder { color:#a3adba; font-weight:400; opacity:1; }
    .scrap-history-field .scrap-date-picker.open > input { border-color:#6e9f82; box-shadow:0 0 0 3px rgba(95,146,120,.12); }
    .scrap-calendar-popup { display:none; position:absolute; top:calc(100% + 8px); left:0; width:min(292px,calc(100vw - 32px)); padding:12px; box-sizing:border-box; background:#fff; color:#263b30; border:1px solid #ddebe0; border-radius:15px; box-shadow:0 16px 38px rgba(35,69,48,.11); z-index:200; }
    .scrap-date-picker.open .scrap-calendar-popup { display:block; }
    .scrap-calendar-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:9px; }
    .scrap-calendar-header strong { color:#263b30; font-size:14px; font-weight:750; letter-spacing:-.3px; text-transform:capitalize; }
    .scrap-calendar-nav { display:flex; align-items:center; justify-content:center; width:38px; height:38px; padding:0; border:0; border-radius:50%; background:#f0f6f1; color:#5f947a; font-family:inherit; font-size:16px; font-weight:650; cursor:pointer; }
    .scrap-calendar-nav:hover { background:#e1eee5; color:#346d50; }
    .scrap-calendar-week, .scrap-calendar-days { display:grid; grid-template-columns:repeat(7,minmax(0,1fr)); gap:2px; }
    .scrap-calendar-week { margin-bottom:6px; }
    .scrap-calendar-week span { text-align:center; color:#819489; font-size:11px; font-weight:750; padding:4px 0; }
    .scrap-calendar-day, .scrap-calendar-empty { height:36px; min-height:36px; }
    .scrap-calendar-day { display:flex; align-items:center; justify-content:center; width:100%; padding:0; border:0; border-radius:50%; background:transparent; color:#293d32; font-family:inherit; font-size:12px; font-weight:650; cursor:pointer; }
    .scrap-calendar-day:hover:not(:disabled) { background:#edf5ef; color:#326a4d; }
    .scrap-calendar-day.today:not(.active) { box-shadow:inset 0 0 0 1px #9cbea8; }
    .scrap-calendar-day.in-range { background:#e4f0e7; color:#34684e; border-radius:50%; }
    .scrap-calendar-day.active { background:#5f947a; color:#fff; }
    .scrap-calendar-day:disabled { color:#cbd5e1; cursor:not-allowed; }
    @media(max-width:440px) { .scrap-calendar-popup { max-width:calc(100vw - 32px); } }
</style>

<div class="scrap-history-card">

    @if($errors->any())
        <div role="alert" style="margin-bottom:14px;padding:12px 14px;background:#fff1f2;color:#b42318;border:1px solid #fecaca;border-radius:10px;font-size:12px">{{ $errors->first() }}</div>
    @endif

    <form id="scrapHistoryFilters" method="GET" action="{{ route('omd.scrap-cycles.index') }}">
        <div class="scrap-history-filter">
            <div class="scrap-history-filter-fields">
                <div class="scrap-history-field">
                    <label for="scrap_start_date">Dari Tanggal</label>
                    <div class="scrap-date-picker" data-scrap-datepicker="start">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        <input id="scrap_start_date" type="text" name="start_date"
                            value="{{ request('start_date') }}" placeholder="Pilih tanggal"
                            readonly autocomplete="off" aria-label="Dari Tanggal" aria-haspopup="dialog" aria-expanded="false">
                        <div class="scrap-calendar-popup" role="dialog" aria-label="Pilih tanggal awal"></div>
                    </div>
                </div>
                <div class="scrap-history-field">
                    <label for="scrap_end_date">Sampai Tanggal</label>
                    <div class="scrap-date-picker" data-scrap-datepicker="end">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        <input id="scrap_end_date" type="text" name="end_date"
                            value="{{ request('end_date') }}" placeholder="Pilih tanggal"
                            readonly autocomplete="off" aria-label="Sampai Tanggal" aria-haspopup="dialog" aria-expanded="false">
                        <div class="scrap-calendar-popup" role="dialog" aria-label="Pilih tanggal akhir"></div>
                    </div>
                </div>
                <div class="scrap-history-field line">
                    <label for="scrap_line_id">Line</label>
                    <select id="scrap_line_id" name="line_id">
                        <option value="">Semua Line</option>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string)request('line_id') === (string)$line->id)>
                                {{ $line->name }}{{ $line->plant?->name ? ' · '.$line->plant->name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="scrap-history-filter-actions">
                    <button class="scrap-history-filter-btn" type="submit"><i class="fa-solid fa-filter" aria-hidden="true"></i> Filter</button>
                    @if(request('start_date') || request('end_date') || request('line_id'))
                        <a class="scrap-history-filter-btn clear" href="{{ route('omd.scrap-cycles.index', array_filter(['search' => request('search'), 'per_page' => request('per_page')])) }}">Reset</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="scrap-history-toolbar">
            <select class="scrap-history-per-page" name="per_page" aria-label="Jumlah data per halaman" onchange="this.form.submit()">
                @foreach ([10,25,50,100] as $size)
                    <option value="{{ $size }}" @selected((int)request('per_page',10) === $size)>{{ $size }}</option>
                @endforeach
            </select>
            <div class="scrap-history-search">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari User, Model, Produk, Line..." aria-label="Cari riwayat scrap limit" autocomplete="off">
                @if(request('search'))
                    <button type="button" class="scrap-history-clear-search" aria-label="Hapus pencarian" onclick="this.form.querySelector('input[name=search]').value='';this.form.submit()"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                @endif
            </div>
        </div>
    </form>

    <div class="scrap-history-table-wrap">
        <table class="scrap-history-table">
            <thead>
                <tr>
                    <th style="width:21%">Tanggal</th>
                    <th style="width:22%">Line / User</th>
                    <th>Model – Produk</th>
                    <th style="width:14%;text-align:center">Scrap / Limit</th>
                    <th style="width:14%;text-align:center">Keputusan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cycles as $cycle)
                    <tr class="scrap-history-row" data-scrap-detail="scrap-detail-{{ $cycle->id }}" tabindex="0" aria-expanded="false" aria-controls="scrap-detail-{{ $cycle->id }}">
                        <td>
                            <div class="scrap-history-expand">
                                <button type="button" class="scrap-history-chevron" aria-label="Lihat detail {{ $cycle->user?->name ?? 'scrap limit' }}" aria-expanded="false" aria-controls="scrap-detail-{{ $cycle->id }}"><i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button>
                                @if($cycle->reached_at)
                                    <time class="scrap-history-date" datetime="{{ $cycle->reached_at->toIso8601String() }}">
                                        <span class="scrap-history-date-main">{{ $cycle->reached_at->format('d-m-Y') }}</span>
                                        <span class="scrap-history-date-time">{{ $cycle->reached_at->format('H:i') }}</span>
                                    </time>
                                @else
                                    <span>—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="scrap-history-main">{{ $cycle->line?->name ?? '-' }}</div>
                            <div class="scrap-history-sub">{{ $cycle->user?->name ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="scrap-history-main">{{ $cycle->product?->masterModel?->model ?? '-' }}</div>
                            <div class="scrap-history-sub">{{ $cycle->product?->name ?? '-' }}</div>
                        </td>
                        <td style="text-align:center;font-weight:600;white-space:nowrap">{{ $cycle->reached_qty ?? 0 }} / {{ $cycle->limit_qty }}</td>
                        <td style="text-align:center">
                            <span class="scrap-history-pill {{ $cycle->decision === 'yes' ? 'yes' : ($cycle->decision === 'no' ? 'no' : 'pending') }}">
                                {{ $cycle->decision === 'yes' ? 'Order' : ($cycle->decision === 'no' ? 'Tidak' : 'Belum dipilih') }}
                            </span>
                        </td>
                    </tr>
                    <tr class="scrap-history-detail-row" id="scrap-detail-{{ $cycle->id }}" hidden>
                        <td colspan="5">
                            <div class="scrap-history-detail-grid">
                                <div class="scrap-history-detail-box">
                                    <span class="scrap-history-detail-label">Alasan Tidak Order</span>
                                    <div class="scrap-history-detail-content">{{ $cycle->decision === 'no' ? ($cycle->decision_reason ?: '—') : '—' }}</div>
                                </div>
                                <div class="scrap-history-detail-box">
                                    <span class="scrap-history-detail-label">Reset Scrap Limit</span>
                                    <div class="scrap-history-detail-content">{{ $cycle->reset_at?->format('d/m/Y H:i') ?? 'Belum reset' }}</div>
                                    @if($cycle->reset_at)
                                        <div class="scrap-history-detail-meta">Petugas: {{ $cycle->resetter?->name ?? '-' }}</div>
                                    @endif
                                    @if($cycle->reset_reason)
                                        <div class="scrap-history-detail-meta">Alasan: {{ $cycle->reset_reason }}</div>
                                    @endif
                                </div>
                            </div>
                            @if(!$cycle->reset_at)
                                <form class="scrap-history-reset" method="POST" action="{{ route('omd.scrap-cycles.reset', $cycle) }}">
                                    @csrf
                                    <input name="reason" type="text" required minlength="5" maxlength="1000" placeholder="Alasan reset (wajib)" aria-label="Alasan reset manual">
                                    <button type="submit">Reset Manual</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="scrap-history-empty">Tidak ada riwayat Scrap Limit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="scrap-history-pagination">
        <span class="scrap-history-pagination-info">Menampilkan {{ $cycles->firstItem() ?? 0 }}–{{ $cycles->lastItem() ?? 0 }} dari {{ $cycles->total() }} data</span>
        @if($cycles->lastPage() > 1)
            <nav class="scrap-history-pagination-links" aria-label="Pagination Scrap Limit">
                @if($cycles->onFirstPage())
                    <span class="scrap-history-page disabled">Previous</span>
                @else
                    <a class="scrap-history-page" href="{{ $cycles->previousPageUrl() }}">Previous</a>
                @endif
                @if($cycles->currentPage() > 3)
                    <a class="scrap-history-page" href="{{ $cycles->url(1) }}">1</a>
                    @if($cycles->currentPage() > 4)<span class="scrap-history-page disabled">…</span>@endif
                @endif
                @for($page = max(1, $cycles->currentPage()-1); $page <= min($cycles->lastPage(), $cycles->currentPage()+1); $page++)
                    @if($page === $cycles->currentPage())
                        <span class="scrap-history-page active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="scrap-history-page" href="{{ $cycles->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
                @if($cycles->currentPage() < $cycles->lastPage()-2)
                    @if($cycles->currentPage() < $cycles->lastPage()-3)<span class="scrap-history-page disabled">…</span>@endif
                    <a class="scrap-history-page" href="{{ $cycles->url($cycles->lastPage()) }}">{{ $cycles->lastPage() }}</a>
                @endif
                @if($cycles->hasMorePages())
                    <a class="scrap-history-page" href="{{ $cycles->nextPageUrl() }}">Next</a>
                @else
                    <span class="scrap-history-page disabled">Next</span>
                @endif
            </nav>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.scrap-history-row[data-scrap-detail]').forEach(function (row) {
            const details = document.getElementById(row.dataset.scrapDetail);
            const button = row.querySelector('.scrap-history-chevron');
            if (!details || !button) return;

            function toggleDetails() {
                const open = details.hidden;
                details.hidden = !open;
                row.classList.toggle('is-open', open);
                row.setAttribute('aria-expanded', String(open));
                button.setAttribute('aria-expanded', String(open));
            }

            row.addEventListener('click', toggleDetails);
            row.addEventListener('keydown', function (event) {
                if (event.target !== row) return;
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleDetails();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pickers = document.querySelectorAll('[data-scrap-datepicker]');
        const startInput = document.getElementById('scrap_start_date');
        const endInput = document.getElementById('scrap_end_date');
        if (!startInput || !endInput || !pickers.length) return;

        const pad = number => String(number).padStart(2, '0');
        const dateValue = date => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
        const readDate = value => {
            if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) return null;
            const [year, month, day] = value.split('-').map(Number);
            const date = new Date(year, month - 1, day);
            return date.getFullYear() === year && date.getMonth() + 1 === month && date.getDate() === day ? date : null;
        };

        function closeCalendars() {
            pickers.forEach(picker => {
                picker.classList.remove('open');
                picker.querySelector('input').setAttribute('aria-expanded', 'false');
            });
        }

        pickers.forEach(picker => {
            const input = picker.querySelector('input');
            const popup = picker.querySelector('.scrap-calendar-popup');
            const role = picker.dataset.scrapDatepicker;
            const selectedDate = readDate(input.value) || new Date();
            let viewedMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);

            function renderCalendar() {
                const year = viewedMonth.getFullYear();
                const month = viewedMonth.getMonth();
                const offset = (new Date(year, month, 1).getDay() + 6) % 7;
                const lastDay = new Date(year, month + 1, 0).getDate();
                const monthLabel = viewedMonth.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                const today = dateValue(new Date());
                const start = startInput.value;
                const end = endInput.value;

                popup.innerHTML = `<div class="scrap-calendar-header">
                    <button type="button" class="scrap-calendar-nav" data-month="-1" aria-label="Bulan sebelumnya">&#8592;</button>
                    <strong>${monthLabel}</strong>
                    <button type="button" class="scrap-calendar-nav" data-month="1" aria-label="Bulan berikutnya">&#8594;</button>
                </div><div class="scrap-calendar-week"><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span><span>M</span></div>
                <div class="scrap-calendar-days"></div>`;

                const grid = popup.querySelector('.scrap-calendar-days');
                for (let i = 0; i < offset; i++) {
                    const blank = document.createElement('span');
                    blank.className = 'scrap-calendar-empty';
                    grid.appendChild(blank);
                }
                for (let day = 1; day <= lastDay; day++) {
                    const value = `${year}-${pad(month + 1)}-${pad(day)}`;
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'scrap-calendar-day';
                    button.textContent = day;
                    button.dataset.value = value;
                    button.setAttribute('aria-label', `${day} ${monthLabel}`);
                    if (value === today) button.classList.add('today');
                    if (start && end && value > start && value < end) button.classList.add('in-range');
                    if (value === input.value) button.classList.add('active');
                    if ((role === 'start' && end && value > end) || (role === 'end' && start && value < start)) button.disabled = true;
                    grid.appendChild(button);
                }
            }

            function toggleCalendar() {
                const alreadyOpen = picker.classList.contains('open');
                closeCalendars();
                if (alreadyOpen) return;
                const current = readDate(input.value);
                if (current) viewedMonth = new Date(current.getFullYear(), current.getMonth(), 1);
                renderCalendar();
                picker.classList.add('open');
                input.setAttribute('aria-expanded', 'true');
            }

            input.addEventListener('click', toggleCalendar);
            input.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleCalendar();
                }
            });
            popup.addEventListener('click', event => {
                event.stopPropagation();
                const navigation = event.target.closest('[data-month]');
                if (navigation) {
                    viewedMonth.setMonth(viewedMonth.getMonth() + Number(navigation.dataset.month));
                    renderCalendar();
                    return;
                }
                const dayButton = event.target.closest('.scrap-calendar-day');
                if (dayButton && !dayButton.disabled) {
                    input.value = dayButton.dataset.value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    closeCalendars();
                }
            });
        });

        document.addEventListener('click', event => {
            if (!event.target.closest('.scrap-date-picker')) closeCalendars();
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') closeCalendars();
        });
    });
</script>
@endsection
