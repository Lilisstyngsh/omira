@extends('layouts.app')

@section('title', 'Pengaturan Target')
@section('header', 'Pengaturan Target')

@section('content')

    <style>
        .target-page {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .page-head h2 {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .page-head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .target-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .04);
            padding: 20px;
        }

        .filter-card {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 180px;
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
            height: 40px;
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
            height: 40px;
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

        .btn-primary:hover {
            background: #4f46d7;
        }

        .btn-success {
            background: #16a34a;
            color: #fff;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .alert-success {
            padding: 13px 15px;
            border-radius: 11px;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
        }

        .alert-error {
            padding: 13px 15px;
            border-radius: 11px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 12px;
        }

        .target-title {
            margin-bottom: 16px;
        }

        .target-title h3 {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .target-title p {
            margin: 0;
            color: #64748b;
            font-size: 11px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .target-table {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
        }

        .target-table th {
            padding: 12px 14px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
        }

        .target-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #eef2f7;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
        }

        .target-table tbody tr:last-child td {
            border-bottom: none;
        }

        .month-number {
            width: 70px;
            text-align: center !important;
            color: #94a3b8 !important;
            font-weight: 700;
        }

        .month-name {
            font-weight: 800;
            color: #172033 !important;
        }

        .target-input {
            width: 180px;
        }

        .target-input input {
            width: 100%;
            height: 38px;
            padding: 0 10px;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            outline: none;
            font-size: 12px;
            color: #334155;
        }

        .target-input input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .08);
        }

        .save-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .info-box {
            padding: 13px 15px;
            border-radius: 11px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #334155;
        }

        @media (max-width: 700px) {
            .filter-card {
                align-items: stretch;
                flex-direction: column;
            }

            .field {
                min-width: 0;
            }

            .btn {
                width: 100%;
            }

            .save-row {
                justify-content: stretch;
            }
        }
    </style>

    @php
        $fiscalMonths = [
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
        ];
    @endphp

    <div class="target-page">

        {{-- HEADER --}}
        <div class="page-head">
            <div>
                <h2>Pengaturan Target FY</h2>
                <p>
                    Atur target repair dan batas Scrap berdasarkan Line untuk setiap bulan.
                </p>
            </div>
        </div>

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>Data belum dapat disimpan.</strong>

                <div style="margin-top: 5px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- FILTER --}}
        <div class="target-card">
            <form method="GET" action="{{ route('omd.targets.index') }}" class="filter-card">

                <div class="field">
                    <label for="year">
                        Tahun FY
                    </label>

                    <input type="number" id="year" name="year" value="{{ $year }}" min="2020"
                        max="2100">
                </div>

                <div class="field">
                    <label for="line_id">
                        Line
                    </label>

                    <select id="line_id" name="line_id">
                        <option value="">
                            Pilih Line
                        </option>

                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string) $lineId === (string) $line->id)>
                                {{ $line->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Tampilkan
                </button>

            </form>
        </div>

        {{-- TABLE --}}
        @if ($lineId)

            @php
                $selectedLine = $lines->firstWhere('id', $lineId);
            @endphp

            <div class="target-card">

                <div class="target-title">
                    <h3>
                        Target FY {{ $year }}
                        @if ($selectedLine)
                            — {{ $selectedLine->name }}
                        @endif
                    </h3>

                    <p>
                        Periode fiscal year: April {{ $year }}
                        sampai Maret {{ $year + 1 }}.
                    </p>
                </div>

                <form method="POST" action="{{ route('omd.targets.store') }}">

                    @csrf

                    <input type="hidden" name="year" value="{{ $year }}">

                    <input type="hidden" name="line_id" value="{{ $lineId }}">

                    <div class="table-wrap">

                        <table class="target-table">

                            <thead>
                                <tr>
                                    <th class="month-number">
                                        No
                                    </th>

                                    <th>
                                        Bulan
                                    </th>

                                    <th>
                                        Target
                                    </th>

                                    <th>
                                        Batas Scrap
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($fiscalMonths as $monthNumber => $monthName)
                                    @php
                                        $target = $targets->get($monthNumber);
                                    @endphp

                                    <tr>

                                        <td class="month-number">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="month-name">
                                            {{ $monthName }}
                                        </td>

                                        <td class="target-input">
                                            <input type="number" name="targets[{{ $monthNumber }}][target_qty]"
                                                value="{{ old("targets.$monthNumber.target_qty", $target?->target_qty ?? 0) }}"
                                                min="0" required>
                                        </td>

                                        <td class="target-input">
                                            <input type="number" name="targets[{{ $monthNumber }}][scrap_limit]"
                                                value="{{ old("targets.$monthNumber.scrap_limit", $target?->scrap_limit ?? 20) }}"
                                                min="0" required>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="save-row">

                        <button type="submit" class="btn btn-success">
                            Simpan Target
                        </button>

                    </div>

                </form>

            </div>
        @else
            <div class="target-card">

                <div class="info-box">
                    Pilih <strong>Tahun FY</strong> dan <strong>Line</strong>
                    terlebih dahulu untuk menampilkan pengaturan target.
                </div>

            </div>

        @endif

    </div>

@endsection
