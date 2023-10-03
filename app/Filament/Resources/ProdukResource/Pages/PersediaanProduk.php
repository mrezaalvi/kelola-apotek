<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support;
use App\Filament\Resources\ProdukResource;

class PersediaanProduk extends ViewRecord
{
    protected static string $resource = ProdukResource::class;

    protected static string $view = 'filament.resources.produk-resource.pages.persediaan-produk';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->url(fn()=>ProdukResource::getUrl()),           
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Produk')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('nama')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(Support\Enums\FontWeight::Bold)
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('kode')
                            ->copyable()
                            ->copyMessage('Salin!')
                            ->copyMessageDuration(1500),
                        Infolists\Components\TextEntry::make('barcode')
                            ->copyable()
                            ->copyMessage('Salin!')
                            ->copyMessageDuration(1500),
                    ])
                    ->columns(4),
            ])
            ->record($this->getRecord())
            ->columns($this->hasInlineLabels() ? 1 : 2)
            ->inlineLabel($this->hasInlineLabels());
    }
}
