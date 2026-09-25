@extends('layouts.app')

@section('title', 'Edit Akun')
@section('header', 'Edit Akun')

@section('content')

    <div class="page-head">

        <div>
            <h2>Edit Akun User</h2>
            <div class="muted">
                Perbarui informasi akun user.
            </div>
        </div>

        <a href="{{ route('omd.users.index') }}" class="btn btn-secondary">
            Kembali
        </a>

    </div>


    <div class="card">

        <form method="POST" action="{{ route('omd.users.update', $user) }}">

            @csrf
            @method('PUT')


            <div class="field">

                <label for="name">
                    Nama
                </label>

                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>

                @error('name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="email">
                    Email
                </label>

                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>

                @error('email')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="user_group">
                    Plant
                </label>

                <select id="user_group" name="user_group" required>

                    <option value="ppic" @selected(old('user_group', $user->user_group) === 'ppic')>
                        PPIC
                    </option>

                    <option value="produksi" @selected(old('user_group', $user->user_group) === 'produksi')>
                        Produksi
                    </option>

                </select>

                @error('user_group')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="password">
                    Password Baru
                </label>

                <input id="password" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">

                @error('password')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="password_confirmation">
                    Konfirmasi Password Baru
                </label>

                <input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="Ulangi password baru">

            </div>


            <div style="margin-top:24px;">

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

@endsection
