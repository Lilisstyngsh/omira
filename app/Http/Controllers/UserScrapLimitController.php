<?php
namespace App\Http\Controllers;

use App\Models\UserScrapLimitCycle;
use Illuminate\Http\Request;

class UserScrapLimitController extends Controller
{
    public function decide(Request $request, UserScrapLimitCycle $cycle)
    {
        abort_unless($cycle->user_id === $request->user()->id && $cycle->reached_at && !$cycle->reset_at, 403);
        $data = $request->validate([
            'decision'=>['required','in:yes,no'],
            'reason'=>['required_if:decision,no','nullable','string','max:1000'],
        ]);
        if ($data['decision'] === 'no' && trim((string) ($data['reason'] ?? '')) === '') {
            throw \Illuminate\Validation\ValidationException::withMessages(['reason' => 'Alasan wajib diisi.']);
        }
        \Illuminate\Support\Facades\DB::transaction(function () use ($cycle, $data) {
            $record = UserScrapLimitCycle::query()->lockForUpdate()->findOrFail($cycle->id);
            abort_if($record->reset_at, 422, 'Limit sudah direset oleh OMD.');

            // Hindari error ketika user melakukan submit ulang dari modal.
            // Decision hanya diperbarui jika belum ada, atau melengkapi alasan Tidak Order.
            if ($record->decision && $record->decision === 'yes') {
                abort(422, 'Keputusan Order sudah tersimpan.');
            }

            $record->update([
                'decision'=>$data['decision'],
                'decision_reason'=>$data['decision'] === 'no' ? trim($data['reason']) : null,
                'decided_at'=>now(),
            ]);
        });
        return $data['decision'] === 'yes'
            ? redirect()->route('user.orders.create')->with('success', 'Silakan membuat Order Repair. Scrap untuk produk yang terkunci tetap tidak dapat dipesan sebelum reset OMD.')
            : redirect()->route('dashboard')->with('success', 'Alasan tidak membuat order berhasil dicatat.');
    }
}
