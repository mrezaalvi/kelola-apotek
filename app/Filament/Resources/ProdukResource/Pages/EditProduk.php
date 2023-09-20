<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProdukResource;

class EditProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['last_edited_by'] = auth()->id();
    
        return $data;
    }

    /** 
     *  Customizing the saving process
    */
    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $record->update($data);
    
    //     return $record;
    // }
}
