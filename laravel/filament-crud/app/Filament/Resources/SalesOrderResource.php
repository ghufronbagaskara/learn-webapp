<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Filament\Resources\SalesOrderResource\RelationManagers\ItemsRelationManager;
use App\Models\SalesOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use function Symfony\Component\Clock\now;

class SalesOrderResource extends Resource {
  protected static ?string $model = SalesOrder::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel = 'Sales Orders';

  public static function form(Form $form): Form {
    return $form
      ->schema([
        Forms\Components\Section::make('Order Information')
          ->schema([
            Forms\Components\Select::make('costumer_id')
              ->label('Costumer')
              ->relationship('costumer', 'name')
              ->searchable()
              ->preload()
              ->required()
              ->createOptionForm([
                Forms\Components\TextInput::make('name')
                  ->required()
                  ->maxLength(255),
                Forms\Components\TextInput::make('email')
                  ->email()
                  ->required()
                  ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                  ->tel()
                  ->maxLength(length: 255),
                Forms\Components\Textarea::make('address')
                  ->rows(3)
              ]),
            Forms\Components\DatePicker::make('order_date')
              ->label('Order Date')
              ->required()
              ->default(now())
              ->native(false),

            Forms\Components\Select::make('status')
              ->options([
                'Pending' => 'Pending',
                'Paid' => 'Paid',
                'Cancelled' => 'Cancelled',
              ])
              ->required()
              ->default('Pending')
              ->native(false),

            Forms\Components\TextInput::make('total_amount')
              ->label('Total Amount')
              ->numeric()
              ->prefix("Rp")
              ->disabled()
              ->dehydrated(false)
              ->default(0)
          ])->columns(2)
      ]);
  }

  public static function table(Table $table): Table {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('Order #')
          ->sortable()
          ->searchable(),

        Tables\Columns\TextColumn::make('customer.name')
          ->label('Customer')
          ->sortable()
          ->searchable(),

        Tables\Columns\TextColumn::make('order_date')
          ->label('Order Date')
          ->sortable()
          ->date('d M Y'),

        Tables\Columns\TextColumn::make('total_amount')
          ->label('Total Amount')
          ->money('IDR')
          ->sortable(),

        Tables\Columns\BadgeColumn::make('status')
          ->colors([
            'warning' => 'Pending',
            'success' => 'Paid',
            'danger' => 'Cancelled',
          ]),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Created At')
          ->date('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('order_date')
          ->form([
            Forms\Components\DatePicker::make('order_date_from')
              ->label('From Date')
              ->native(false),

            Forms\Components\DatePicker::make('order_date_until')
              ->label('Until Date')
              ->native(false),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['order_date_from'],
                fn(Builder $query, $date) => $query->whereDate('order_date', '>=', $date),
              )
              ->when(
                $data['order_date_from'],
                fn(Builder $query, $date) => $query->whereDate('order_date', '<=', $date),
              );
          })
          ->indicateUsing(function (array $data) {
            $indicators = [];

            if ($data['order_date_from'] ?? null) {
              $indicators[] = 'From: ' . \Carbon\Carbon::parse($data['order_date_from'])->format('d M Y');
            }

            if ($data['order_date_until'] ?? null) {
              $indicators[] = 'Until: ' . \Carbon\Carbon::parse($data['order_date_until'])->format('d M Y');
            }

            return $indicators;
          }),
      ])
      ->actions([
        Tables\Actions\Action::make('markAsPaid')
          ->label('Mark as Paid')
          ->icon('heroicon-o-check-circle')
          ->color('success')
          ->requiresConfirmation()
          ->visible(fn(SalesOrder $record): bool => $record->status === 'Pending')
          ->action(function (SalesOrder $record) {
            $record->markAsPaid();

            Notification::make()
              ->title('Order Marked as Paid')
              ->success()
              ->send();
          }),
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->defaultSort('created_at', 'desc');
  }

  public static function getRelations(): array {
    return [
      ItemsRelationManager::class
    ];
  }

  public static function getPages(): array {
    return [
      'index' => Pages\ListSalesOrders::route('/'),
      'create' => Pages\CreateSalesOrder::route('/create'),
      'view' => Pages\ViewSalesOrder::route('/{record}'),
      'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
    ];
  }
}
