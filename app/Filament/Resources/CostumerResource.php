<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostumerResource\Pages;
use App\Filament\Resources\CostumerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostumerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'user';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),

            Forms\Components\FileUpload::make('image')
                ->label('Profile picture')
                ->image()
                ->directory('products')
                ->required(),

            Forms\Components\TextInput::make('password')
                ->label('password')
                ->password()
                ->maxLength(20),
        ]);
    }

    public static function table(Table $table): Table
    {
            return $table->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('password')
                    ->label('Password')
                    ->formatStateUsing(fn () => '***'),
                Tables\Columns\ImageColumn::make('image')->label('Image')->circular(),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCostumers::route('/'),
            'create' => Pages\CreateCostumer::route('/create'),
            'edit' => Pages\EditCostumer::route('/{record}/edit'),
        ];
    }
}
