<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\TpsRepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TpsRepairController extends Controller
{
    public function userIndex(Request $request)
    {
        $orders = TpsRepairOrder::with('area')->where('user_id', $request->user()->id)->latest()->paginate(10);
        return view('user.tps.index', compact('orders'));
    }
    public function create()
    {
        return view('user.tps.create', ['areas' => Area::where('is_active', true)->orderBy('category')->orderBy('name')->get()]);
    }
    public function store(Request $request)
    {
        $data = $request->validate(['area_id' => ['nullable', 'exists:areas,id'], 'reported_date' => ['required', 'date'], 'tool_name' => ['required', 'string', 'max:150'], 'problem_description' => ['required', 'string', 'max:2000']]);
        $data['order_number'] = 'TPS-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'submitted';
        $order = TpsRepairOrder::create($data);
        return redirect()->route('user.tps.show', $order)->with('success', 'Form TPS Tool berhasil dikirim.');
    }
    public function userShow(Request $request, TpsRepairOrder $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('area');
        return view('user.tps.show', compact('order'));
    }
    public function omdIndex()
    {
        $orders = TpsRepairOrder::with(['user', 'area'])->latest()->paginate(15);
        return view('omd.tps.index', compact('orders'));
    }
    public function omdShow(TpsRepairOrder $order)
    {
        $order->load(['user', 'area', 'leader', 'member', 'repairer']);
        return view('omd.tps.show', compact('order'));
    }
    public function leaderCheck(Request $request, TpsRepairOrder $order)
    {
        abort_unless($request->user()->role === 'omd_leader', 403);
        abort_unless($order->status === 'submitted', 422);
        $order->update(['status' => 'leader_checked', 'leader_checked_by' => $request->user()->id, 'leader_checked_at' => now()]);
        return back()->with('success', 'Problem TPS Tool sudah dicek Leader.');
    }
    public function verify(Request $request, TpsRepairOrder $order)
    {
        abort_unless(in_array($request->user()->role, ['omd_member', 'omd_leader'], true), 403);
        abort_unless($order->status === 'leader_checked', 422);
        $order->update(['status' => 'verified', 'member_verified_by' => $request->user()->id, 'member_verified_at' => now()]);
        return back()->with('success', 'Problem TPS Tool berhasil diverifikasi.');
    }
    public function schedule(Request $request, TpsRepairOrder $order)
    {
        abort_unless($request->user()->role === 'omd_leader', 403);
        $data = $request->validate(['scheduled_at' => ['required', 'date']]);
        abort_unless($order->status === 'verified', 422);
        $order->update(['status' => 'scheduled', 'scheduled_at' => $data['scheduled_at']]);
        return back()->with('success', 'Jadwal repair berhasil disimpan.');
    }
    public function complete(Request $request, TpsRepairOrder $order)
    {
        abort_unless(in_array($request->user()->role, ['omd_member', 'omd_leader'], true), 403);
        $data = $request->validate(['repair_result' => ['required', 'string', 'max:2000']]);
        abort_unless(in_array($order->status, ['scheduled', 'in_repair'], true), 422);
        $order->update(['status' => 'completed', 'repaired_by' => $request->user()->id, 'repaired_at' => now(), 'repair_result' => $data['repair_result']]);
        return back()->with('success', 'Hasil repair TPS Tool berhasil disimpan.');
    }
    public function confirm(Request $request, TpsRepairOrder $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->status === 'completed', 422);
        $order->update(['status' => 'confirmed', 'confirmed_by_user_id' => $request->user()->id, 'confirmed_at' => now()]);
        return back()->with('success', 'Serah terima TPS Tool dikonfirmasi.');
    }
}
