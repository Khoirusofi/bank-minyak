<?php

namespace App\Filament\Resources;

use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaxResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaxResource\RelationManagers;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Biaya Admin';
    protected static ?string $navigationGroup = 'Harga Minyak & Biaya Admin';
    protected static ?int $navigationSort = 2;


    public static function getLabel(): string
    {
        return 'Biaya Admin';
    }

    public static function getPluralLabel(): string
    {
        return 'Biaya Admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('taxCash')
                ->label('Biaya Admin Tunai')
                ->prefix('Rp ')
                ->numeric()
                ->required(),

                Forms\Components\TextInput::make('taxTransfer')
                ->label('Biaya Admin Transfer')
                ->prefix('Rp ')
                ->numeric()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('taxCash')
                ->weight(FontWeight::Bold)
                ->label('Biaya Admin Tunai')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->wrap()
                ->icon('heroicon-m-banknotes')
                ->iconColor('primary'),

            Tables\Columns\TextColumn::make('taxTransfer')
                ->weight(FontWeight::Bold)
                ->label('Biaya Admin Transfer')
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
            'index' => Pages\ListTaxes::route('/'),
            'create' => Pages\CreateTax::route('/create'),
            'edit' => Pages\EditTax::route('/{record}/edit'),
        ];
    }
}
