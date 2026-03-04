<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopCustomersWidget extends BaseWidget {
  protected static ?int $sort = 4;

  protected int | string | array $columnSpan = 'full';

  public function table(Table $table): Table {
    return $table
      ->heading('Top 5 Customers by Revenue')
      ->query(
        Customer::query()
          ->withSum('salesOrders', 'total_amount')
          ->orderByDesc('sales_orders_sum_total_amount')
          ->limit(5)
      )
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Customer Name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('email')
          ->label('Email')
          ->searchable(),

        Tables\Columns\TextColumn::make('sales_orders_count')
          ->label('Total Orders')
          ->counts('salesOrders')
          ->sortable(),

        Tables\Columns\TextColumn::make('sales_orders_sum_total_amount')
          ->label('Total Revenue')
          ->money('IDR')
          ->sortable(),
      ]);
  }
}
