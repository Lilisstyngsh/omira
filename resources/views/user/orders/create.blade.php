@extends('layouts.app')

@section('title', 'Buat Order Repair Box')
@section('header', 'Buat Order Repair Box')

@section('content')

    <style>
        .order-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .04);
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .info-box span {
            display: block;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .info-box strong {
            font-size: 14px;
            color: #111827;
        }

        .table-wrap {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            position: relative;
        }

        .order-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 850px;
        }

        .order-table thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .order-table th {
            background: #f8fafc;
            padding: 12px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            white-space: nowrap;
        }

        .order-table th.left {
            text-align: left;
        }

        .order-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
            color: #374151;
            vertical-align: middle;
            background: #fff;
        }

        .order-table tbody tr:last-child td {
            border-bottom: none;
        }

        .order-table tbody tr:hover td {
            background: #fcfcff;
        }

        .model-name {
            font-weight: 700;
            color: #111827;
        }

        .product-name {
            color: #374151;
            font-weight: 600;
        }

        .qty-input {
            width: 72px;
            height: 36px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            outline: none;
            box-sizing: border-box;
        }

        .qty-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
        }

        .row-total {
            font-weight: 700;
            text-align: center;
            color: #111827;
        }

        .grand-total {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            text-align: right;
            margin-top: 14px;
        }

        .grand-total span {
            color: #4f46e5;
            margin-left: 8px;
        }

        .field {
            margin-top: 24px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }

        .field textarea {
            width: 100%;
            min-height: 110px;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            padding: 11px 12px;
            font-size: 13px;
            resize: vertical;
            outline: none;
            box-sizing: border-box;
        }

        .field textarea:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
        }

        .bottom-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-reset {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            border-radius: 9px;
            padding: 11px 18px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .btn-reset:hover {
            background: #f8fafc;
        }

        .btn-submit {
            border: none;
            background: #4f46e5;
            color: #fff;
            border-radius: 9px;
            padding: 11px 20px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .btn-submit:hover {
            background: #4338ca;
        }

        .empty-state {
            text-align: center;
            padding: 35px;
            color: #9ca3af;
            font-size: 13px;
        }

        .ng-name {
            display: block;
            font-size: 9px;
            font-weight: 500;
            margin-top: 2px;
            color: #94a3b8;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #b42318;
            font-size: 11px;
        }

        @media (max-width: 800px) {

            .info-grid {
                grid-template-columns: 1fr;
            }

            .bottom-actions {
                flex-direction: column-reverse;
            }

            .btn-reset,
            .btn-submit {
                width: 100%;
            }
        }
    </style>


    <div class="order-card">

        @if ($errors->any())

            <div class="error-box">

                <ul style="margin:0;padding-left:16px;">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        <div class="section-title">
            Informasi Order
        </div>


        <div class="info-grid">

            <div class="info-box">

                <span>
                    Plant
                </span>

                <strong>
                    {{ $line->plant?->name ?? '-' }}
                </strong>

            </div>


            <div class="info-box">

                <span>
                    Line
                </span>

                <strong>
                    {{ $line->name }}
                </strong>

            </div>


            <div class="info-box">

                <span>
                    Tanggal
                </span>

                <strong>
                    {{ now()->format('d-m-Y H:i') }}
                </strong>

            </div>

        </div>


        <div class="section-title">
            Detail NG
        </div>


        @php

            $ngMap = $ngTypes->keyBy(function ($ngType) {
                return strtoupper($ngType->code);
            });

            $ngCodes = ['P', 'H', 'C', 'S'];

        @endphp


        <form method="POST" action="{{ route('user.orders.store') }}" id="orderForm">

            @csrf


            <div class="table-wrap">

                <table class="order-table">

                    <thead>

                        <tr>

                            <th class="left" style="width:150px;">
                                Model
                            </th>

                            <th class="left">
                                Produk
                            </th>


                            @foreach ($ngCodes as $code)
                                <th style="width:100px;">

                                    {{ $code }}

                                    @if (isset($ngMap[$code]))
                                        <span class="ng-name">
                                            {{ $ngMap[$code]->name }}
                                        </span>
                                    @endif

                                </th>
                            @endforeach


                            <th style="width:100px;">
                                Total NG
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $rowIndex = 0;
                        @endphp


                        @forelse ($models as $model)

                            @foreach ($model->products as $product)
                                <tr>

                                    <td>

                                        <span class="model-name">
                                            {{ $model->model }}
                                        </span>

                                        <input type="hidden" name="items[{{ $rowIndex }}][master_model_id]"
                                            value="{{ $model->id }}">

                                    </td>


                                    <td>

                                        <span class="product-name">
                                            {{ $product->name }}
                                        </span>

                                        <input type="hidden" name="items[{{ $rowIndex }}][product_id]"
                                            value="{{ $product->id }}">

                                    </td>


                                    @foreach ($ngCodes as $code)
                                        @php
                                            $ng = $ngMap[$code] ?? null;
                                        @endphp


                                        <td style="text-align:center;">

                                            @if ($ng)
                                                <input type="number" class="qty-input"
                                                    name="items[{{ $rowIndex }}][qty][{{ $ng->id }}]"
                                                    min="0" step="1"
                                                    value="{{ old('items.' . $rowIndex . '.qty.' . $ng->id, '') }}"
                                                    placeholder="0">
                                            @else
                                                <span style="color:#cbd5e1;">
                                                    -
                                                </span>
                                            @endif

                                        </td>
                                    @endforeach


                                    <td class="row-total">
                                        0
                                    </td>

                                </tr>


                                @php
                                    $rowIndex++;
                                @endphp
                            @endforeach


                        @empty

                            <tr>

                                <td colspan="7" class="empty-state">
                                    Belum ada Model & Produk untuk Line Anda.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="grand-total">

                Total NG:

                <span id="grandTotal">
                    0
                </span>

            </div>


            <div class="field">

                <label>
                    Keterangan
                </label>

                <textarea name="description" placeholder="Tambahkan keterangan jika diperlukan...">{{ old('description') }}</textarea>

            </div>


            <div class="bottom-actions">

                <button type="button" class="btn-reset" onclick="resetOrderForm()">
                    Reset
                </button>


                <button type="submit" class="btn-submit">
                    Kirim Order Repair Box
                </button>

            </div>

        </form>

    </div>


    <script>
        function calculateTotals() {

            let grandTotal = 0;


            document
                .querySelectorAll('.order-table tbody tr')
                .forEach(row => {

                    let rowTotal = 0;


                    row
                        .querySelectorAll('.qty-input')
                        .forEach(input => {

                            rowTotal +=
                                parseInt(input.value) || 0;

                        });


                    const totalCell =
                        row.querySelector('.row-total');


                    if (totalCell) {
                        totalCell.textContent =
                            rowTotal;
                    }


                    grandTotal += rowTotal;

                });


            document.getElementById(
                'grandTotal'
            ).textContent = grandTotal;
        }


        document
            .querySelectorAll('.qty-input')
            .forEach(input => {

                input.addEventListener(
                    'input',
                    calculateTotals
                );


                input.addEventListener(
                    'keydown',
                    function(event) {

                        if (event.key === 'Enter') {

                            event.preventDefault();


                            const inputs =
                                Array.from(
                                    document.querySelectorAll(
                                        '.qty-input'
                                    )
                                );


                            const currentIndex =
                                inputs.indexOf(this);


                            if (
                                inputs[currentIndex + 1]
                            ) {

                                inputs[currentIndex + 1]
                                    .focus();

                                inputs[currentIndex + 1]
                                    .select();
                            }

                        }

                    }
                );

            });


        function resetOrderForm() {

            document
                .querySelectorAll('.qty-input')
                .forEach(input => {

                    input.value = '';

                });


            calculateTotals();
        }


        document
            .getElementById('orderForm')
            .addEventListener(
                'submit',
                function(event) {

                    let grandTotal = 0;


                    document
                        .querySelectorAll('.qty-input')
                        .forEach(input => {

                            grandTotal +=
                                parseInt(input.value) || 0;

                        });


                    if (grandTotal <= 0) {

                        event.preventDefault();


                        alert(
                            'Masukkan minimal satu Quantity NG sebelum mengirim order.'
                        );

                    }

                }
            );


        calculateTotals();
    </script>

@endsection
