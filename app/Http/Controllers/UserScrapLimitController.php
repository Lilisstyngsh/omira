<?php

namespace App\Http\Controllers;

use App\Models\AdditionalBoxRequest;
use App\Models\User;
use App\Models\UserScrapLimitCycle;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserScrapLimitController extends Controller
{
    public function decide(Request $request, UserScrapLimitCycle $cycle, UserScrapLimitService $service)
    {
        abort_unless((int) $cycle->user_id === (int) $request->user()->id, 403);
        $data = $request->validate([
            'decision' => ['required', 'in:yes,no'],
            'reason' => ['required_if:decision,no', 'nullable', 'string', 'max:1000'],
        ]);

        if ($data['decision'] === 'yes') {
            return redirect()->route('dashboard')->with('success', 'Pilih Order pada dashboard, lalu Submit keputusan.');
        }
        $reason = trim((string) ($data['reason'] ?? ''));
        if ($reason === '') throw ValidationException::withMessages(['reason' => 'Alasan wajib diisi.']);
        $document = DB::transaction(function () use ($request, $cycle, $reason, $service) {
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $record = UserScrapLimitCycle::query()->lockForUpdate()->findOrFail($cycle->id);
            if ($record->reset_at && $record->decision === 'no') {
                return AdditionalBoxRequest::whereHas('decisions', fn($q) => $q->where('user_scrap_limit_cycle_id', $record->id))->first();
            }
            abort_if($record->reset_at || !$record->reached_at, 422, 'Peringatan sudah diproses atau direset. Muat ulang dashboard.');
            $record->loadMissing('product.masterModel');
            if (!$record->product || !$record->product->masterModel) {
                throw ValidationException::withMessages(['decision' => 'Produk tidak tersedia.']);
            }
            $quantity = $service->quantity($user, $record->product_id, $record);
            if ($quantity < (int) $record->limit_qty) {
                throw ValidationException::withMessages(['decision' => 'Data scrap berubah. Muat ulang dashboard.']);
            }
            // Compatibility: old bookmarked form must still appear in Additional Box history.
            $document = AdditionalBoxRequest::create([
                'user_id' => $user->id,
                'submission_token' => (string) Str::uuid(),
                'purpose' => AdditionalBoxRequest::PURPOSE,
            ]);
            $document->decisions()->create([
                'user_scrap_limit_cycle_id' => $record->id,
                'product_id' => $record->product_id,
                'model_name' => $record->product->masterModel->model,
                'product_name' => $record->product->name,
                'scrap_qty' => $quantity,
                'limit_qty' => $record->limit_qty,
                'decision' => 'no',
                'reason' => $reason,
            ]);
            $record->update(['decision' => 'no', 'decision_reason' => $reason, 'decided_at' => now()]);
            $service->reset($record, $user, 'Reset otomatis setelah keputusan Tidak Order.');
            return $document;
        }, 3);
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'document_number' => $document?->document_number,
                'detail_url' => $document ? route('user.additional-box.show', $document) : null,
            ]);
        }
        return $document ? redirect()->route('user.additional-box.show', $document)
            ->with('success', 'Keputusan Tidak Order tersimpan di riwayat.')
            : redirect()->route('user.additional-box.index');
    }
}
