@extends('layouts.app')

@section('title', 'Order Repair Box')
@section('header', 'Order Repair Box')

@section('content')
    @php
        $indexRoute = 'user.orders.index';
        $showLineFilter = false;
    @endphp
    @php $completedOrders = $orders->getCollection()->where('status', 'completed')->count(); @endphp

    <div class="repair-index">
        <div class="ri-heading">
            <h2>Daftar Order Repair</h2><a href="{{ route('user.orders.create') }}" class="ri-create"><i
                    class="fa-solid fa-plus" aria-hidden="true"></i>Buat Order</a>
        </div>
        @include('partials.repair-order-index-filter')
        <div class="ri-table-wrap">
            <table class="ri-table">
                <colgroup>
                    <col style="width:6%">
                    <col style="width:14%">
                    <col style="width:22%">
                    <col style="width:9%">
                    <col style="width:12.25%">
                    <col style="width:12.25%">
                    <col style="width:12.25%">
                    <col style="width:12%">
                </colgroup>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No Order</th>
                        <th rowspan="2">Qty</th>
                        <th colspan="4">Status Proses</th>
                    </tr>
                    <tr>
                        <th class="process-cell">User Submit</th>
                        <th class="process-cell">Verifikasi OMD</th>
                        <th class="process-cell">Repair OMD</th>
                        <th class="process-cell">Serah Terima</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse ($orders as $index => $order)
                        <tr class="ri-link-row" role="link" tabindex="0"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"
                            onclick="window.location='{{ route('user.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="ri-count">
                                {{ $orders->firstItem() + $index }}
                            </td>

                            <td class="ri-date">
                                @if ($order->created_at)
                                    <strong>{{ $order->created_at->format('d-m-Y') }}</strong>
                                    <small>{{ $order->created_at->format('H:i') }}</small>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="ri-order-number">
                                {{ $order->order_number }}
                            </td>

                            <td class="ri-qty">
                                {{ in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true) ? (int) ($order->after_qty_sum ?? 0) : (int) ($order->before_qty_sum ?? $order->quantity) }}
                            </td>

                            {{-- USER SUBMIT --}}
                            <td class="process-cell">

                                <span class="process-icon process-done" title="User sudah submit order">
                                    ✓
                                </span>

                            </td>

                            {{-- VERIFIED OMD --}}
                            <td class="process-cell">

                                @if (in_array($order->status, ['in_repair', 'completed', 'revision_requested', 'confirmed']))
                                    <span class="process-icon process-done" title="Order sudah diverifikasi OMD">
                                        ✓
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Belum diverifikasi OMD">
                                        ✕
                                    </span>
                                @endif

                            </td>

                            {{-- REPAIR OMD --}}
                            <td class="process-cell">

                                @if (in_array($order->status, ['completed', 'revision_requested', 'confirmed']))
                                    <span class="process-icon process-done" title="Repair OMD sudah selesai">
                                        ✓
                                    </span>
                                @elseif ($order->status === 'in_repair')
                                    <span class="process-icon process-progress" title="Repair OMD sedang diproses">
                                        △
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Repair OMD belum dimulai">
                                        ✕
                                    </span>
                                @endif

                            </td>

                            {{-- SERAH TERIMA --}}
                            <td class="process-cell">

                                @if ($order->status === 'confirmed')
                                    <span class="process-icon process-done" title="Serah terima sudah selesai">
                                        ✓
                                    </span>
                                @elseif ($order->status === 'revision_requested')
                                    <span class="process-icon process-progress"
                                        title="User meminta koreksi hasil repair dari OMD">
                                        △
                                    </span>
                                @elseif ($order->status === 'completed')
                                    <span class="process-icon process-progress"
                                        title="Menunggu serah terima / konfirmasi User">
                                        △
                                    </span>
                                @else
                                    <span class="process-icon process-pending" title="Belum masuk tahap serah terima">
                                        ✕
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="ri-empty">
                                <div class="order-empty-text">
                                    {{ request()->hasAny(['start_date', 'end_date', 'search', 'line_id']) ? 'Tidak ada order sesuai filter.' : 'Belum ada Order Repair Box.' }}
                                </div>
                            </td>

                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <div class="ri-pagination">
            <span class="ri-pagination-info">Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }}
                dari {{ $orders->total() }} order</span>
            @if ($orders->lastPage() > 1)
                <nav class="ri-pagination-links" aria-label="Halaman Order Repair">
                    @if ($orders->onFirstPage())
                        <span class="ri-page disabled">Previous</span>
                    @else
                        <a class="ri-page" href="{{ $orders->previousPageUrl() }}">Previous</a>
                    @endif
                    @if ($orders->currentPage() > 3)
                        <a class="ri-page" href="{{ $orders->url(1) }}">1</a>
                        @if ($orders->currentPage() > 4)
                            <span class="ri-pagination-info">…</span>
                        @endif
                    @endif
                    @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $number => $url)
                        <a href="{{ $url }}"
                            class="ri-page {{ $number === $orders->currentPage() ? 'active' : '' }}"
                            @if ($number === $orders->currentPage()) aria-current="page" @endif>{{ $number }}</a>
                    @endforeach
                    @if ($orders->currentPage() < $orders->lastPage() - 2)
                        @if ($orders->currentPage() < $orders->lastPage() - 3)
                            <span class="ri-pagination-info">…</span>
                        @endif
                        <a class="ri-page" href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a>
                    @endif
                    @if ($orders->hasMorePages())
                        <a class="ri-page" href="{{ $orders->nextPageUrl() }}">Next</a>
                    @else
                        <span class="ri-page disabled">Next</span>
                    @endif
                </nav>
            @endif
        </div>
    </div>

@endsection
