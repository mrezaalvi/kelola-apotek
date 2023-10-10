<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;
    
    protected static string $view = 'filament.resources.produk-resource.pages.view-produk';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->url(fn()=>ProdukResource::getUrl()),
            Actions\ActionGroup::make([
                Actions\Action::make('edit-multisatuan')
                    ->label('Multi Satuan/Harga')
                    ->color('primary')
                    ->icon('heroicon-m-square-3-stack-3d')
                    ->outlined()
                    ->url(fn()=>$this->getResource()::getUrl('multisatuan', ['record'=>$this->record])),
                Actions\EditAction::make()
                    ->label('Ubah')
                    ->color('primary')
                    ->icon('heroicon-m-pencil'),
            ]),
            
            
        ];
    }
}
