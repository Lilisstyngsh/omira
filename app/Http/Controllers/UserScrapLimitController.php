<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserScrapLimitCycle;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserScrapLimitController extends Controller
{
    public function decide(Request $request, UserScrapLimitCycle $cycle, UserScrapLimitService $service)
    {
        abort_unless((int) $cycle->user_id === (int) $request->user()->id, 403);
        $data = $request->validate([
            'decision' => ['required', 'in:yes,no'],
            'reason' => ['required_if:decision,no', 'nullable', 'string', 'max:1000'],
        ]);
        // Compatibility for previously opened pages; Order now needs product quantities.
        if ($data['decision'] === 'yes') {
            return redirect()->route('dashboard')->with('success', 'Pilih produk Order dan isi Qty, lalu Submit Request Additional Box.');
        }
        $reason = trim((string) ($data['reason'] ?? ''));
        if ($reason === '') throw ValidationException::withMessages(['reason' => 'Alasan wajib diisi.']);
        DB::transaction(function () use ($request, $cycle, $reason, $service) {
            $user = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $record = UserScrapLimitCycle::query()->lockForUpdate()->findOrFail($cycle->id);
            if ($record->reset_at && $record->decision === 'no') return;
            abort_if($record->reset_at || !$record->reached_at, 422, 'Peringatan sudah diproses atau direset. Muat ulang dashboard.');
            $record->update(['decision' => 'no', 'decision_reason' => $reason, 'decided_at' => now()]);
            $service->reset($record, $user, 'Reset otomatis setelah keputusan Tidak Order.');
        }, 3);
        return redirect()->route('dashboard')->with('success', 'Alasan Tidak Order tersimpan dan siklus scrap produk telah direset.');
    }
}
