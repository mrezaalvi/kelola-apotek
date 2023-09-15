<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->outlined()
                ->color('danger')
                ->icon('heroicon-m-arrow-uturn-left')
                ->url(fn()=>ProdukResource::getUrl()),
            Actions\EditAction::make()
                ->label('Ubah Data Produk')
                ->icon('heroicon-m-pencil'),
        ];
    }
}
