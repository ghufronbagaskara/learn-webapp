<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportingService
{
  /** Build paginated sales report rows from filters and sorting. */
  public function getSalesReport(
    ?string $startDate,
    ?string $endDate,
    ?string $status,
    string $sortField,
    string $sortDirection
  ): LengthAwarePaginator {
    return Order::query()
      ->with(['user'])
      ->filterByDate($startDate, $endDate)
      ->filterByStatus($status)
      ->orderBy($this->sanitizeSortField($sortField), $this->sanitizeSortDirection($sortDirection))
      ->paginate(25);
  }

  /** Build full sales report rows for CSV export. */
  public function getSalesReportForExport(
    ?string $startDate,
    ?string $endDate,
    ?string $status,
    string $sortField,
    string $sortDirection
  ): Collection {
    return Order::query()
      ->with(['user'])
      ->filterByDate($startDate, $endDate)
      ->filterByStatus($status)
      ->orderBy($this->sanitizeSortField($sortField), $this->sanitizeSortDirection($sortDirection))
      ->get();
  }

  /** Build executive summary cards for the selected date range. */
  public function getSalesSummary(?string $startDate, ?string $endDate, ?string $status): array
  {
    $baseQuery = Order::query()
      ->filterByDate($startDate, $endDate)
      ->filterByStatus($status);

    $currentTotalOrders = (clone $baseQuery)->count();
    $currentRevenue = (float) (clone $baseQuery)->sum('total');
    $averageOrderValue = $currentTotalOrders > 0 ? ($currentRevenue / $currentTotalOrders) : 0.0;
    $newCustomers = (clone $baseQuery)->whereNotNull('user_id')->distinct('user_id')->count('user_id');

    [$previousStart, $previousEnd] = $this->resolvePreviousRange($startDate, $endDate);

    $previousQuery = Order::query()
      ->filterByDate($previousStart, $previousEnd)
      ->filterByStatus($status);

    $previousTotalOrders = (clone $previousQuery)->count();
    $previousRevenue = (float) (clone $previousQuery)->sum('total');
    $previousNewCustomers = (clone $previousQuery)->whereNotNull('user_id')->distinct('user_id')->count('user_id');

    return [
      [
        'label' => 'Total Orders',
        'value' => number_format($currentTotalOrders),
        'trend' => $this->calculateTrend($currentTotalOrders, $previousTotalOrders),
      ],
      [
        'label' => 'Total Revenue',
        'value' => 'Rp ' . number_format($currentRevenue, 0, ',', '.'),
        'trend' => $this->calculateTrend($currentRevenue, $previousRevenue),
      ],
      [
        'label' => 'Avg Order Value',
        'value' => 'Rp ' . number_format($averageOrderValue, 0, ',', '.'),
        'trend' => null,
      ],
      [
        'label' => 'New Customers',
        'value' => number_format($newCustomers),
        'trend' => $this->calculateTrend($newCustomers, $previousNewCustomers),
      ],
    ];
  }

  /** Build labels and data points for revenue time-series chart. */
  public function getChartData(?string $startDate, ?string $endDate, ?string $status = null): array
  {
    $rows = Order::query()
      ->selectRaw('DATE(created_at) as chart_date, SUM(total) as revenue_total')
      ->filterByDate($startDate, $endDate)
      ->filterByStatus($status)
      ->groupBy('chart_date')
      ->orderBy('chart_date')
      ->get();

    return [
      'labels' => $rows->pluck('chart_date')->all(),
      'data' => $rows->pluck('revenue_total')->map(static fn(mixed $value): float => (float) $value)->all(),
    ];
  }

  /** Build flat row data for PivotTable.js renderer. */
  public function getPivotData(?string $startDate, ?string $endDate, ?string $status = null): array
  {
    return DB::table('orders')
      ->selectRaw('category, MONTHNAME(created_at) as month, MONTH(created_at) as month_number, SUM(total) as revenue')
      ->whereBetween('created_at', [$startDate, $endDate])
      ->when(filled($status), static fn($query) => $query->where('status', $status))
      ->groupBy('category', 'month', 'month_number')
      ->orderBy('month_number')
      ->orderBy('category')
      ->get()
      ->map(static function (object $row): array {
        return [
          'category' => (string) $row->category,
          'month' => (string) $row->month,
          'revenue' => (float) $row->revenue,
        ];
      })
      ->toArray();
  }

  /** Return the list of statuses available in order rows. */
  public function getAvailableStatuses(): array
  {
    return Order::query()
      ->select('status')
      ->distinct()
      ->orderBy('status')
      ->pluck('status')
      ->all();
  }

  /** Sanitize sortable columns to avoid unsafe orderBy usage. */
  private function sanitizeSortField(string $sortField): string
  {
    $allowedSortFields = ['created_at', 'order_number', 'status', 'category', 'total'];

    return in_array($sortField, $allowedSortFields, true) ? $sortField : 'created_at';
  }

  /** Sanitize sort direction to asc or desc. */
  private function sanitizeSortDirection(string $sortDirection): string
  {
    return strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
  }

  /** Resolve previous comparison range with same duration as current filter. */
  private function resolvePreviousRange(?string $startDate, ?string $endDate): array
  {
    if (blank($startDate) || blank($endDate)) {
      return [null, null];
    }

    $start = Carbon::parse($startDate)->startOfDay();
    $end = Carbon::parse($endDate)->endOfDay();

    $rangeInDays = $start->diffInDays($end) + 1;

    $previousEnd = $start->copy()->subDay()->endOfDay();
    $previousStart = $previousEnd->copy()->subDays($rangeInDays - 1)->startOfDay();

    return [$previousStart->toDateString(), $previousEnd->toDateString()];
  }

  /** Calculate trend percentage against a previous value. */
  private function calculateTrend(float|int $current, float|int $previous): ?float
  {
    if ((float) $previous === 0.0) {
      return null;
    }

    return round((($current - $previous) / $previous) * 100, 1);
  }
}
