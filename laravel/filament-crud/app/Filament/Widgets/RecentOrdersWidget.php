<?php

namespace App\Filament\Widgets;

use App\Models\SalesOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget {
  protected static ?int $sort = 6;

  protected int | string | array $columnSpan = 'full';

  public function table(Table $table): Table {
    return $table
      ->heading('Recent Orders (Last 10)')
      ->query(
        SalesOrder::query()
          ->with(['customer'])
          ->latest()
          ->limit(10)
      )
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('Order #')
          ->sortable(),

        Tables\Columns\TextColumn::make('customer.name')
          ->label('Customer')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('order_date')
          ->label('Order Date')
          ->date('d M Y')
          ->sortable(),

        Tables\Columns\TextColumn::make('total_amount')
          ->label('Total')
          ->money('IDR')
          ->sortable(),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'warning' => 'Pending',
            'success' => 'Paid',
            'danger' => 'Cancelled',
          ]),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Created')
          ->since()
          ->sortable(),
      ])
      ->actions([
        Tables\Actions\Action::make('view')
          ->label('View')
          ->icon('heroicon-o-eye')
          ->url(fn(SalesOrder $record): string => route('filament.admin.resources.sales-orders.edit', $record)),
      ]);
  }
}
