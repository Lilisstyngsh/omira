@php
    $showAfter = $showAfter ?? in_array($order->status, ['completed', 'revision_requested', 'confirmed'], true);
    $showProductNote = $showProductNote ?? $showAfter;
    $ngCodes = ['P', 'H', 'C', 'S'];
    $modelGroups = $order->items->groupBy('master_model_id');
    $columnCount = 2 + 4 + ($showAfter ? 4 : 0) + ($showProductNote ? 1 : 0);
    $beforeTotals = collect($ngCodes)->mapWithKeys(fn($code) => [$code => (int) $order->items->filter(fn($item) => strtoupper($item->ngType?->code ?? '') === $code)->sum('before_qty')]);
    $afterTotals = collect($ngCodes)->mapWithKeys(fn($code) => [$code => (int) $order->items->filter(fn($item) => strtoupper($item->ngType?->code ?? '') === $code)->sum('after_qty')]);
    $beforeGrandTotal = (int) $beforeTotals->sum();
    $afterGrandTotal = (int) $afterTotals->sum();
@endphp

<style>
    .repair-detail-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch}
    .repair-detail-table{width:100%;border-collapse:collapse;background:#fff;font-size:12px;color:#334155}
    .repair-detail-table th,.repair-detail-table td{border:1px solid #cbd5e1;padding:8px 7px;vertical-align:middle;font-weight:400}
    .repair-detail-table thead th{text-align:center;font-weight:650;color:#0f172a;background:#fff;white-space:nowrap}
    .repair-detail-table .model-group-row td{padding:9px 10px;font-weight:700;color:#1e293b;background:#fff;text-align:left}
    .repair-detail-table .cell-center{text-align:center}
    .repair-detail-table .product-name{font-weight:450;color:#1f2937}
    .repair-detail-table .model-total-row td{font-weight:550;color:#334155;background:#fff}
    .repair-detail-table .total-row td{font-weight:550;background:#fff}
    .repair-detail-table .grand-total-row td{font-weight:600;background:#fff;color:#334155}
    .repair-detail-table .match-cell{background:#dcfce7}
    .repair-detail-table .mismatch-cell{background:#fee2e2}
    .repair-detail-table .note-cell{min-width:130px;white-space:normal;line-height:1.4}
    @media(max-width:768px){.repair-detail-table{font-size:11px;min-width:760px}.repair-detail-table th,.repair-detail-table td{padding:7px 6px}}
</style>

<div class="repair-detail-scroll">
    <table class="repair-detail-table">
        <thead>
            <tr>
                <th rowspan="{{ $showAfter ? 3 : 2 }}" style="width:42px">No</th>
                <th rowspan="{{ $showAfter ? 3 : 2 }}" style="min-width:130px">Produk</th>
                <th colspan="{{ $showAfter ? 8 : 4 }}">Qty NG</th>
                @if($showProductNote)
                    <th rowspan="3" style="min-width:140px">Catatan</th>
                @endif
            </tr>
            @if($showAfter)
                <tr><th colspan="4">Order</th><th colspan="4">Hasil</th></tr>
            @endif
            <tr>
                @foreach($ngCodes as $code)<th style="width:48px">{{ $code }}</th>@endforeach
                @if($showAfter) @foreach($ngCodes as $code)<th style="width:48px">{{ $code }}</th>@endforeach @endif
            </tr>
        </thead>
        <tbody>
            @foreach($modelGroups as $modelItems)
                @php
                    $productGroups = $modelItems->groupBy('product_id');
                    $modelName = $modelItems->first()->masterModel?->model ?? '-';
                    $modelBeforeTotals = collect($ngCodes)->mapWithKeys(fn($code) => [$code => (int) $modelItems->filter(fn($item) => strtoupper($item->ngType?->code ?? '') === $code)->sum('before_qty')]);
                    $modelAfterTotals = collect($ngCodes)->mapWithKeys(fn($code) => [$code => (int) $modelItems->filter(fn($item) => strtoupper($item->ngType?->code ?? '') === $code)->sum('after_qty')]);
                @endphp
                <tr class="model-group-row"><td colspan="{{ $columnCount }}">Model {{ $modelName }}</td></tr>
                @foreach($productGroups as $productItems)
                    @php
                        $ngItems = $productItems->keyBy(fn($item) => strtoupper($item->ngType?->code ?? ''));
                        $firstItem = $productItems->first();
                        $productNote = $productItems->first(fn($item) => filled($item->mismatch_note))?->mismatch_note;
                    @endphp
                    <tr>
                        <td class="cell-center">{{ $loop->iteration }}</td>
                        <td><span class="product-name">{{ $firstItem->product?->name ?? '-' }}</span></td>
                        @foreach($ngCodes as $code)
                            @php $item = $ngItems->get($code); $qty=(int)($item?->before_qty ?? 0); @endphp
                            <td class="cell-center">{{ $qty > 0 ? $qty : '' }}</td>
                        @endforeach
                        @if($showAfter)
                            @foreach($ngCodes as $code)
                                @php
                                    $item=$ngItems->get($code); $before=(int)($item?->before_qty ?? 0); $after=(int)($item?->after_qty ?? 0);
                                    $stateClass = ($before > 0 || $after > 0) ? ($before === $after ? 'match-cell' : 'mismatch-cell') : '';
                                @endphp
                                <td class="cell-center {{ $stateClass }}">{{ $after > 0 ? $after : '' }}</td>
                            @endforeach
                        @endif
                        @if($showProductNote)<td class="note-cell">{{ $productNote ?: '-' }}</td>@endif
                    </tr>
                @endforeach
                <tr class="model-total-row">
                    <td colspan="2">Total</td>
                    @foreach($ngCodes as $code)<td class="cell-center">{{ $modelBeforeTotals[$code] > 0 ? $modelBeforeTotals[$code] : '' }}</td>@endforeach
                    @if($showAfter) @foreach($ngCodes as $code)<td class="cell-center">{{ $modelAfterTotals[$code] > 0 ? $modelAfterTotals[$code] : '' }}</td>@endforeach @endif
                    @if($showProductNote)<td></td>@endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Total NG</td>
                @foreach($ngCodes as $code)<td class="cell-center">{{ $beforeTotals[$code] > 0 ? $beforeTotals[$code] : '' }}</td>@endforeach
                @if($showAfter) @foreach($ngCodes as $code)<td class="cell-center">{{ $afterTotals[$code] > 0 ? $afterTotals[$code] : '' }}</td>@endforeach @endif
                @if($showProductNote)<td></td>@endif
            </tr>
            <tr class="grand-total-row">
                <td colspan="2">Grand Total</td>
                <td colspan="4" class="cell-center">{{ $beforeGrandTotal > 0 ? $beforeGrandTotal . ' NG' : '' }}</td>
                @if($showAfter)<td colspan="4" class="cell-center">{{ $afterGrandTotal > 0 ? $afterGrandTotal . ' NG' : '' }}</td>@endif
                @if($showProductNote)<td></td>@endif
            </tr>
        </tfoot>
    </table>
</div>
