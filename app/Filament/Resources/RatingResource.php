<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatingResource\Pages;
use App\Filament\Resources\RatingResource\RelationManagers;
use App\Models\Rating;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'user';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Form $form): Form
{
    return $form->schema([
            Select::make('transaction_detail_id')
                    ->label('Transaksi')
                    ->options(function (): array {
                        return TransactionDetail::all()->pluck('id', 'id')->all();
                    })
                    ->searchable()
                    ->required(),

            Forms\Components\Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'title')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('customer_id')
                ->label('Customer')
                ->relationship('customer', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('rating')
                ->label('Rating')
                ->options([
                    1 => '★☆☆☆☆',
                    2 => '★★☆☆☆',
                    3 => '★★★☆☆',
                    4 => '★★★★☆',
                    5 => '★★★★★',
                ])
                ->required(),

            Forms\Components\Textarea::make('review')
                ->label('Review')
                ->nullable(),
    ]);
}

public static function table(Table $table): Table
{
    return $table->columns([
        Tables\Columns\TextColumn::make('product.title')->label('Product'),
        Tables\Columns\TextColumn::make('customer.name')->label('Customer'),
        Tables\Columns\TextColumn::make('rating')
            ->label('Rating')
            ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state)),
        Tables\Columns\TextColumn::make('review')->limit(50),
          Tables\columns\Textcolumn::make('created_at')
                ->label('Tanggal')
                ->formatStateUsing(function ($state) {
                    return Carbon::parse($state)->translatedFormat('l, d F Y H.i');
                })
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
            'index' => Pages\ListRatings::route('/'),
            'create' => Pages\CreateRating::route('/create'),
            'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}