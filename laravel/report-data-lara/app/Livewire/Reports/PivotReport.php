<?php

namespace App\Livewire\Reports;

use App\Services\ReportingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class PivotReport extends Component
{
  #[Url]
  public string $startDate = '';

  #[Url]
  public string $endDate = '';

  #[Url]
  public string $status = '';

  public bool $readyToLoad = false;

  /** Set default filter values on mount. */
  public function mount(): void
  {
    $this->startDate = now()->startOfMonth()->toDateString();
    $this->endDate = now()->toDateString();
  }

  /** Enable heavy pivot loading after first paint. */
  public function initLoad(): void
  {
    $this->readyToLoad = true;
  }

  /** Reset all pivot filters to defaults. */
  public function resetFilters(): void
  {
    $this->reset(['status']);
    $this->startDate = now()->startOfMonth()->toDateString();
    $this->endDate = now()->toDateString();

    $this->dispatch(
      'pivotFiltersReset',
      startDate: $this->startDate,
      endDate: $this->endDate,
      status: $this->status,
      pivotRows: $this->pivotRows,
    );
  }

  /** Get summary metrics for pivot page cards. */
  public function getSummaryProperty(): array
  {
    if (! $this->readyToLoad) {
      return [
        ['label' => 'Total Orders', 'value' => '0', 'trend' => null],
        ['label' => 'Total Revenue', 'value' => 'Rp 0', 'trend' => null],
        ['label' => 'Avg Order Value', 'value' => 'Rp 0', 'trend' => null],
        ['label' => 'New Customers', 'value' => '0', 'trend' => null],
      ];
    }

    return app(ReportingService::class)->getSalesSummary($this->startDate, $this->endDate, $this->status);
  }

  /** Get flattened rows for PivotTable.js rendering. */
  public function getPivotRowsProperty(): array
  {
    if (! $this->readyToLoad) {
      return [];
    }

    return app(ReportingService::class)->getPivotData($this->startDate, $this->endDate, $this->status);
  }

  /** Get status options for Select2 filter. */
  public function getStatusesProperty(): array
  {
    return app(ReportingService::class)->getAvailableStatuses();
  }

  /** Render the pivot analysis page. */
  public function render()
  {
    return view('livewire.reports.pivot-report', [
      'summary' => $this->summary,
      'pivotRows' => $this->pivotRows,
      'statuses' => $this->statuses,
      'title' => 'Pivot Report',
    ]);
  }
}
