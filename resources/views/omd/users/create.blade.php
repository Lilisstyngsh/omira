@extends('layouts.app')

@section('title', 'Tambah Akun')
@section('header', 'Tambah Akun')

@section('content')

    <style>
        .account-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .account-page-title {
            margin: 0 0 6px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .account-page-desc {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .account-form-card {
            background: #ffffff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .account-form-header {
            padding: 22px 24px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(
                135deg,
                #f8f7ff 0%,
                #ffffff 75%
            );
        }

        .account-form-header h3 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .account-form-header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .account-form-body {
            padding: 24px;
        }

        .account-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .account-field {
            display: flex;
            flex-direction: column;
        }

        .account-field-full {
            grid-column: 1 / -1;
        }

        .account-field label {
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
        }

        .account-field input,
        .account-field select {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid #dce3ec;
            border-radius: 10px;
            background: #ffffff;
            color: #172033;
            font-size: 13px;
            outline: none;
            transition: .2s ease;
            box-sizing: border-box;
        }

        .account-field input:focus,
        .account-field select:focus {
            border-color: #7c6cf4;
            box-shadow: 0 0 0 3px rgba(124, 108, 244, .10);
        }

        .account-field select:disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .account-field input::placeholder {
            color: #94a3b8;
        }

        .account-helper {
            margin-top: 6px;
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }

        .account-error {
            margin-top: 6px;
            font-size: 11px;
            color: #dc5b68;
        }

        .account-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid #edf1f5;
        }

        .account-btn {
            height: 38px;
            padding: 0 17px;
            border-radius: 9px;
            border: none;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .account-btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .account-btn-secondary:hover {
            background: #e2e8f0;
        }

        .account-btn-primary {
            background: #6d5dfc;
            color: #ffffff;
            box-shadow: 0 6px 15px rgba(109, 93, 252, .18);
        }

        .account-btn-primary:hover {
            background: #5d4df0;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {

            .account-page-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .account-form-grid {
                grid-template-columns: 1fr;
            }

            .account-field-full {
                grid-column: auto;
            }

            .account-actions {
                justify-content: stretch;
            }

            .account-actions .account-btn {
                flex: 1;
            }
        }
    </style>


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="account-page-head">

        <div>
            <h2 class="account-page-title">
                Tambah Akun User
            </h2>
        </div>

    </div>


    {{-- =========================================================
        FORM CARD
    ========================================================== --}}

    <div class="account-form-card">

        <div class="account-form-header">

            <h3>
                Informasi Akun
            </h3>

        </div>


        <div class="account-form-body">

            <form
                method="POST"
                action="{{ route('omd.users.store') }}"
            >

                @csrf


                <div class="account-form-grid">

                    {{-- NAMA --}}
                    <div class="account-field">

                        <label for="name">
                            Nama
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Contoh: User INJ"
                            maxlength="100"
                            required
                        >

                        @error('name')
                            <span class="account-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- EMAIL --}}
                    <div class="account-field">

                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@omd.com"
                            maxlength="255"
                            required
                        >

                        @error('email')
                            <span class="account-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- PLANT --}}
                    <div class="account-field">

                        <label for="plant_id">
                            Plant
                        </label>

                        <select
                            id="plant_id"
                            name="plant_id"
                            required
                        >

                            <option value="">
                                Pilih Plant
                            </option>

                            @foreach ($plants as $plant)

                                <option
                                    value="{{ $plant->id }}"
                                    @selected(old('plant_id') == $plant->id)
                                >
                                    {{ $plant->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('plant_id')
                            <span class="account-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- LINE --}}
                    <div class="account-field">

                        <label for="line_id">
                            Line
                        </label>

                        <select
                            id="line_id"
                            name="line_id"
                            required
                            disabled
                        >

                            <option value="">
                                Pilih Plant terlebih dahulu
                            </option>

                        </select>

                        @error('line_id')
                            <span class="account-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div class="account-field">

                        <label for="password">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Kosongkan untuk password default"
                        >

                        <span class="account-helper">
                            Kosongkan untuk menggunakan password default sistem:
                            <strong>Aiia@2026</strong>
                        </span>

                        @error('password')
                            <span class="account-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="account-field">

                        <label for="password_confirmation">
                            Konfirmasi Password
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password jika diisi"
                        >

                        <span class="account-helper">
                            Tidak perlu diisi jika menggunakan password default.
                        </span>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="account-actions">

                    <a
                        href="{{ route('omd.users.index') }}"
                        class="account-btn account-btn-secondary"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="account-btn account-btn-primary"
                    >
                        Simpan Akun
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PLANT → LINE SCRIPT
    ========================================================== --}}

    <script>

        var plants = @json($plants);

        var plantSelect =
            document.getElementById('plant_id');

        var lineSelect =
            document.getElementById('line_id');

        var oldLineId =
            "{{ old('line_id') }}";


        function loadLines(plantId)
        {
            lineSelect.innerHTML = '';


            if (!plantId) {

                lineSelect.innerHTML =
                    '<option value="">Pilih Plant terlebih dahulu</option>';

                lineSelect.disabled = true;

                return;
            }


            var plant = null;


            for (
                var i = 0;
                i < plants.length;
                i++
            ) {

                if (
                    String(plants[i].id) ===
                    String(plantId)
                ) {

                    plant = plants[i];

                    break;
                }

            }


            if (
                !plant ||
                !plant.lines ||
                plant.lines.length === 0
            ) {

                lineSelect.innerHTML =
                    '<option value="">Tidak ada Line tersedia</option>';

                lineSelect.disabled = true;

                return;
            }


            lineSelect.disabled = false;


            lineSelect.innerHTML =
                '<option value="">Pilih Line</option>';


            for (
                var j = 0;
                j < plant.lines.length;
                j++
            ) {

                var line =
                    plant.lines[j];

                var option =
                    document.createElement('option');


                option.value =
                    line.id;

                option.textContent =
                    line.name;


                if (
                    String(oldLineId) ===
                    String(line.id)
                ) {

                    option.selected = true;

                }


                lineSelect.appendChild(option);

            }
        }


        plantSelect.addEventListener(
            'change',
            function ()
            {
                oldLineId = '';

                loadLines(
                    this.value
                );
            }
        );


        if (plantSelect.value) {

            loadLines(
                plantSelect.value
            );

        }

    </script>

@endsection