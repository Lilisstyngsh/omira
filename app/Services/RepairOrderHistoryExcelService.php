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

/** Single print template used by User and OMD history exports. */
class RepairOrderHistoryExcelService
{
    public function download(RepairOrder $order, array $entries, string $filename, bool $isClosed, ?string $userApproval, ?string $omdApproval, ?string $approvedAt)
    {
        $book = new Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Form Repair Box NG');
        $sheet->setShowGridLines(false);
        $sheet->getDefaultRowDimension()->setRowHeight(19);
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $thin = Border::BORDER_THIN;
        foreach (['A'=>5,'B'=>15,'C'=>23,'D'=>6,'E'=>6,'F'=>6,'G'=>6,'H'=>2.5,
                  'I'=>5,'J'=>15,'K'=>23,'L'=>6,'M'=>6,'N'=>6,'O'=>6,'P'=>26] as $col=>$width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        $sheet->mergeCells('A1:C3');
        $logo = public_path('images/aisin-form-logo.jpg');
        if (is_file($logo)) {
            $drawing = new Drawing();
            $drawing->setPath($logo);
            $drawing->setCoordinates('A1');
            $drawing->setHeight(43);
            $drawing->setOffsetX(8);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue('A1','AISIN');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(17);
        }
        $sheet->mergeCells('D1:M3');
        $sheet->setCellValue('D1','FORM ORDER REPAIR BOX NG');
        $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(17);
        $sheet->mergeCells('N1:P2');
        $sheet->setCellValue('N1','PSD - OMD');
        $sheet->getStyle('N1')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('N3:P3');
        $sheet->setCellValue('N3','FRM-PSD-P3-057-00');
        $sheet->getStyle('A1:P3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:C3')->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle('D1:M3')->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle('N1:P2')->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle('N3:P3')->getBorders()->getOutline()->setBorderStyle($thin);
        foreach ([1,2,3] as $r) $sheet->getRowDimension($r)->setRowHeight(19);
        $sheet->mergeCells('A5:P5');
        $sheet->mergeCells('A6:P6');
        $sheet->mergeCells('A7:P7');
        $sheet->setCellValue('A5', 'Nama User : '.($order->user?->name ?? '-').' / '.($order->line?->name ?? '-'));
        $sheet->setCellValue('A6', 'Hari/Tanggal Order : '.($order->created_at?->locale('id')->translatedFormat('l, d F Y') ?? '-'));
        $sheet->setCellValueExplicit('A7','No. Order : '.(string)$order->order_number,DataType::TYPE_STRING);
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);
        foreach (['A9:G9'=>'DI ISI OLEH USER (SEBELUM REPAIR)','I9:P9'=>'DI ISI OLEH OMD (SETELAH REPAIR)'] as $range=>$label) {
            $sheet->mergeCells($range);$sheet->setCellValue(explode(':',$range)[0],$label);
            $sheet->getStyle($range)->getFont()->setBold(true)->setSize(10);
        }
        foreach (['A10:A11'=>'NO','B10:B11'=>'MODEL','C10:C11'=>'PRODUK','D10:G10'=>'JENIS NG & QTY',
                  'I10:I11'=>'NO','J10:J11'=>'MODEL','K10:K11'=>'PRODUK','L10:O10'=>'JENIS NG & QTY','P10:P11'=>'Catatan Ketidaksesuaian'] as $range=>$label) {
            $sheet->mergeCells($range);$sheet->setCellValue(explode(':',$range)[0],$label);
        }
        foreach (['D'=>'P','E'=>'H','F'=>'C','G'=>'S','L'=>'P','M'=>'H','N'=>'C','O'=>'S'] as $col=>$code) $sheet->setCellValue($col.'11',$code);
        foreach (['A9:G11','I9:P11'] as $range) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        }
        $sheet->getStyle('A9:P11')->getFont()->setBold(true);
        $sheet->getRowDimension(10)->setRowHeight(27);
        $feedbacks = $order->feedbacks()->orderBy('created_at')->pluck('reason')->filter()->values()->all();
        $note = implode("\n",array_unique(array_map('strval',$feedbacks)));
        $firstRow=12;
        $entryCount=max(1,count($entries));
        $row=$firstRow;
        $sumsLeft=array_fill_keys(['P','H','C','S'],0);
        $sumsRight=array_fill_keys(['P','H','C','S'],0);
        $starts=[];
        foreach ($entries as $idx=>$entry) {
            foreach (['A','I'] as $col) $sheet->setCellValue($col.$row,$idx+1);
            foreach (['B','J'] as $col) $sheet->setCellValueExplicit($col.$row,(string)$entry['model'],DataType::TYPE_STRING);
            foreach (['C','K'] as $col) $sheet->setCellValueExplicit($col.$row,(string)$entry['product'],DataType::TYPE_STRING);
            foreach (['P','H','C','S'] as $idxNg=>$ng) {
                $left=(int)($entry['before'][$ng]??0);$right=(int)($entry['after'][$ng]??0);
                $sumsLeft[$ng]+=$left;$sumsRight[$ng]+=$right;
                if ($left) $sheet->setCellValue(chr(ord('D')+$idxNg).$row,$left);
                if ($right) $sheet->setCellValue(chr(ord('L')+$idxNg).$row,$right);
            }
            if ($idx===0 || $entries[$idx-1]['model']!==$entry['model']) $starts[]=$row;
            $sheet->getStyle("A{$row}:G{$row}")->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle("I{$row}:P{$row}")->getBorders()->getAllBorders()->setBorderStyle($thin);
            $sheet->getStyle("A{$row}:P{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$row}:J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("L{$row}:O{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(21);
            $row++;
        }
        if (!$entries) {
            $sheet->mergeCells("A{$row}:G{$row}");$sheet->mergeCells("I{$row}:P{$row}");
            $sheet->setCellValue("A{$row}",'Tidak ada item.');$sheet->setCellValue("I{$row}",'Tidak ada item.');
            foreach (["A{$row}:G{$row}","I{$row}:P{$row}"] as $range) $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($thin);
            $row++;
        } else {
            $starts[]=$row;
            for ($i=0;$i<count($starts)-1;$i++) {
                $a=$starts[$i];$b=$starts[$i+1]-1;
                if ($b>$a) {
                    $sheet->mergeCells("B{$a}:B{$b}");$sheet->mergeCells("J{$a}:J{$b}");
                    foreach (['B','J'] as $col) $sheet->getStyle("{$col}{$a}:{$col}{$b}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        // The right-hand notes column remains in the OMD table; full feedback is also provided below.
        if ($row - 1 > $firstRow) $sheet->mergeCells("P{$firstRow}:P".($row-1));
        if ($note!=='') $sheet->setCellValue("P{$firstRow}",mb_strimwidth($note,0,220,'…'));
        $sheet->getStyle("P{$firstRow}:P".($row-1))->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
        foreach ([['SUB TOTAL',$sumsLeft,$sumsRight],['GRAND TOTAL',['total'=>array_sum($sumsLeft)],['total'=>array_sum($sumsRight)]]] as [$label,$before,$after]) {
            $sheet->mergeCells("A{$row}:C{$row}");$sheet->mergeCells("I{$row}:K{$row}");
            $sheet->setCellValue("A{$row}",$label);$sheet->setCellValue("I{$row}",$label);
            if ($label==='SUB TOTAL') {
                foreach (['P','H','C','S'] as $i=>$ng) {
                    $sheet->setCellValue(chr(68+$i).$row,$before[$ng]);
                    $sheet->setCellValue(chr(76+$i).$row,$after[$ng]);
                }
            } else {
                $sheet->mergeCells("D{$row}:G{$row}");$sheet->mergeCells("L{$row}:O{$row}");
                $sheet->setCellValue("D{$row}",$before['total']);$sheet->setCellValue("L{$row}",$after['total']);
            }
            foreach (["A{$row}:G{$row}","I{$row}:P{$row}"] as $area) {
                $sheet->getStyle($area)->getBorders()->getAllBorders()->setBorderStyle($thin);
                $sheet->getStyle($area)->getFont()->setBold(true);
                $sheet->getStyle($area)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3F5F7');
            }
            $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("L{$row}:O{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }
        $legend=$row+2;
        $sheet->mergeCells("A{$legend}:C{$legend}");$sheet->setCellValue("A{$legend}",'LEGEND :');
        foreach (['P'=>'NG Partisi','H'=>'NG Holder Kanban','C'=>'NG Cover','S'=>'Box Scrap/Pecah'] as $key=>$value) {
            $legend++;$sheet->setCellValue("A{$legend}",$key);$sheet->mergeCells("B{$legend}:C{$legend}");$sheet->setCellValue("B{$legend}",$value);
            $sheet->getStyle("A{$legend}:C{$legend}")->getBorders()->getAllBorders()->setBorderStyle($thin);
        }
        $sheet->getStyle('A'.($row+2).':C'.$legend)->getBorders()->getOutline()->setBorderStyle($thin);
        $notesHeader=$row+2;
        $sheet->mergeCells("I{$notesHeader}:P{$notesHeader}");
        $sheet->setCellValue("I{$notesHeader}",'Catatan Ketidaksesuaian');
        $sheet->getStyle("I{$notesHeader}:P{$notesHeader}")->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle("I{$notesHeader}")->getFont()->setBold(true);
        $sheet->mergeCells('I'.($notesHeader+1).':P'.($notesHeader+5));
        $sheet->setCellValue('I'.($notesHeader+1),$note);
        $sheet->getStyle('I'.($notesHeader+1).':P'.($notesHeader+5))->getBorders()->getOutline()->setBorderStyle($thin);
        $sheet->getStyle('I'.($notesHeader+1))->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
        $sign=max($legend,$notesHeader+5)+3;
        foreach ([['A','C','Diserahkan oleh:','PPIC',$order->user?->name],['D','G','Diterima oleh:','OMD',$omdApproval],
                  ['I','K','Diserahkan oleh:','OMD',$omdApproval],['L','P','Diterima oleh:','PPIC',$userApproval]] as [$from,$to,$head,$role,$name]) {
            $sheet->mergeCells("{$from}{$sign}:{$to}{$sign}");$sheet->setCellValue("{$from}{$sign}",$head);
            $sheet->mergeCells($from.($sign+1).':'.$to.($sign+4));
            if ($isClosed && $name) $sheet->setCellValue($from.($sign+1),"DIKONFIRMASI ELEKTRONIK\n".$name.($approvedAt?"\n".$approvedAt:''));
            $sheet->mergeCells($from.($sign+5).':'.$to.($sign+5));$sheet->setCellValue($from.($sign+5),$role);
            $range=$from.$sign.':'.$to.($sign+5);
            $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle($thin);
            $sheet->getStyle($from.$sign.':'.$to.$sign)->getBorders()->getBottom()->setBorderStyle($thin);
            $sheet->getStyle($from.($sign+5).':'.$to.($sign+5))->getBorders()->getTop()->setBorderStyle($thin);
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle($from.$sign)->getFont()->setBold(true);
            $sheet->getStyle($from.($sign+5))->getFont()->setBold(true);
        }
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)->setPaperSize(PageSetup::PAPERSIZE_A3)->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(1);
        $sheet->getPageMargins()->setTop(.25)->setBottom(.25)->setLeft(.2)->setRight(.2);
        $sheet->getPageSetup()->setPrintArea('A1:P'.($sign+5));
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getSheetView()->setZoomScale(80);
        $temporary=tempnam(sys_get_temp_dir(),'omira_form_');
        (new Xlsx($book))->save($temporary);
        $book->disconnectWorksheets();
        return response()->download($temporary,$filename.'.xlsx')->deleteFileAfterSend(true);
    }
}
