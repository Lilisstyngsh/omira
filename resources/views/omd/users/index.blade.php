@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('header', 'Manajemen Akun')

@section('content')

    <style>
        /* =====================================================
           PAGE HEADER
        ===================================================== */

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


        /* =====================================================
           SUCCESS ALERT
        ===================================================== */

        .account-success-alert,
        .account-error-alert {
            margin-bottom: 22px;
            padding: 18px 20px;
            border-radius: 16px;
            font-size: 13px;
            line-height: 1.5;
        }

        .account-success-alert {
            border: 1px solid #75e6bc;
            background: #effdf6;
            color: #059669;
        }

        .account-error-alert {
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #be123c;
        }


        /* =====================================================
           PRIMARY BUTTON
        ===================================================== */

        .account-btn-primary {
            height: 38px;
            padding: 0 16px;
            border-radius: 9px;
            background: #6d5dfc;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 750;
            box-shadow: 0 6px 15px rgba(109, 93, 252, .18);
            transition: .2s ease;
        }

        .account-btn-primary:hover {
            background: #5d4df0;
            transform: translateY(-1px);
            color: #fff;
        }


        /* =====================================================
           TABLE CARD
        ===================================================== */

        .account-table-card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .account-table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #edf1f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            background: linear-gradient(135deg,
                    #f8f7ff 0%,
                    #fff 75%);
        }

        .account-table-header h3 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: 800;
            color: #172033;
        }

        .account-table-header p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .account-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            padding: 0 12px;
            border-radius: 10px;
            background: #f1efff;
            color: #6557dc;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .account-table-wrap {
            overflow-x: auto;
        }

        .account-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        .account-table thead th {
            padding: 14px 18px;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #e9eef4;
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .account-table tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 13px;
            vertical-align: middle;
        }

        .account-table tbody tr {
            transition: .2s ease;
        }

        .account-table tbody tr:hover {
            background: #fafaff;
        }

        .account-table tbody tr:last-child td {
            border-bottom: none;
        }

        .account-number {
            width: 65px;
            color: #94a3b8 !important;
            font-weight: 700;
        }


        /* =====================================================
           USER
        ===================================================== */

        .account-user {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .account-avatar {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: #f1efff;
            color: #6659df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .account-user-name {
            font-weight: 750;
            color: #172033;
            margin-bottom: 2px;
        }

        .account-user-label {
            font-size: 10px;
            color: #94a3b8;
        }

        .account-email {
            color: #64748b;
        }


        /* =====================================================
           BADGES
        ===================================================== */

        .account-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 750;
        }

        .account-badge-plant {
            background: #f5f3ff;
            color: #6659df;
        }

        .account-badge-line {
            background: #eff6ff;
            color: #3478c5;
        }


        /* =====================================================
           ACTION
        ===================================================== */

        .account-actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .account-action-btn {
            height: 32px;
            padding: 0 11px;
            border-radius: 8px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 750;
            cursor: pointer;
            transition: .2s ease;
        }

        .account-action-edit {
            background: #f1f5f9;
            color: #475569;
        }

        .account-action-edit:hover {
            background: #e2e8f0;
        }

        .account-action-reset {
            background: #fff7e8;
            color: #b77906;
        }

        .account-action-reset:hover {
            background: #ffedc2;
        }

        .account-action-delete {
            background: #fff0f1;
            color: #d14d5b;
        }

        .account-action-delete:hover {
            background: #ffe0e3;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .account-empty {
            padding: 50px 20px !important;
            text-align: center !important;
            color: #94a3b8 !important;
        }

        .account-empty-icon {
            width: 46px;
            height: 46px;
            margin: 0 auto 10px;
            border-radius: 13px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .account-empty-title {
            font-weight: 750;
            color: #475569;
            margin-bottom: 3px;
        }

        .account-empty-text {
            font-size: 11px;
            color: #94a3b8;
        }



        .account-filter-bar {
            display: grid;
            grid-template-columns: minmax(240px, 1fr) minmax(180px, 220px) 120px auto;
            gap: 10px;
            align-items: end;
            padding: 16px 18px;
            border-bottom: 1px solid #edf1f5;
            background: #fbfcfe;
        }

        .account-filter-field label {
            display: block;
            margin-bottom: 6px;
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .account-filter-field input,
        .account-filter-field select {
            width: 100%;
            height: 38px;
            box-sizing: border-box;
            border: 1px solid #dbe2ea;
            border-radius: 10px;
            background: #fff;
            padding: 0 11px;
            color: #334155;
            font-size: 11px;
            font-weight: 650;
            outline: none;
        }

        .account-filter-field input:focus,
        .account-filter-field select:focus {
            border-color: #7566ea;
            box-shadow: 0 0 0 3px rgba(117, 102, 234, .10);
        }

        .account-filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .account-filter-btn {
            height: 38px;
            border-radius: 10px;
            padding: 0 14px;
            border: 1px solid #dbe2ea;
            background: #fff;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .account-filter-btn.primary {
            border-color: #6d5dfc;
            background: #6d5dfc;
            color: #fff;
        }

        .account-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 18px 18px;
            border-top: 1px solid #edf1f5;
            background: #fff;
        }

        .account-pagination-info {
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
        }

        .account-pagination-list {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .account-page-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-decoration: none;
        }

        .account-page-btn.active {
            background: #6d5dfc;
            border-color: #6d5dfc;
            color: #fff;
        }

        .account-page-btn.disabled {
            background: #f8fafc;
            color: #cbd5e1;
            pointer-events: none;
        }

        /* =====================================================
           SWEETALERT
           MENGIKUTI KONSEP DATA MASTER
        ===================================================== */

        .swal-account-popup {
            width: 320px !important;
            padding: 20px !important;
            border-radius: 18px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18) !important;
        }

        .swal-account-popup .swal2-icon {
            width: 55px !important;
            height: 55px !important;
            margin: 0 auto 12px !important;
        }

        .swal-account-title {
            font-size: 18px !important;
            font-weight: 800 !important;
            color: #172033 !important;
        }

        .swal-account-text {
            font-size: 12px !important;
            color: #64748b !important;
        }

        .swal-account-popup .swal2-actions {
            gap: 10px !important;
            margin-top: 18px !important;
        }


        /* =====================================================
           SWEETALERT CONFIRM BUTTON
        ===================================================== */

        .swal-account-confirm {
            background: #dc5b68 !important;
            color: white !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-confirm:hover {
            background: #c94d5a !important;
        }


        /* =====================================================
           SWEETALERT RESET BUTTON
        ===================================================== */

        .swal-account-reset {
            background: #f59e0b !important;
            color: white !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-reset:hover {
            background: #d98800 !important;
        }


        /* =====================================================
           SWEETALERT CANCEL BUTTON
        ===================================================== */

        .swal-account-cancel {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: none !important;
            border-radius: 9px !important;
            height: 36px !important;
            padding: 0 18px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }

        .swal-account-cancel:hover {
            background: #e2e8f0 !important;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 768px) {

            .account-page-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .account-table-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .account-success-alert {
                padding: 16px;
            }

            .account-filter-bar {
                grid-template-columns: 1fr;
            }

            .account-filter-actions {
                width: 100%;
            }

            .account-filter-btn {
                flex: 1;
            }

            .account-pagination {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>


    {{-- =====================================================
    PAGE HEADER
===================================================== --}}

    <div class="account-page-head">

        <div>

            <h2 class="account-page-title">
                Manajemen Akun
            </h2>

        </div>


        <a href="{{ route('omd.users.create') }}" class="account-btn-primary">
            + Tambah Akun
        </a>

    </div>


    {{-- =====================================================
    SUCCESS ALERT
===================================================== --}}

    @if (session('account_success'))
        <div class="account-success-alert">
            {{ session('account_success') }}
        </div>
    @endif

    @if ($errors->has('account_error'))
        <div class="account-error-alert">
            {{ $errors->first('account_error') }}
        </div>
    @endif


    {{-- =====================================================
    TABLE CARD
===================================================== --}}

    <div class="account-table-card">

        <form method="GET" action="{{ route('omd.users.index') }}" class="account-filter-bar">
            <div class="account-filter-field">
                <label for="account_search">Pencarian</label>
                <input id="account_search" type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama, email, role, plant, atau line">
            </div>

            <div class="account-filter-field">
                <label for="account_line">Filter Line</label>
                <select id="account_line" name="line_id">
                    <option value="">Semua Line</option>
                    @foreach ($lines as $line)
                        <option value="{{ $line->id }}" @selected((string) request('line_id') === (string) $line->id)>
                            {{ $line->name }}{{ $line->plant?->name ? ' · ' . $line->plant->name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="account-filter-field">
                <label for="account_per_page">Tampilkan</label>
                <select id="account_per_page" name="per_page" onchange="this.form.submit()">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="account-filter-actions">
                <button type="submit" class="account-filter-btn primary">Cari</button>
                @if (request('search') || request('line_id') || (int) request('per_page', 10) !== 10)
                    <a href="{{ route('omd.users.index') }}" class="account-filter-btn">Reset</a>
                @endif
            </div>
        </form>

        <div class="account-table-wrap">

            <table class="account-table">

                <thead>

                    <tr>

                        <th style="width: 65px;">
                            No
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            Email
                        </th>

                        <th style="width: 110px;">
                            Role
                        </th>

                        <th style="width: 150px;">
                            Plant
                        </th>

                        <th style="width: 160px;">
                            Line
                        </th>

                        <th style="width: 270px;">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $index => $user)
                        <tr>

                            {{-- NO --}}
                            <td class="account-number">
                                {{ $users->firstItem() + $index }}
                            </td>


                            {{-- USER --}}
                            <td>

                                <div class="account-user">

                                    <div>

                                        <div class="account-user-name">
                                            {{ $user->name }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- EMAIL --}}
                            <td class="account-email">
                                {{ $user->email }}
                            </td>


                            {{-- ROLE --}}
                            <td>

                                {{ $user->role === 'omd' ? 'OMD' : 'User' }}

                            </td>


                            {{-- PLANT --}}
                            <td>

                                @if ($user->role === 'omd')
                                    -
                                @else
                                    <span class="account-badge account-badge-plant">
                                        {{ $user->line?->plant?->name ?? '-' }}
                                    </span>
                                @endif

                            </td>


                            {{-- LINE --}}
                            <td>

                                @if ($user->role === 'omd')
                                    <span class="account-badge account-badge-line">OMD Workshop</span>
                                @else
                                    <span class="account-badge account-badge-line">
                                        {{ $user->line?->name ?? '-' }}
                                    </span>
                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="account-actions">


                                    {{-- EDIT --}}
                                    <a href="{{ route('omd.users.edit', $user) }}"
                                        class="account-action-btn account-action-edit" title="Edit akun">
                                        Edit
                                    </a>


                                    {{-- RESET PASSWORD --}}
                                    <form method="POST" action="{{ route('omd.users.reset-password', $user) }}"
                                        class="reset-password-form" data-name="{{ $user->name }}">

                                        @csrf

                                        <button type="submit" class="account-action-btn account-action-reset" title="Reset password ke default role">
                                            Reset
                                        </button>

                                    </form>


                                    {{-- HAPUS --}}
                                    <form method="POST" action="{{ route('omd.users.destroy', $user) }}"
                                        class="delete-user-form" data-name="{{ $user->name }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="account-action-btn account-action-delete" title="Hapus akun">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="account-empty">

                                <div class="account-empty-icon">
                                    👤
                                </div>

                                <div class="account-empty-title">
                                    Belum ada akun
                                </div>

                                <div class="account-empty-text">
                                    Tambahkan akun User atau OMD untuk mulai menggunakan sistem.
                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="account-pagination">
            <div class="account-pagination-info">
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} akun
            </div>

            <div class="account-pagination-list">
                @if ($users->onFirstPage())
                    <span class="account-page-btn disabled">Previous</span>
                @else
                    <a class="account-page-btn" href="{{ $users->previousPageUrl() }}">Previous</a>
                @endif

                @for ($page = max(1, $users->currentPage() - 1); $page <= min($users->lastPage(), $users->currentPage() + 1); $page++)
                    <a class="account-page-btn {{ $users->currentPage() === $page ? 'active' : '' }}"
                        href="{{ $users->url($page) }}">{{ $page }}</a>
                @endfor

                @if ($users->hasMorePages())
                    <a class="account-page-btn" href="{{ $users->nextPageUrl() }}">Next</a>
                @else
                    <span class="account-page-btn disabled">Next</span>
                @endif
            </div>
        </div>

    </div>


    {{-- =====================================================
    SWEETALERT2
===================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                /* =================================================
                   DELETE USER
                ================================================= */

                document
                    .querySelectorAll('.delete-user-form')
                    .forEach(function(form) {

                        form.addEventListener(
                            'submit',
                            function(event) {

                                event.preventDefault();

                                var userName =
                                    form.dataset.name;


                                Swal.fire({

                                    icon: 'warning',

                                    title: 'Hapus Akun?',

                                    html: 'Akun <b>' +
                                        userName +
                                        '</b> akan dihapus dan data tidak dapat dikembalikan.',

                                    showCancelButton: true,

                                    confirmButtonText: 'Ya, Hapus',

                                    cancelButtonText: 'Batal',

                                    reverseButtons: true,

                                    buttonsStyling: false,

                                    customClass: {

                                        popup: 'swal-account-popup',

                                        title: 'swal-account-title',

                                        htmlContainer: 'swal-account-text',

                                        confirmButton: 'swal-account-confirm',

                                        cancelButton: 'swal-account-cancel'

                                    }

                                }).then(
                                    function(result) {

                                        if (
                                            result.isConfirmed
                                        ) {

                                            form.submit();

                                        }

                                    }
                                );

                            }
                        );

                    });


                /* =================================================
                   RESET PASSWORD
                ================================================= */

                document
                    .querySelectorAll('.reset-password-form')
                    .forEach(function(form) {

                        form.addEventListener(
                            'submit',
                            function(event) {

                                event.preventDefault();

                                var userName =
                                    form.dataset.name;


                                Swal.fire({

                                    icon: 'warning',

                                    title: 'Reset Password?',

                                    html: 'Password akun <b>' +
                                        userName +
                                        '</b> akan direset ke <b>password default sesuai role</b>.',

                                    showCancelButton: true,

                                    confirmButtonText: 'Ya, Reset',

                                    cancelButtonText: 'Batal',

                                    reverseButtons: true,

                                    buttonsStyling: false,

                                    customClass: {

                                        popup: 'swal-account-popup',

                                        title: 'swal-account-title',

                                        htmlContainer: 'swal-account-text',

                                        confirmButton: 'swal-account-reset',

                                        cancelButton: 'swal-account-cancel'

                                    }

                                }).then(
                                    function(result) {

                                        if (
                                            result.isConfirmed
                                        ) {

                                            form.submit();

                                        }

                                    }
                                );

                            }
                        );

                    });

            }
        );
    </script>

@endsection
