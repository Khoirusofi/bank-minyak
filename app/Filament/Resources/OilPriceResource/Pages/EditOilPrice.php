<?php

namespace App\Filament\Resources\OilPriceResource\Pages;

use App\Filament\Resources\OilPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOilPrice extends EditRecord
{
    protected static string $resource = OilPriceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
