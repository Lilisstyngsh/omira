@extends('layouts.app')

@section('title', 'Order Repair Box')
@section('header', 'Order Repair Box')

@section('content')
    @php
        $indexRoute = 'omd.orders.index';
        $showLineFilter = true;
    @endphp

    <div class="repair-index">
        <div class="ri-heading">
            <h2>Daftar Order Repair</h2>
        </div>
        @include('partials.repair-order-index-filter')
        <div class="ri-table-wrap">
            <table class="ri-table">
                <colgroup>
                    <col style="width:5%">
                    <col style="width:12%">
                    <col style="width:19%">
                    <col style="width:12%">
                    <col style="width:7%">
                    <col style="width:11.25%">
                    <col style="width:11.25%">
                    <col style="width:11.25%">
                    <col style="width:11.25%">
                </colgroup>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No Order</th>
                        <th rowspan="2">Line</th>
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

                    @forelse($orders as $order)
                        <tr class="ri-link-row" role="link" tabindex="0"
                            onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}"
                            onclick="window.location='{{ route('omd.orders.show', $order) }}'"
                            title="Klik untuk melihat detail order">

                            <td class="ri-count">
                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
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
                                @if ($order->openFeedback)
                                    <span class="feedback-flag">
                                        <button type="button" class="feedback-flag-btn" title="Lihat feedback User"
                                            aria-label="Lihat feedback User" aria-expanded="false"
                                            data-feedback-id="{{ $order->id }}"
                                            data-feedback-reason="{{ $order->openFeedback->reason }}"
                                            data-feedback-time="{{ $order->openFeedback->created_at?->format('d-m-Y H:i') }}"
                                            onclick="toggleFeedbackPopover(event, this)">
                                            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="ri-line">
                                    {{ $order->line?->name ?? ($order->area ? $order->area->name : '-') }}
                                </span>
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

                            <td colspan="9" class="ri-empty">

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
    <style>
        .feedback-flag {
            display: inline-flex;
            align-items: center;
            margin-left: 6px;
            vertical-align: middle
        }

        .feedback-flag-btn {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #fed7aa;
            background: #fff7ed;
            color: #ea580c;
            font-size: 11px;
            line-height: 1;
            cursor: pointer;
            padding: 0;
            border-radius: 7px;
            transition: background .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease
        }

        .feedback-flag-btn:hover {
            background: #ffedd5;
            border-color: #fdba74;
            color: #c2410c;
            box-shadow: 0 3px 8px rgba(234, 88, 12, .12)
        }

        .feedback-flag-btn:focus-visible {
            outline: 2px solid rgba(234, 88, 12, .22);
            outline-offset: 2px
        }

        .feedback-popover {
            position: fixed;
            z-index: 10050;
            width: min(300px, calc(100vw - 24px));
            padding: 12px 13px;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            background: #fffdf9;
            color: #7c2d12;
            box-shadow: 0 18px 44px rgba(15, 23, 42, .18);
            font-size: 11px;
            line-height: 1.5;
            text-align: left
        }

        .feedback-popover strong {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 6px;
            color: #9a3412;
            font-size: 11px;
            font-weight: 750
        }

        .feedback-popover .feedback-popover-reason {
            white-space: pre-wrap;
            word-break: break-word;
            color: #7c2d12
        }

        .feedback-popover small {
            display: block;
            margin-top: 7px;
            color: #a16207
        }
    </style>
    <div id="feedbackFloatingPopover" class="feedback-popover" hidden onclick="event.stopPropagation()">
        <strong><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> Feedback User</strong>
        <div class="feedback-popover-reason" id="feedbackPopoverReason"></div>
        <small id="feedbackPopoverTime"></small>
    </div>

    <script>
        const feedbackPopover = document.getElementById('feedbackFloatingPopover');
        const feedbackReason = document.getElementById('feedbackPopoverReason');
        const feedbackTime = document.getElementById('feedbackPopoverTime');
        let activeFeedbackButton = null;

        function closeFeedbackPopover() {
            if (!feedbackPopover) return;
            feedbackPopover.hidden = true;
            feedbackPopover.style.left = '';
            feedbackPopover.style.top = '';
            feedbackPopover.dataset.owner = '';
            if (activeFeedbackButton) {
                activeFeedbackButton.setAttribute('aria-expanded', 'false');
            }
            activeFeedbackButton = null;
        }

        function positionFeedbackPopover(button) {
            if (!feedbackPopover) return;
            const buttonRect = button.getBoundingClientRect();
            const popoverRect = feedbackPopover.getBoundingClientRect();
            const gap = 10;
            const edge = 12;

            // Prioritas kanan-atas icon. Jika ruang tidak cukup, otomatis bergeser tanpa masuk area scroll tabel.
            let left = buttonRect.right + gap;
            let top = buttonRect.top - popoverRect.height + 8;

            if (left + popoverRect.width > window.innerWidth - edge) {
                left = buttonRect.left - popoverRect.width - gap;
            }
            left = Math.max(edge, Math.min(left, window.innerWidth - popoverRect.width - edge));

            if (top < edge) {
                top = buttonRect.bottom + gap;
            }
            top = Math.max(edge, Math.min(top, window.innerHeight - popoverRect.height - edge));

            feedbackPopover.style.left = Math.round(left) + 'px';
            feedbackPopover.style.top = Math.round(top) + 'px';
        }

        function toggleFeedbackPopover(event, button) {
            event.preventDefault();
            event.stopPropagation();
            if (!feedbackPopover) return;

            const feedbackId = button.dataset.feedbackId || '';
            const isSameOpen = !feedbackPopover.hidden && feedbackPopover.dataset.owner === feedbackId;
            if (isSameOpen) {
                closeFeedbackPopover();
                return;
            }

            if (activeFeedbackButton && activeFeedbackButton !== button) {
                activeFeedbackButton.setAttribute('aria-expanded', 'false');
            }

            feedbackReason.textContent = button.dataset.feedbackReason || '-';
            feedbackTime.textContent = button.dataset.feedbackTime || '';
            feedbackPopover.dataset.owner = feedbackId;
            feedbackPopover.hidden = false;
            activeFeedbackButton = button;
            button.setAttribute('aria-expanded', 'true');

            requestAnimationFrame(function() {
                positionFeedbackPopover(button);
            });
        }

        document.addEventListener('click', function(event) {
            if (!feedbackPopover || feedbackPopover.hidden) return;
            if (feedbackPopover.contains(event.target)) return;
            if (event.target.closest('.feedback-flag-btn')) return;
            closeFeedbackPopover();
        });

        window.addEventListener('resize', closeFeedbackPopover);
        window.addEventListener('scroll', closeFeedbackPopover, true);
    </script>


@endsection
