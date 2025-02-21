<?php

namespace App\Filament\Resources\OilDataResource\Pages;

use App\Filament\Resources\OilDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOilData extends EditRecord
{
    protected static string $resource = OilDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
