@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('header', 'Manajemen Akun')

@section('content')

    <div class="page-head">
        <div>
            <h2>Data Akun User</h2>
            <div class="muted">
            </div>
        </div>

        <a href="{{ route('omd.users.create') }}" class="btn btn-primary">
            + Tambah Akun
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom:20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card">

        <div class="table-wrap">

            <table class="table">

                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width:150px;">Plant</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $index => $user)
                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <b>{{ $user->name }}</b>
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <span class="badge badge-{{ $user->user_group }}">
                                    {{ strtoupper($user->user_group) }}
                                </span>
                            </td>

                            <td>

                                <div style="display:flex; gap:8px;">

                                    <a href="{{ route('omd.users.edit', $user) }}" class="btn btn-secondary">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('omd.users.destroy', $user) }}"
                                        onsubmit="return confirm('Hapus akun ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty">
                                Belum ada akun user.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection
