<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Support\Enums\Alignment;
use App\Forms\Components\FileDownload;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProdukResource;

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
                    FileDownload::make('import-produk-file')
                        ->label('File Import Template')
                        ->url(Storage::disk('files-import')->url('templates\produk-import-template.csv'))
                        ->dehydrated(false),
                    Forms\Components\FileUpload::make('produk-import-file')
                        ->label('File Import')
                        ->disk('files-import')
                        ->preserveFilenames()
                        ->required(),
                ])
                ->action(
                    function(array $data):void{
                        if($data['produk-import-file'])
                        {
                            Excel::import(new ProdukImport, $data['produk-import-file'], 'files-import');
                            Storage::disk('files-import')->delete($data['produk-import-file']);
                        }
                    }
                )
                ->hidden(! auth()->user()->hasPermissionTo('product: import')),
            Actions\CreateAction::make()
                ->label('Buat Data Produk')
                ->icon('heroicon-m-plus'),
        ];
    }
}
