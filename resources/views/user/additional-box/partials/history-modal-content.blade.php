@if ($historyType === 'order')
    @php
        $rows = $document->decisions->isNotEmpty() ? $document->decisions : $document->items;
        $canExport = $document->items->isNotEmpty();
        $hasNoDecision = $rows->contains(fn ($item) => isset($item->decision) && $item->decision === 'no');
    @endphp
    <div class="ab-history-modal-content" data-modal-title="Detail Order Scrap">
        @if ($canExport)
            <div class="ab-modal-actions">
                <a href="{{ route('user.additional-box.export', [$document, 'pdf']) }}" class="ab-export-btn pdf">
                    <i class="fa-solid fa-file-pdf" aria-hidden="true"></i> PDF
                </a>
                <a href="{{ route('user.additional-box.export', [$document, 'excel']) }}" class="ab-export-btn excel">
                    <i class="fa-solid fa-file-excel" aria-hidden="true"></i> Excel
                </a>
            </div>
        @endif

        <section class="ab-modal-card">
            <div class="ab-modal-table-wrap">
                <table class="ab-modal-table">
                    <colgroup>
                        <col style="width:15%">
                        <col style="width:14%">
                        <col style="{{ $hasNoDecision ? 'width:24%' : 'width:29%' }}">
                        <col style="width:13%">
                        <col style="{{ $hasNoDecision ? 'width:13%' : 'width:29%' }}">
                        @if ($hasNoDecision)
                            <col style="width:21%">
                        @endif
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">No Order</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Model – Produk</th>
                            <th scope="col">Scrap / Limit</th>
                            <th scope="col">Keputusan</th>
                            @if ($hasNoDecision)
                                <th scope="col">Alasan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $item)
                            @php $isOrder = !isset($item->decision) || $item->decision === 'yes'; @endphp
                            <tr>
                                <td>{{ $document->document_number }}</td>
                                <td>{{ $document->created_at->format('d-m-Y H:i') }}</td>
                                <td class="ab-modal-model">{{ $item->model_name ?: '—' }} – {{ $item->product_name ?: '—' }}</td>
                                <td class="ab-modal-center">
                                    {{ isset($item->scrap_qty) && isset($item->limit_qty) ? $item->scrap_qty . ' / ' . $item->limit_qty : '—' }}
                                </td>
                                <td>
                                    <span class="ab-decision-pill {{ $isOrder ? 'yes' : 'no' }}">{{ $isOrder ? 'Order' : 'Tidak' }}</span>
                                </td>
                                @if ($hasNoDecision)
                                    <td class="ab-modal-reason">{{ $isOrder ? '' : ($item->reason ?: '—') }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $hasNoDecision ? 6 : 5 }}" class="ab-empty">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if ($canExport)
            <section class="ab-modal-card" aria-label="Preview PDF">
                <div class="ab-modal-card-header"><h3>Preview PDF</h3></div>
                <div class="ab-modal-preview">
                    <iframe class="ab-modal-preview-frame"
                        src="{{ route('user.additional-box.preview', $document) }}"
                        title="Preview PDF {{ $document->document_number }}" loading="lazy"></iframe>
                    <p class="ab-modal-preview-fallback">Jika preview tidak tampil, <a
                            href="{{ route('user.additional-box.preview', $document) }}" target="_blank"
                            rel="noopener noreferrer">buka PDF di tab baru</a>.</p>
                </div>
            </section>
        @endif
    </div>
@else
    <div class="ab-history-modal-content" data-modal-title="Detail Reset Scrap">
        <section class="ab-modal-card">
            <div class="ab-modal-table-wrap">
                <table class="ab-modal-table ab-modal-table-reset">
                    <colgroup>
                        <col style="width:18%">
                        <col style="width:23%">
                        <col style="width:13%">
                        <col style="width:17%">
                        <col style="width:16%">
                        <col style="width:13%">
                    </colgroup>
                    <thead>
                        <tr>
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
                            <td>{{ $cycle->reset_at->format('d-m-Y H:i') }}</td>
                            <td class="ab-modal-model">{{ $cycle->product?->masterModel?->model ?: '—' }} – {{ $cycle->product?->name ?: '—' }}</td>
                            <td class="ab-modal-center">{{ $cycle->reached_qty ?? 0 }} / {{ $cycle->limit_qty }}</td>
                            <td><span class="ab-decision-pill reset">Reset oleh OMD</span></td>
                            <td class="ab-modal-reason">{{ $cycle->reset_reason ?: '—' }}</td>
                            <td>{{ $cycle->resetter?->name ?? 'OMD' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endif
