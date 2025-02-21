<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Deposit;
use App\Models\OilPrice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\DepositResource\Pages;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\FontWeight;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Simpanan Nasabah';
    protected static ?string $navigationGroup = 'Simpanan & Pencairan';
    protected static ?int $navigationSort = 1;


    public static function getLabel(): string
    {
        return 'Data Simpanan Nasabah';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Simpanan Nasabah';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'Jumlah Data Simpanan Minyak Nasabah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Nama Nasabah')
                    ->placeholder('Pilih Nasabah')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\TextInput::make('total_liter')
                    ->label('Total Liter')
                    ->placeholder('Total Liter Simpanan')
                    ->numeric()
                    ->live()
                    ->step('0.01')
                    ->required()
                    ->suffix('Liter')
                    ->afterStateUpdated(function (callable $set, $state) {
                        $oilPrice = OilPrice::latest()->first();
                        if ($oilPrice && $state) {
                            $totalPrice = $oilPrice->price * (float) $state;
                            $set('total_price', $totalPrice);
                            $set('oil_price', $oilPrice->price);
                        } else {
                            $set('total_price', 0);
                            $set('oil_price', 0);
                        }
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Saldo')
                    ->placeholder('Total saldo yang didapatkan')
                    ->numeric()
                    ->live()
                    ->step('0.01')
                    ->prefix('Rp ')
                    ->readOnly(),

                Forms\Components\TextInput::make('oil_price')
                    ->label('Harga Perliter')
                    ->numeric()
                    ->live()
                    ->suffix('Rp')
                    ->readOnly(),
            ]);
        }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_liter')
                    ->weight(FontWeight::Bold)
                    ->label('Total Liter Simpanan')
                    ->formatStateUsing(fn ($state): string => $state . ' Liter')
                    ->wrap()
                    ->icon('heroicon-m-arrow-down-on-square')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('oil_price')
                    ->label('Harga Perliter Saat Itu')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('total_price')
                    ->weight(FontWeight::Bold)
                    ->label('Total Harga Simpanan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->wrap()
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updater.name')
                    ->label('Diperbarui Oleh')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Simpanan')
                    ->formatStateUsing(fn (Deposit $record): string => Carbon::parse($record->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Filter::make('from_date')
                    ->form([
                                DatePicker::make('from')
                                    ->label('Dari Tanggal')
                                    ->native(false) // Agar menggunakan UI Datepicker Filament
                                    ->displayFormat('d M Y') // Format Indonesia
                                    ->closeOnDateSelection(),

                                DatePicker::make('to')
                                    ->label('Sampai Tanggal')
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->closeOnDateSelection(),
                            ])
                            ->query(function ($query, array $data) {
                                if (!empty($data['from']) && !empty($data['to'])) {
                                    return $query->whereBetween('created_at', [
                                        Carbon::parse($data['from'])->startOfDay(),
                                        Carbon::parse($data['to'])->endOfDay(),
                                    ]);
                                }
                                return $query;
                            })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable()->withFilename(date('d-m-Y') . ' - Data Simpanan Nasabah'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
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
            'index' => Pages\ListDeposits::route('/'),
            'create' => Pages\CreateDeposit::route('/create'),
            'edit' => Pages\EditDeposit::route('/{record}/edit'),
        ];
    }
}
