<?php

namespace App\Filament\Resources\PersediaanResource\Pages;

use App\Filament\Resources\PersediaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersediaans extends ListRecords
{
    protected static string $resource = PersediaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->label('Tambah Persediaan'),
        ];
    }
}
