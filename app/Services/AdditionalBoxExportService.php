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
    /** Keep five row-heights per page; long product names receive extra space. */
    private function pages(AdditionalBoxRequest $document): array
    {
        $pages = []; $rows = []; $used = 0; $number = 1;
        foreach ($document->items as $item) {
            $slots = min(5, max(1, (int) ceil(mb_strwidth($item->model_name . ' — ' . $item->product_name) / 40)));
            if ($used + $slots > 5) {
                $pages[] = $rows; $rows = []; $used = 0;
            }
            $rows[] = ['item' => $item, 'slots' => $slots, 'number' => $number++];
            $used += $slots;
        }
        if ($rows) $pages[] = $rows;
        foreach ($pages as &$rows) {
            $free = 5 - array_sum(array_column($rows, 'slots'));
            for ($i = 0; $i < $free; $i++) $rows[] = ['item' => null, 'slots' => 1, 'number' => null];
        }
        unset($rows);
        return $pages;
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
                ->setFitToWidth(1)->setFitToHeight(1)->setPrintArea('A1:P40');
            $sheet->getPageMargins()->setTop(0.3)->setBottom(0.3)->setLeft(0.3)->setRight(0.3);
            $sheet->getHeaderFooter()->setOddHeader('')->setOddFooter('');
            $sheet->getColumnDimension('A')->setWidth(9);
            $sheet->getColumnDimension('B')->setWidth(21);
            foreach (range(3,16) as $col) $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(6.2);
            $sheet->getStyle('A1:P40')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle('A1:P40')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR)->getColor()->setARGB('FFE1E1E1');
            foreach (range(1,40) as $row) $sheet->getRowDimension($row)->setRowHeight(18);
            $box = function (string $range, string $text = '', ?string $fill = null, int $size = 10, bool $bold = false) use ($sheet) {
                if (str_contains($range, ':') && explode(':', $range)[0] !== explode(':', $range)[1]) $sheet->mergeCells($range);
                $cell = explode(':', $range)[0];
                // User/master data is always text, never an Excel formula.
                $sheet->setCellValueExplicit($cell, $text, DataType::TYPE_STRING);
                $style = $sheet->getStyle($range);
                $style->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF000000');
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $style->getFont()->setSize($size)->setBold($bold);
                if ($fill) $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $fill);
            };
            $box('A1:B5');
            $box('C1:L5', "FORM REQUEST ADDITIONAL\nBOX", null, 23, true);
            $box('M1:P5', "OPERATION\nMANAGEMENT\nDEVELOPMENT DEPT.", null, 11, true);
            $logo = public_path('images/aisin-form-logo.jpg');
            if (is_file($logo)) {
                $drawing = new Drawing();
                $drawing->setName('AISIN')->setPath($logo)->setHeight(40)->setCoordinates('A2')->setOffsetX(16)->setOffsetY(4)->setWorksheet($sheet);
            } else $sheet->setCellValueExplicit('A1', 'AISIN', DataType::TYPE_STRING);
            $box('L7:P7', 'Date : ' . $document->created_at->format('d-m-Y'));
            $sheet->getStyle('L7:P7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            foreach (['A9'=>'NO','B9'=>'TYPE BOX','C9:F9'=>'MARKING','G9:M9'=>'PURPOSE','N9:P9'=>'QTY'] as $range=>$title) $box($range, $title, '195F7B', 14, true);
            $sheet->getRowDimension(9)->setRowHeight(36);
            $row = 10;
            foreach ($rows as $line) {
                $entry = $line['item'];
                $lastRow = $row + $line['slots'] - 1;
                foreach (range($row, $lastRow) as $r) $sheet->getRowDimension($r)->setRowHeight(32);
                $box('A'.$row.':A'.$lastRow);
                if ($entry) $sheet->setCellValue('A'.$row, $line['number']);
                $box('B'.$row.':B'.$lastRow, $entry ? $entry->model_name . ' — ' . $entry->product_name : '', null, 9);
                $box('C'.$row.':F'.$lastRow);
                $box('G'.$row.':M'.$lastRow, $entry ? $document->purpose : '');
                $box('N'.$row.':P'.$lastRow);
                if ($entry) $sheet->setCellValue('N'.$row, (int) $entry->quantity);
                $row = $lastRow + 1;
            }
            foreach (['H:J'=>['APPROVED','GM'],'K:M'=>['CHECKED','Ka. Dept'],'N:P'=>['PREPARED','Ka. Sie']] as $columns=>$labels) {
                [$left,$right] = explode(':',$columns);
                $box($left.'17:'.$right.'17', $labels[0]);
                $box($left.'18:'.$right.'21');
                $box($left.'22:'.$right.'22', $labels[1]);
            }
            $box('A24:P24', 'SCHEDULE ADDITIONAL BOX', '195F7B', 18, true);
            $sheet->getRowDimension(24)->setRowHeight(30);
            $box('A25:B26', 'ACTIVITY', 'C0C0C0', 16, true);
            $box('C25:P25', 'LEAD TIME (IN WEEK)', 'C0C0C0', 18, true);
            $sheet->getRowDimension(25)->setRowHeight(30);
            for ($week=1; $week<=14; $week++) {
                $col = Coordinate::stringFromColumnIndex($week+2);
                $box($col.'26', (string) $week);
                $sheet->setCellValue($col.'26', $week);
                foreach (range(27,31) as $row) $box($col.$row);
            }
            foreach (range(27,31) as $row) { $box('A'.$row.':B'.$row); $sheet->getRowDimension($row)->setRowHeight(27); }
            $box('A34:F37', "Note:\nLEAD TIME TERHITUNG SETELAH PENGAJUAN DISETUJUI", '98DDF1', 12, true);
            $sheet->getStyle('A34:F37')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $box('K34:P34','CONFIRMED');
            $box('K35:M39'); $box('N35:P39');
            $box('K40:M40','Ka. Dept OMC'); $box('N40:P40','Ka. Sie OMC');
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
