<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScrapHistoryExportService
{
    /** @param array<int, array{order_number:string,date:string,model_product:string,scrap_limit:string,decision:string,reason:string}> $rows */
    public function pdf(string $title, string $filename, array $rows)
    {
        return Pdf::loadView('shared.scrap-history-export-pdf', [
            'title' => $title,
            'rows' => $rows,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape')->download($filename . '.pdf');
    }

    /** @param array<int, array{order_number:string,date:string,model_product:string,scrap_limit:string,decision:string,reason:string}> $rows */
    public function excel(string $title, string $filename, array $rows)
    {
        $book = new Spreadsheet();
        $book->getDefaultStyle()->getFont()->setName('Arial')->setSize(10)->getColor()->setARGB('FF111827');

        $sheet = $book->getActiveSheet();
        $sheet->setTitle('History Scrap');
        $sheet->setShowGridlines(false);
        $sheet->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.35)->setBottom(0.35)->setLeft(0.35)->setRight(0.35);
        $sheet->getColumnDimension('A')->setWidth(21);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(38);
        $sheet->getColumnDimension('D')->setWidth(17);
        $sheet->getColumnDimension('E')->setWidth(58);

        $sheet->mergeCells('A1:E1');
        $sheet->setCellValueExplicit('A1', $title, DataType::TYPE_STRING);
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FF263E30');
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValueExplicit('A2', 'Diekspor: ' . now()->format('d-m-Y H:i'), DataType::TYPE_STRING);
        $sheet->getStyle('A2:E2')->getFont()->setSize(9)->getColor()->setARGB('FF64748B');
        $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $headers = ['No Order', 'Tanggal', 'Model – Produk', 'Scrap / Limit', 'Keputusan dan Alasan'];
        foreach ($headers as $index => $header) {
            $column = chr(65 + $index);
            $sheet->setCellValueExplicit($column . '4', $header, DataType::TYPE_STRING);
        }
        $sheet->getRowDimension(4)->setRowHeight(24);
        $sheet->getStyle('A4:E4')->getFont()->setBold(true)->getColor()->setARGB('FF64748B');
        $sheet->getStyle('A4:E4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF9FAFB');
        $sheet->getStyle('A4:E4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);

        $rowNumber = 5;
        foreach ($rows as $row) {
            $combinedDecision = trim((string) ($row['decision'] ?? ''));
            $reason = trim((string) ($row['reason'] ?? ''));
            if ($reason !== '') {
                $combinedDecision .= "\nAlasan: " . $reason;
            }

            $values = [
                (string) ($row['order_number'] ?? '—'),
                (string) ($row['date'] ?? '—'),
                (string) ($row['model_product'] ?? '—'),
                (string) ($row['scrap_limit'] ?? '—'),
                $combinedDecision !== '' ? $combinedDecision : '—',
            ];
            foreach ($values as $index => $value) {
                $column = chr(65 + $index);
                $sheet->setCellValueExplicit($column . $rowNumber, $value, DataType::TYPE_STRING);
            }

            $lineCount = max(1, (int) ceil(mb_strwidth($values[2]) / 32), (int) ceil(mb_strwidth($values[4]) / 55));
            $sheet->getRowDimension($rowNumber)->setRowHeight(max(23, min(90, $lineCount * 14)));
            if (($rowNumber % 2) === 0) {
                $sheet->getStyle('A' . $rowNumber . ':E' . $rowNumber)->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }
            $rowNumber++;
        }

        if ($rows === []) {
            $sheet->mergeCells('A5:E5');
            $sheet->setCellValueExplicit('A5', 'Tidak ada data untuk filter yang dipilih.', DataType::TYPE_STRING);
            $sheet->getRowDimension(5)->setRowHeight(24);
            $rowNumber = 6;
        }

        $lastRow = max(5, $rowNumber - 1);
        $sheet->getStyle('A4:E' . $lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFE5EAF0');
        if ($rows !== []) {
            $sheet->getStyle('A5:E' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
            $sheet->getStyle('A5:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D5:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->setAutoFilter('A4:E' . $lastRow);
        } else {
            $sheet->getStyle('A5:E5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        }
        $sheet->freezePane('A5');

        $writer = new Xlsx($book);
        return response()->streamDownload(
            static fn () => $writer->save('php://output'),
            $filename . '.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
