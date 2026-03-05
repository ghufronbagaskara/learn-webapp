<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProductsWidget extends BaseWidget {
  protected static ?int $sort = 5;

  protected int | string | array $columnSpan = 'full';

  public function table(Table $table): Table {
    return $table
      ->heading('Top 5 Best Selling Products')
      ->query(
        Product::query()
        ->withSum('salesOrderItems', 'quantity')
          ->withSum('salesOrderItems', 'subtotal')
        ->orderByDesc('sales_order_items_sum_quantity')
          ->limit(5)
      )
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Product Name')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('price')
          ->label('Price')
          ->money('IDR')
          ->sortable(),

      Tables\Columns\TextColumn::make('sales_order_items_sum_quantity')
          ->label('Total Sold')
          ->sortable()
          ->badge()
          ->color('success'),

        Tables\Columns\TextColumn::make('sales_order_items_sum_subtotal')
          ->label('Total Revenue')
          ->money('IDR')
          ->sortable(),

        Tables\Columns\TextColumn::make('stock')
          ->label('Current Stock')
          ->sortable()
          ->badge()
          ->color(fn(int $state): string => match (true) {
            $state > 50 => 'success',
            $state > 20 => 'warning',
            default => 'danger',
          }),
      ]);
  }
}
