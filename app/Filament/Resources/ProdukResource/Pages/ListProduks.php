<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Filament\Resources\ProdukResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListProduks extends ListRecords
{
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('produk-import')
                ->label('Import Produk')
                ->outlined()
                ->icon('heroicon-s-arrow-up-tray')
                ->modalButton('Import Data Produk')
                ->form([
                    Forms\Components\FileUpload::make('produk-import-file')
                        ->label('File Import')
                        ->disk('file-import')
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(
                    function(array $data):void{
                        if($data['produk-import-file'])
                        {
                            Excel::import(new ProdukImport, $data['produk-import-file'], 'file-import');
                            Storage::disk('file-import')->delete($data['produk-import-file']);
                        }
                    }
                ),
            Actions\CreateAction::make()
                ->label('Buat Data Produk')
                ->icon('heroicon-m-plus'),
        ];
    }
}
