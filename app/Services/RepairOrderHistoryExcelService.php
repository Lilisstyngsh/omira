<?php

namespace App\Services;

use App\Models\RepairOrder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/** Excel export that mirrors the approved operational A4 portrait PDF layout. */
class RepairOrderHistoryExcelService
{
    public function download(RepairOrder $order, array $entries, string $filename, bool $isClosed, ?string $userApproval, ?string $omdApproval, ?string $approvedAt)
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Form Repair Box NG');
        $sheet->setShowGridLines(false);
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(8);
        $thin = Border::BORDER_THIN;
        $greenGrey = 'FFF3F5F7';

        // Six columns per table, one narrow gutter between tables.
        foreach (['A'=>14,'B'=>20,'C'=>6,'D'=>6,'E'=>6,'F'=>6,'G'=>2.5,
                  'H'=>14,'I'=>20,'J'=>6,'K'=>6,'L'=>6,'M'=>6] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $sheet->getDefaultRowDimension()->setRowHeight(12);
        foreach ([1=>18,2=>9,3=>13] as $r=>$h) $sheet->getRowDimension($r)->setRowHeight($h);

        // Header: logo / form title / PSD-OMD order block.
        $sheet->mergeCells('A1:C3');
        $sheet->mergeCells('D1:J3');
        $sheet->mergeCells('K1:M2');
        $sheet->mergeCells('K3:M3');
        $sheet->setCellValue('D1', 'FORM ORDER REPAIR BOX NG');
        $sheet->setCellValue('K1', 'PSD - OMD');
        $sheet->setCellValueExplicit('K3', (string) $order->order_number, DataType::TYPE_STRING);
        $logoPath = public_path('images/aisin-form-logo.jpg');
        if (is_file($logoPath)) {
            $drawing = new Drawing();
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('A1');
            // Preserve the logo's original 517:163 ratio and center it inside the left header block.
            $drawing->setHeight(47);
            $drawing->setOffsetX(65);
            $drawing->setOffsetY(3);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A1', 'AISIN');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        }
        $sheet->getStyle('A1:M3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('K1')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('K3')->getFont()->setSize(8);
        foreach (['A1:C3','D1:J3','K1:M2','K3:M3'] as $range) {
            $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($thin);
        }

        $sheet->mergeCells('A5:M5');
        $sheet->mergeCells('A6:M6');
        $sheet->setCellValue('A5', 'Nama User : '.($order->user?->name ?? '-').' / '.($order->line?->name ?? '-'));
        $sheet->setCellValue('A6', 'Hari/Tanggal Order : '.($order->created_at?->locale('id')->translatedFormat('l, d F Y') ?? '-'));
        $sheet->getStyle('A5:A6')->getFont()->setBold(true)->setSize(8);
        $sheet->getRowDimension(5)->setRowHeight(13);
        $sheet->getRowDimension(6)->setRowHeight(13);

        // Side-by-side tables without a NO column and without the side notes column.
        $sheet->mergeCells('A9:F9');
        $sheet->setCellValue('A9', 'DI ISI OLEH USER (SEBELUM REPAIR)');
        $sheet->mergeCells('H9:M9');
        $sheet->setCellValue('H9', 'DI ISI OLEH OMD (SETELAH REPAIR)');
        foreach (['A10:A11'=>'MODEL','B10:B11'=>'PRODUK','C10:F10'=>'JENIS NG & QTY',
                  'H10:H11'=>'MODEL','I10:I11'=>'PRODUK','J10:M10'=>'JENIS NG & QTY'] as $range=>$label) {
            $sheet->mergeCells($range);
            $sheet->setCellValue(explode(':', $range)[0], $label);
        }
        foreach (['C'=>'P','D'=>'H','E'=>'C','F'=>'S','J'=>'P','K'=>'H','L'=>'C','M'=>'S'] as $col=>$code) {
            $sheet->setCellValue($col.'11', $code);
        }
        foreach (['A9:F11','H9:M11'] as $range) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        }
        $sheet->getStyle('A9:M11')->getFont()->setBold(true)->setSize(7);
        $sheet->getRowDimension(9)->setRowHeight(13);
        $sheet->getRowDimension(10)->setRowHeight(16);
        $sheet->getRowDimension(11)->setRowHeight(11);

        $firstRow = 12;
        $row = $firstRow;
        $sumsLeft = array_fill_keys(['P','H','C','S'], 0);
        $sumsRight = array_fill_keys(['P','H','C','S'], 0);
        $modelStarts = [];
        foreach ($entries as $idx=>$entry) {
            foreach (['A','H'] as $col) $sheet->setCellValueExplicit($col.$row, (string) $entry['model'], DataType::TYPE_STRING);
            foreach (['B','I'] as $col) $sheet->setCellValueExplicit($col.$row, (string) $entry['product'], DataType::TYPE_STRING);
            foreach (['P','H','C','S'] as $i=>$ng) {
                $before = (int) ($entry['before'][$ng] ?? 0);
                $after = (int) ($entry['after'][$ng] ?? 0);
                $sumsLeft[$ng] += $before;
                $sumsRight[$ng] += $after;
                if ($before !== 0) $sheet->setCellValue(chr(ord('C')+$i).$row, $before);
                if ($after !== 0) $sheet->setCellValue(chr(ord('J')+$i).$row, $after);
            }
            if ($idx === 0 || ($entries[$idx-1]['model'] ?? null) !== $entry['model']) $modelStarts[] = $row;
            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle("H{$row}:M{$row}")->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle("A{$row}:M{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A{$row}:A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$row}:H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$row}:M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("I{$row}:I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getRowDimension($row)->setRowHeight(12);
            $row++;
        }
        if (!$entries) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->mergeCells("H{$row}:M{$row}");
            $sheet->setCellValue("A{$row}", 'Tidak ada item.');
            $sheet->setCellValue("H{$row}", 'Tidak ada item.');
            foreach (["A{$row}:F{$row}", "H{$row}:M{$row}"] as $range) $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($thin);
            $row++;
        } else {
            $modelStarts[] = $row;
            for ($i=0; $i<count($modelStarts)-1; $i++) {
                $a=$modelStarts[$i]; $b=$modelStarts[$i+1]-1;
                if ($b>$a) {
                    foreach (['A','H'] as $col) {
                        $sheet->mergeCells("{$col}{$a}:{$col}{$b}");
                        $sheet->getStyle("{$col}{$a}:{$col}{$b}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }
            }
        }

        foreach ([['SUB TOTAL',$sumsLeft,$sumsRight], ['GRAND TOTAL',['total'=>array_sum($sumsLeft)],['total'=>array_sum($sumsRight)]]] as [$label,$before,$after]) {
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->mergeCells("H{$row}:I{$row}");
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("H{$row}", $label);
            if ($label === 'SUB TOTAL') {
                foreach (['P','H','C','S'] as $i=>$ng) {
                    $sheet->setCellValue(chr(ord('C')+$i).$row, $before[$ng] ?: '');
                    $sheet->setCellValue(chr(ord('J')+$i).$row, $after[$ng] ?: '');
                }
            } else {
                $sheet->mergeCells("C{$row}:F{$row}");
                $sheet->mergeCells("J{$row}:M{$row}");
                $sheet->setCellValue("C{$row}", $before['total'] ?: '');
                $sheet->setCellValue("J{$row}", $after['total'] ?: '');
            }
            foreach (["A{$row}:F{$row}", "H{$row}:M{$row}"] as $range) {
                $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle($thin);
                $sheet->getStyle($range)->getFont()->setBold(true)->setSize(7);
                $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($greenGrey);
            }
            $sheet->getStyle("C{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$row}:M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(12);
            $row++;
        }

        // Lower block: legend on left, single notes area on right; no side-note column.
        $legendHeader = $row + 2;
        $sheet->mergeCells("A{$legendHeader}:C{$legendHeader}");
        $sheet->setCellValue("A{$legendHeader}", 'LEGEND :');
        $sheet->getStyle("A{$legendHeader}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$legendHeader}:C{$legendHeader}")->getFont()->setBold(true)->setSize(8);
        $legendRows = [
            ['P','NG Partisi'], ['H','NG Holder Kanban'], ['C','NG Cover'], ['S','Box Scrap/Pecah'],
        ];
        $lr = $legendHeader;
        foreach ($legendRows as [$code,$desc]) {
            $lr++;
            $sheet->setCellValue("A{$lr}", $code);
            $sheet->mergeCells("B{$lr}:C{$lr}");
            $sheet->setCellValue("B{$lr}", $desc);
            $sheet->getStyle("A{$lr}:C{$lr}")->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle("A{$lr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$lr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getRowDimension($lr)->setRowHeight(14);
        }
        $sheet->getStyle("A{$legendHeader}:C{$lr}")->getBorders()->getOutline()->setBorderStyle($thin);

        $notesHeader = $legendHeader;
        $sheet->mergeCells("H{$notesHeader}:M{$notesHeader}");
        $sheet->setCellValue("H{$notesHeader}", 'Catatan Ketidaksesuaian');
        $sheet->getStyle("H{$notesHeader}:M{$notesHeader}")->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle("H{$notesHeader}")->getFont()->setBold(true)->setSize(8);
        $sheet->getStyle("H{$notesHeader}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($notesHeader)->setRowHeight(15);
        $noteBodyEnd = $notesHeader + 5;
        $sheet->mergeCells("H".($notesHeader+1).":M{$noteBodyEnd}");
        // Use OMD's per-product mismatch_note only. Do not include user feedback.
        // Notes are stored on repair_order_items, while $entries contains only aggregated quantities.
        $mismatchLines = [];
        $items = $order->items()->with(['masterModel', 'product'])->get();
        foreach ($items as $item) {
            $note = trim((string) ($item->mismatch_note ?? ''));
            if ($note === '') {
                continue;
            }

            $modelName = (string) ($item->masterModel?->model ?? $item->product?->model_name ?? $order->model ?? '');
            $productName = (string) ($item->product?->name ?? '');
            $label = trim($modelName . ($productName !== '' ? ' - ' . $productName : ''), ' -');
            $line = ($label !== '' ? $label . ': ' : '') . $note;
            if (!in_array($line, $mismatchLines, true)) {
                $mismatchLines[] = $line;
            }
        }
        $sheet->setCellValue("H".($notesHeader+1), implode("\n", $mismatchLines));
        $sheet->getStyle("H".($notesHeader+1).":M{$noteBodyEnd}")->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle("H".($notesHeader+1))->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        for ($r=$notesHeader+1; $r<=$noteBodyEnd; $r++) $sheet->getRowDimension($r)->setRowHeight(12);

        $sign = max($lr, $noteBodyEnd) + 3;
        $signatureBlocks = [
            ['A','B','Diserahkan oleh:','PPIC',$order->user?->name],
            ['C','F','Diterima oleh:','OMD',$omdApproval],
            ['H','I','Diserahkan oleh:','OMD',$omdApproval],
            ['J','M','Diterima oleh:','PPIC',$userApproval],
        ];
        foreach ($signatureBlocks as [$from,$to,$head,$role,$name]) {
            $sheet->mergeCells("{$from}{$sign}:{$to}{$sign}");
            $sheet->setCellValue("{$from}{$sign}", $head);
            $sheet->mergeCells("{$from}".($sign+1).":{$to}".($sign+4));
            if ($isClosed && $name) $sheet->setCellValue("{$from}".($sign+1), "DIKONFIRMASI ELEKTRONIK\n".$name.($approvedAt ? "\n".$approvedAt : ''));
            $sheet->mergeCells("{$from}".($sign+5).":{$to}".($sign+5));
            $sheet->setCellValue("{$from}".($sign+5), $role);
            $range = "{$from}{$sign}:{$to}".($sign+5);
            $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($thin);
            $sheet->getStyle("{$from}{$sign}:{$to}{$sign}")->getBorders()->getBottom()->setBorderStyle($thin);
            $sheet->getStyle("{$from}".($sign+5).":{$to}".($sign+5))->getBorders()->getTop()->setBorderStyle($thin);
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle("{$from}{$sign}")->getFont()->setBold(true)->setSize(7);
            $sheet->getStyle("{$from}".($sign+5))->getFont()->setBold(true)->setSize(7);
        }
        for ($r=$sign+1; $r<=$sign+4; $r++) $sheet->getRowDimension($r)->setRowHeight(13);
        $sheet->getRowDimension($sign)->setRowHeight(15);
        $sheet->getRowDimension($sign+5)->setRowHeight(14);

        $formRow = $sign + 8;
        $sheet->mergeCells("A{$formRow}:C{$formRow}");
        $sheet->setCellValue("A{$formRow}", 'FRM-PSD-P3-057-00');
        $sheet->getStyle("A{$formRow}:C{$formRow}")->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle("A{$formRow}:C{$formRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($formRow)->setRowHeight(16);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(1);
        $sheet->getPageMargins()->setTop(.25)->setBottom(.25)->setLeft(.25)->setRight(.25);
        $sheet->getPageSetup()->setPrintArea('A1:M'.$formRow);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getSheetView()->setZoomScale(90);

        $temporary = tempnam(sys_get_temp_dir(), 'omira_form_');
        (new Xlsx($book))->save($temporary);
        $book->disconnectWorksheets();
        return response()->download($temporary, $filename.'.xlsx')->deleteFileAfterSend(true);
    }
}
