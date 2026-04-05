<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Penduduk;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PendudukExportService
{
  public function exportCsv(): string
  {
    return $this->exportSpreadsheet(writerType: 'Csv', extension: 'csv');
  }

  public function exportXls(): string
  {
    return $this->exportSpreadsheet(writerType: 'Xls', extension: 'xls');
  }

  public function exportXlsx(): string
  {
    return $this->exportSpreadsheet(writerType: 'Xlsx', extension: 'xlsx');
  }

  private function exportSpreadsheet(string $writerType, string $extension): string
  {
    // Membuat folder ekspor sementara jika belum ada.
    $exportDirectory = storage_path('app/exports');

    if (! is_dir($exportDirectory)) {
      mkdir($exportDirectory, 0775, true);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Nama', 'Usia', 'Alamat', 'Pekerjaan'];
    $sheet->fromArray($headers, null, 'A1');

    $rows = Penduduk::query()
      ->orderBy('nama')
      ->get(['nama', 'usia', 'alamat', 'pekerjaan'])
      ->map(fn(Penduduk $penduduk): array => [
        $penduduk->nama,
        $penduduk->usia,
        $penduduk->alamat,
        $penduduk->pekerjaan,
      ])
      ->all();

    if ($rows !== []) {
      $sheet->fromArray($rows, null, 'A2');
    }

    $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    $sheet->getStyle('A1:D1')->getFill()
      ->setFillType(Fill::FILL_SOLID)
      ->getStartColor()
      ->setARGB('FFE2E8F0');

    foreach (range('A', 'D') as $column) {
      $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $filename = sprintf('penduduk_%s.%s', now()->format('Ymd_His'), $extension);
    $absolutePath = sprintf('%s/%s', $exportDirectory, $filename);

    $writer = IOFactory::createWriter($spreadsheet, $writerType);
    $writer->save($absolutePath);

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);

    return $absolutePath;
  }
}
