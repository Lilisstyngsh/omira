@extends('layouts.app')

@section('title', 'Scrap Limit Model')
@section('header', 'Scrap Limit Model')

@section('content')
    <style>
        .scrap-limit-page {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .scrap-page-head h2 {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .scrap-page-head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.6;
        }

        .scrap-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .04);
            padding: 20px;
        }

        .scrap-filter {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .scrap-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 240px;
        }

        .scrap-field label,
        .scrap-form-field label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .scrap-field select,
        .scrap-form-field input,
        .scrap-form-field textarea {
            width: 100%;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-size: 12px;
            outline: none;
        }

        .scrap-field select,
        .scrap-form-field input {
            height: 42px;
            padding: 0 12px;
        }

        .scrap-form-field textarea {
            min-height: 82px;
            padding: 10px 12px;
            resize: vertical;
        }

        .scrap-field select:focus,
        .scrap-form-field input:focus,
        .scrap-form-field textarea:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .08);
        }

        .scrap-btn {
            min-height: 42px;
            padding: 0 16px;
            border: 0;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .scrap-btn-primary {
            background: #5b4ce6;
            color: #fff;
        }

        .scrap-btn-success {
            background: #16a34a;
            color: #fff;
        }

        .scrap-alert-success,
        .scrap-alert-error {
            padding: 13px 15px;
            border-radius: 11px;
            font-size: 12px;
        }

        .scrap-alert-success {
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-weight: 700;
        }

        .scrap-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .model-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .model-limit-card {
            border: 1px solid #e5eaf1;
            border-radius: 14px;
            padding: 16px;
            background: #fff;
        }

        .model-limit-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .model-limit-name {
            font-size: 15px;
            font-weight: 900;
            color: #172033;
        }

        .model-limit-line {
            margin-top: 3px;
            font-size: 11px;
            color: #64748b;
        }

        .limit-value {
            margin-top: 16px;
            font-size: 28px;
            font-weight: 900;
            color: #172033;
            line-height: 1;
        }

        .limit-value span {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 700;
        }

        .limit-period {
            margin-top: 8px;
            color: #64748b;
            font-size: 11px;
            line-height: 1.5;
        }

        .limit-status {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 0 9px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .04em;
            background: #ecfdf3;
            color: #15803d;
        }

        .limit-status.empty {
            background: #f1f5f9;
            color: #64748b;
        }

        .limit-editor {
            margin-top: 15px;
            border-top: 1px solid #eef2f7;
            padding-top: 12px;
        }

        .limit-editor summary {
            cursor: pointer;
            color: #5b4ce6;
            font-size: 11px;
            font-weight: 800;
            list-style: none;
        }

        .limit-editor summary::-webkit-details-marker {
            display: none;
        }

        .scrap-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 12px;
        }

        .scrap-form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .scrap-form-field.full {
            grid-column: 1 / -1;
        }

        .history-title {
            margin: 0 0 14px;
            font-size: 16px;
            font-weight: 900;
            color: #172033;
        }

        .history-wrap {
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
        }

        .history-table th {
            padding: 11px 12px;
            background: #f8fafc;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .history-table td {
            padding: 11px 12px;
            font-size: 11px;
            color: #334155;
            border-bottom: 1px solid #eef2f7;
            vertical-align: top;
        }

        .empty-box {
            padding: 14px 15px;
            border-radius: 11px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        @media (max-width: 1199px) {
            .model-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .scrap-card {
                padding: 15px;
            }

            .scrap-filter,
            .scrap-form-grid {
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }

            .scrap-field {
                min-width: 0;
            }

            .scrap-btn {
                width: 100%;
                min-height: 46px;
            }

            .model-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="scrap-limit-page">
        <div class="scrap-page-head">
            <h2>Scrap Limit per Model</h2>
            <p>
                Setiap perubahan disimpan sebagai periode baru. Limit lama tidak ditimpa sehingga histori tetap dapat digunakan untuk monitoring data pada periode sebelumnya.
            </p>
        </div>

        @if (session('success'))
            <div class="scrap-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="scrap-alert-error">
                <strong>Data belum dapat disimpan.</strong>
                <div style="margin-top: 5px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="scrap-card">
            <form method="GET" action="{{ route('omd.scrap-limits.index') }}" class="scrap-filter">
                <div class="scrap-field">
                    <label for="line_id">Line</label>
                    <select id="line_id" name="line_id" required>
                        <option value="">Pilih Line</option>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string) $lineId === (string) $line->id)>
                                {{ $line->plant?->name ? $line->plant->name . ' — ' : '' }}{{ $line->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="scrap-btn scrap-btn-primary">
                    Tampilkan Model
                </button>
            </form>
        </div>

        @if ($lineId)
            @if ($models->isEmpty())
                <div class="scrap-card">
                    <div class="empty-box">Belum ada Master Model aktif pada Line yang dipilih.</div>
                </div>
            @else
                <div class="model-grid">
                    @foreach ($models as $model)
                        @php
                            $activeLimit = $model->scrapLimits->first(function ($limit) use ($today) {
                                return $limit->effective_from->toDateString() <= $today
                                    && ($limit->effective_to === null || $limit->effective_to->toDateString() >= $today);
                            });
                        @endphp

                        <div class="model-limit-card">
                            <div class="model-limit-head">
                                <div>
                                    <div class="model-limit-name">{{ $model->model }}</div>
                                    <div class="model-limit-line">{{ $model->line?->name }}</div>
                                </div>

                                <span class="limit-status {{ $activeLimit ? '' : 'empty' }}">
                                    {{ $activeLimit ? 'Aktif' : 'Belum diatur' }}
                                </span>
                            </div>

                            <div class="limit-value">
                                {{ $activeLimit ? number_format($activeLimit->limit_qty) : '—' }}
                                @if ($activeLimit)
                                    <span>box</span>
                                @endif
                            </div>

                            <div class="limit-period">
                                @if ($activeLimit)
                                    Berlaku {{ $activeLimit->effective_from->format('d M Y') }}
                                    @if ($activeLimit->effective_to)
                                        s.d. {{ $activeLimit->effective_to->format('d M Y') }}
                                    @else
                                        dan seterusnya
                                    @endif
                                @else
                                    Belum ada Scrap Limit yang aktif untuk tanggal hari ini.
                                @endif
                            </div>

                            <details class="limit-editor" @if ((string) old('master_model_id') === (string) $model->id && $errors->any()) open @endif>
                                <summary>+ Set / Ubah Scrap Limit</summary>

                                <form method="POST" action="{{ route('omd.scrap-limits.store') }}">
                                    @csrf
                                    <input type="hidden" name="master_model_id" value="{{ $model->id }}">

                                    <div class="scrap-form-grid">
                                        <div class="scrap-form-field">
                                            <label>Limit Qty</label>
                                            <input type="number" name="limit_qty"
                                                value="{{ (string) old('master_model_id') === (string) $model->id ? old('limit_qty') : '' }}"
                                                min="0" required>
                                        </div>

                                        <div class="scrap-form-field">
                                            <label>Mulai Berlaku</label>
                                            <input type="date" name="effective_from"
                                                value="{{ (string) old('master_model_id') === (string) $model->id ? old('effective_from', $today) : $today }}"
                                                required>
                                        </div>

                                        <div class="scrap-form-field full">
                                            <label>Keterangan Perubahan</label>
                                            <textarea name="note" placeholder="Opsional. Contoh: revisi limit hasil evaluasi bulanan.">{{ (string) old('master_model_id') === (string) $model->id ? old('note') : '' }}</textarea>
                                        </div>

                                        <div class="scrap-form-field full">
                                            <button type="submit" class="scrap-btn scrap-btn-success">
                                                Simpan Periode Baru
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </details>
                        </div>
                    @endforeach
                </div>

                <div class="scrap-card">
                    <h3 class="history-title">Histori Scrap Limit</h3>

                    @if ($history->isEmpty())
                        <div class="empty-box">Belum ada histori Scrap Limit pada Line ini.</div>
                    @else
                        <div class="history-wrap">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Limit</th>
                                        <th>Mulai</th>
                                        <th>Sampai</th>
                                        <th>Keterangan</th>
                                        <th>Diinput Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $limit)
                                        <tr>
                                            <td><strong>{{ $limit->masterModel?->model ?? '-' }}</strong></td>
                                            <td>{{ number_format($limit->limit_qty) }}</td>
                                            <td>{{ $limit->effective_from->format('d M Y') }}</td>
                                            <td>{{ $limit->effective_to?->format('d M Y') ?? 'Seterusnya' }}</td>
                                            <td>{{ $limit->note ?: '-' }}</td>
                                            <td>{{ $limit->creator?->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        @else
            <div class="scrap-card">
                <div class="empty-box">
                    Pilih <strong>Line</strong> terlebih dahulu untuk melihat Master Model dan mengatur Scrap Limit.
                </div>
            </div>
        @endif
    </div>
@endsection
