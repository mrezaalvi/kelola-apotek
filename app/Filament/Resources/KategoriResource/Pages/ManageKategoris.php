<?php

namespace App\Filament\Resources\KategoriResource\Pages;

use App\Filament\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoris extends ManageRecords
{
    protected static string $resource = KategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Data Kategori')
                ->icon('heroicon-m-plus')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();
            
                    return $data;
                }),
        ];
    }
}
