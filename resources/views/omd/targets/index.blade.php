@extends('layouts.app')

@section('title', 'Target FY')
@section('header', 'Target FY')

@section('content')
    <style>
        .target-page {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .target-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .target-head h2 {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .target-head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.6;
        }

        .target-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .04);
            padding: 20px;
        }

        .filter-grid,
        .target-form-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            align-items: end;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .field select,
        .field input {
            width: 100%;
            height: 42px;
            padding: 0 12px;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-size: 12px;
            outline: none;
        }

        .field select:focus,
        .field input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .08);
        }

        .btn {
            min-height: 42px;
            padding: 0 16px;
            border: none;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #5b4ce6;
            color: #fff;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
        }

        .alert-success,
        .alert-error {
            padding: 13px 15px;
            border-radius: 11px;
            font-size: 12px;
        }

        .alert-success {
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-weight: 700;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .section-title {
            margin-bottom: 14px;
        }

        .section-title h3 {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .section-title p {
            margin: 0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .summary-box {
            border: 1px solid #e8edf4;
            border-radius: 13px;
            background: #f8fafc;
            padding: 14px;
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: .04em;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 900;
            color: #172033;
        }

        .summary-sub {
            margin-top: 4px;
            color: #64748b;
            font-size: 11px;
        }

        .scope-badge {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            padding: 0 9px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 10px;
            font-weight: 800;
        }

        .info-box {
            padding: 13px 15px;
            border-radius: 11px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.65;
        }

        .info-box strong {
            color: #334155;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 11px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
        }

        .data-table td {
            padding: 11px 12px;
            border-bottom: 1px solid #eef2f7;
            font-size: 11px;
            color: #334155;
            vertical-align: top;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .empty-state {
            padding: 22px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
            border: 1px dashed #dbe2ea;
            border-radius: 12px;
            background: #fbfdff;
        }

        .reason-cell {
            min-width: 260px;
            white-space: normal;
            line-height: 1.55;
        }

        @media (max-width: 1100px) {
            .filter-grid,
            .target-form-grid,
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .target-card {
                padding: 15px;
                border-radius: 13px;
            }

            .target-head h2 {
                font-size: 19px;
            }

            .filter-grid,
            .target-form-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
                min-height: 46px;
            }

            .field select,
            .field input {
                min-height: 46px;
            }
        }
    </style>

    @php
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $selectedLine = $lineId ? $lines->firstWhere('id', $lineId) : null;
        $scopeLabel = $selectedLine
            ? ($selectedLine->plant?->name ? $selectedLine->plant->name . ' — ' : '') . $selectedLine->name
            : 'Semua Line (Global)';
    @endphp

    <div class="target-page">
        <div class="target-head">
            <div>
                <h2>Target FY</h2>
                <p>
                    Satu nilai target untuk setiap tahun dan scope. Target ini akan menjadi pembanding realtime
                    Order pada Dashboard dan dapat berbeda pada tahun berikutnya.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Data belum dapat disimpan.</strong>
                <div style="margin-top:5px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="target-card">
            <div class="section-title">
                <h3>Pilih periode dan scope</h3>
                <p>Gunakan Global untuk target seluruh Line atau pilih Line jika mempunyai target khusus.</p>
            </div>

            <form method="GET" action="{{ route('omd.targets.index') }}" class="filter-grid">
                <div class="field">
                    <label for="year">Tahun</label>
                    <input type="number" id="year" name="year" value="{{ $year }}" min="2020" max="2100">
                </div>

                <div class="field">
                    <label for="line_id">Scope Target</label>
                    <select id="line_id" name="line_id">
                        <option value="">Semua Line (Global)</option>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string) $lineId === (string) $line->id)>
                                {{ $line->plant?->name ? $line->plant->name . ' — ' : '' }}{{ $line->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>

        <div class="target-card">
            <div class="section-title">
                <h3>Target {{ $year }} — {{ $scopeLabel }}</h3>
                <p>
                    Nilai target digunakan sebagai garis pembanding bulanan. Jika Order realtime melewati nilai ini,
                    Dashboard akan menampilkan abnormality dan meminta alasan dari OMD pada Batch Dashboard.
                </p>
            </div>

            <div class="summary-grid">
                <div class="summary-box">
                    <div class="summary-label">Target Aktif</div>
                    <div class="summary-value">{{ number_format((int) ($target?->target_qty ?? 0), 0, ',', '.') }}</div>
                    <div class="summary-sub">Box / qty pembanding per bulan</div>
                </div>

                <div class="summary-box">
                    <div class="summary-label">Tahun</div>
                    <div class="summary-value">{{ $year }}</div>
                    <div class="summary-sub">Berubah berdasarkan tahun pengaturan</div>
                </div>

                <div class="summary-box">
                    <div class="summary-label">Scope</div>
                    <div style="margin-top:8px;">
                        <span class="scope-badge">{{ $scopeLabel }}</span>
                    </div>
                    <div class="summary-sub">
                        {{ $target?->updated_at ? 'Update ' . $target->updated_at->format('d-m-Y H:i') : 'Belum pernah disimpan' }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('omd.targets.store') }}" class="target-form-grid">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                @if ($lineId)
                    <input type="hidden" name="line_id" value="{{ $lineId }}">
                @endif

                <div class="field">
                    <label for="target_qty">Target Qty</label>
                    <input
                        type="number"
                        id="target_qty"
                        name="target_qty"
                        min="0"
                        required
                        value="{{ old('target_qty', $target?->target_qty ?? 0) }}"
                    >
                </div>

                <div class="info-box">
                    <strong>Rule Dashboard:</strong><br>
                    Line spesifik menggunakan target Line jika tersedia. Jika tidak ada, Dashboard dapat memakai target Global sebagai fallback.
                </div>

                <button type="submit" class="btn btn-success">Simpan Target FY</button>
            </form>
        </div>

        <div class="target-card">
            <div class="section-title">
                <h3>Ringkasan Target {{ $year }}</h3>
                <p>Memudahkan OMD memeriksa target Global dan target khusus per Line dalam tahun yang sama.</p>
            </div>

            @if ($targetHistory->isEmpty())
                <div class="empty-state">Belum ada Target FY untuk tahun {{ $year }}.</div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Scope</th>
                                <th>Target</th>
                                <th>Diubah Oleh</th>
                                <th>Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($targetHistory as $row)
                                <tr>
                                    <td>
                                        <span class="scope-badge">
                                            {{ $row->line?->name ?? 'Semua Line (Global)' }}
                                        </span>
                                    </td>
                                    <td><strong>{{ number_format($row->target_qty, 0, ',', '.') }}</strong></td>
                                    <td>{{ $row->updater?->name ?? '-' }}</td>
                                    <td>{{ $row->updated_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="target-card">
            <div class="section-title">
                <h3>Histori Abnormality — {{ $scopeLabel }}</h3>
                <p>
                    Tabel ini disiapkan untuk menyimpan alasan ketika Order melewati Target FY. Form alasan dan alert
                    realtime akan dihubungkan dari Dashboard pada Batch 4 agar actual qty berasal dari perhitungan sistem,
                    bukan input manual.
                </p>
            </div>

            @if ($abnormalities->isEmpty())
                <div class="empty-state">Belum ada abnormality Target FY pada scope ini.</div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Actual Order</th>
                                <th>Target</th>
                                <th>Selisih</th>
                                <th>Alasan</th>
                                <th>Diinput Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($abnormalities as $row)
                                <tr>
                                    <td>{{ $monthNames[$row->month] ?? $row->month }} {{ $row->year }}</td>
                                    <td>{{ number_format($row->actual_qty, 0, ',', '.') }}</td>
                                    <td>{{ number_format($row->threshold_qty, 0, ',', '.') }}</td>
                                    <td>
                                        <strong>+{{ number_format(max(0, $row->actual_qty - $row->threshold_qty), 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="reason-cell">{{ $row->reason }}</td>
                                    <td>{{ $row->updater?->name ?? $row->creator?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
