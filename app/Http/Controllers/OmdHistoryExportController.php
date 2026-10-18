<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairOrder;

class OmdHistoryExportController extends Controller
{
    public function exportHistory(Request $request, RepairOrder $order, string $format)
    {
        abort_unless($request->user()?->role === 'omd', 403);
        abort_unless(in_array($format, ['pdf', 'excel'], true), 404);

        $order->load(['user', 'line.plant', 'items.masterModel', 'items.product', 'items.ngType', 'result', 'result.processedBy', 'confirmation', 'confirmation.user']);
        $groups = [];
        foreach ($order->items as $item) {
            $model = (string) ($item->masterModel?->model ?? $order->model ?? '-');
            $product = (string) ($item->product?->name ?? '-');
            $key = ($item->master_model_id ?? $model) . ':' . ($item->product_id ?? $product);
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'model' => $model, 'product' => $product,
                    'mismatch_note' => trim((string) ($item->mismatch_note ?? '')),
                    'before' => ['P' => null, 'H' => null, 'C' => null, 'S' => null],
                    'after' => ['P' => null, 'H' => null, 'C' => null, 'S' => null],
                ];
            }
            if (trim((string) ($item->mismatch_note ?? '')) !== '') {
                $groups[$key]['mismatch_note'] = trim((string) $item->mismatch_note);
            }
            $ng = strtoupper((string) ($item->ngType?->code ?? ''));
            if (!in_array($ng, ['P', 'H', 'C', 'S'], true)) continue;
            $groups[$key]['before'][$ng] = ($groups[$key]['before'][$ng] ?? 0) + (int) ($item->before_qty ?? 0);
            $groups[$key]['after'][$ng] = ($groups[$key]['after'][$ng] ?? 0) + (int) ($item->after_qty ?? 0);
        }
        $entries = array_values($groups);
        usort($entries, fn ($a, $b) => [$a['model'], $a['product']] <=> [$b['model'], $b['product']]);
        $handedBy = ($order->user?->name ?? '-') . ' - ' . ($order->line?->name ?? '-');
        $date = ($order->handed_over_at ?? $order->created_at)?->locale('id')->translatedFormat('l, d F Y') ?? '-';
        $logoPath = public_path('images/aisin-form-logo.jpg');
        $logoData = is_file($logoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) : null;
        // Status confirmed is the closed state in this project. Names are approval records, not handwritten signatures.
        $isClosed = $order->status === 'confirmed' && $order->confirmation?->confirmed_at;
        $userApproval = $isClosed ? ($order->confirmation?->user?->name ?? $order->user?->name ?? '-') : null;
        $omdApproval = $isClosed ? ($order->result?->processedBy?->name ?? null) : null;
        $approvedAt = $isClosed ? $order->confirmation->confirmed_at->locale('id')->translatedFormat('d F Y, H:i') : null;
        $filename = 'Form_Order_Repair_Box_' . preg_replace('/[^A-Za-z0-9_-]/', '_', (string) $order->order_number);

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.orders.history-export-pdf', compact('order', 'entries', 'handedBy', 'date', 'logoData', 'isClosed', 'userApproval', 'omdApproval', 'approvedAt'))
                ->setPaper('a4', 'portrait');
            return $request->boolean('preview')
                ? $pdf->stream($filename . '.pdf')
                : $pdf->download($filename . '.pdf');
        }

        return app(\App\Services\RepairOrderHistoryExcelService::class)->download(
            $order, $entries, $filename, (bool) $isClosed, $userApproval, $omdApproval, $approvedAt
        );
    }

}
