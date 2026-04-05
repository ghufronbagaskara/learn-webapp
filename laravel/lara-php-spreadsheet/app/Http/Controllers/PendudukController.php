<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\PendudukExportService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendudukController extends Controller
{
  public function export(string $format, PendudukExportService $exportService): BinaryFileResponse|Response
  {
    $filePath = match ($format) {
      'csv' => $exportService->exportCsv(),
      'xls' => $exportService->exportXls(),
      'xlsx' => $exportService->exportXlsx(),
      default => abort(404),
    };

    return response()
      ->download($filePath)
      ->deleteFileAfterSend(true);
  }
}
