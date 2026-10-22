<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScrapHistoryExportService
{
    /** @param array<int, array{date:string,order_number:string,line:string,model:string,product:string,scrap_limit:string,decision:string,reason:string}> $rows */
    public function pdf(string $title, string $filename, array $rows)
    {
        return Pdf::loadView('shared.scrap-history-export-pdf', [
            'title' => $title,
            'rows' => $rows,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait')->download($filename . '.pdf');
    }

    /** @param array<int, array{date:string,order_number:string,line:string,model:string,product:string,scrap_limit:string,decision:string,reason:string}> $rows */
    public function excel(string $title, string $filename, array $rows)
    {
        $book = new Spreadsheet();
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(10)->getColor()->setARGB('FF000000');

        $sheet = $book->getActiveSheet();
        $sheet->setTitle('History Scrap');
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.45)->setBottom(0.45)->setLeft(0.35)->setRight(0.35);

        foreach ([
            'A' => 14, // Tanggal
            'B' => 17, // No Order
            'C' => 15, // Line
            'D' => 16, // Model
            'E' => 19, // Produk
            'F' => 14, // Scrap / Limit
            'G' => 16, // Keputusan
            'H' => 30, // Alasan
        ] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->mergeCells('A1:H1');
        $sheet->setCellValueExplicit('A1', $title, DataType::TYPE_STRING);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FF000000');
        $sheet->getStyle('A1:H1')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValueExplicit('A2', 'Diekspor: ' . now()->format('d-m-Y H:i'), DataType::TYPE_STRING);
        $sheet->getStyle('A2:H2')->getFont()->setSize(9)->getColor()->setARGB('FF000000');
        $sheet->getStyle('A2:H2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $headers = ['Tanggal', 'No Order', 'Line', 'Model', 'Produk', 'Scrap / Limit', 'Keputusan', 'Alasan'];
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValueExplicit($column . '4', $header, DataType::TYPE_STRING);
        }
        $sheet->getStyle('A4:H4')->getFont()->setBold(true)->getColor()->setARGB('FF000000');
        $sheet->getStyle('A4:H4')->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setWrapText(true);

        $rowNumber = 5;
        foreach ($rows as $row) {
            $values = [
                (string) ($row['date'] ?? '—'),
                (string) ($row['order_number'] ?? '—'),
                (string) ($row['line'] ?? '—'),
                (string) ($row['model'] ?? '—'),
                (string) ($row['product'] ?? '—'),
                (string) ($row['scrap_limit'] ?? '—'),
                (string) ($row['decision'] ?? '—'),
                (string) ($row['reason'] ?? '—'),
            ];
            foreach ($values as $index => $value) {
                $column = chr(65 + $index);
                $sheet->setCellValueExplicit($column . $rowNumber, $value !== '' ? $value : '—', DataType::TYPE_STRING);
            }
            $rowNumber++;
        }

        if ($rows === []) {
            $sheet->mergeCells('A5:H5');
            $sheet->setCellValueExplicit('A5', 'Tidak ada data untuk filter yang dipilih.', DataType::TYPE_STRING);
            $rowNumber = 6;
        }

        $lastRow = max(5, $rowNumber - 1);
        $sheet->getStyle('A4:H' . $lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF000000');
        $sheet->getStyle('A5:H' . $lastRow)->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        if ($rows !== []) {
            $sheet->setAutoFilter('A4:H' . $lastRow);
        }
        $sheet->freezePane('A5');

        // Row heights are intentionally not set: use Excel's default/auto row sizing.
        $writer = new Xlsx($book);
        return response()->streamDownload(
            static fn () => $writer->save('php://output'),
            $filename . '.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
