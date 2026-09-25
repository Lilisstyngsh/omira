@extends('layouts.app')

@section('title', 'Buat Order Repair')
@section('header', 'Buat Order Repair')

@section('content')

    <div class="page-head">
        <div>
            <h2>Form Order Repair Box NG</h2>

            <div class="muted">
                Data model dan produk otomatis mengikuti akun {{ $userGroupLabel }}.
            </div>
        </div>
    </div>


    <div class="card form-card">

        {{-- Tidak ada master --}}
        @if ($models->isEmpty())

            <div class="alert alert-error">
                Belum ada master Model dan Produk untuk {{ $userGroupLabel }}.
                Silakan hubungi OMD.
            </div>

        @endif


        {{-- Error validasi --}}
        @if ($errors->any())

            <div
                style="
                    background:#fef2f2;
                    border:1px solid #fecaca;
                    color:#b91c1c;
                    padding:12px 15px;
                    border-radius:10px;
                    margin-bottom:18px;
                "
            >

                <strong>Periksa kembali data:</strong>

                <ul style="margin:8px 0 0 18px;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('user.orders.store') }}"
        >

            @csrf


            <div class="form-grid">


                {{-- AREA / LINE --}}
                <div class="field">

                    <label for="area_id">
                        Area / Line
                    </label>

                    <select
                        name="area_id"
                        id="area_id"
                        required
                    >

                        <option value="">
                            Pilih area / line
                        </option>

                        @foreach ($areas as $area)

                            <option
                                value="{{ $area->id }}"
                                @selected(
                                    old(
                                        'area_id',
                                        auth()->user()->area_id
                                    ) == $area->id
                                )
                            >

                                {{ $area->category }} - {{ $area->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- MODEL --}}
                <div class="field">

                    <label for="master_model_id">
                        Model
                    </label>

                    <select
                        name="master_model_id"
                        id="master_model_id"
                        required
                    >

                        <option value="">
                            Pilih model
                        </option>

                        @foreach ($models as $model)

                            <option
                                value="{{ $model->id }}"
                                @selected(
                                    old('master_model_id') == $model->id
                                )
                            >

                                {{ $model->number }} - {{ $model->model }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- PRODUK --}}
                <div class="field">

                    <label for="product_id">
                        Produk
                    </label>

                    <select
                        name="product_id"
                        id="product_id"
                        required
                        disabled
                    >

                        <option value="">
                            Pilih model terlebih dahulu
                        </option>

                    </select>

                </div>


                {{-- JENIS NG --}}
                <div class="field">

                    <label for="ng_type_id">
                        Jenis NG
                    </label>

                    <select
                        name="ng_type_id"
                        id="ng_type_id"
                        required
                    >

                        <option value="">
                            Pilih jenis NG
                        </option>

                        @foreach ($ngTypes as $type)

                            <option
                                value="{{ $type->id }}"
                                @selected(
                                    old('ng_type_id') == $type->id
                                )
                            >

                                {{ $type->code }} - {{ $type->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- QUANTITY --}}
                <div class="field">

                    <label for="quantity">
                        Quantity
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        min="1"
                        value="{{ old('quantity', 1) }}"
                        required
                    >

                </div>


                {{-- KETERANGAN --}}
                <div class="field full">

                    <label for="description">
                        Keterangan
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        placeholder="Jelaskan kondisi/problem jika diperlukan."
                    >{{ old('description') }}</textarea>

                </div>


            </div>


            {{-- BUTTON --}}
            <div
                style="
                    margin-top:18px;
                    display:flex;
                    gap:8px;
                "
            >

                <a
                    class="btn btn-secondary"
                    href="{{ route('user.orders.index') }}"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                    {{ $models->isEmpty() ? 'disabled' : '' }}
                >
                    Kirim Order ke OMD
                </button>

            </div>


        </form>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script>

        const models = {};

        @foreach ($models as $model)

            models["{{ $model->id }}"] = {

                id: "{{ $model->id }}",

                products: [

                    @foreach ($model->products as $product)

                        {
                            id: "{{ $product->id }}",
                            name: @json($product->name)
                        },

                    @endforeach

                ]

            };

        @endforeach


        const modelSelect =
            document.getElementById('master_model_id');

        const productSelect =
            document.getElementById('product_id');


        const oldProductId =
            "{{ old('product_id') }}";


        function loadProducts() {

            const modelId =
                modelSelect.value;


            productSelect.innerHTML = '';


            if (!modelId || !models[modelId]) {

                const option =
                    document.createElement('option');

                option.value = '';

                option.textContent =
                    'Pilih model terlebih dahulu';

                productSelect.appendChild(option);

                productSelect.disabled = true;

                return;
            }


            const defaultOption =
                document.createElement('option');

            defaultOption.value = '';

            defaultOption.textContent =
                'Pilih produk';

            productSelect.appendChild(
                defaultOption
            );


            const products =
                models[modelId].products;


            products.forEach(function (product) {

                const option =
                    document.createElement('option');


                option.value =
                    product.id;


                option.textContent =
                    product.name;


                if (
                    String(product.id) ===
                    String(oldProductId)
                ) {

                    option.selected = true;

                }


                productSelect.appendChild(
                    option
                );

            });


            productSelect.disabled = false;

        }


        modelSelect.addEventListener(
            'change',
            function () {

                loadProducts();

            }
        );


        loadProducts();

    </script>

@endsection