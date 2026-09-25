<table>
    <thead>
        <tr>
            <th style="font-weight:bold;background:#f7f8fa;border:1px solid #000;">No</th>
            <th style="font-weight:bold;background:#f7f8fa;border:1px solid #000;">Model</th>
            <th style="font-weight:bold;background:#f7f8fa;border:1px solid #000;">No. Produk</th>
            <th style="font-weight:bold;background:#f7f8fa;border:1px solid #000;">Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($models as $model)
            @forelse ($model->products as $index => $product)
                <tr>
                    @if ($index === 0)
                        <td rowspan="{{ $model->products->count() }}" style="border:1px solid #000;">
                            {{ $model->number }}
                        </td>
                        <td rowspan="{{ $model->products->count() }}" style="border:1px solid #000;">
                            {{ $model->model }}
                        </td>
                    @endif
                    <td style="border:1px solid #000;">{{ $index + 1 }}</td>
                    <td style="border:1px solid #000;">{{ $product->name }}</td>
                </tr>
            @empty
                <tr>
                    <td style="border:1px solid #000;">{{ $model->number }}</td>
                    <td style="border:1px solid #000;">{{ $model->model }}</td>
                    <td style="border:1px solid #000;">-</td>
                    <td style="border:1px solid #000;">Belum ada produk.</td>
                </tr>
            @endforelse
        @endforeach
    </tbody>
</table>
