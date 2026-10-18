<?php

namespace App\Http\Controllers;

use App\Models\FormNumber;
use App\Models\FormNumberHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FormNumberController extends Controller
{
    public function index()
    {
        return view('omd.master.form-numbers', [
            'formNumbers' => FormNumber::withTrashed()->latest('id')->get(),
            'histories' => FormNumberHistory::with(['formNumber', 'actor'])->latest('id')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\/-]+$/', 'unique:form_numbers,number'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $data) {
            // Lock current rows before changing the single active number.
            FormNumber::withTrashed()->orderBy('id')->lockForUpdate()->get();
            $active = (bool) $data['is_active'];
            if ($active) $this->deactivatePrevious($request->user()->id);
            $form = FormNumber::create([
                'number' => $data['number'],
                'description' => $data['description'] ?? null,
                'is_active' => $active,
                'created_by' => $request->user()->id,
            ]);
            $this->record($form, 'created', null, $request->user()->id);
        });

        return back()->with('success', 'Nomor Form berhasil ditambahkan.');
    }

    public function update(Request $request, FormNumber $formNumber): RedirectResponse
    {
        $data = $request->validate([
            'number' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\/-]+$/', Rule::unique('form_numbers', 'number')->ignore($formNumber->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $formNumber, $data) {
            FormNumber::withTrashed()->orderBy('id')->lockForUpdate()->get();
            $formNumber->refresh();
            $before = $formNumber->only('number', 'description', 'is_active');
            $activate = (bool) $data['is_active'];
            if ($formNumber->is_active && !$activate) {
                throw ValidationException::withMessages(['is_active' => 'Aktifkan Nomor Form pengganti terlebih dahulu.']);
            }
            if ($activate) $this->deactivatePrevious($request->user()->id, $formNumber->id);
            $formNumber->update([
                'number' => $data['number'],
                'description' => $data['description'] ?? null,
                'is_active' => $activate,
            ]);
            if ($formNumber->wasChanged()) {
                $this->record($formNumber, 'updated', $before, $request->user()->id);
            }
        });

        return back()->with('success', 'Nomor Form berhasil diperbarui.');
    }

    public function destroy(Request $request, FormNumber $formNumber): RedirectResponse
    {
        DB::transaction(function () use ($request, $formNumber) {
            FormNumber::withTrashed()->orderBy('id')->lockForUpdate()->get();
            $formNumber->refresh();
            if ($formNumber->is_active) {
                throw ValidationException::withMessages(['is_active' => 'Nomor Form aktif tidak bisa diarsipkan. Aktifkan penggantinya terlebih dahulu.']);
            }
            $before = $formNumber->only('number', 'description', 'is_active');
            $formNumber->delete(); // Soft delete. Data dan riwayat tetap tersimpan.
            $this->record($formNumber, 'archived', $before, $request->user()->id);
        });

        return back()->with('success', 'Nomor Form diarsipkan tanpa menghapus data.');
    }

    private function deactivatePrevious(int $actorId, ?int $exceptId = null): void
    {
        $query = FormNumber::query()->where('is_active', true);
        if ($exceptId !== null) $query->where('id', '!=', $exceptId);
        foreach ($query->get() as $previous) {
            $before = $previous->only('number', 'description', 'is_active');
            $previous->update(['is_active' => false]);
            $this->record($previous, 'deactivated', $before, $actorId);
        }
    }

    private function record(FormNumber $form, string $action, ?array $before, int $actorId): void
    {
        FormNumberHistory::create([
            'form_number_id' => $form->id,
            'action' => $action,
            'old_number' => $before['number'] ?? null,
            'new_number' => $form->number,
            'old_description' => $before['description'] ?? null,
            'new_description' => $form->description,
            'old_is_active' => $before['is_active'] ?? null,
            'new_is_active' => $form->is_active,
            'changed_by' => $actorId,
        ]);
    }
}
