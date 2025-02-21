<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\UserResource\Pages;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\FontWeight;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Data Nasabah';
    protected static ?string $navigationGroup = 'Nasabah';
    protected static ?int $navigationSort = 1;


    public static function getLabel(): string
    {
        return 'Data Nasabah';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Nasabah';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'Jumlah Data Nasabah';

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
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Peran'),
                    // ->hidden(function ($get) {
                    //     return $get('roles.name') === 'super_admin';
                    // }),

                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(16),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required(fn (string $context) => $context === 'create')
                    ->maxLength(255)
                    ->visible(fn (string $context) => $context === 'create')
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null),

                Forms\Components\TextInput::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('number')
                    ->label('No Telepon')
                    ->required()
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->maxLength(25),

                Forms\Components\TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\TextInput::make('bank_number')
                    ->label('No Rekening')
                    ->nullable()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('No')
                // ->rowIndex(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Nasabah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Peran'),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('number')
                    ->label('No Telepon')
                    ->formatStateUsing((fn (User $record): string => $record->number
                    ? self::formatBankNumber($record->number)
                    : 'No Telepon belum diinput'
                    ))
                    ->icon('heroicon-m-phone')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Nama Bank')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bank_number')
                    ->label('No Rekening')
                    ->formatStateUsing(fn (User $record): string =>
                    $record->bank_number
                        ? self::formatBankNumber($record->bank_number)
                        : 'Data belum diinput'
                        ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->formatStateUsing(fn (User $record): string => Carbon::parse($record->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')),

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
                Tables\Actions\ViewAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable()->withFilename(date('d-m-Y') . ' - Data Nasabah'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
