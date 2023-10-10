<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Models\Satuan;
use Filament\Forms;
use Filament\Pages;
use Filament\Resources;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource;
use Illuminate\Contracts\Support\Htmlable;

class MultiSatuanProduk extends Resources\Pages\Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use Resources\Pages\Concerns\InteractsWithRecord;
    use Pages\Concerns\InteractsWithFormActions;

    protected static string $resource = ProdukResource::class;

    protected static string $view = 'filament.resources.produk-resource.pages.multi-satuan-produk';

    public ?array $data=[];

    public ?string $previousUrl = null;

    public function mount(int | string $record):void
    {
        $this->record = $this->resolveRecord($record);
     
        $this->form->fill($this->record->attributesToArray());

        $this->previousUrl = url()->previous();
    }

    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
            Forms\Components\Section::make()
                    ->schema([
                        
                        Forms\Components\Grid::make(3)
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
                            ]),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan Dasar')
                            ->relationship('satuan', 'nama')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->extraAttributes(['class' => 'max-w-xs']),
                        Forms\Components\Grid::make()
                            ->schema([                            
                                Forms\Components\TextInput::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->numeric()           
                                    ->placeholder('0,00')
                                    ->live(onBlur: true)
                                    ->extraInputAttributes(['class' => 'text-end'])
                                    ->disabled(),

                                Forms\Components\TextInput::make('harga_jual')
                                    ->label('Harga Jual')
                                    ->numeric()
                                    ->placeholder('0,00')
                                    ->live()
                                    ->hint(function(Forms\Get $get){
                                        $hargaBeli = floatval($get('harga_beli'));
                                        $hargaJual = floatval($get('harga_jual'));
                                        $marginHarga = 0;
                                        if($hargaBeli && $hargaJual)
                                            $marginHarga = number_format((($hargaJual - $hargaBeli)/$hargaBeli)*100,2);
                                        return "Margin : ".$marginHarga."%";
                                    })
                                    ->hintColor('info')          
                                    ->extraInputAttributes(function(Forms\Get $get){
                                        $attribute = ['class' => 'text-end'];
                                        $readOnlyAttribute = ['class'=>'text-end bg-gray-200','readonly' => 'true'];
                                        if($get('use_margin'))
                                            $attribute = $readOnlyAttribute;
                                        return $attribute;
                                    })
                                    ->disabled(),
                            ]),
                        Forms\Components\Repeater::make('multiSatuan')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Select::make('satuan_lanjutan')
                                            ->label('Satuan Lanjutan')
                                            ->relationship(
                                                'satuan', 
                                                'nama',
                                                fn(Builder $query, Forms\Get $get) 
                                                    => $query->where('id', '!=', $get('../../satuan')) 
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('nama')
                                                    ->unique(ignoreRecord: true)
                                                    ->required(),
                                            ]),
                                        Forms\Components\TextInput::make('nilai_konversi')
                                            ->label('Nilai Konversi')
                                            ->numeric()
                                            ->placeholder('0')
                                            ->extraInputAttributes(['class' => 'text-end'])
                                            ->required()
                                            ->suffix(
                                                fn(Forms\Get $get)=>
                                                    $get('../../satuan')?Satuan::find($get('../../satuan'))->nama:'satuan'),
                                        Forms\Components\TextInput::make('harga_beli')
                                            ->label('Harga Beli')
                                            ->numeric()
                                            ->placeholder('0')
                                            ->extraInputAttributes(['class' => 'text-end'])
                                            ->required(),
                                        Forms\Components\TextInput::make('harga_jual')
                                            ->label('Harga Jual')
                                            ->numeric()
                                            ->placeholder('0')
                                            ->extraInputAttributes(['class' => 'text-end'])
                                            ->required(),
                                    ]),  
                            ])
                            ->defaultItems(0)
                            ->hidden(fn(Forms\Get $get)=>!$get('satuan'))
                            ->reorderable(false)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderableWithButtons(),
                    ]),
        ])
        ->model($this->getRecord())
        ->statePath('data')
        ->operation('multisatuan');
    }
 
    public function getTitle(): string | Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('Ubah Multi Satuan', [
            'label' => $this->getRecordTitle(),
        ]);
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s'])
            ->successRedirectUrl($this->getResource()::getUrl('view',['record' => $this->record]));
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view',['record' => $this->record]);
    }

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        try {
            $data = $this->form->getState();

            $this->record->update($data);
           
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
        
        $this->redirect($this->getRedirectUrl());
    }
}
