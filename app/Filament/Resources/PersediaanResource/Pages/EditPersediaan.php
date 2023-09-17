<?php

namespace App\Filament\Resources\PersediaanResource\Pages;

use App\Filament\Resources\PersediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersediaan extends EditRecord
{
    protected static string $resource = PersediaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
