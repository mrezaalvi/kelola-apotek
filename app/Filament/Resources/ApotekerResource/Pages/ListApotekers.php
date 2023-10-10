<?php

namespace App\Filament\Resources\ApotekerResource\Pages;

use App\Filament\Resources\ApotekerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApotekers extends ListRecords
{
    protected static string $resource = ApotekerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Data Apoteker')
                ->icon('heroicon-m-plus'),
        ];
    }
}
