<?php
namespace App\Services;

use App\Models\AdditionalBoxRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdditionalBoxExportService
{
    /** Render only real, non-empty request items; maximum five items per form page. */
    private function pages(AdditionalBoxRequest $document): array
    {
        $items = $document->items->filter(static function ($item): bool {
            return (int) $item->quantity > 0
                && (trim((string) $item->model_name) !== '' || trim((string) $item->product_name) !== '');
        })->values();

        $pages = $items->chunk(5)->values()->map(static function ($items, $pageIndex): array {
            return $items->values()->map(static function ($item, $index) use ($pageIndex): array {
                return ['item' => $item, 'number' => $pageIndex * 5 + $index + 1];
            })->all();
        })->all();

        return $pages ?: [[]];
    }

    public function pdf(AdditionalBoxRequest $document)
    {
        $logo = public_path('images/aisin-form-logo.jpg');
        $logoData = is_file($logo) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logo)) : null;
        $pages = $this->pages($document);
        return Pdf::loadView('user.additional-box.pdf', compact('document', 'logoData', 'pages'))
            ->setPaper('a4', 'portrait')->download($document->document_number . '.pdf');
    }

    public function excel(AdditionalBoxRequest $document)
    {
        $book = new Spreadsheet();
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        foreach ($this->pages($document) as $page => $rows) {
            $sheet = $page === 0 ? $book->getActiveSheet() : $book->createSheet();
            $sheet->setTitle('Form ' . ($page + 1));
            $sheet->setShowGridlines(false);
            $sheet->setPrintGridlines(false);
            $sheet->getSheetView()->setView('normal');
            $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4)
                ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                ->setFitToPage(true)->setFitToWidth(1)->setFitToHeight(1);
            $sheet->getPageMargins()->setTop(0.3)->setBottom(0.3)->setLeft(0.3)->setRight(0.3);
            $sheet->getHeaderFooter()->setOddHeader('')->setOddFooter('');
            $sheet->getColumnDimension('A')->setWidth(9);
            $sheet->getColumnDimension('B')->setWidth(21);
            foreach (range(3,16) as $col) $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(6.2);
            $sheet->getDefaultRowDimension()->setRowHeight(15);
            $sheet->getStyle('A1:P45')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)->setWrapText(true);
            $box = function (string $range, string $text = '', ?string $fill = null, int $size = 10, bool $bold = false, string $alignment = Alignment::HORIZONTAL_LEFT) use ($sheet) {
                if (str_contains($range, ':') && explode(':', $range)[0] !== explode(':', $range)[1]) $sheet->mergeCells($range);
                $cell = explode(':', $range)[0];
                // User/master data is always text, never an Excel formula.
                $sheet->setCellValueExplicit($cell, $text, DataType::TYPE_STRING);
                $style = $sheet->getStyle($range);
                $style->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF000000');
                $style->getAlignment()->setHorizontal($alignment);
                $style->getFont()->setSize($size)->setBold($bold);
                if ($fill) $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $fill);
                if ($fill === '195F7B') $style->getFont()->getColor()->setARGB('FFFFFFFF');
            };
            $box('A1:B5', '', null, 10, false, Alignment::HORIZONTAL_CENTER);
            $box('C1:L5', "FORM REQUEST ADDITIONAL\nBOX", null, 23, true, Alignment::HORIZONTAL_CENTER);
            $box('M1:P5', "OPERATION\nMANAGEMENT\nDEVELOPMENT DEPT.", null, 11, true, Alignment::HORIZONTAL_CENTER);
            $logo = public_path('images/aisin-form-logo.jpg');
            if (is_file($logo)) {
                $drawing = new Drawing();
                $drawing->setName('AISIN')->setPath($logo)->setHeight(40)->setCoordinates('A2')->setOffsetX(16)->setOffsetY(4)->setWorksheet($sheet);
            } else $sheet->setCellValueExplicit('A1', 'AISIN', DataType::TYPE_STRING);
            $box('L7:P7', 'Date : ' . $document->created_at->format('d-m-Y'));
            $sheet->getStyle('L7:P7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            foreach (['A9'=>'NO','B9'=>'TYPE BOX','C9:F9'=>'MARKING','G9:M9'=>'PURPOSE','N9:P9'=>'QTY'] as $range=>$title) {
                $box($range, $title, '195F7B', 11, true, Alignment::HORIZONTAL_CENTER);
            }
            $sheet->getRowDimension(9)->setRowHeight(22);
            $row = 10;
            foreach ($rows as $line) {
                $entry = $line['item'];
                $typeBox = trim((string) $entry->model_name . ' — ' . (string) $entry->product_name, ' —');
                // Standard Excel height for ordinary items; only expand genuinely wrapped names.
                $lines = max(1, (int) ceil(mb_strwidth($typeBox) / 19));
                $sheet->getRowDimension($row)->setRowHeight(max(15, $lines * 14));
                $box('A'.$row);
                $sheet->setCellValue('A'.$row, $line['number']);
                $box('B'.$row, $typeBox, null, 9);
                $box('C'.$row.':F'.$row);
                $box('G'.$row.':M'.$row, (string) $document->purpose);
                $box('N'.$row.':P'.$row);
                $sheet->setCellValue('N'.$row, (int) $entry->quantity);
                $row++;
            }

            // Move all following sections up so no unused item-table rows remain.
            $approvalHeader = $row + 1;
            $approvalStart = $approvalHeader + 1;
            $approvalEnd = $approvalHeader + 4;
            $approvalLabel = $approvalEnd + 1;
            foreach (['H:J'=>['APPROVED','GM'],'K:M'=>['CHECKED','Ka. Dept'],'N:P'=>['PREPARED','Ka. Sie']] as $columns=>$labels) {
                [$left,$right] = explode(':',$columns);
                $box($left.$approvalHeader.':'.$right.$approvalHeader, $labels[0], null, 10, false, Alignment::HORIZONTAL_CENTER);
                $box($left.$approvalStart.':'.$right.$approvalEnd);
                $box($left.$approvalLabel.':'.$right.$approvalLabel, $labels[1], null, 10, false, Alignment::HORIZONTAL_CENTER);
            }
            $scheduleTitle = $approvalLabel + 2;
            $scheduleHeader = $scheduleTitle + 1;
            $weekHeader = $scheduleTitle + 2;
            $activityStart = $scheduleTitle + 3;
            $activityEnd = $activityStart + 4;
            $noteStart = $activityEnd + 3;
            $noteEnd = $noteStart + 3;
            $box('A'.$scheduleTitle.':P'.$scheduleTitle, 'SCHEDULE ADDITIONAL BOX', '195F7B', 15, true, Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($scheduleTitle)->setRowHeight(22);
            $box('A'.$scheduleHeader.':B'.$weekHeader, 'ACTIVITY', 'C0C0C0', 12, true, Alignment::HORIZONTAL_CENTER);
            $box('C'.$scheduleHeader.':P'.$scheduleHeader, 'LEAD TIME (IN WEEK)', 'C0C0C0', 13, true, Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($scheduleHeader)->setRowHeight(21);
            for ($week=1; $week<=14; $week++) {
                $col = Coordinate::stringFromColumnIndex($week+2);
                $box($col.$weekHeader, (string) $week, null, 10, false, Alignment::HORIZONTAL_CENTER);
                foreach (range($activityStart, $activityEnd) as $activityRow) $box($col.$activityRow);
            }
            foreach (range($activityStart, $activityEnd) as $activityRow) $box('A'.$activityRow.':B'.$activityRow);
            $box('A'.$noteStart.':F'.$noteEnd, "Note:\nLEAD TIME TERHITUNG SETELAH PENGAJUAN DISETUJUI", '98DDF1', 12, true);
            $box('K'.$noteStart.':P'.$noteStart, 'CONFIRMED', null, 10, false, Alignment::HORIZONTAL_CENTER);
            $box('K'.($noteStart+1).':M'.($noteEnd+2));
            $box('N'.($noteStart+1).':P'.($noteEnd+2));
            $lastRow = $noteEnd + 3;
            $box('K'.$lastRow.':M'.$lastRow, 'Ka. Dept OMC', null, 10, false, Alignment::HORIZONTAL_CENTER);
            $box('N'.$lastRow.':P'.$lastRow, 'Ka. Sie OMC', null, 10, false, Alignment::HORIZONTAL_CENTER);
            $sheet->getPageSetup()->setPrintArea('A1:P'.$lastRow);
        }
        $book->setActiveSheetIndex(0);
        return response()->streamDownload(function () use ($book) {
            try { (new Xlsx($book))->save('php://output'); }
            finally { $book->disconnectWorksheets(); }
        }, $document->document_number . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
