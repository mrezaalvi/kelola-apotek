<?php

namespace App\Filament\Resources\PersediaanResource\Pages;

use App\Filament\Resources\PersediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPersediaan extends ViewRecord
{
    protected static string $resource = PersediaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
