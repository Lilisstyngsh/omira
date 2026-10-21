@extends('layouts.app')
@section('title', 'Additional Box')
@section('header', 'Detail Order Scrap')
@section('content')
    <style>
        .ab-detail {
            display: grid;
            gap: 17px;
        }

        .ab-detail-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            flex-wrap: wrap;
        }

        .ab-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 650;
            color: #438866;
            margin-bottom: 9px;
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

        .ab-downloads {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ab-download {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #347554;
            color: white !important;
            padding: 10px 14px;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            transition: background .15s ease;
        }

        .ab-download:hover {
            background: #24563d;
        }

        .ab-download.excel {
            background: #215f77;
        }

        .ab-download.excel:hover {
            background: #16495e;
        }

        .ab-no-export {
            display: inline-flex;
            gap: 7px;
            align-items: center;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 11px;
        }

        .ab-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .ab-summary-card {
            border: 1px solid #e8edf4;
            border-radius: 14px;
            padding: 14px 17px;
            background: white;
            box-shadow: 0 4px 15px rgba(15, 23, 42, .03);
        }

        .ab-summary-label {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
        }

        .ab-summary-value {
            display: block;
            color: #172033;
            font-size: 23px;
            font-weight: 750;
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
            min-width: 620px;
            table-layout: fixed;
        }

        .ab-table th {
            font-size: 10px;
            letter-spacing: .02em;
            text-transform: uppercase;
            color: #64748b;
            background: #f6f7fb;
            padding: 12px 10px;
            text-align: left;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
        }

        .ab-table td {
            padding: 13px 10px;
            color: #334155;
            font-size: 12px;
            vertical-align: top;
            border-right: 1px solid #dfe6ef;
            border-bottom: 1px solid #dfe6ef;
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
        }

        .ab-status {
            display: inline-flex;
            border-radius: 999px;
            padding: 5px 9px;
            font-weight: 700;
            font-size: 10px;
        }

        .ab-status.yes {
            color: #23633d;
            background: #e7f5eb;
        }

        .ab-status.no {
            color: #92400e;
            background: #fff5db;
        }

        .ab-quantity {
            font-weight: 750;
            color: #172033;
        }

        .ab-muted {
            color: #94a3b8;
        }

        .ab-preview-wrap {
            padding: 14px;
        }

        .ab-preview-frame {
            display: block;
            width: 100%;
            height: 680px;
            border: 1px solid #dfe6ef;
            border-radius: 10px;
            background: #f8fafc;
        }

        .ab-preview-fallback {
            margin: 10px 2px 0;
            color: #64748b;
            font-size: 11px;
        }

        .ab-preview-fallback a {
            color: #347554;
            font-weight: 700;
        }

        @media(max-width:640px) {
            .ab-detail-title {
                font-size: 19px;
            }

            .ab-summary {
                gap: 7px;
            }

            .ab-summary-card {
                padding: 12px;
            }

            .ab-summary-value {
                font-size: 19px;
            }

            .ab-preview-wrap {
                padding: 9px;
            }

            .ab-preview-frame {
                height: 470px;
            }
        }
    </style>
    @php
        // Documents made before decision history existed still show their Order export items.
        $rows = $document->decisions->isNotEmpty() ? $document->decisions : $document->items;
        $orders = $document->decisions->isNotEmpty()
            ? $document->decisions->where('decision', 'yes')->count()
            : $document->items->count();
        $noOrders = $document->decisions->where('decision', 'no')->count();
        $canExport = $document->items->isNotEmpty();
    @endphp
    <div class="ab-detail">
        <div class="ab-detail-top">
            <div>
                <a href="{{ route('user.additional-box.index') }}" class="ab-back"><i class="fa-solid fa-arrow-left"
                        aria-hidden="true"></i> Additional Box</a>
                <h1 class="ab-detail-title">{{ $document->document_number }}</h1>
                <div class="ab-detail-date">{{ $document->created_at->format('d/m/Y · H:i') }}</div>
            </div>
            <div class="ab-downloads">
                @if ($canExport)
                    <a href="{{ route('user.additional-box.export', [$document, 'pdf']) }}" class="ab-download"><i
                            class="fa-solid fa-file-pdf" aria-hidden="true"></i> PDF</a>
                    <a href="{{ route('user.additional-box.export', [$document, 'excel']) }}" class="ab-download excel"><i
                            class="fa-solid fa-file-excel" aria-hidden="true"></i> Excel</a>
                @else
                    <span class="ab-no-export"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Tidak ada produk
                        Order untuk diekspor</span>
                @endif
            </div>
        </div>
        <section class="ab-card">
            <div class="ab-card-header">
                <h2>Detail Keputusan Produk</h2>
            </div>
            <div class="ab-table-wrap">
                <table class="ab-table">
                    <colgroup>
                        <col style="width:43%">
                        <col style="width:20%">
                        <col style="width:21%">
                        <col style="width:16%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">Model – Produk</th>
                            <th scope="col">Scrap / Limit</th>
                            <th scope="col">Keputusan</th>
                            <th scope="col">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $item)
                            @php $isOrder = !isset($item->decision) || $item->decision === 'yes'; @endphp
                            <tr>
                                <td>
                                    <div class="ab-name">{{ $item->model_name }} – {{ $item->product_name }}</div>
                                    @if (!$isOrder && $item->reason)
                                        <div class="ab-note"><strong>Alasan:</strong> {{ $item->reason }}</div>
                                    @endif
                                </td>
                                <td>{{ isset($item->scrap_qty) ? $item->scrap_qty . ' / ' . $item->limit_qty : '—' }}</td>
                                <td><span
                                        class="ab-status {{ $isOrder ? 'yes' : 'no' }}">{{ $isOrder ? 'Order' : 'Tidak' }}</span>
                                </td>
                                <td>
                                    @if ($isOrder)
                                    <span class="ab-quantity">{{ $item->quantity }}</span>@else<span
                                            class="ab-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty<tr>
                                <td colspan="4" class="ab-muted" style="text-align:center;padding:28px">Tidak ada produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @if ($canExport)
            <section class="ab-card" aria-label="Preview Form Additional Box">
                <div class="ab-card-header">
                    <h2>Preview Form Additional Box</h2>
                </div>
                <div class="ab-preview-wrap">
                    <iframe class="ab-preview-frame" src="{{ route('user.additional-box.preview', $document) }}"
                        title="Preview PDF {{ $document->document_number }}" loading="lazy"></iframe>
                    <p class="ab-preview-fallback">Jika preview tidak tampil, <a
                            href="{{ route('user.additional-box.preview', $document) }}" target="_blank"
                            rel="noopener noreferrer">buka PDF di tab baru</a>.</p>
                </div>
            </section>
        @endif
    </div>
@endsection
