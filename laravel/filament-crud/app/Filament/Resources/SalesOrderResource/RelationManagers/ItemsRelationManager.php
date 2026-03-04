<?php

namespace App\Filament\Resources\SalesOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager {
  protected static string $relationship = 'items';

  protected static ?string $title = 'Order Items';

  public function form(Form $form): Form {
    return $form
      ->schema([
        Forms\Components\Select::make('product_id')
          ->label('Product')
          ->relationship('product', 'name')
          ->searchable()
          ->preload()
          ->required()
          ->reactive()
          ->afterStateUpdated(function ($state, callable $set) {
            if ($state) {
              $product = \App\Models\Product::find($state);
              if ($product) {
                $set('price', $product->price);
              }
            }
          })
          ->createOptionForm([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(255),

            Forms\Components\Textarea::make('description')
              ->rows(3),

            Forms\Components\TextInput::make('price')
              ->required()
              ->numeric()
              ->prefix('Rp'),

            Forms\Components\TextInput::make('stock')
              ->required()
              ->numeric()
              ->default(0),
          ]),

        Forms\Components\TextInput::make('quantity')
          ->label('Quantity')
          ->numeric()
          ->required()
          ->minValue(1)
          ->default(1)
          ->reactive()
          ->afterStateUpdated(function ($state, callable $set, callable $get) {
            $price = $get('price') ?? 0;
            $set('subtotal', $state * $price);
          }),

        Forms\Components\TextInput::make('price')
          ->label('Price')
          ->numeric()
          ->required()
          ->prefix('Rp')
          ->reactive()
          ->afterStateUpdated(function ($state, callable $set, callable $get) {
            $quantity = $get('quantity') ?? 1;
            $set('subtotal', $state * $quantity);
          }),

        Forms\Components\TextInput::make('subtotal')
          ->label('Subtotal')
          ->numeric()
          ->prefix('Rp')
          ->disabled()
          ->dehydrated()
          ->default(0)
      ]);
  }

  public function table(Table $table): Table {
    return $table
      ->recordTitleAttribute('product.name')
      ->columns([
        Tables\Columns\TextColumn::make('product.name')
          ->label('Product')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('quantity')
          ->label('Quantity')
          ->sortable(),

        Tables\Columns\TextColumn::make('price')
          ->label('Price')
          ->money('IDR')
          ->sortable(),

        Tables\Columns\TextColumn::make('subtotal')
          ->label('Subtotal')
          ->money('IDR')
          ->sortable(),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make()
          ->mutateFormDataUsing(function (array $data): array {
            $data['subtotal'] = $data['quantity'] * $data['price'];
            return $data;
          }),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }
}
