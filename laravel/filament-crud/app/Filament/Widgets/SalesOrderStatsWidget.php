<?php

namespace App\Filament\Widgets;

use App\Models\SalesOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOrderStatsWidget extends BaseWidget {
  protected function getStats(): array {
    $totalOrders = SalesOrder::count();
    $pendingOrders = SalesOrder::where('status', 'Pending')->count();
    $paidOrders = SalesOrder::where('status', 'Paid')->count();
    $cancelledOrders = SalesOrder::where('status', 'Cancelled')->count();

    $totalRevenue = SalesOrder::where('status', 'Paid')->sum('total_amount');
    $pendingRevenue = SalesOrder::where('status', 'Pending')->sum('total_amount');

    $thisMonthOrders = SalesOrder::whereMonth('order_date', now()->month)
      ->whereYear('order_date', now()->year)
      ->count();

    $lastMonthOrders = SalesOrder::whereMonth('order_date', now()->subMonth()->month)
      ->whereYear('order_date', now()->subMonth()->year)
      ->count();

    $orderGrowth = $lastMonthOrders > 0
      ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
      : 0;

    return [
      Stat::make('Total Orders', $totalOrders)
        ->description($orderGrowth >= 0 ? "{$orderGrowth}% increase" : "{$orderGrowth}% decrease")
        ->descriptionIcon($orderGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
        ->color($orderGrowth >= 0 ? 'success' : 'danger')
        ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

      Stat::make('Total Revenue (Paid)', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
        ->description('From paid orders')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('success')
        ->chart([3, 5, 6, 7, 8, 10, 12, 15]),

      Stat::make('Pending Orders', $pendingOrders)
        ->description('Rp ' . number_format($pendingRevenue, 0, ',', '.') . ' pending revenue')
        ->descriptionIcon('heroicon-m-clock')
        ->color('warning'),

      Stat::make('Paid Orders', $paidOrders)
        ->description(round(($paidOrders / max($totalOrders, 1)) * 100, 1) . '% of total orders')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color('success'),
    ];
  }
}
