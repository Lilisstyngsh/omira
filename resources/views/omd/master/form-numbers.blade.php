@extends('layouts.app')

@section('title', 'Master Nomor Form')
@section('header', 'Master Nomor Form')

@section('content')
<style>
    .fn-head { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; margin-bottom:18px; }
    .fn-head h2 { margin:0 0 4px; font-size:21px; font-weight:800; color:#253d30; }
    .fn-head p { margin:0; color:#64748b; font-size:12px; }
    .fn-card { padding:0; background:#fff; border:1px solid #e0e9e2; border-radius:14px; overflow:hidden; margin-bottom:20px; }
    .fn-card-heading { padding:15px 18px; background:#f5f9f6; border-bottom:1px solid #e0e9e2; font-size:14px; font-weight:750; color:#284b35; }
    .fn-table-wrap { overflow-x:auto; }
    .fn-table { width:100%; border-collapse:collapse; min-width:750px; }
    .fn-table th { background:#f8faf9; color:#5c7062; font-size:11px; text-transform:uppercase; letter-spacing:.025em; text-align:left; padding:13px 16px; border-bottom:1px solid #e3ebe5; }
    .fn-table td { padding:14px 16px; border-bottom:1px solid #ecf0ec; font-size:12px; color:#334155; vertical-align:middle; }
    .fn-table tr:last-child td { border-bottom:0; }
    .fn-table tbody tr:hover { background:#fbfdfb; }
    .fn-code { font-weight:800; color:#244934; white-space:nowrap; }
    .fn-muted { color:#64748b; font-size:11px; }
    .fn-status { display:inline-block; border-radius:999px; padding:5px 10px; font-size:11px; font-weight:750; background:#f1f5f9; color:#64748b; }
    .fn-status.active { background:#e3f4e9; color:#1d7541; }
    .fn-actions { display:inline-flex; gap:7px; flex-wrap:wrap; align-items:center; margin-left:10px; }
    .fn-actions form { display:inline; }
    .fn-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px; border:1px solid #bad3c3; border-radius:9px; padding:9px 13px; font-size:12px; font-weight:750; color:#2c6546; background:#f3f9f5; cursor:pointer; text-decoration:none; }
    .fn-btn.primary { color:#fff; background:#54896b; border-color:#54896b; }
    .fn-btn.danger { color:#a74040; background:#fff7f7; border-color:#f0d5d5; }
    .fn-btn:hover { filter:brightness(.97); }
    .fn-btn:disabled { opacity:.5; cursor:not-allowed; }
    .fn-alert { padding:12px 15px; border:1px solid #f3d0d0; background:#fff3f3; color:#9f3030; border-radius:10px; font-size:12px; margin-bottom:14px; }
    .fn-alert.success { border-color:#c5e5cc; background:#eff9f1; color:#227342; }
    .fn-modal { position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:1100; display:none; justify-content:center; align-items:center; padding:16px; }
    .fn-modal.open { display:flex; }
    .fn-dialog { width:min(510px,100%); background:#fff; border-radius:15px; border:1px solid #dbe8df; box-shadow:0 22px 55px rgba(0,0,0,.16); padding:22px; max-height:90vh; overflow-y:auto; }
    .fn-dialog h3 { margin:0 0 16px; font-size:17px; color:#284b35; }
    .fn-field { display:grid; gap:7px; margin-bottom:14px; }
    .fn-field label { font-size:12px; font-weight:700; color:#344b3c; }
    .fn-field input,.fn-field textarea,.fn-field select { width:100%; box-sizing:border-box; border-radius:9px; border:1px solid #cedbd1; padding:10px 12px; color:#23372b; background:#fff; font-size:13px; }
    .fn-dialog-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:18px; }
    .fn-history-cell { max-width:300px; overflow-wrap:anywhere; }
    .fn-history-info { display:block; margin-top:3px; color:#64748b; font-size:10px; }
    .fn-pagination { padding:12px 16px; border-top:1px solid #e0e9e2; }
    .fn-pagination nav { font-size:12px; }
    @media(max-width:700px) { .fn-table td,.fn-table th { padding:11px; } .fn-actions { margin-left:0; margin-top:6px; } }
</style>

<div class="fn-head">
    <div>
        <h2>Nomor Form</h2>
        <p>Nomor aktif otomatis digunakan pada PDF dan Excel History Order.</p>
    </div>
    <button class="fn-btn primary" type="button" id="fnAdd"><i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah Nomor Form</button>
</div>

@if(session('success'))
    <div class="fn-alert success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="fn-alert" role="alert">
        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
@endif

<div class="fn-card">
    <div class="fn-card-heading">Daftar Nomor Form</div>
    <div class="fn-table-wrap">
        <table class="fn-table">
            <thead><tr><th>Nomor Form</th><th>Keterangan</th><th>Tanggal Dibuat</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($formNumbers as $form)
                <tr>
                    <td><span class="fn-code">{{ $form->number }}</span>@if($form->trashed())<span class="fn-history-info">Diarsipkan (data tetap tersimpan)</span>@endif</td>
                    <td>{{ $form->description ?: '-' }}</td>
                    <td>{{ $form->created_at?->locale('id')->translatedFormat('d F Y') }}</td>
                    <td>
                        <span class="fn-status {{ $form->is_active && !$form->trashed() ? 'active' : '' }}">{{ $form->is_active && !$form->trashed() ? 'Aktif' : 'Non-aktif' }}</span>
                        @unless($form->trashed())
                        <span class="fn-actions">
                            <button type="button" class="fn-btn js-form-edit"
                                data-url="{{ route('omd.master.form-numbers.update', $form) }}"
                                data-number="{{ $form->number }}"
                                data-description="{{ $form->description }}"
                                data-active="{{ $form->is_active ? '1' : '0' }}">Edit</button>
                            @unless($form->is_active)
                                <form method="POST" action="{{ route('omd.master.form-numbers.destroy', $form) }}" onsubmit="return confirm('Arsipkan nomor form ini? Data dan riwayat tetap tersimpan.')">
                                    @csrf @method('DELETE')
                                    <button class="fn-btn danger" type="submit">Arsipkan</button>
                                </form>
                            @endunless
                        </span>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#64748b">Belum ada Nomor Form.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="fn-card">
    <div class="fn-card-heading">Riwayat Perubahan Nomor Form</div>
    <div class="fn-table-wrap">
        <table class="fn-table">
            <thead><tr><th>Waktu</th><th>OMD</th><th>Perubahan</th><th>Nomor Sebelum → Sesudah</th></tr></thead>
            <tbody>
                @forelse($histories as $entry)
                    <tr>
                        <td>{{ $entry->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ $entry->actor?->name ?? 'Sistem' }}</td>
                        <td>
                            {{ ['created'=>'Tambah', 'updated'=>'Edit', 'deactivated'=>'Non-aktif otomatis', 'archived'=>'Arsip'] [$entry->action] ?? $entry->action }}
                            <span class="fn-history-info">{{ $entry->old_is_active ? 'Aktif' : 'Non-aktif' }} → {{ $entry->new_is_active ? 'Aktif' : 'Non-aktif' }}</span>
                        </td>
                        <td class="fn-history-cell">
                            {{ $entry->old_number ?: '—' }} → <strong>{{ $entry->new_number }}</strong>
                            @if($entry->old_description !== $entry->new_description)
                                <span class="fn-history-info">{{ $entry->old_description ?: '—' }} → {{ $entry->new_description ?: '—' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:#64748b">Belum ada perubahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())<div class="fn-pagination">{{ $histories->links() }}</div>@endif
</div>

<div class="fn-modal" id="fnModal" aria-hidden="true">
    <div class="fn-dialog" role="dialog" aria-modal="true" aria-labelledby="fnModalTitle">
        <h3 id="fnModalTitle">Tambah Nomor Form</h3>
        <form method="POST" action="{{ route('omd.master.form-numbers.store') }}" id="fnForm" data-create-url="{{ route('omd.master.form-numbers.store') }}">
            @csrf
            <input type="hidden" id="fnMethod" name="_method" value="PUT" disabled>
            <div class="fn-field"><label for="fnNumber">Nomor Form</label><input id="fnNumber" type="text" name="number" maxlength="20" pattern="[A-Za-z0-9/-]+" required placeholder="FRM-PSD-P3-057-00"></div>
            <div class="fn-field"><label for="fnDescription">Keterangan</label><textarea id="fnDescription" name="description" rows="3" maxlength="255" placeholder="Keterangan form"></textarea></div>
            <div class="fn-field"><label for="fnActive">Status</label><select id="fnActive" name="is_active"><option value="0">Non-aktif</option><option value="1">Aktif</option></select><small class="fn-muted">Mengaktifkan nomor ini otomatis menonaktifkan nomor sebelumnya.</small></div>
            <div class="fn-dialog-actions"><button class="fn-btn" type="button" id="fnCancel">Batal</button><button class="fn-btn primary" type="submit">Simpan</button></div>
        </form>
    </div>
</div>
<script>
(function () {
    const modal = document.getElementById('fnModal');
    const form = document.getElementById('fnForm');
    const method = document.getElementById('fnMethod');
    const number = document.getElementById('fnNumber');
    const description = document.getElementById('fnDescription');
    const active = document.getElementById('fnActive');
    const title = document.getElementById('fnModalTitle');
    function show() { modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); number.focus(); }
    function hide() { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); }
    document.getElementById('fnAdd').addEventListener('click', function () {
        form.action = form.dataset.createUrl; method.disabled = true;
        form.reset(); active.value = '0'; title.textContent = 'Tambah Nomor Form'; show();
    });
    document.querySelectorAll('.js-form-edit').forEach(function (button) {
        button.addEventListener('click', function () {
            form.action = button.dataset.url; method.disabled = false;
            number.value = button.dataset.number;
            description.value = button.dataset.description || '';
            active.value = button.dataset.active;
            title.textContent = 'Edit Nomor Form'; show();
        });
    });
    document.getElementById('fnCancel').addEventListener('click', hide);
    modal.addEventListener('click', function (e) { if (e.target === modal) hide(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') hide(); });
})();
</script>
@endsection
