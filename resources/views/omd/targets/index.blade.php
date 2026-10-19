@extends('layouts.app')

@section('title', 'Fiscal Year')
@section('header', 'Fiscal Year')

@section('content')
<style>
    .fy-page {
        display: grid;
        gap: 16px;
    }

    .fy-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .fy-header h2 {
        margin: 0;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .fy-action {
        min-height: 38px;
        padding: 0 16px;
        border: 0;
        border-radius: 9px;
        background: #5F9278;
        color: #fff;
        font-size: 12px;
        font-weight: 750;
        cursor: pointer;
        box-shadow: 0 6px 15px rgba(95, 146, 120, .18);
    }

    .fy-action:hover {
        background: #47775c;
    }

    .fy-card {
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .fy-card-head {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        background: #fff;
    }

    .fy-card-head h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 800;
    }

    .fy-scroll {
        max-height: 330px;
        overflow: auto;
    }

    .fy-scroll.abnormality {
        max-height: 390px;
    }

    .fy-table {
        width: 100%;
        min-width: 720px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .fy-table th,
    .fy-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #eef2f6;
        color: #334155;
        font-size: 12px;
        text-align: left;
        vertical-align: middle;
    }

    .fy-table th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #fafbfc;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .fy-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .fy-year {
        color: #172033;
        font-weight: 900;
    }

    .fy-target {
        color: #376B52;
        font-size: 12px;
        font-weight: 900;
    }

    .fy-badge {
        display: inline-flex;
        align-items: center;
        min-height: 22px;
        padding: 0 8px;
        margin-left: 6px;
        border-radius: 999px;
        background: #eef2ff;
        color: #376B52;
        font-size: 9px;
        font-weight: 900;
    }

    .fy-reason {
        min-width: 260px;
        white-space: normal;
        line-height: 1.55;
    }

    .fy-difference.positive {
        color: #dc2626;
        font-weight: 900;
    }

    .fy-difference.neutral {
        color: #64748b;
        font-weight: 800;
    }

    .fy-difference.negative {
        color: #16a34a;
        font-weight: 800;
    }

    .fy-empty {
        padding: 28px 18px;
        color: #94a3b8;
        font-size: 11px;
        text-align: center;
    }

    .fy-alert {
        padding: 12px 14px;
        border-radius: 11px;
        font-size: 11px;
        line-height: 1.55;
    }

    .fy-alert.success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #166534;
    }

    .fy-alert.error {
        border: 1px solid #fecaca;
        background: #fff7f7;
        color: #b91c1c;
    }

    .fy-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1200;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(15, 23, 42, .48);
    }

    .fy-modal-backdrop.is-open {
        display: flex;
    }

    .fy-modal {
        width: min(440px, 100%);
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .24);
        overflow: hidden;
    }

    .fy-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
    }

    .fy-modal-head h3 {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 800;
    }

    .fy-modal-close {
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 9px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 17px;
        cursor: pointer;
    }

    .fy-modal-body {
        display: grid;
        gap: 15px;
        padding: 20px;
    }

    .fy-field {
        display: grid;
        gap: 6px;
    }

    .fy-field label {
        color: #334155;
        font-size: 11px;
        font-weight: 750;
    }

    .fy-field input {
        width: 100%;
        height: 42px;
        box-sizing: border-box;
        padding: 0 12px;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        background: #fff;
        color: #172033;
        font-size: 12px;
        outline: none;
    }

    .fy-field input:focus {
        border-color: #76a88b;
        box-shadow: 0 0 0 3px rgba(95, 146, 120, .10);
    }

    .fy-field-error {
        color: #dc2626;
        font-size: 10px;
        font-weight: 700;
    }

    .fy-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding: 14px 20px 18px;
        border-top: 1px solid #edf1f5;
    }

    .fy-btn {
        min-height: 40px;
        padding: 0 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 750;
        cursor: pointer;
    }

    .fy-btn.cancel {
        border: 0;
        background: #f1f5f9;
        color: #475569;
    }

    .fy-btn.save {
        border: 0;
        background: #5F9278;
        color: #fff;
        box-shadow: 0 6px 15px rgba(95, 146, 120, .18);
    }

    .fy-btn.save:hover {
        background: #47775c;
    }

    @media (max-width: 700px) {
        .fy-header {
            align-items: stretch;
        }

        .fy-action {
            width: 100%;
        }

        .fy-scroll,
        .fy-scroll.abnormality {
            max-height: 320px;
        }

        .fy-modal-actions {
            flex-direction: column-reverse;
        }

        .fy-btn {
            width: 100%;
            min-height: 44px;
        }
    }
</style>

@php
$monthNames = [
1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
];

$targetMap = $targetHistory
->mapWithKeys(fn ($row) => [(string) $row->year => (int) $row->target_qty]);
@endphp

<div class="fy-page">
    <div class="fy-header">
        <h2>Target FY</h2>

        <button type="button" class="fy-action" id="openTargetModal">
            + Atur Target
        </button>
    </div>

    @if (session('success'))
    <div class="fy-alert success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="fy-alert error">
        @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div class="fy-card">
        <div class="fy-card-head">
            <h3>Daftar Target FY</h3>
        </div>

        @if ($targetHistory->isEmpty())
        <div class="fy-empty">Belum ada Target Fiscal Year.</div>
        @else
        <div class="fy-scroll">
            <table class="fy-table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Target FY</th>
                        <th>Diubah Oleh</th>
                        <th>Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($targetHistory as $row)
                    <tr>
                        <td>
                            <span class="fy-year">{{ $row->year }}</span>
                            @if ((int) $row->year === (int) $currentYear)
                            <span class="fy-badge">Aktif</span>
                            @endif
                        </td>
                        <td><span class="fy-target">{{ number_format($row->target_qty, 0, ',', '.') }}</span></td>
                        <td>{{ $row->updater?->name ?? $row->creator?->name ?? '-' }}</td>
                        <td>{{ $row->updated_at?->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="fy-card">
        <div class="fy-card-head">
            <h3>Riwayat Abnormality</h3>
        </div>

        @if ($abnormalities->isEmpty())
        <div class="fy-empty">Belum ada histori abnormality Target Fiscal Year.</div>
        @else
        <div class="fy-scroll abnormality">
            <table class="fy-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Actual Order</th>
                        <th>Target FY</th>
                        <th>Selisih</th>
                        <th>Alasan</th>
                        <th>Diinput Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($abnormalities as $row)
                    @php
                    $difference = (int) $row->actual_qty - (int) $row->threshold_qty;
                    $differenceClass = $difference > 0
                    ? 'positive'
                    : ($difference < 0 ? 'negative' : 'neutral' );
                        @endphp
                        <tr>
                        <td><strong>{{ $monthNames[$row->month] ?? $row->month }} {{ $row->year }}</strong></td>
                        <td>{{ number_format($row->actual_qty, 0, ',', '.') }}</td>
                        <td>{{ number_format($row->threshold_qty, 0, ',', '.') }}</td>
                        <td>
                            <span class="fy-difference {{ $differenceClass }}">
                                {{ $difference > 0 ? '+' : '' }}{{ number_format($difference, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="fy-reason">{{ $row->reason }}</td>
                        <td>{{ $row->creator?->name ?? $row->updater?->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<div class="fy-modal-backdrop @if($errors->has('year') || $errors->has('target_qty')) is-open @endif" id="targetModal" aria-hidden="{{ $errors->has('year') || $errors->has('target_qty') ? 'false' : 'true' }}">
    <div class="fy-modal" role="dialog" aria-modal="true" aria-labelledby="targetModalTitle">
        <div class="fy-modal-head">
            <h3 id="targetModalTitle">Atur Target FY</h3>
            <button type="button" class="fy-modal-close" id="closeTargetModal" aria-label="Tutup">×</button>
        </div>

        <form method="POST" action="{{ route('omd.targets.store') }}">
            @csrf

            <div class="fy-modal-body">
                <div class="fy-field">
                    <label for="target_year">Tahun</label>
                    <input
                        type="number"
                        id="target_year"
                        name="year"
                        min="{{ $currentYear }}"
                        max="2100"
                        value="{{ old('year', $currentYear) }}"
                        required>
                    @error('year')
                    <div class="fy-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="fy-field">
                    <label for="target_qty">Target FY</label>
                    <input
                        type="number"
                        id="target_qty"
                        name="target_qty"
                        min="0"
                        value="{{ old('target_qty', $targetMap->get((string) old('year', $currentYear), '')) }}"
                        required>
                    @error('target_qty')
                    <div class="fy-field-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="fy-modal-actions">
                <button type="button" class="fy-btn cancel" id="cancelTargetModal">Batal</button>
                <button type="submit" class="fy-btn save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (() => {
        const modal = document.getElementById('targetModal');
        const openButton = document.getElementById('openTargetModal');
        const closeButton = document.getElementById('closeTargetModal');
        const cancelButton = document.getElementById('cancelTargetModal');
        const yearInput = document.getElementById('target_year');
        const targetInput = document.getElementById('target_qty');
        const targets = @json($targetMap);

        const openModal = () => {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            setTimeout(() => yearInput?.focus(), 0);
        };

        const closeModal = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        };

        openButton?.addEventListener('click', openModal);
        closeButton?.addEventListener('click', closeModal);
        cancelButton?.addEventListener('click', closeModal);

        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal?.classList.contains('is-open')) {
                closeModal();
            }
        });

        yearInput?.addEventListener('input', () => {
            const key = String(yearInput.value || '');
            targetInput.value = Object.prototype.hasOwnProperty.call(targets, key) ?
                targets[key] :
                '';
        });
    })();
</script>
@endsection