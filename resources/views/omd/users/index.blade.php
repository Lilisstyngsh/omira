@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('header', 'Manajemen Akun')

@section('content')

    @php
        $accountPlants = $lines->pluck('plant')->filter()->unique('id')->sortBy('name')->values();
        $accountLinesByPlant = $lines->groupBy('plant_id')->map(fn ($items) => $items->map(fn ($line) => [
            'id' => $line->id,
            'name' => $line->name,
        ])->values());
        $accountUsersPayload = $users->getCollection()->mapWithKeys(fn ($account) => [
            (string) $account->id => [
                'id' => $account->id,
                'name' => $account->name,
                'email' => $account->email,
                'role' => $account->role,
                'plant_id' => $account->line?->plant_id,
                'line_id' => $account->line_id,
                'update_url' => route('omd.users.update', $account),
            ],
        ]);
    @endphp

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
            width: 32px;
            height: 32px;
            padding: 0;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 18px;
            border-bottom: 1px solid #edf1f5;
            background: #fbfcfe;
        }

        .account-filter-left {
            flex: 0 0 108px;
            width: 108px;
        }

        .account-filter-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            min-width: 0;
        }

        .account-filter-line {
            flex: 0 0 256px;
            width: 256px;
        }

        .account-filter-search {
            flex: 0 1 480px;
            width: min(480px, 38vw);
            min-width: 300px;
        }

        .account-filter-field > label {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
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

        .account-search-wrap {
            position: relative;
        }

        .account-search-wrap .account-search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
        }

        .account-search-wrap input {
            padding-left: 31px;
            padding-right: 34px;
        }

        .account-search-clear {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 27px;
            height: 27px;
            padding: 0;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .account-search-clear:hover {
            background: #f1f5f9;
            color: #475569;
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
           EDIT ACCOUNT MODAL
        ===================================================== */
        .account-edit-overlay {
            position: fixed;
            inset: 0;
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, .42);
            backdrop-filter: blur(2px);
        }

        .account-edit-overlay.is-open { display: flex; }

        .account-edit-modal {
            width: min(720px, 100%);
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 18px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .22);
        }

        .account-edit-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid #edf1f5;
        }

        .account-edit-title { margin: 0; color: #172033; font-size: 17px; font-weight: 800; }
        .account-edit-close { width: 34px; height: 34px; border: 0; border-radius: 9px; background: #f1f5f9; color: #64748b; cursor: pointer; }
        .account-edit-body { padding: 20px; overflow: auto; }
        .account-edit-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 15px; }
        .account-edit-field { display: grid; gap: 7px; }
        .account-edit-field label { font-size: 11px; font-weight: 750; color: #334155; }
        .account-edit-field input, .account-edit-field select { width: 100%; height: 42px; padding: 0 12px; border: 1px solid #dbe2ea; border-radius: 10px; background: #fff; color: #172033; outline: none; }
        .account-edit-field input:focus, .account-edit-field select:focus { border-color: #7566ea; box-shadow: 0 0 0 3px rgba(117, 102, 234, .10); }
        .account-edit-helper { margin: 0; font-size: 10px; line-height: 1.45; color: #94a3b8; }
        .account-edit-errors { margin: 0 0 14px; padding: 10px 12px; border: 1px solid #fecaca; border-radius: 10px; background: #fff1f2; color: #b42318; font-size: 11px; }
        .account-edit-errors ul { margin: 0; padding-left: 17px; }
        .account-edit-actions { display: flex; justify-content: flex-end; gap: 9px; padding: 14px 20px 18px; border-top: 1px solid #edf1f5; }
        .account-edit-btn { min-height: 40px; padding: 0 16px; border: 0; border-radius: 9px; font-size: 12px; font-weight: 750; cursor: pointer; }
        .account-edit-cancel { background: #f1f5f9; color: #475569; }
        .account-edit-save { background: #6d5dfc; color: #fff; box-shadow: 0 6px 15px rgba(109, 93, 252, .18); }
        .account-edit-save:hover { background: #5d4df0; }
        body.account-modal-open { overflow: hidden; }

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
                flex-wrap: wrap;
                align-items: stretch;
            }

            .account-filter-left {
                flex: 0 0 108px;
            }

            .account-filter-right {
                width: 100%;
                margin-left: 0;
                justify-content: stretch;
            }

            .account-filter-line, .account-filter-search {
                flex: 1 1 220px;
                width: auto;
                min-width: 0;
            }

            .account-edit-grid {
                grid-template-columns: 1fr;
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
            <div class="account-filter-field account-filter-left">
                <label for="account_per_page">Tampilkan</label>
                <select id="account_per_page" name="per_page" onchange="this.form.submit()" aria-label="Jumlah data per halaman">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="account-filter-right">
                <div class="account-filter-field account-filter-line">
                    <label for="account_line">Filter Line</label>
                    <select id="account_line" name="line_id" onchange="this.form.submit()" aria-label="Filter Line">
                        <option value="">Semua Line</option>
                        @foreach ($lines as $line)
                            <option value="{{ $line->id }}" @selected((string) request('line_id') === (string) $line->id)>
                                {{ $line->name }}{{ $line->plant?->name ? ' · ' . $line->plant->name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="account-filter-field account-filter-search">
                    <label for="account_search">Pencarian</label>
                    <div class="account-search-wrap">
                        <i class="fa-solid fa-magnifying-glass account-search-icon" aria-hidden="true"></i>
                        <input id="account_search" type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email, role, plant, atau line..." autocomplete="off" aria-label="Pencarian akun">
                        @if (request('search'))
                            <button type="button" class="account-search-clear" title="Hapus pencarian" aria-label="Hapus pencarian"
                                onclick="this.closest('form').querySelector('[name=search]').value=''; this.closest('form').submit();">
                                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            </button>
                        @endif
                    </div>
                </div>
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

                        <th style="width: 150px;">
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
                                    <button type="button"
                                        class="account-action-btn account-action-edit action-icon-only js-edit-account"
                                        data-user-id="{{ $user->id }}" title="Edit akun" aria-label="Edit akun">
                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                    </button>


                                    {{-- RESET PASSWORD --}}
                                    <form method="POST" action="{{ route('omd.users.reset-password', $user) }}"
                                        class="reset-password-form" data-name="{{ $user->name }}">

                                        @csrf

                                        <button type="submit" class="account-action-btn account-action-reset action-icon-only" title="Reset password ke default role" aria-label="Reset password ke default role">
                                            <i class="fa-solid fa-key" aria-hidden="true"></i>
                                        </button>

                                    </form>


                                    {{-- HAPUS --}}
                                    <form method="POST" action="{{ route('omd.users.destroy', $user) }}"
                                        class="delete-user-form" data-name="{{ $user->name }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="account-action-btn account-action-delete action-icon-only" title="Hapus akun" aria-label="Hapus akun">
                                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="account-empty">

                                <div class="account-empty-icon">
                                    <i class="fa-solid fa-user" aria-hidden="true"></i>
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




    <div class="account-edit-overlay" id="accountEditModal" aria-hidden="true">
        <div class="account-edit-modal" role="dialog" aria-modal="true" aria-labelledby="accountEditTitle">
            <div class="account-edit-head">
                <h3 class="account-edit-title" id="accountEditTitle">Edit Akun</h3>
                <button type="button" class="account-edit-close js-close-account-modal" title="Tutup" aria-label="Tutup">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>

            <form method="POST" id="accountEditForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_user_id" id="edit_user_id" value="{{ old('edit_user_id') }}">

                <div class="account-edit-body">
                    @if ($errors->any() && old('edit_user_id'))
                        <div class="account-edit-errors">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="account-edit-grid">
                        <div class="account-edit-field">
                            <label for="edit_name">Nama</label>
                            <input id="edit_name" name="name" type="text" maxlength="100" required>
                        </div>
                        <div class="account-edit-field">
                            <label for="edit_email">Email</label>
                            <input id="edit_email" name="email" type="email" maxlength="255" required>
                        </div>
                        <div class="account-edit-field">
                            <label for="edit_role">Role</label>
                            <select id="edit_role" name="role" required>
                                <option value="user">User</option>
                                <option value="omd">OMD</option>
                            </select>
                        </div>
                        <div class="account-edit-field" id="editPlantField">
                            <label for="edit_plant_id">Plant</label>
                            <select id="edit_plant_id" name="plant_id">
                                <option value="">Pilih Plant</option>
                                @foreach ($accountPlants as $plant)
                                    <option value="{{ $plant->id }}">{{ $plant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="account-edit-field" id="editLineField">
                            <label for="edit_line_id">Line</label>
                            <select id="edit_line_id" name="line_id">
                                <option value="">Pilih Line</option>
                            </select>
                        </div>
                        <div class="account-edit-field">
                            <label for="edit_password">Password Baru</label>
                            <input id="edit_password" name="password" type="password" minlength="4" autocomplete="new-password" placeholder="Kosongkan jika tidak diubah">
                            <p class="account-edit-helper">Opsional.</p>
                        </div>
                        <div class="account-edit-field">
                            <label for="edit_password_confirmation">Konfirmasi Password</label>
                            <input id="edit_password_confirmation" name="password_confirmation" type="password" minlength="4" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="account-edit-actions">
                    <button type="button" class="account-edit-btn account-edit-cancel js-close-account-modal">Batal</button>
                    <button type="submit" class="account-edit-btn account-edit-save">Simpan</button>
                </div>
            </form>
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

                const accountUsers = @json($accountUsersPayload);
                const linesByPlant = @json($accountLinesByPlant);
                const editModal = document.getElementById('accountEditModal');
                const editForm = document.getElementById('accountEditForm');
                const editUserId = document.getElementById('edit_user_id');
                const editName = document.getElementById('edit_name');
                const editEmail = document.getElementById('edit_email');
                const editRole = document.getElementById('edit_role');
                const editPlant = document.getElementById('edit_plant_id');
                const editLine = document.getElementById('edit_line_id');
                const editPlantField = document.getElementById('editPlantField');
                const editLineField = document.getElementById('editLineField');

                function syncAccountLines(selectedLineId = '') {
                    const plantId = String(editPlant.value || '');
                    const options = linesByPlant[plantId] || [];
                    editLine.innerHTML = '<option value="">Pilih Line</option>' + options.map(line =>
                        `<option value="${line.id}">${line.name}</option>`
                    ).join('');
                    if (selectedLineId) editLine.value = String(selectedLineId);
                }

                function syncAccountRole() {
                    const isUser = editRole.value === 'user';
                    editPlantField.style.display = isUser ? '' : 'none';
                    editLineField.style.display = isUser ? '' : 'none';
                    editPlant.required = isUser;
                    editLine.required = isUser;
                    editPlant.disabled = !isUser;
                    editLine.disabled = !isUser;
                }

                function openAccountEdit(userId, oldValues = null) {
                    const user = accountUsers[String(userId)];
                    if (!user || !editModal || !editForm) return;

                    editForm.action = user.update_url;
                    editUserId.value = user.id;
                    editName.value = oldValues?.name ?? user.name ?? '';
                    editEmail.value = oldValues?.email ?? user.email ?? '';
                    editRole.value = oldValues?.role ?? user.role ?? 'user';
                    editPlant.value = String(oldValues?.plant_id ?? user.plant_id ?? '');
                    syncAccountRole();
                    syncAccountLines(oldValues?.line_id ?? user.line_id ?? '');
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';

                    editModal.classList.add('is-open');
                    editModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('account-modal-open');
                    setTimeout(() => editName.focus(), 30);
                }

                function closeAccountEdit() {
                    editModal?.classList.remove('is-open');
                    editModal?.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('account-modal-open');
                }

                document.querySelectorAll('.js-edit-account').forEach(button => {
                    button.addEventListener('click', () => openAccountEdit(button.dataset.userId));
                });
                document.querySelectorAll('.js-close-account-modal').forEach(button => {
                    button.addEventListener('click', closeAccountEdit);
                });
                editModal?.addEventListener('click', event => {
                    if (event.target === editModal) closeAccountEdit();
                });
                editRole?.addEventListener('change', () => {
                    syncAccountRole();
                    if (editRole.value === 'user') syncAccountLines();
                });
                editPlant?.addEventListener('change', () => syncAccountLines());
                document.addEventListener('keydown', event => {
                    if (event.key === 'Escape' && editModal?.classList.contains('is-open')) closeAccountEdit();
                });

                @if (old('edit_user_id'))
                    openAccountEdit(@json((string) old('edit_user_id')), {
                        name: @json(old('name')),
                        email: @json(old('email')),
                        role: @json(old('role')),
                        plant_id: @json(old('plant_id')),
                        line_id: @json(old('line_id')),
                    });
                @endif

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
