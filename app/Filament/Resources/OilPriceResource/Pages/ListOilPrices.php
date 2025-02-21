<?php

namespace App\Filament\Resources\OilPriceResource\Pages;

use App\Filament\Resources\OilPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOilPrices extends ListRecords
{
    protected static string $resource = OilPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
