<?php

namespace App\Filament\Resources\OilDataResource\Pages;

use App\Filament\Resources\OilDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOilData extends ListRecords
{
    protected static string $resource = OilDataResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
