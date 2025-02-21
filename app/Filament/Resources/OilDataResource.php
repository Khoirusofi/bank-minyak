<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Deposit;
use App\Models\OilData;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OilDataResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OilDataResource\RelationManagers;

class OilDataResource extends Resource
{
    protected static ?string $model = OilData::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationLabel = 'Total Saldo Nasabah';
    protected static ?string $navigationGroup = 'Simpanan Nasabah';
    protected static ?int $navigationSort = 3;

    public static function getLabel(): string
    {
        return 'Data Total Saldo Simpanan';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Total Saldo Simpanan';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'Jumlah Data Total Saldo Simpanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Nasabah')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->icon('heroicon-m-user')
                    ->iconColor('primary'),

                 Tables\Columns\TextColumn::make('total_saldo_price')
                    ->weight(FontWeight::Bold)
                    ->label('Total Saldo Simpanan')
                    ->formatStateUsing(fn ($state) => 'Rp. ' . number_format($state, 0, ',', '.'))
                    ->wrap()
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('primary'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListOilData::route('/'),
            // 'create' => Pages\CreateOilData::route('/create'),
            // 'edit' => Pages\EditOilData::route('/{record}/edit'),
        ];
    }
}
