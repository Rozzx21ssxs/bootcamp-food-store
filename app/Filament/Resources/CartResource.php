<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Carts & Orders';

public static function getNavigationSort(): ?int
{
    return 4;
}

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'title') // pastikan relasi di model Cart ke Product benar
                ->required(),

            Forms\Components\Select::make('customer_id')
                ->label('Customer')
                ->relationship('customer', 'name') // pastikan relasi di model Cart ke Customer benar
                ->required(),

            Forms\Components\TextInput::make('qty')
                ->label('Quantity')
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),

            Forms\Components\TextInput::make('total_price')
                ->label('Total Price')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending')
                ->required(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('product.title')->searchable(),
            Tables\Columns\TextColumn::make('customer.name')->searchable(),
            Tables\Columns\TextColumn::make('qty'),
            Tables\Columns\TextColumn::make('total')
            ->money('IDR', locale: 'id')
            ->getStateUsing(fn($record) => $record->qty * $record->product->price),
        ])
        ->filters([
            //
        ])
        ->actions([
            //Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
            Tables\Actions\DeleteBulkAction::make(),
        ]),
    ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
{
    return [
        'index' => Pages\ListCarts::route('/'),
    ];
}

    public static function canCreate(): bool
{
    return true;
}
}
