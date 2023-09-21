<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ProdukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduk extends CreateRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
    
        return $data;
    }

    /**
     * Customizing the creation process
     */
    protected function handleRecordCreation(array $data): Model
    {
        DB::transaction(function () use ($data) {
            $produkCreated = static::getModel()::create($data);
        });
        return $produkCreated;
    }

    // protected function afterCreate(): void
    // {
    //     // Runs after the form fields are saved to the database.
    // }
    
}
