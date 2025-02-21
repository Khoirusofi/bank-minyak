<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\OilPrice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OilPriceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OilPriceResource\RelationManagers;

class OilPriceResource extends Resource
{
    protected static ?string $model = OilPrice::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Harga Minyak';
    protected static ?string $navigationGroup = 'Harga Minyak & Biaya Admin';
    protected static ?int $navigationSort = 1;
    // protected static ?int $navigationSort = 99;



    public static function getLabel(): string
    {
        return 'Harga Minyak Perliter';
    }

    public static function getPluralLabel(): string
    {
        return 'Harga Minyak Perliter';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('price')
                ->label('Harga Minyak Perliter')
                ->prefix('Rp ')
                ->numeric()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->weight(FontWeight::Bold)
                    ->label('Harga Minyak Perliter')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->wrap()
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('primary'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
               //
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
            'index' => Pages\ListOilPrices::route('/'),
            'create' => Pages\CreateOilPrice::route('/create'),
            'edit' => Pages\EditOilPrice::route('/{record}/edit'),
        ];
    }
}
