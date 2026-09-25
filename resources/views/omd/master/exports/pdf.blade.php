<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Master {{ $scopeLabel }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; margin-bottom: 18px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #222; padding: 7px; }
        th { background: #f1f3f5; text-align: center; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h2>Data Master Model & Produk {{ $scopeLabel }}</h2>
    <div class="subtitle">OMD Order - Workshop Digital System</div>

    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="25%">Model</th>
                <th width="15%">No. Produk</th>
                <th>Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
                @forelse ($model->products as $index => $product)
                    <tr>
                        @if ($index === 0)
                            <td rowspan="{{ $model->products->count() }}" class="center">
                                {{ $model->number }}
                            </td>
                            <td rowspan="{{ $model->products->count() }}">
                                {{ $model->model }}
                            </td>
                        @endif
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="center">{{ $model->number }}</td>
                        <td>{{ $model->model }}</td>
                        <td class="center">-</td>
                        <td>Belum ada produk.</td>
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>
</body>
</html>
