@extends('layouts.app')

@section('title', 'Edit Model ' . $scopeLabel)
@section('header', 'Edit Model ' . $scopeLabel)

@section('content')

    <div class="page-head">
        <div>
            <h2>Edit Model {{ $scopeLabel }}</h2>
            <div class="muted">
                Perbarui nama model yang tersimpan pada data master.
            </div>
        </div>

        <a href="{{ route('omd.master.index', ['scope' => $scope]) }}" class="btn">
            ← Kembali
        </a>
    </div>


    <div class="card" style="max-width: 650px;">

        <div style="margin-bottom: 22px;">
            <h3 style="margin: 0 0 6px;">
                Informasi Model
            </h3>

            <div class="muted">
                Nomor model dibuat otomatis oleh sistem dan tidak dapat diubah.
            </div>
        </div>


        {{-- Error --}}
        @if ($errors->any())
            <div
                style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 14px;
            border-radius:10px;
            margin-bottom:18px;
        ">
                <strong>Periksa kembali data:</strong>

                <ul style="margin:8px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST"
            action="{{ route('omd.master.model.update', [
                'scope' => $scope,
                'masterModel' => $masterModel->id,
            ]) }}">

            @csrf
            @method('PUT')


            {{-- Nomor --}}
            <div class="field">
                <label for="number">
                    Nomor
                </label>

                <input id="number" type="text" value="{{ $masterModel->number }}" disabled
                    style="background:#f5f5f5; cursor:not-allowed;">
            </div>


            {{-- Model --}}
            <div class="field" style="margin-top:16px;">
                <label for="model">
                    Model
                </label>

                <input id="model" type="text" name="model" value="{{ old('model', $masterModel->model) }}"
                    placeholder="Contoh: 660" maxlength="100" required autofocus>
            </div>


            {{-- Scope --}}
            <div class="field" style="margin-top:16px;">
                <label>
                    Data
                </label>

                <input type="text" value="{{ strtoupper($scope) }}" disabled
                    style="background:#f5f5f5; cursor:not-allowed;">
            </div>


            {{-- Action --}}
            <div
                style="
            display:flex;
            justify-content:flex-end;
            gap:10px;
            margin-top:24px;
        ">

                <a href="{{ route('omd.master.index', ['scope' => $scope]) }}" class="btn">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

@endsection
