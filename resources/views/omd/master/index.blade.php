@extends('layouts.app')

@section('title', 'Data Master ' . $scopeLabel)
@section('header', 'Data Master ' . $scopeLabel)

@section('content')
    <style>
        .master-hero {
            background: linear-gradient(135deg, #5b21b6, #7c3aed 58%, #8b5cf6);
            color: #fff;
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 12px 30px rgba(124, 58, 237, .18);
        }

        .master-hero h2 {
            margin: 0 0 6px;
        }

        .master-hero p {
            margin: 0;
            color: rgba(255, 255, 255, .78);
        }

        .breadcrumb {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 14px;
            font-size: 12px;
            color: #667085;
        }

        .breadcrumb a {
            color: #6d28d9;
            font-weight: 650;
        }

        .master-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .master-card {
            display: block;
            border: 1px solid #e8e5f0;
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 22px rgba(58, 35, 120, .06);
            transition: .2s ease;
            text-decoration: none;
            color: inherit;
        }

        .master-card:hover {
            transform: translateY(-3px);
            border-color: #c4b5fd;
            box-shadow: 0 12px 28px rgba(124, 58, 237, .12);
        }

        .master-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .master-icon {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ede9fe;
            color: #6d28d9;
            font-weight: 800;
            font-size: 20px;
        }

        .master-card h3 {
            margin: 0;
            font-size: 16px;
        }

        .master-card p {
            margin: 8px 0 0;
            color: #667085;
            font-size: 13px;
        }

        .master-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .badge-soft {
            padding: 5px 9px;
            border-radius: 999px;
            background: #f5f3ff;
            color: #6d28d9;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-link {
            color: #6d28d9;
            font-weight: 700;
            font-size: 12px;
        }

        .plant-form {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        }

        .plant-form .field {
            margin: 0;
            min-width: 240px;
        }

        @media (max-width: 700px) {
            .master-hero {
                padding: 18px;
            }

            .plant-form {
                width: 100%;
            }

            .plant-form .field {
                width: 100%;
                min-width: 0;
            }
        }
    </style>


    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <span>Data Master</span>
        <span>›</span>
        <strong>{{ $scopeLabel }}</strong>
    </div>

    {{-- Tambah Plant --}}
    <div class="card" style="margin-bottom:20px;">
        <div
            style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                gap:16px;
                flex-wrap:wrap;
            "
        >

            <div>
                <h3 style="margin:0;">
                    Tambah Plant
                </h3>
            </div>


            <form
                method="POST"
                action="{{ route('omd.master.plant.store', [
                    'scope' => $scope
                ]) }}"
                class="plant-form"
            >
                @csrf

                <div class="field">
                    <label for="plant_name">
                        Nama Plant
                    </label>

                    <input
                        id="plant_name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Unit"
                        required
                        maxlength="100"
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Tambah
                </button>
            </form>

        </div>
    </div>


    {{-- Header Plant --}}
    <div class="page-head">

        <div>
            <h3 style="margin:0;">
                Plant {{ $scopeLabel }}
            </h3>
        </div>


        {{-- Export --}}
        <div
            class="export-actions"
            style="display:flex;gap:8px;flex-wrap:wrap;"
        >

            <a
                class="btn btn-success"
                href="{{ route('omd.master.export.excel', [
                    'scope' => $scope
                ]) }}"
            >
                Export Excel
            </a>

            <a
                class="btn btn-danger"
                href="{{ route('omd.master.export.pdf', [
                    'scope' => $scope
                ]) }}"
            >
                Export PDF
            </a>

        </div>

    </div>


    {{-- Daftar Plant --}}
    @if ($plants->isEmpty())

        <div class="card">

            <strong>
                Belum ada Plant {{ $scopeLabel }}.
            </strong>

            <div
                class="muted"
                style="margin-top:6px;"
            >
                Tambahkan Plant terlebih dahulu
                untuk membuat struktur Area / Line.
            </div>

        </div>

    @else

        <div class="master-grid">

            @foreach ($plants as $plant)

                <a
                    class="master-card"
                    href="{{ route('omd.master.plant.index', [
                        'scope' => $scope,
                        'plant' => $plant
                    ]) }}"
                >

                    <div class="master-card-top">

                        <span class="badge-soft">
                            {{ $plant->areas_count }} Line
                        </span>

                    </div>


                    <div style="margin-top:16px;">

                        <h3>
                            {{ $plant->name }}
                        </h3>

                    </div>


                    <div class="master-card-footer">

                        <span class="btn-link">
                            Lihat Area →
                        </span>

                    </div>

                </a>

            @endforeach

        </div>

    @endif

@endsection