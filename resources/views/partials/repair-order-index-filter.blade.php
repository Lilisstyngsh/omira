{{-- Common controls for the two active Order Repair indexes; History styling is the visual reference. --}}
<style>
    .repair-index { background:#fff;border:1px solid #e8edf4;border-radius:18px;padding:22px;box-shadow:0 10px 35px rgba(15,23,42,.06) }
    .repair-index .ri-heading { display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:17px }
    .repair-index .ri-heading h2 { margin:0;font-size:18px;font-weight:800;color:#172033 }
    .repair-index .ri-create { display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:38px;padding:0 15px;background:#5f9278;color:#fff;border-radius:10px;text-decoration:none;font-size:11px;font-weight:800 }
    .repair-index .ri-create:hover { background:#4c8167;color:#fff }
    .repair-index .history-filter-box { margin-bottom:18px;padding:16px 18px;background:#f8fafc;border:1px solid #e7ebf0;border-radius:14px }
    .repair-index .history-filter-form { display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap }
    .repair-index .history-filter-field { width:160px;max-width:100% }
    .repair-index .history-filter-field>label { display:block;margin-bottom:6px;color:#64748b;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.04em }
    .repair-index .history-date-input { position:relative }
    .repair-index .history-date-input>i { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#5f9278;font-size:12px;pointer-events:none;z-index:2 }
    .repair-index .history-date-input>input { box-sizing:border-box;width:100%;height:38px;padding:0 12px 0 34px;border:1px solid #dbe2ea;border-radius:10px;background:#fff;color:#334155;font-size:11px;font-weight:600;font-family:inherit;outline:none;cursor:pointer }
    .repair-index .history-date-input>input::placeholder { color:#a3adba;font-weight:400 }
    .repair-index .history-date-input.open>input, .repair-index .history-date-input>input:focus { border-color:#5f9278;box-shadow:0 0 0 3px rgba(95,146,120,.10) }
    .repair-index .history-filter-actions { display:flex;gap:8px;align-items:center }
    .repair-index .history-filter-btn { height:38px;padding:0 16px;border-radius:10px;display:inline-flex;gap:7px;align-items:center;justify-content:center;font-size:11px;font-weight:800;text-decoration:none;cursor:pointer;white-space:nowrap }
    .repair-index .history-filter-btn-primary { background:#5f9278;border:1px solid #5f9278;color:#fff }
    .repair-index .history-filter-btn-primary:hover { background:#4c8167;color:#fff }
    .repair-index .history-filter-btn-reset { background:#fff;border:1px solid #dbe2ea;color:#64748b }
    .repair-index .history-filter-btn-reset:hover { background:#f1f5f9;color:#334155 }
    .repair-index .history-toolbar { display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px }
    .repair-index .history-toolbar-query { display:flex;align-items:center;gap:10px;margin-left:auto }
    .repair-index .history-per-page select { width:72px;height:34px;box-sizing:border-box;padding:0 10px;background:#fff;border:1px solid #dbe2ea;border-radius:9px;color:#334155;font-size:11px;font-weight:800;cursor:pointer }
    .repair-index .history-line-filter-inline select { height:36px;max-width:190px;border:1px solid #dbe2ea;border-radius:10px;background:#fff;color:#334155;padding:0 10px;font-size:11px;cursor:pointer }
    .repair-index .history-search { position:relative;width:320px;max-width:100% }
    .repair-index .history-search>i { position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none }
    .repair-index .history-search input { box-sizing:border-box;width:100%;height:36px;padding:0 34px;border:1px solid #dbe2ea;border-radius:10px;background:#fff;color:#334155;font-size:11px;font-weight:600;outline:none }
    .repair-index .history-search input::placeholder { color:#a3adba;font-weight:400 }
    .repair-index .history-search input:focus,.repair-index .history-line-filter-inline select:focus,.repair-index .history-per-page select:focus { border-color:#5f9278;box-shadow:0 0 0 3px rgba(95,146,120,.1);outline:none }
    .repair-index .history-search-clear { position:absolute;right:6px;top:50%;transform:translateY(-50%);width:25px;height:25px;border:0;border-radius:7px;background:transparent;color:#94a3b8;cursor:pointer }
    .repair-index .history-search-clear:hover { background:#f1f5f9 }
    .repair-index .ri-table-wrap { border:1px solid #dfe6ef;border-radius:12px;overflow-x:auto }
    .repair-index .ri-table { width:100%;min-width:780px;border-collapse:separate;border-spacing:0 }
    .repair-index .ri-table th { padding:11px 9px;background:#f9fafb;border-right:1px solid #dfe6ef;border-bottom:1px solid #dfe6ef;text-align:center;color:#64748b;font-size:10px;font-weight:800;letter-spacing:.025em;text-transform:uppercase }
    .repair-index .ri-table td { padding:12px 9px;border-right:1px solid #dfe6ef;border-bottom:1px solid #dfe6ef;color:#334155;font-size:11px;vertical-align:middle }
    .repair-index .ri-table th:last-child,.repair-index .ri-table td:last-child { border-right:0 }
    .repair-index .ri-table tbody tr:last-child td { border-bottom:0 }
    .repair-index .ri-table tbody tr.ri-link-row { cursor:pointer;transition:background .15s }
    .repair-index .ri-table tbody tr.ri-link-row:hover,.repair-index .ri-table tbody tr.ri-link-row:focus-visible { background:#f4f8f5;outline:none }
    .repair-index .ri-table thead tr:nth-child(2) th { font-size:9px }
    .repair-index .ri-date { text-align:center;white-space:nowrap }
    .repair-index .ri-date strong { display:block;color:#334155;font-size:11px }
    .repair-index .ri-date small { color:#94a3b8;font-size:10px }
    .repair-index .ri-order-number { font-weight:700;color:#172033;overflow-wrap:anywhere }
    .repair-index .ri-count { color:#94a3b8;text-align:center }
    .repair-index .ri-qty { text-align:center;font-weight:700 }
    .repair-index .ri-line { display:inline-flex;align-items:center;min-height:26px;padding:0 9px;background:#eff6ff;border-radius:8px;color:#3478c5;font-size:10px;font-weight:650 }
    .repair-index .process-cell { text-align:center!important;padding:9px 5px!important }
    .repair-index .process-icon { display:inline-flex;align-items:center;justify-content:center;width:27px;height:27px;border-radius:50%;font-size:12px;font-weight:900;line-height:1 }
    .repair-index .process-done { background:#dcfce7;color:#16a34a;box-shadow:inset 0 0 0 1px #bbf7d0 }
    .repair-index .process-progress { background:#fef3c7;color:#d97706;box-shadow:inset 0 0 0 1px #fde68a }
    .repair-index .process-pending { background:#fee2e2;color:#dc2626;box-shadow:inset 0 0 0 1px #fecaca }
    .repair-index .ri-notice { display:flex;align-items:center;gap:10px;margin-bottom:16px;padding:12px 15px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;color:#166534;font-size:12px }
    .repair-index .ri-notice i { font-size:15px }
    .repair-index .ri-empty { padding:45px 16px!important;text-align:center!important;color:#94a3b8!important }
    .repair-index .ri-pagination { display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-top:16px }
    .repair-index .ri-pagination-info { font-size:11px;color:#64748b }
    .repair-index .ri-pagination-links { display:flex;gap:5px;align-items:center;flex-wrap:wrap }
    .repair-index .ri-page { min-width:34px;height:34px;box-sizing:border-box;padding:0 10px;display:inline-flex;align-items:center;justify-content:center;border:1px solid #dbe2ea;border-radius:8px;background:#fff;color:#64748b;font-size:11px;font-weight:750;text-decoration:none }
    .repair-index .ri-page:hover { background:#f1f5f9;color:#334155 }
    .repair-index .ri-page.active { background:#5f9278;color:#fff;border-color:#5f9278 }
    .repair-index .ri-page.disabled { color:#cbd5e1;background:#f8fafc;pointer-events:none }
    .repair-index .calendar-popup { display:none;position:absolute;top:calc(100% + 8px);left:0;z-index:2000;width:min(292px,calc(100vw - 32px));box-sizing:border-box;padding:12px;border:1px solid #ddebe0;border-radius:15px;background:#fff;box-shadow:0 16px 38px rgba(35,69,48,.11) }
    .repair-index .history-date-input.open .calendar-popup { display:block }
    .repair-index .calendar-header { display:flex;align-items:center;justify-content:space-between;gap:5px;margin-bottom:9px }
    .repair-index .calendar-header strong { color:#263b30;font-size:14px;font-weight:750;text-transform:capitalize }
    .repair-index .calendar-nav { width:38px;height:38px;border:0;border-radius:50%;background:#f0f6f1;color:#5f947a;font-size:16px;cursor:pointer }
    .repair-index .calendar-nav:hover { background:#e1eee5 }
    .repair-index .calendar-week,.repair-index .calendar-days { display:grid;grid-template-columns:repeat(7,1fr);gap:2px }
    .repair-index .calendar-week span { padding:4px 0;text-align:center;color:#819489;font-size:11px;font-weight:750 }
    .repair-index .calendar-day { width:100%;height:36px;border:0;border-radius:50%;background:transparent;color:#293d32;font-size:12px;font-weight:650;cursor:pointer }
    .repair-index .calendar-day:hover:not(:disabled) { background:#edf5ef;color:#326a4d }
    .repair-index .calendar-day.active { background:#5f947a;color:#fff }
    .repair-index .calendar-day.in-range { background:#e4f0e7;color:#34684e }
    .repair-index .calendar-day.today:not(.active) { box-shadow:inset 0 0 0 1px #9cbea8 }
    .repair-index .calendar-day:disabled { color:#cbd5e1;cursor:not-allowed }
    .repair-index .calendar-empty { height:36px }
    @media(max-width:850px) { .repair-index .history-toolbar { align-items:stretch;flex-direction:column }.repair-index .history-toolbar-query { width:100%;margin-left:0 }.repair-index .history-search { width:100%;flex:1 }.repair-index .ri-pagination { justify-content:center } }
    @media(max-width:600px) { .repair-index { padding:15px }.repair-index .history-filter-form { flex-direction:column;align-items:stretch }.repair-index .history-filter-field { width:100% }.repair-index .history-filter-actions { width:100% }.repair-index .history-filter-btn { flex:1 }.repair-index .history-toolbar-query { align-items:stretch;flex-direction:column }.repair-index .history-line-filter-inline select { width:100%;max-width:none }.repair-index .ri-heading { align-items:flex-start;flex-direction:column } }
</style>

<form method="GET" action="{{ route($indexRoute) }}" id="repairIndexForm" class="ri-filter-form">
    <div class="history-filter-box">
        <div class="history-filter-form">
            <div class="history-filter-field">
                <label for="start_date">Dari Tanggal</label>
                <div class="history-date-input" data-datepicker="start">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Pilih tanggal" readonly autocomplete="off" aria-label="Dari Tanggal">
                    <div class="calendar-popup"></div>
                </div>
            </div>
            <div class="history-filter-field">
                <label for="end_date">Sampai Tanggal</label>
                <div class="history-date-input" data-datepicker="end">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                    <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="Pilih tanggal" readonly autocomplete="off" aria-label="Sampai Tanggal">
                    <div class="calendar-popup"></div>
                </div>
            </div>
            <div class="history-filter-actions">
                <button type="submit" class="history-filter-btn history-filter-btn-primary"><i class="fa-solid fa-filter" aria-hidden="true"></i>Filter</button>
                @if (request('start_date') || request('end_date'))
                    <a class="history-filter-btn history-filter-btn-reset" href="{{ route($indexRoute, array_filter(['search' => request('search'), 'per_page' => request('per_page'), 'line_id' => $showLineFilter ? request('line_id') : null])) }}"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i>Reset</a>
                @endif
            </div>
        </div>
    </div>
    <div class="history-toolbar">
        <label class="history-per-page" for="per_page">
            <select id="per_page" name="per_page" aria-label="Jumlah data per halaman" onchange="this.form.submit()">
                @foreach ([10,25,50,100] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page',10) === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </label>
        <div class="history-toolbar-query">
            @if ($showLineFilter)
                <label class="history-line-filter-inline" for="line_id">
                    <select id="line_id" name="line_id" aria-label="Filter Line" onchange="this.form.submit()">
                        <option value="">Semua Line</option>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string) request('line_id') === (string) $line->id)>{{ $line->name }}</option>
                        @endforeach
                    </select>
                </label>
            @endif
            <div class="history-search">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $showLineFilter ? 'Cari No Order, User, atau Line...' : 'Cari No Order...' }}" maxlength="100" autocomplete="off" aria-label="Cari order">
                @if (request('search'))
                    <button type="button" class="history-search-clear" aria-label="Hapus pencarian" onclick="this.form.querySelector('[name=search]').value='';this.form.submit()"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                @endif
            </div>
        </div>
    </div>
</form>

<script>
(function () {
    var pickers = document.querySelectorAll('.repair-index [data-datepicker]');
    var startInput = document.getElementById('start_date');
    var endInput = document.getElementById('end_date');
    if (!startInput || !endInput) return;
    function pad(n) { return String(n).padStart(2, '0'); }
    function toValue(y, m, d) { return y + '-' + pad(m + 1) + '-' + pad(d); }
    function parseValue(v) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(v || '')) return null;
        var p = v.split('-').map(Number);
        var date = new Date(p[0], p[1] - 1, p[2]);
        return date.getFullYear() === p[0] && date.getMonth() === p[1] - 1 && date.getDate() === p[2] ? date : null;
    }
    function closeAll() { pickers.forEach(function (p) { p.classList.remove('open'); }); }
    pickers.forEach(function (wrap) {
        var role = wrap.getAttribute('data-datepicker');
        var input = wrap.querySelector('input');
        var popup = wrap.querySelector('.calendar-popup');
        var base = parseValue(input.value) || new Date();
        var view = new Date(base.getFullYear(), base.getMonth(), 1);
        function render() {
            var year = view.getFullYear(), month = view.getMonth();
            var weekday = (new Date(year, month, 1).getDay() + 6) % 7;
            var count = new Date(year, month + 1, 0).getDate();
            var start = startInput.value, end = endInput.value;
            var now = new Date(), today = toValue(now.getFullYear(), now.getMonth(), now.getDate());
            popup.innerHTML = '<div class="calendar-header"><button type="button" class="calendar-nav" data-nav="-1" aria-label="Bulan sebelumnya">&#8249;</button><strong>' + view.toLocaleDateString('id-ID', {month:'long',year:'numeric'}) + '</strong><button type="button" class="calendar-nav" data-nav="1" aria-label="Bulan berikutnya">&#8250;</button></div><div class="calendar-week"><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span><span>M</span></div><div class="calendar-days"></div>';
            var days = popup.querySelector('.calendar-days');
            for (var i = 0; i < weekday; i++) { var empty = document.createElement('div'); empty.className = 'calendar-empty'; days.appendChild(empty); }
            for (var day = 1; day <= count; day++) {
                var v = toValue(year, month, day), button = document.createElement('button');
                button.type = 'button'; button.className = 'calendar-day'; button.textContent = day; button.dataset.value = v;
                if (v === today) button.classList.add('today');
                if (v === input.value) button.classList.add('active');
                if (start && end && v > start && v < end) button.classList.add('in-range');
                if (role === 'start' && end && v > end || role === 'end' && start && v < start) button.disabled = true;
                days.appendChild(button);
            }
        }
        input.addEventListener('click', function () {
            var wasOpen = wrap.classList.contains('open'); closeAll();
            if (!wasOpen) {
                var current = parseValue(input.value);
                if (current) view = new Date(current.getFullYear(), current.getMonth(), 1);
                render(); wrap.classList.add('open');
            }
        });
        popup.addEventListener('click', function (event) {
            event.stopPropagation();
            var nav = event.target.closest('[data-nav]');
            var day = event.target.closest('.calendar-day');
            if (nav) { view.setMonth(view.getMonth() + Number(nav.dataset.nav)); render(); }
            if (day && !day.disabled) { input.value = day.dataset.value; closeAll(); }
        });
    });
    document.addEventListener('click', function (e) { if (!e.target.closest('.repair-index .history-date-input')) closeAll(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });
})();
</script>
