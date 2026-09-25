<?php

namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\RepairResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OmdOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = RepairOrder::with(['user', 'area', 'product', 'masterModel', 'ngType', 'result'])->latest('order_date')->latest()->paginate(15);
        return view('omd.orders.index', compact('orders'));
    }

    public function show(RepairOrder $order)
    {
        $order->load(['user', 'area', 'product', 'masterModel', 'ngType', 'result', 'confirmation', 'omdVerifier']);
        return view('omd.orders.show', compact('order'));
    }

    public function verify(Request $request, RepairOrder $order)
    {
        abort_unless($order->status === 'submitted', 422, 'Order tidak berada pada status Submitted.');
        $order->update(['status' => 'verified', 'verified_by' => $request->user()->id, 'verified_at' => now()]);
        return back()->with('success', 'Order berhasil diverifikasi.');
    }

    public function startRepair(RepairOrder $order)
    {
        abort_unless($order->status === 'verified', 422, 'Order belum diverifikasi.');
        $order->update(['status' => 'in_repair', 'repair_started_at' => now()]);
        return back()->with('success', 'Order masuk proses repair.');
    }

    public function complete(Request $request, RepairOrder $order)
    {
        abort_unless(in_array($order->status, ['verified', 'in_repair'], true), 422, 'Order belum dapat diselesaikan.');
        $data = $request->validate([
            'ok_qty' => ['required', 'integer', 'min:0'],
            'scrap_qty' => ['required', 'integer', 'min:0'],
            'ng_qty' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $sum = $data['ok_qty'] + $data['scrap_qty'] + $data['ng_qty'];
        if ($sum > $order->quantity) {
            return back()->withErrors(['ok_qty' => 'Total hasil repair tidak boleh melebihi quantity order.'])->withInput();
        }

        DB::transaction(function () use ($request, $order, $data) {
            $order->update(['status' => 'completed', 'repair_completed_at' => now()]);
            $order->result()->updateOrCreate([], [
                'ok_qty' => $data['ok_qty'],
                'scrap_qty' => $data['scrap_qty'],
                'ng_qty' => $data['ng_qty'],
                'notes' => $data['notes'] ?? null,
                'processed_by' => $request->user()->id,
            ]);
        });

        return back()->with('success', 'Hasil repair berhasil disimpan.');
    }
}
