<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Services\ReportingService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SalesReport extends Component
{
  use WithPagination;

  #[Url]
  public string $startDate = '';

  #[Url]
  public string $endDate = '';

  #[Url]
  public string $status = '';

  public string $draftStartDate = '';

  public string $draftEndDate = '';

  public string $draftStatus = '';

  #[Url]
  public string $sortField = 'created_at';

  #[Url]
  public string $sortDir = 'desc';

  public bool $readyToLoad = false;

  /** Set default date range when component boots. */
  public function mount(): void
  {
    $this->startDate = now()->startOfMonth()->toDateString();
    $this->endDate = now()->toDateString();
    $this->draftStartDate = $this->startDate;
    $this->draftEndDate = $this->endDate;
    $this->draftStatus = $this->status;
  }

  /** Enable heavy report queries after initial render. */
  public function initLoad(): void
  {
    $this->readyToLoad = true;
  }

  /** Toggle table sorting for the selected field. */
  public function sortBy(string $field): void
  {
    $this->sortDir = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
    $this->sortField = $field;
    $this->resetPage();
  }

  /** Reset active report filters to defaults. */
  public function resetFilters(): void
  {
    $this->status = '';
    $this->startDate = now()->startOfMonth()->toDateString();
    $this->endDate = now()->toDateString();
    $this->draftStatus = $this->status;
    $this->draftStartDate = $this->startDate;
    $this->draftEndDate = $this->endDate;
    $this->resetPage();
    $this->dispatchWidgetsUpdate('filtersReset');
  }

  /** Apply current filter state and refresh dependent widgets. */
  public function applyFilters(): void
  {
    $this->startDate = $this->draftStartDate;
    $this->endDate = $this->draftEndDate;
    $this->status = $this->draftStatus;
    $this->resetPage();
    $this->dispatchWidgetsUpdate('filtersApplied');
  }

  /** Export current filtered report rows to CSV. */
  public function exportCsv(): StreamedResponse
  {
    $rows = app(ReportingService::class)->getSalesReportForExport(
      $this->startDate,
      $this->endDate,
      $this->status,
      $this->sortField,
      $this->sortDir,
    );

    $fileName = 'sales-report-' . now()->format('Ymd-His') . '.csv';

    return response()->streamDownload(static function () use ($rows): void {
      $output = fopen('php://output', 'wb');

      if ($output === false) {
        return;
      }

      fputcsv($output, ['Order Number', 'Customer', 'Status', 'Category', 'Total', 'Created At']);

      foreach ($rows as $row) {
        fputcsv($output, [
          $row->order_number,
          $row->user?->name ?? 'Guest',
          $row->status,
          $row->category,
          (string) $row->total,
          $row->created_at?->format('Y-m-d H:i:s'),
        ]);
      }

      fclose($output);
    }, $fileName, [
      'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
  }

  /** Refresh chart when sort values change. */
  public function updated(string $property): void
  {
    if (! in_array($property, ['sortField', 'sortDir'], true)) {
      return;
    }

    $this->resetPage();
  }

  /** Get paginated report rows from reporting service. */
  public function getReportDataProperty()
  {
    if (! $this->readyToLoad) {
      return Order::query()
        ->with(['user'])
        ->whereRaw('1 = 0')
        ->paginate(25);
    }

    return app(ReportingService::class)->getSalesReport(
      $this->startDate,
      $this->endDate,
      $this->status,
      $this->sortField,
      $this->sortDir
    );
  }

  /** Get summary card data from reporting service. */
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

  /** Get chart labels and points from reporting service. */
  public function getChartProperty(): array
  {
    if (! $this->readyToLoad) {
      return ['labels' => [], 'data' => []];
    }

    return app(ReportingService::class)->getChartData($this->startDate, $this->endDate, $this->status);
  }

  /** Get row source for client-side pivot analysis. */
  public function getPivotRowsProperty(): array
  {
    if (! $this->readyToLoad) {
      return [];
    }

    return app(ReportingService::class)->getPivotData($this->startDate, $this->endDate, $this->status);
  }

  /** Get status filter options from reporting service. */
  public function getStatusesProperty(): array
  {
    return app(ReportingService::class)->getAvailableStatuses();
  }

  /** Render the sales report page. */
  public function render()
  {
    return view('livewire.reports.sales-report', [
      'rows' => $this->reportData,
      'summary' => $this->summary,
      'chartLabels' => $this->chart['labels'],
      'chartData' => $this->chart['data'],
      'pivotRows' => $this->pivotRows,
      'statuses' => $this->statuses,
      'title' => 'Sales Report',
    ]);
  }

  /** Dispatch browser event to refresh chart and pivot visualizations. */
  private function dispatchWidgetsUpdate(string $eventName): void
  {
    if (! $this->readyToLoad) {
      return;
    }

    $chart = app(ReportingService::class)->getChartData($this->startDate, $this->endDate, $this->status);
    $pivot = app(ReportingService::class)->getPivotData($this->startDate, $this->endDate, $this->status);

    $this->dispatch(
      $eventName,
      startDate: $this->draftStartDate,
      endDate: $this->draftEndDate,
      status: $this->draftStatus,
      chartLabels: $chart['labels'],
      chartData: $chart['data'],
      pivotRows: $pivot,
    );
  }
}
