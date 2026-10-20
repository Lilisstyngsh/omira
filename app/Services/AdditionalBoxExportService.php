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
    /** Restore the reference PDF: real rows only, at most five per page.
     * Excel continues using its existing page/row layout unchanged.
     */
    private function pdfPages(AdditionalBoxRequest $document): array
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

    /** Preview and download share the exact same PDF template and data. */
    private function renderPdf(AdditionalBoxRequest $document)
    {
        $logo = public_path('images/aisin-form-logo.jpg');
        $logoData = is_file($logo) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logo)) : null;
        $pages = $this->pdfPages($document);
        return Pdf::loadView('user.additional-box.pdf', compact('document', 'logoData', 'pages'))
            ->setPaper('a4', 'portrait');
    }

    public function pdf(AdditionalBoxRequest $document)
    {
        return $this->renderPdf($document)->download($document->document_number . '.pdf');
    }

    public function previewPdf(AdditionalBoxRequest $document)
    {
        return $this->renderPdf($document)->stream($document->document_number . '.pdf');
    }

    /** Excel uses real request items only; its layout is independent of PDF pagination. */
    public function excel(AdditionalBoxRequest $document)
    {
        $book = new Spreadsheet();
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);

        foreach ($this->pdfPages($document) as $page => $rows) {
            $sheet = $page === 0 ? $book->getActiveSheet() : $book->createSheet();
            $sheet->setTitle('Form ' . ($page + 1));
            $sheet->setShowGridlines(false);
            $sheet->setPrintGridlines(false);
            $sheet->getSheetView()->setView('normal');
            $sheet->getPageSetup()
                ->setPaperSize(PageSetup::PAPERSIZE_A4)
                ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                ->setFitToPage(true)
                ->setFitToWidth(1)
                ->setFitToHeight(1);
            $sheet->getPageMargins()->setTop(0.28)->setBottom(0.28)->setLeft(0.28)->setRight(0.28);
            $sheet->getHeaderFooter()->setOddHeader('')->setOddFooter('');

            $sheet->getColumnDimension('A')->setWidth(6.5); // NO
            $sheet->getColumnDimension('B')->setWidth(29);  // TYPE BOX
            for ($col = 3; $col <= 16; $col++) {
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(5.8);
            }
            $sheet->getDefaultRowDimension()->setRowHeight(15);
            $sheet->getStyle('A1:P45')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $box = function (
                string $range,
                string $text = '',
                ?string $fill = null,
                int $size = 9,
                bool $bold = false,
                string $alignment = Alignment::HORIZONTAL_CENTER,
                bool $wrap = false
            ) use ($sheet): void {
                if (str_contains($range, ':')) {
                    [$first, $last] = explode(':', $range, 2);
                    if ($first !== $last) $sheet->mergeCells($range);
                }
                $cell = explode(':', $range, 2)[0];
                // Master/product text must never be interpreted as a formula.
                $sheet->setCellValueExplicit($cell, $text, DataType::TYPE_STRING);
                $style = $sheet->getStyle($range);
                $style->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setARGB('FF000000');
                $style->getAlignment()->setHorizontal($alignment)->setWrapText($wrap);
                $style->getFont()->setName('Arial')->setSize($size)->setBold($bold);
                if ($fill !== null) {
                    $style->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FF' . $fill);
                    if ($fill === '195F7B') $style->getFont()->getColor()->setARGB('FFFFFFFF');
                }
            };

            // Header: match the restored PDF reference, without oversized merged rows.
            $box('A1:B4');
            $box('C1:L4', "FORM REQUEST ADDITIONAL\nBOX", null, 16, true);
            $box('M1:P4', "PSD - OMD", null, 14, true, Alignment::HORIZONTAL_CENTER, true);
            $logo = public_path('images/aisin-form-logo.jpg');
            if (is_file($logo)) {
                $drawing = new Drawing();
                $drawing->setName('AISIN')->setPath($logo)->setHeight(36)
                    ->setCoordinates('A2')->setOffsetX(5)->setOffsetY(0)->setWorksheet($sheet);
            } else {
                $sheet->setCellValueExplicit('A1', 'AISIN', DataType::TYPE_STRING);
            }

            $date = $document->created_at->copy()->locale('id')->translatedFormat('d F Y');
            $box('L6:P6', 'Date : ' . $date, null, 9, false, Alignment::HORIZONTAL_LEFT);
            $sheet->getRowDimension(7)->setRowHeight(8);
            foreach (
                [
                    'A8' => 'NO',
                    'B8' => 'TYPE BOX',
                    'C8:F8' => 'MARKING',
                    'G8:M8' => 'PURPOSE',
                    'N8:P8' => 'QTY'
                ] as $range => $title
            ) {
                $box($range, $title, '195F7B', 10, true);
            }
            $sheet->getRowDimension(8)->setRowHeight(22);

            $row = 9;
            foreach ($rows as $line) {
                $entry = $line['item'];
                $typeBox = trim((string) $entry->model_name . ' - ' . (string) $entry->product_name, ' -');
                $purpose = (string) $document->purpose;
                $lines = max(
                    1,
                    (int) ceil(mb_strwidth($typeBox) / 28),
                    (int) ceil(mb_strwidth($purpose) / 39)
                );
                $sheet->getRowDimension($row)->setRowHeight(max(15, $lines * 14));
                $box('A' . $row);
                $sheet->setCellValueExplicit('A' . $row, (int) $line['number'], DataType::TYPE_NUMERIC);
                $box('B' . $row, $typeBox, null, 9, false, Alignment::HORIZONTAL_LEFT, true);
                $box('C' . $row . ':F' . $row);
                $box('G' . $row . ':M' . $row, $purpose, null, 9, false, Alignment::HORIZONTAL_LEFT, true);
                $box('N' . $row . ':P' . $row);
                $sheet->setCellValueExplicit('N' . $row, (int) $entry->quantity, DataType::TYPE_NUMERIC);
                $row++;
            }

            // No blank item rows: all subsequent sections follow the final actual item.
            $approvalHeader = $row + 1;
            $approvalStart = $approvalHeader + 1;
            $approvalEnd = $approvalHeader + 3;
            $approvalLabel = $approvalEnd + 1;
            foreach (
                [
                    'H:J' => ['APPROVED', 'GM'],
                    'K:M' => ['CHECKED', 'Ka. Dept'],
                    'N:P' => ['PREPARED', 'Ka. Sie']
                ] as $columns => $labels
            ) {
                [$left, $right] = explode(':', $columns);
                $box($left . $approvalHeader . ':' . $right . $approvalHeader, $labels[0]);
                $box($left . $approvalStart . ':' . $right . $approvalEnd);
                $box($left . $approvalLabel . ':' . $right . $approvalLabel, $labels[1]);
            }

            $scheduleTitle = $approvalLabel + 2;
            $scheduleHeader = $scheduleTitle + 1;
            $weekHeader = $scheduleTitle + 2;
            $activityRow = $scheduleTitle + 3;
            $sheet->getRowDimension($scheduleTitle)->setRowHeight(23);
            $sheet->getRowDimension($scheduleHeader)->setRowHeight(20);
            $box('A' . $scheduleTitle . ':P' . $scheduleTitle, 'SCHEDULE ADDITIONAL BOX', '195F7B', 13, true);
            $box('A' . $scheduleHeader . ':B' . $weekHeader, 'ACTIVITY', 'C0C0C0', 10, true);
            $box('C' . $scheduleHeader . ':P' . $scheduleHeader, 'LEAD TIME (IN WEEK)', 'C0C0C0', 11, true);
            $box('A' . $activityRow . ':B' . $activityRow); // One blank row for manual entry.
            for ($week = 1; $week <= 14; $week++) {
                $col = Coordinate::stringFromColumnIndex($week + 2);
                $box($col . $weekHeader, (string) $week);
                $sheet->setCellValueExplicit($col . $weekHeader, $week, DataType::TYPE_NUMERIC);
                $box($col . $activityRow);
            }

            $noteStart = $activityRow + 2;
            $noteEnd = $noteStart + 3;
            $box(
                'A' . $noteStart . ':F' . $noteEnd,
                "Note:\nLEAD TIME TERHITUNG SETELAH PENGAJUAN DISETUJUI",
                '98DDF1',
                9,
                true,
                Alignment::HORIZONTAL_LEFT,
                true
            );
            $box('K' . $noteStart . ':P' . $noteStart, 'CONFIRMED PSD - OMD');
            $box('K' . ($noteStart + 1) . ':M' . ($noteEnd - 1));
            $box('N' . ($noteStart + 1) . ':P' . ($noteEnd - 1));
            $box('K' . $noteEnd . ':M' . $noteEnd, 'Ka. Dept', null, 9);
            $box('N' . $noteEnd . ':P' . $noteEnd, 'Ka. Sie', null, 9);

            $sheet->getPageSetup()->setPrintArea('A1:P' . $noteEnd);
        }

        $book->setActiveSheetIndex(0);
        return response()->streamDownload(function () use ($book) {
            try {
                (new Xlsx($book))->save('php://output');
            } finally {
                $book->disconnectWorksheets();
            }
        }, $document->document_number . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
