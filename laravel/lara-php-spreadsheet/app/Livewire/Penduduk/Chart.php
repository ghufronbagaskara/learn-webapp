<?php

declare(strict_types=1);

namespace App\Livewire\Penduduk;

use App\Models\Penduduk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Chart extends Component
{
  #[On('penduduk-updated')]
  public function refreshChart(): void
  {
    // Method ini digunakan agar komponen re-render saat data berubah.
  }

  public function render(): View
  {
    $pekerjaanData = Penduduk::query()
      ->select('pekerjaan', DB::raw('COUNT(*) as total'))
      ->groupBy('pekerjaan')
      ->orderByDesc('total')
      ->get();

    $usiaData = Penduduk::query()
      ->selectRaw('SUM(CASE WHEN usia < 18 THEN 1 ELSE 0 END) AS anak')
      ->selectRaw('SUM(CASE WHEN usia BETWEEN 18 AND 35 THEN 1 ELSE 0 END) AS dewasa_muda')
      ->selectRaw('SUM(CASE WHEN usia BETWEEN 36 AND 55 THEN 1 ELSE 0 END) AS dewasa')
      ->selectRaw('SUM(CASE WHEN usia > 55 THEN 1 ELSE 0 END) AS lansia')
      ->first();

    return view('livewire.penduduk.chart', [
      'pekerjaanLabels' => $pekerjaanData->pluck('pekerjaan')->values()->all(),
      'pekerjaanTotals' => $pekerjaanData->pluck('total')->map(static fn($value): int => (int) $value)->values()->all(),
      'usiaLabels' => ['< 18', '18-35', '36-55', '> 55'],
      'usiaTotals' => [
        (int) ($usiaData?->anak ?? 0),
        (int) ($usiaData?->dewasa_muda ?? 0),
        (int) ($usiaData?->dewasa ?? 0),
        (int) ($usiaData?->lansia ?? 0),
      ],
    ]);
  }
}
