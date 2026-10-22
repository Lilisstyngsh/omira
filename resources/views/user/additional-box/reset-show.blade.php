@extends('layouts.app')
@section('title', 'Additional Box')
@section('header', 'Detail Reset Scrap Limit')
@section('content')
    <style>
        .ab-detail {
            display: grid;
            gap: 17px;
        }

        .ab-detail-top {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .ab-back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            padding: 0 15px;
            border: 1px solid #2563eb;
            border-radius: 9px;
            background: #2563eb;
            color: #fff !important;
            text-decoration: none !important;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            transition: background .15s ease, border-color .15s ease;
        }

        .ab-back-button:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .ab-detail-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
            width: 100%;
        }

        .ab-detail-title {
            margin: 0;
            color: #172033;
            font-size: 21px;
            font-weight: 750;
        }

        .ab-detail-date {
            color: #64748b;
            font-size: 12px;
            margin-top: 5px;
        }

        .ab-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .ab-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(135deg, #F4F8F5 0%, #fff 75%);
        }

        .ab-card-header h2 {
            margin: 0;
            font-size: 15px;
            font-weight: 650;
            color: #172033;
        }

        .ab-table-wrap {
            padding: 12px;
            overflow-x: auto;
        }

        .ab-table {
            border: 1px solid #dfe6ef;
            border-radius: 12px;
            border-spacing: 0;
            border-collapse: separate;
            overflow: hidden;
            width: 100%;
            min-width: 1120px;
            table-layout: fixed;
        }

        .ab-table th {
            font-size: 11px;
            letter-spacing: .03em;
            text-transform: uppercase;
            color: #64748b;
            background: #F9FAFB;
            padding: 12px 14px;
            text-align: left;
            border-right: 1px solid #e5eaf0;
            border-bottom: 1px solid #e5eaf0;
        }

        .ab-table td {
            padding: 13px 14px;
            color: #111827;
            font-size: 12px;
            vertical-align: top;
            border-right: 1px solid #e5eaf0;
            border-bottom: 1px solid #e5eaf0;
            overflow-wrap: anywhere;
        }

        .ab-table th:last-child,
        .ab-table td:last-child {
            border-right: 0;
        }

        .ab-table tr:last-child td {
            border-bottom: 0;
        }

        .ab-name {
            font-weight: 700;
            color: #263e30;
        }

        .ab-note {
            color: #64748b;
            margin-top: 6px;
            font-size: 11px;
            line-height: 1.5;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .ab-status {
            display: inline-flex;
            border-radius: 999px;
            padding: 5px 9px;
            font-weight: 700;
            font-size: 10px;
        }

        .ab-status.reset {
            color: #245ba8;
            background: #eaf2ff;
        }

        .ab-reason-content {
            padding: 18px 20px;
            color: #334155;
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        @media(max-width:640px) {
            .ab-detail-title {
                font-size: 19px;
            }
        }
    </style>

    <div class="ab-detail">
        <div class="ab-detail-top">
            <a href="{{ route('user.additional-box.index') }}" class="ab-back-button">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>

        <section class="ab-card">
            <div class="ab-card-header">
                <h2>Rincian Reset</h2>
            </div>
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <colgroup>
                        <col style="width:11%">
                        <col style="width:14%">
                        <col style="width:21%">
                        <col style="width:12%">
                        <col style="width:14%">
                        <col style="width:16%">
                        <col style="width:12%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">No Order</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Model – Produk</th>
                            <th scope="col">Scrap / Limit</th>
                            <th scope="col">Keputusan</th>
                            <th scope="col">Alasan</th>
                            <th scope="col">Petugas OMD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="ab-muted">—</span></td>
                            <td>{{ $cycle->reset_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="ab-name">{{ $cycle->product?->masterModel?->model ?: '—' }} – {{ $cycle->product?->name ?: '—' }}</div>
                            </td>
                            <td style="text-align:center;white-space:nowrap">{{ $cycle->reached_qty ?? 0 }} / {{ $cycle->limit_qty }}</td>
                            <td><span class="ab-status reset">Reset oleh OMD</span></td>
                            <td><div class="ab-note">{{ $cycle->reset_reason ?: '—' }}</div></td>
                            <td>{{ $cycle->resetter?->name ?? 'OMD' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
@endsection
