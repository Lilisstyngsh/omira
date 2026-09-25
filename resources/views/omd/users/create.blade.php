@extends('layouts.app')

@section('title', 'Tambah Akun')
@section('header', 'Tambah Akun')

@section('content')

    <div class="page-head">
        <div>
            <h2>Tambah Akun User</h2>
            <div class="muted">
                Tambahkan akun Leader PPIC atau Leader Produksi.
            </div>
        </div>

        <a href="{{ route('omd.users.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card">

        <form method="POST" action="{{ route('omd.users.store') }}">
            @csrf

            <div class="field">
                <label for="name">Nama</label>

                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama leader"
                    required>

                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="field" style="margin-top:16px;">
                <label for="email">Email</label>

                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@omd.com"
                    required>

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="field" style="margin-top:16px;">
                <label for="user_group">Plant</label>

                <select id="user_group" name="user_group" required>
                    <option value="">Pilih Bagian</option>

                    <option value="ppic" @selected(old('user_group') === 'ppic')>
                        PPIC
                    </option>

                    <option value="produksi" @selected(old('user_group') === 'produksi')>
                        Produksi
                    </option>
                </select>

                @error('user_group')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="field" style="margin-top:16px;">
                <label for="password">Password</label>

                <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" required>

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="field" style="margin-top:16px;">
                <label for="password_confirmation">
                    Konfirmasi Password
                </label>

                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password"
                    required>
            </div>

            <div style="margin-top:24px; display:flex; gap:10px;">

                <a href="{{ route('omd.users.index') }}" class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Akun
                </button>

            </div>

        </form>

    </div>

@endsection
