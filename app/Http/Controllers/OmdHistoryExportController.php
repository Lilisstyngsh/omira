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
                    'before' => ['P' => null, 'H' => null, 'C' => null, 'S' => null],
                    'after' => ['P' => null, 'H' => null, 'C' => null, 'S' => null],
                ];
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
            return \Barryvdh\DomPDF\Facade\Pdf::loadView('user.orders.history-export-pdf', compact('order', 'entries', 'handedBy', 'date', 'logoData', 'isClosed', 'userApproval', 'omdApproval', 'approvedAt'))
                ->setPaper('a4', 'portrait')->download($filename . '.pdf');
        }

        // Printable operational form: two symmetrical sections separated by column G.
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Form Repair Box NG');
        $sheet->setShowGridLines(false);
        foreach (['A'=>13,'B'=>24,'C'=>3,'D'=>3,'E'=>3,'F'=>3,'G'=>2,
                  'H'=>13,'I'=>24,'J'=>3,'K'=>3,'L'=>3,'M'=>3] as $col=>$width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $border = \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN;
        $sheet->mergeCells('A1:B3');
        if (is_file($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('A1');
            $drawing->setHeight(40);
            $drawing->setOffsetX(7);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A1', 'AISIN');
        }
        $sheet->mergeCells('C1:I3');
        $sheet->mergeCells('J1:M3');
        $sheet->setCellValue('C1', 'FORM ORDER REPAIR BOX NG');
        $sheet->setCellValueExplicit('J1', (string) $order->order_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->getStyle('A1:M3')->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
        $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('J1')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A1:B3')->getBorders()->getOutline()->setBorderStyle($border);
        $sheet->getStyle('C1:I3')->getBorders()->getOutline()->setBorderStyle($border);
        $sheet->getStyle('J1:M3')->getBorders()->getOutline()->setBorderStyle($border);
        foreach ([1,2,3] as $r) $sheet->getRowDimension($r)->setRowHeight(20);
        $sheet->mergeCells('A5:M5');
        $sheet->mergeCells('A6:M6');
        $sheet->setCellValue('A5', 'Diserahkan oleh: ' . $handedBy);
        $sheet->setCellValue('A6', 'Hari, tanggal: ' . $date);

        // Multi-level column headers. G is intentionally blank as a separator.
        foreach (['A8:F8'=>'PENYERAHAN USER', 'H8:M8'=>'KONFIRMASI PENGEMBALIAN OMD',
                  'A9:A10'=>'Model', 'B9:B10'=>'Produk', 'C9:F9'=>'Jenis NG & Qty',
                  'H9:H10'=>'Model', 'I9:I10'=>'Produk', 'J9:M9'=>'Jenis NG & Qty'] as $range=>$label) {
            $sheet->mergeCells($range);
            $sheet->setCellValue(explode(':', $range)[0], $label);
        }
        foreach (['C'=>'P','D'=>'H','E'=>'C','F'=>'S','J'=>'P','K'=>'H','L'=>'C','M'=>'S'] as $col=>$ng) {
            $sheet->setCellValue($col.'10', $ng);
        }
        foreach (['A8:F10', 'H8:M10'] as $range) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle($border);
            $sheet->getStyle($range)->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
            $sheet->getStyle($range)->getFont()->setBold(true);
        }
        $row = 11;
        $grandBefore = array_fill_keys(['P','H','C','S'],0);
        $grandAfter = array_fill_keys(['P','H','C','S'],0);
        $writeSummary = function (string $label, array $left, array $right, int $r) use ($sheet, $border) {
            $sheet->mergeCells("A{$r}:B{$r}");
            $sheet->mergeCells("H{$r}:I{$r}");
            $sheet->setCellValue("A{$r}", $label);
            $sheet->setCellValue("H{$r}", $label);
            foreach (['P','H','C','S'] as $idx=>$ng) {
                foreach ([[$left,$idx+3],[$right,$idx+10]] as [$values,$index]) {
                    $n = (int) ($values[$ng] ?? 0);
                    if ($n !== 0) $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index).$r, $n);
                }
            }
            foreach (["A{$r}:F{$r}","H{$r}:M{$r}"] as $area) {
                $sheet->getStyle($area)->getBorders()->getAllBorders()->setBorderStyle($border);
                $sheet->getStyle($area)->getFont()->setBold(true);
            }
        };
        $modelCount = count($entries);
        $modelStartRow = $row;
        foreach ($entries as $index=>$entry) {
            if ($index === 0 || $entries[$index-1]['model'] !== $entry['model']) {
                $modelStartRow = $row;
                $sheet->setCellValueExplicit('A'.$row, $entry['model'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('H'.$row, $entry['model'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
            $sheet->setCellValueExplicit('B'.$row, $entry['product'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I'.$row, $entry['product'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            foreach (['P','H','C','S'] as $idx=>$ng) {
                $before = (int) ($entry['before'][$ng] ?? 0);
                $after = (int) ($entry['after'][$ng] ?? 0);
                $grandBefore[$ng] += $before;
                $grandAfter[$ng] += $after;
                if ($before) $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx+3).$row, $before);
                if ($after) $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($idx+10).$row, $after);
            }
            foreach (["A{$row}:F{$row}","H{$row}:M{$row}"] as $area) $sheet->getStyle($area)->getBorders()->getAllBorders()->setBorderStyle($border);
            $row++;
            if ($index === $modelCount-1 || $entries[$index+1]['model'] !== $entry['model']) {
                // Merge only product rows; keep the subtotal row separate.
                $modelEndRow = $row - 1;
                if ($modelEndRow > $modelStartRow) {
                    $sheet->mergeCells("A{$modelStartRow}:A{$modelEndRow}");
                    $sheet->mergeCells("H{$modelStartRow}:H{$modelEndRow}");
                    foreach (['A', 'H'] as $col) {
                        $sheet->getStyle("{$col}{$modelStartRow}:{$col}{$modelEndRow}")
                            ->getAlignment()->setHorizontal('center')->setVertical('center');
                        $sheet->getStyle("{$col}{$modelStartRow}:{$col}{$modelEndRow}")
                            ->getBorders()->getOutline()->setBorderStyle($border);
                    }
                }
            }
        }
        if (!$entries) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->mergeCells("H{$row}:M{$row}");
            $sheet->setCellValue("A{$row}", 'Tidak ada item Repair Order.');
            $sheet->setCellValue("H{$row}", 'Tidak ada item Repair Order.');
            $row++;
        }
        // One subtotal row for the entire order, broken down by NG category.
        $writeSummary('SUB TOTAL', $grandBefore, $grandAfter, $row++);
        // Grand total is one figure across P + H + C + S for each side.
        // Merge the four NG columns to match the operational paper form.
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->mergeCells("C{$row}:F{$row}");
        $sheet->mergeCells("H{$row}:I{$row}");
        $sheet->mergeCells("J{$row}:M{$row}");
        $sheet->setCellValue("A{$row}", 'GRAND TOTAL');
        $sheet->setCellValue("H{$row}", 'GRAND TOTAL');
        if (array_sum($grandBefore) !== 0) $sheet->setCellValue("C{$row}", array_sum($grandBefore));
        if (array_sum($grandAfter) !== 0) $sheet->setCellValue("J{$row}", array_sum($grandAfter));
        foreach (["A{$row}:F{$row}", "H{$row}:M{$row}"] as $area) {
            $sheet->getStyle($area)->getBorders()->getAllBorders()->setBorderStyle($border);
            $sheet->getStyle($area)->getFont()->setBold(true);
        }
        foreach (["C{$row}:F{$row}", "J{$row}:M{$row}"] as $area) {
            $sheet->getStyle($area)->getAlignment()->setHorizontal('center');
        }
        $row++;
        $lastData = $row-1;
        foreach (['A8:F'.$lastData, 'H8:M'.$lastData] as $area) $sheet->getStyle($area)->getBorders()->getOutline()->setBorderStyle($border);
        // Enclose both sections in clear borders, including the left and right edges.
        foreach (['A8:F'.$lastData, 'H8:M'.$lastData] as $range) {
            $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($border);
        }
        $sheet->getStyle('A11:A'.$lastData)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B11:B'.$lastData)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('I11:I'.$lastData)->getAlignment()->setHorizontal('left');
        $sheet->getStyle('H11:H'.$lastData)->getAlignment()->setHorizontal('center');
        foreach (['C11:F'.$lastData,'J11:M'.$lastData] as $range) $sheet->getStyle($range)->getAlignment()->setHorizontal('center');
        $noteRow = $row+2;
        $sheet->mergeCells("A{$noteRow}:M{$noteRow}");
        $sheet->setCellValue("A{$noteRow}", 'Catatan ketidaksesuaian:');
        $noteRow++;
        $sheet->mergeCells("A{$noteRow}:M".($noteRow+2));
        $sheet->getStyle("A{$noteRow}:M".($noteRow+2))->getBorders()->getOutline()->setBorderStyle($border);
        $signRow = $noteRow+5;
        $sheet->mergeCells("A{$signRow}:F{$signRow}");
        $sheet->mergeCells("H{$signRow}:M{$signRow}");
        $sheet->setCellValue("A{$signRow}", 'Diterima oleh (OMD)');
        $sheet->setCellValue("H{$signRow}", 'Diserahkan oleh (User / Line)');
        $sheet->getStyle("A{$signRow}:M{$signRow}")->getAlignment()->setHorizontal('center');
        $sheet->mergeCells('A'.($signRow+1).':F'.($signRow+4));
        $sheet->mergeCells('H'.($signRow+1).':M'.($signRow+4));
        foreach (['A'.$signRow.':F'.($signRow+4),'H'.$signRow.':M'.($signRow+4)] as $area) $sheet->getStyle($area)->getBorders()->getOutline()->setBorderStyle($border);
        if ($isClosed) {
            $sheet->setCellValue('H'.($signRow+1), "DIKONFIRMASI ELEKTRONIK\n".$userApproval."\n".$approvedAt);
            if ($omdApproval) $sheet->setCellValue('A'.($signRow+1), "DIPROSES OLEH OMD\n".$omdApproval);
            foreach (['A','H'] as $col) $sheet->getStyle($col.($signRow+1))->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
        }

        // Full printable form border, from the AISIN header through the signature boxes.
        // Apply after merges so blank spacer cells cannot leave open outer edges.
        $frameBottom = $signRow + 4;
        $sheet->getStyle("A1:M{$frameBottom}")->getBorders()->getOutline()->setBorderStyle($border);
        // Keep the blank separator column G bounded throughout both data sections.
        for ($frameRow = 8; $frameRow <= $lastData; $frameRow++) {
            $sheet->getStyle("G{$frameRow}")->getBorders()->getLeft()->setBorderStyle($border);
            $sheet->getStyle("G{$frameRow}")->getBorders()->getRight()->setBorderStyle($border);
        }
        // Give the notes/signature area a continuous outer frame as well.
        $sheet->getStyle("A{$noteRow}:M".($noteRow+2))->getBorders()->getOutline()->setBorderStyle($border);
        $sheet->getStyle("A{$signRow}:M{$frameBottom}")->getBorders()->getOutline()->setBorderStyle($border);

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4)->setFitToWidth(1)->setFitToHeight(0);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageMargins()->setTop(.35)->setBottom(.35)->setLeft(.2)->setRight(.2);
        $sheet->getPageSetup()->setPrintArea('A1:M'.($signRow+4));
        $temporary = tempnam(sys_get_temp_dir(), 'omira_history_');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($temporary);
        $spreadsheet->disconnectWorksheets();
        return response()->download($temporary, $filename . '.xlsx')->deleteFileAfterSend(true);
    }

}
