<?php

namespace App\Filament\Resources;

use App\Models\Tax;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Redeem;
use App\Models\OilData;
use App\Models\OilPrice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RedeemResource\Pages;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\FontWeight;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
class RedeemResource extends Resource
{
    protected static ?string $model = Redeem::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Pencairan Nasabah';
    protected static ?string $navigationGroup = 'Simpanan & Pencairan';
    protected static ?int $navigationSort = 2;


    public static function getLabel(): string
    {
        return 'Data Pencairan Nasabah';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Pencairan Nasabah';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'Jumlah Pencairan Minyak Nasabah';

    public static function formatBankNumber($number)
    {
        // Menghapus karakter selain angka terlebih dahulu
        $number = preg_replace('/\D/', '', $number);

        // Membagi menjadi potongan 4 digit, 8 digit, 12 digit, dan 16 digit
        $formatted = substr_replace($number, ' ', 4, 0);
        $formatted = substr_replace($formatted, ' ', 9, 0);
        $formatted = substr_replace($formatted, ' ', 14, 0);
        $formatted = substr_replace($formatted, ' ', 19, 0);

        return $formatted;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_trx_id')
                    ->required()
                    ->label('ID pencairan')
                    ->default(fn () => Redeem::generateUniqueTrxId())
                    ->readOnly()
                    ->maxLength(255),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->placeholder('Pilih Nasabah')
                    ->label('Nama Nasabah')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $oilData = OilData::where('user_id', $state)->first();
                        $set('available_saldo', $oilData ? $oilData->total_saldo_price : 0);
                    }),

                Forms\Components\TextInput::make('total_redeem_price')
                    ->numeric()
                    ->required()
                    ->live()
                    ->columnSpanFull()
                    ->label('Total Pencairan')
                    ->prefix(fn ($get) => 'Saldo Tersedia: Rp ' . number_format($get('available_saldo') ?? 0, 0, ',', '.') . ' | Pencairan Sebesar: Rp ')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $userId = $get('user_id');
                        $oilPrice = OilPrice::latest()->first();
                        $tax = Tax::latest()->first();
                        $oilData = OilData::where('user_id', $userId)->lockForUpdate()->first();

                        if (!$oilPrice) {
                            Notification::make()
                                ->title('Harga minyak belum tersedia!')
                                ->danger()
                                ->send();
                            $set('total_redeem_price', null);
                            return;
                        }

                        if ($oilData && $state > $oilData->total_saldo_price) {
                            Notification::make()
                                ->title('Saldo tidak mencukupi!')
                                ->danger()
                                ->send();
                            $set('total_redeem_price', null);
                            return;
                        }

                        $totalSaldo = $oilData ? $oilData->total_saldo_price : 0;
                        $totalPendingRedeem = Redeem::where('user_id', $userId)
                            ->where('status', 'pending')
                            ->sum('total_redeem_price');
                        $availableSaldo = $totalSaldo - $totalPendingRedeem;

                        if ($state > $availableSaldo) {
                            Notification::make()
                                ->title('Saldo tidak mencukupi untuk pencairan baru!')
                                ->danger()
                                ->send();
                            $set('total_redeem_price', null);
                            return;
                        }

                        // Ambil metode pencairan
                        $method = $get('method');
                        $taxAmount = $method === 'cash' ? $tax->taxCash : $tax->taxTransfer;
                        $grandTotal = round($state - $taxAmount);

                        $set('tax', $taxAmount);
                        $set('grand_redeem_total', $grandTotal);
                    }),

                Forms\Components\TextInput::make('tax')
                    ->numeric()
                    ->readOnly()
                    ->live()
                    ->label('Biaya Admin')
                    ->dehydrated(),

                Forms\Components\TextInput::make('grand_redeem_total')
                    ->numeric()
                    ->readOnly()
                    ->label('Total Pencairan Bersih')
                    ->live()
                    ->dehydrated(),

                Forms\Components\Select::make('method')
                    ->options([
                        'transfer' => 'Transfer',
                        'cash' => 'Cash',
                    ])
                    ->required()
                    ->placeholder('Pilih Metode Pencairan')
                    ->live()
                    ->label('Metode Pencairan')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $userId = $get('user_id');

                        if (!$userId) {
                            Notification::make()
                                ->title('Pilih nasabah terlebih dahulu!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $user = User::find($userId);

                        if ($state === 'transfer') {
                            if ($user && $user->bank_number) {
                                $set('bank_number', $user->bank_number);
                            } else {
                                Notification::make()
                                    ->title('No Rekening tidak ditemukan, harap perbarui profil nasabah.')
                                    ->danger()
                                    ->send();
                                $set('bank_number', null);
                            }
                        } else {
                            $set('bank_number', null);
                        }

                        // Perbarui nilai tax sesuai metode pencairan
                        $tax = Tax::latest()->first();
                        $taxAmount = $state === 'cash' ? $tax->taxCash : $tax->taxTransfer;
                        $totalRedeem = $get('total_redeem_price');
                        $grandTotal = $totalRedeem ? round($totalRedeem - $taxAmount) : null;

                        $set('tax', $taxAmount);
                        $set('grand_redeem_total', $grandTotal);
                    }),

                Forms\Components\TextInput::make('bank_number')
                    ->default(fn ($get) => $get('method') === 'transfer' ? (User::find($get('user_id'))?->bank_number ?? 'No Rekening Tidak Ada') : null)
                    ->required()
                    ->label('No Rekening')
                    ->readOnly()
                    ->hidden(fn ($get) => $get('method') !== 'transfer')
                    ->dehydrated(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Proses',
                        'approved' => 'Berhasil',
                        'rejected' => 'Gagal',
                    ])
                    ->required()
                    ->default('pending')
                    ->placeholder('Pilih Status')
                    ->label('Status')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $status = $state;
                        $redeem = $get('model');
                        if ($redeem) {
                            if ($status === 'approved') {
                                $redeem->adjustOilData('reduce');
                            } elseif ($redeem->getOriginal('status') === 'approved' && $status !== 'approved') {
                                $redeem->adjustOilData('rollback');
                            }
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_trx_id')
                    ->label('No Pencairan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nasabah')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_redeem_price')
                    ->label('Total Pencairan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('tax')
                    ->label('Biaya Admin')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('grand_redeem_total')
                    ->weight(FontWeight::Bold)
                    ->label('Total Pencairan Bersih')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->icon('heroicon-m-banknotes')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode Pencairan')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'transfer' => 'Transfer',
                        'cash' => 'Cash',
                    }),

                Tables\Columns\TextColumn::make('user.bank_name')
                    ->label('No Rekening'),

                Tables\Columns\TextColumn::make('bank_number')
                    ->label('No Rekening')
                    ->formatStateUsing(fn (Redeem $record): string =>
                    $record->bank_number
                        ? self::formatBankNumber($record->bank_number)
                        : 'Tidak Ada No Rekening'
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pencairan')
                    ->formatStateUsing(fn (Redeem $record): string => Carbon::parse($record->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')),

                Tables\Columns\TextColumn::make('status')
                    ->weight(FontWeight::Bold)
                    ->color(fn(?string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'pending' => 'Proses',
                        'approved' => 'Berhasil',
                        'rejected' => 'Gagal',
                    })
                    ->icon(fn(?string $state): ?string => match ($state) {
                        'approved' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-clock',
                        'rejected' => 'heroicon-o-x-circle',
                    })
                    ->sortable()
                    ->label('Status'),

            ])
            ->filters([
                Tables\Filters\Filter::make('approved')
                    ->label('Sukses')
                    ->query(fn (Builder $query) => $query->where('status', 'approved')),

                Tables\Filters\Filter::make('pending')
                    ->label('Proses')
                    ->query(fn (Builder $query) => $query->where('status', 'pending')),

                Tables\Filters\Filter::make('rejected')
                    ->label('Gagal')
                    ->query(fn (Builder $query) => $query->where('status','rejected')),

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
            ->defaultSort('updated_at', 'desc')
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable()->withFilename(date('d-m-Y') . ' - Data Pencairan Nasabah'),
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
            'index' => Pages\ListRedeems::route('/'),
            'create' => Pages\CreateRedeem::route('/create'),
            'edit' => Pages\EditRedeem::route('/{record}/edit'),
        ];
    }

}
