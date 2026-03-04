<?php

namespace App\Filament\Widgets;

use App\Models\SalesOrder;
use Filament\Widgets\ChartWidget;

class RevenueByStatusWidget extends ChartWidget {
  protected static ?string $heading = 'Revenue by Status';

  protected static ?int $sort = 3;



  protected function getData(): array {
    $pending = SalesOrder::where('status', 'Pending')->sum('total_amount');
    $paid = SalesOrder::where('status', 'Paid')->sum('total_amount');
    $cancelled = SalesOrder::where('status', 'Cancelled')->sum('total_amount');

    return [
      'datasets' => [
        'label' => 'Revenue',
        'data' => [$pending, $paid, $cancelled],
        'backgroundColor' => [
          'rgb(251, 191, 36)', // warning
          'rgb(34, 197, 94)',  // success
          'rgb(239, 68, 68)',  // danger
        ],
      ],
      'labels' => ['Pending', 'Paid', 'Cancelled'],
    ];
  }

  protected function getType(): string {
    return 'doughnut';
  }
}
