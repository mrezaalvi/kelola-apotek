<?php

namespace App\Filament\Resources\LokasiResource\Pages;

use App\Filament\Resources\LokasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLokasis extends ManageRecords
{
    protected static string $resource = LokasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Data Lokasi')
                ->icon('heroicon-m-plus')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();
            
                    return $data;
                }),
        ];
    }
}
