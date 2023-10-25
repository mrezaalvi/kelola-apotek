<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Support;
use App\Models\Produk;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProdukResource;
use Illuminate\Contracts\Support\Htmlable;

class PersediaanProduk extends EditRecord
{
    protected static string $resource = ProdukResource::class;

    protected static string $view = 'filament.resources.produk-resource.pages.persediaan-produk';

    public function getBreadcrumb(): string
    {
        return static::$breadcrumb ?? __('Daftar Persediaan');
    }

    public function mount(int | string $record):void
    {
        parent::mount($record);
        abort_unless($this->record->satuan_id, 404);        
    }

    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);
    }
    
    public function getTitle(): string | Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return $this->getRecordTitle()." - Persediaan";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->url(fn(Produk $record)=> ProdukResource::getUrl('view', ['record' => $record])),           
        ];
    }
    
    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->autofocus(fn(string $operation)=>$operation==='edit')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(150)
                                    ->disabled()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('kode')
                                    ->label('Kode/SKU')
                                    ->maxLength(40)
                                    ->disabled(),
                                Forms\Components\TextInput::make('barcode')
                                    ->label('Barcode')
                                    ->maxLength(40)
                                    ->disabled(),
                            ]),
                    ]),
            ])
            ->model($this->getRecord())
            ->statePath('data')
            ->operation('persediaan');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            //
        ];
    }
}
