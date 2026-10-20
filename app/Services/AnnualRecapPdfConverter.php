<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Process\Process;

/** Convert the actual annual Excel workbook, including its native chart, to PDF. */
final class AnnualRecapPdfConverter
{
    private function resolveBinary(): ?string
    {
        $configured = trim((string) (app()->bound('config') ? config('services.libreoffice.binary', '') : ''));
        $candidates = array_filter(array_unique([
            $configured,
            'C:\Program Files\LibreOffice\program\soffice.exe',
            'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            'libreoffice',
            'soffice',
        ]));

        foreach ($candidates as $binary) {
            if ((str_contains($binary, '/') || str_contains($binary, '\\')) && ! is_file($binary)) {
                continue;
            }
            try {
                $probe = new Process([$binary, '--version']);
                $probe->setTimeout(8);
                $probe->run();
                if ($probe->isSuccessful()) {
                    return $binary;
                }
            } catch (\Throwable) {
                // Try the next known binary or the configured path.
            }
        }

        return null;
    }

    public function isAvailable(): bool
    {
        return $this->resolveBinary() !== null;
    }

    public function convert(Spreadsheet $workbook): string
    {
        $binary = $this->resolveBinary();
        if ($binary === null) {
            throw new \RuntimeException(
                'Export PDF tahunan memerlukan LibreOffice. Instal LibreOffice pada server Laragon, '
                . 'atau tentukan path soffice.exe pada OMIRA_LIBREOFFICE_BIN di .env.'
            );
        }

        $key = bin2hex(random_bytes(12));
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'omira_fy_' . $key;
        $pdfResult = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'omira_fy_pdf_' . $key . '.pdf';
        if (! mkdir($directory, 0700) && ! is_dir($directory)) {
            throw new \RuntimeException('Tidak dapat membuat folder sementara untuk export PDF tahunan.');
        }

        $xlsxPath = $directory . DIRECTORY_SEPARATOR . 'rekap_tahunan.xlsx';
        $pdfPath = $directory . DIRECTORY_SEPARATOR . 'rekap_tahunan.pdf';
        $profile = $directory . DIRECTORY_SEPARATOR . 'lo_profile';
        if (! mkdir($profile, 0700)) {
            $this->cleanDirectory($directory);
            throw new \RuntimeException('Tidak dapat membuat profil sementara untuk konversi PDF.');
        }
        // The -env: profile isolates concurrent requests and avoids locking a user's GUI LibreOffice.
        $profileUri = 'file:///' . ltrim(str_replace('\\', '/', $profile), '/');

        try {
            $writer = IOFactory::createWriter($workbook, 'Xlsx');
            $writer->setIncludeCharts(true);
            $writer->save($xlsxPath);

            $process = new Process([
                $binary,
                '-env:UserInstallation=' . $profileUri,
                '--headless', '--nologo', '--nodefault', '--nofirststartwizard',
                '--convert-to', 'pdf:calc_pdf_Export',
                '--outdir', $directory,
                $xlsxPath,
            ], $directory);
            $process->setTimeout(120);
            $process->run();

            if (! $process->isSuccessful() || ! is_file($pdfPath) || filesize($pdfPath) < 100
                || file_get_contents($pdfPath, false, null, 0, 5) !== '%PDF-') {
                Log::error('Annual recap PDF conversion failed', [
                    'exit_code' => $process->getExitCode(),
                    'stderr' => mb_substr($process->getErrorOutput(), 0, 1000),
                    'stdout' => mb_substr($process->getOutput(), 0, 1000),
                ]);
                throw new \RuntimeException('Konversi PDF tahunan gagal. Periksa instalasi LibreOffice dan laravel.log.');
            }
            if (! rename($pdfPath, $pdfResult)) {
                throw new \RuntimeException('Tidak dapat menyiapkan hasil export PDF tahunan.');
            }

            return $pdfResult;
        } catch (\Throwable $e) {
            @unlink($pdfResult);
            if ($e instanceof \RuntimeException) {
                throw $e;
            }
            Log::error('Annual recap PDF export exception', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Konversi PDF tahunan gagal. Periksa laravel.log.', 0, $e);
        } finally {
            $this->cleanDirectory($directory);
        }
    }

    private function cleanDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }
        $entries = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($entries as $entry) {
            if ($entry->isDir() && ! $entry->isLink()) {
                @rmdir($entry->getPathname());
            } else {
                @unlink($entry->getPathname());
            }
        }
        @rmdir($directory);
    }
}
