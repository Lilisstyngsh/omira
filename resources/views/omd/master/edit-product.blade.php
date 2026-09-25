@extends('layouts.app')

@section('title', 'Edit Produk')
@section('header', 'Edit Produk')

@section('content')
    <div class="card form-card">
        <div class="page-head">
            <div>
                <h2>Edit Produk</h2>
                <div class="muted">Perbarui produk dan model induknya.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('omd.master.product.update', [$scope, $product]) }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="master_model_id">Model</label>
                <select id="master_model_id" name="master_model_id" required>
                    @foreach ($models as $model)
                        <option
                            value="{{ $model->id }}"
                            @selected(old('master_model_id', $product->master_model_id) == $model->id)
                        >
                            {{ $model->number }} - {{ $model->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field" style="margin-top:14px;">
                <label for="product">Produk</label>
                <input
                    id="product"
                    name="product"
                    value="{{ old('product', $product->name) }}"
                    required
                >
            </div>

            <div class="actions" style="margin-top:16px;">
                <a
                    class="btn btn-secondary"
                    href="{{ route('omd.master.index', [$scope, 'model' => $product->master_model_id]) }}"
                >
                    Batal
                </a>

                <button class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
