<?php
namespace App\Http\Controllers;

use App\Models\UserScrapLimitCycle;
use App\Services\UserScrapLimitService;
use Illuminate\Http\Request;

class OmdScrapCycleController extends Controller
{
    public function index()
    {
        $cycles = UserScrapLimitCycle::query()->whereNotNull('reached_at')
            ->with(['user','product.masterModel.line.plant','line','resetter'])
            ->latest('reached_at')->paginate(20);
        return view('omd.scrap-limits.cycles', compact('cycles'));
    }

    public function reset(Request $request, UserScrapLimitCycle $cycle, UserScrapLimitService $service)
    {
        $data = $request->validate(['reason'=>['required','string','min:5','max:1000']]);
        $service->reset($cycle, $request->user(), trim($data['reason']));
        return back()->with('success', 'Scrap Limit direset ke default master. Riwayat sebelumnya tetap disimpan.');
    }
}
