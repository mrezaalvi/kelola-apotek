<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Support;
use App\Models\Produk;

use App\Models\Satuan;
use Filament\Infolists;
use Filament\Support\RawJs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\Alignment;

use Illuminate\Validation\Rules\Unique;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Data Produk';

    protected static ?string $pluralModelLabel = 'Data Produk';

    protected static ?string $modelLabel = 'Data Produk';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-produk';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->autofocus(fn(string $operation)=>$operation==='edit')
                            ->unique(ignoreRecord: true)
                            ->autocomplete(false)
                            ->required()
                            ->maxLength(150),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode')
                                    ->label('Kode/SKU')
                                    ->autocomplete(false)
                                    ->maxLength(40),
                                Forms\Components\TextInput::make('barcode')
                                    ->maxLength(50)
                                    ->autocomplete(false)
                                    ->suffixIcon('heroicon-m-view-columns'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('kategories')
                                    ->label('Kategori')
                                    ->multiple()
                                    ->relationship('kategories', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->unique(ignoreRecord: true)
                                            ->required(),
                                    ]),
                                Forms\Components\Select::make('satuan')
                                    ->label('Satuan Dasar')
                                    ->relationship('satuan', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->live(onBlur: true)
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->unique(ignoreRecord: true)
                                            ->required(),
                                    ])
                                    ->required(),
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
                                                fn(Eloquent\Builder $query, Forms\Get $get) 
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
                                            ->autocomplete(false)
                                            ->extraInputAttributes(['class' => 'text-end'])
                                            ->required()
                                            ->suffix(
                                                fn(Forms\Get $get)=>
                                                    $get('../../satuan')?Satuan::find($get('../../satuan'))->nama:'satuan'),
                                    ]),  
                            ])
                            ->defaultItems(0)
                            ->hidden(fn(Forms\Get $get)=>!$get('satuan'))
                            ->hiddenOn('edit')
                            ->reorderable(false)
                            ->reorderableWithButtons(),

                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(2)
                            ->autocomplete(false)
                            ->maxLength(255),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('pabrik')
                                    ->autocomplete(false),
                                Forms\Components\TextInput::make('kemasan')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        
                        Forms\Components\TextInput::make('minimal_stok')
                            ->label('Stok Minimal')
                            ->numeric()
                            ->placeholder('0')
                            ->default(0)
                            ->autocomplete(false)
                            ->extraAttributes(['class' => 'max-w-xs'])
                            ->extraInputAttributes(['class' => 'text-end']),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('margin_harga')
                                    ->label('Margin')
                                    ->numeric()
                                    ->autocomplete(false)
                                    ->placeholder('0,00')
                                    ->suffix('%')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                        $hargaBeli = floatval($get('harga_beli')) ;
                                        $marginHarga = floatval($state);
                                        $gunakanMargin = $get('use_margin'); 
                                        if(
                                            $gunakanMargin
                                        ){
                                            if(($hargaBeli == 0)){
                                                return $set('harga_jual', 0);    
                                            }

                                            if(($marginHarga == 0)){
                                                return $set('harga_jual', $hargaBeli);    
                                            }
                                            $hargaJual = ($hargaBeli + (($hargaBeli * $marginHarga)/100));
                                            return $set('harga_jual', $hargaJual);
                                        }
                                    })                                    
                                    ->extraAttributes(['class' => 'max-w-xs'])
                                    ->extraInputAttributes(['class' => 'text-end']),
                                Forms\Components\Toggle::make('use_margin')
                                    ->label('Gunakan Margin?')
                                    ->dehydrated(false)
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                        $hargaBeli = floatval($get('harga_beli')) ;
                                        $marginHarga = floatval($get('margin_harga'));
                                        $gunakanMargin = $state; 
                                        if(
                                            $gunakanMargin
                                        ){
                                            if(($hargaBeli == 0)){
                                                return $set('harga_jual', 0);    
                                            }

                                            if(($marginHarga == 0)){
                                                return $set('harga_jual', $hargaBeli);    
                                            }
                                            $hargaJual = ($hargaBeli + (($hargaBeli * $marginHarga)/100));
                                            return $set('harga_jual', $hargaJual);
                                        }
                                    })
                                    ->helperText(new HtmlString('<span class="text-primary-500">Aktifkan untuk menggunakan margin.</span>')),
                            ])->columns(1),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->numeric()         
                                    ->autocomplete(false)   
                                    ->placeholder('0,00')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                        $hargaBeli = ($state)?floatval($state):0;
                                        $marginHarga = floatval($get('margin_harga'));
                                        $gunakanMargin = $get('use_margin'); 
                                        if(
                                            $gunakanMargin
                                        ){
                                            if(($hargaBeli == 0)){
                                                return $set('harga_jual', 0);    
                                            }

                                            if(($marginHarga == 0)){
                                                return $set('harga_jual', $hargaBeli);    
                                            }
                                            $hargaJual = ($hargaBeli + ($hargaBeli * ($marginHarga/100)));
                                            return $set('harga_jual', $hargaJual);
                                        }
                                    })
                                    ->extraInputAttributes(['class' => 'text-end']),

                                Forms\Components\TextInput::make('harga_jual')
                                    ->label('Harga Jual')
                                    ->numeric()
                                    ->autocomplete(false)
                                    ->placeholder('0,00')
                                    ->live()
                                    ->hint(function(Forms\Get $get){
                                        $gunakanMargin = $get('use_margin');
                                        $hargaBeli = floatval($get('harga_beli'));
                                        $hargaJual = floatval($get('harga_jual'));
                                        $marginHarga = 0;
                                        if($gunakanMargin)
                                            return "Margin : ".$get('margin_harga')."%";
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
                                    }),
                            ]),
                            Forms\Components\Toggle::make('update_all_price')
                                    ->label('Terapkan pada seluruh satuan?')
                                    ->default(true)
                                    ->hiddenOn('create')
                                    ->helperText(new HtmlString('<span class="text-primary-500">Aktifkan untuk memperbarui harga pada multi satuan.</span>')),
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('diskon')
                                            ->label('Diskon/Potongan Pertama')
                                            ->numeric()   
                                            ->autocomplete(false)                                 
                                            ->placeholder('0,00')
                                            ->suffix('%')
                                            ->live()
                                            ->hint(function(Forms\Get $get, ?string $state){
                                                $hargaJual = floatval($get('harga_jual'));
                                                $diskon = floatval($state);
                                                $label = "Harga setelah diskon : ";
                                                if(floatval($hargaJual)!=0)
                                                    return $label.number_format($hargaJual - ($hargaJual * ($diskon/100)),2,",",".");
                                                
                                                return $label.number_format(0, 2,",",".");
                                            })
                                            ->hintColor('info')
                                            ->extraInputAttributes(['class' => 'text-end']),
                                    Forms\Components\TextInput::make('diskon2')
                                        ->label('Diskon/Potongan Kedua (Optional)')
                                        ->numeric()  
                                        ->autocomplete(false)                                  
                                        ->placeholder('0,00')
                                        ->suffix('%')
                                        ->live()
                                        ->hint(function(Forms\Get $get, ?string $state){
                                            $hargaJual = floatval($get('harga_jual'));
                                            $diskon = floatval($get('diskon'));
                                            $diskon2 = floatval($state);
                                            $label = "Harga setelah diskon 2 : ";
                                            if($hargaJual != 0 && $diskon != 0){
                                                $hargaDiskon = $hargaJual - ($hargaJual * ($diskon/100)); 
                                                return $label.number_format($hargaDiskon - ($hargaDiskon * ($diskon2/100)), 2, ",", ".");
                                            }
                                            if($hargaJual != 0 && $diskon == 0){
                                                return $label.number_format($hargaJual, 2, ",", ".");
                                            }   
                                            return $label.number_format(0,2,",",".");
                                        })
                                        ->hintColor('info')
                                        ->extraInputAttributes(['class' => 'text-end']),
                                ]),
                                Forms\Components\Toggle::make('update_all_diskon')
                                    ->label('Terapkan pada multi satuan?')
                                    ->default(true)
                                    ->hiddenOn('create')
                                    ->helperText(new HtmlString('<span class="text-primary-500">Aktifkan untuk memperbarui diskon pada seluruh satuan.</span>')),
                        Forms\Components\Toggle::make('digunakan')
                            ->label('Digunakan?')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode/SKU')
                    ->searchable()
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan.nama')
                    ->label('Satuan Dasar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kemasan')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kategories.nama')
                    ->label('Kategori')
                    ->searchable()
                    ->badge()
                    ->separator(',')
                    ->default('-'),
                // Tables\Columns\TextColumn::make('persediaan_count')
                //     ->counts('persediaan')
                //     ->label('Stok yang tersedia')
                //     ->alignCenter(),
                Tables\Columns\TextColumn::make('minimal_stok')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('idr')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->money('idr')
                    ->alignment(Alignment::Center)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pabrik')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('digunakan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat oleh')
                    ->sortable()
                    ->default('-')
                    ->formatStateUsing(fn (string $state): string => ($state != 'Superuser')?$state:"-")
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat tanggal')
                    ->date('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lastEditedBy.name')
                    ->label('Terakhir diperbarui oleh')
                    ->sortable()
                    ->default('-')
                    ->formatStateUsing(fn (string $state): string => ($state != 'Superuser')?$state:"-")
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir diperbarui tanggal')
                    ->date('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->groups([
                'satuan.nama',
                'pabrik',
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategories')
                    ->relationship('kategories', 'nama')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('satuan')
                    ->relationship('satuan', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\Action::make('multisatuan')
                        ->label('Multi Satuan')
                        ->icon('heroicon-m-square-3-stack-3d')
                        ->color('primary')
                        ->hidden(fn(Produk $produk)=>!$produk->satuan_id)
                        ->url(fn(Produk $produk)=> ProdukResource::getUrl('multisatuan', ['record' => $produk])),
                    Tables\Actions\Action::make('persediaan')
                        ->label('Persediaan')
                        ->icon('heroicon-m-archive-box')
                        ->color('primary')
                        ->hidden(fn(Produk $produk)=>!$produk->satuan_id)
                        ->url(fn(Produk $produk)=>ProdukResource::getUrl('persediaan', ['record' => $produk])),
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make()
                        ->before(function(Produk $record){
                            DB::transaction(function () use ($record) {
                                $record->kategories()->detach();
                                $record->multiSatuan()->delete();
                            });
                        }),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function(Eloquent\Collection $records){
                            $records->each(function($record){
                                DB::transaction(function () use ($record) {
                                    $record->kategories()->detach();
                                    $record->multiSatuan()->delete();
                                     
                                    $record->persediaan()->delete();
                                    $record->delete();
                                },5);
                            });
                        })
                        ->hidden(! auth()->user()->can('product: delete')),
                ]),
            ])
            ->emptyStateHeading('Belum ada data produk')
            ->emptyStateDescription('Buat data produk melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Produk')
                    ->icon('heroicon-m-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('10s');
    }
    
    public static function infolist(Infolist $infolist): Infolist
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
                        Infolists\Components\TextEntry::make('deskripsi')
                            ->columnSpan(4),
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('pabrik'),
                                Infolists\Components\TextEntry::make('kemasan'),
                                Infolists\Components\TextEntry::make('minimal_stok'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('kategories.nama')
                            ->formatStateUsing(fn (string $state): string => ($state)?$state:"-"),
                        Infolists\Components\TextEntry::make('satuan.nama')
                            ->formatStateUsing(fn (string $state): string => ($state)?$state:"-"),
                        Infolists\Components\RepeatableEntry::make('multiSatuan')
                            ->schema([
                                Infolists\Components\TextEntry::make('satuan.nama'),
                                Infolists\Components\TextEntry::make('nilai_konversi')
                                    ->label("Nilai Konversi"),
                                Infolists\Components\TextEntry::make('harga_jual')
                                    ->label("Harga Jual")
                                    ->money('idr'),
                            ])
                            ->hidden(fn($record)=>$record->multiSatuan()->count()<1)
                            ->columns(4)
                            // ->hidden(fn($record)=>multiSatuan.count())
                            ->columnSpanFull(),
                    ])->columns(4),

                InfoLists\Components\Section::make('Harga')
                    ->schema([
                        InfoLists\Components\Grid::make()
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->schema([
                                 InfoLists\Components\TextEntry::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->money('idr')
                                    ->color('primary'),
                                InfoLists\Components\TextEntry::make('harga_jual')
                                    ->label('Harga Jual')
                                    ->money('idr')
                                    ->color('primary'),
                            ]),
                        InfoLists\Components\Grid::make()
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 3,
                                '2xl' => 4,
                            ])
                            ->schema([
                                Infolists\Components\TextEntry::make('diskon')
                                    ->label('Diskon/Potongan Pertama')
                                    ->color('primary')
                                    ->formatStateUsing(function(string $state): string {
                                        $diskon = str_replace(".",",",$state);
                                        return __("{$diskon}%");
                                    }),
                                Infolists\Components\TextEntry::make('diskon2')
                                    ->label('Diskon/Potongan Kedua (optional)')
                                    ->color('primary')
                                    ->formatStateUsing(function(string $state): string {
                                        $diskon = str_replace(".",",",$state);
                                        return __("{$diskon}%");
                                    }),
                            ]),
                    ])->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PersediaanRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'view' => Pages\ViewProduk::route('/{record}'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
            'persediaan' => Pages\PersediaanProduk::route('/{record}/persediaan'),
            'multisatuan' => Pages\MultiSatuanProduk::route('/{record}/multisatuan'),
        ];
    }    
}
