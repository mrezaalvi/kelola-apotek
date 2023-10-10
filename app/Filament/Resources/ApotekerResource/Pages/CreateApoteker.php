<?php

namespace App\Filament\Resources\ApotekerResource\Pages;

use App\Filament\Resources\ApotekerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApoteker extends CreateRecord
{
    protected static string $resource = ApotekerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
