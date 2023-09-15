<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Satuan;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wallo\FilamentSelectify\Components\ButtonGroup;
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
                            ->required()
                            ->maxLength(150),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode')
                                    ->label('Kode/SKU')
                                    ->maxLength(40),
                                Forms\Components\TextInput::make('barcode')
                                    ->maxLength(50)
                                    ->suffixIcon('heroicon-m-view-columns'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
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
                                    ]),
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
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('nama')
                                                    ->unique(ignoreRecord: true)
                                                    ->required(),
                                            ]),
                                        Forms\Components\TextInput::make('nilai_konversi')
                                            ->label('Nilai Konversi')
                                            ->placeholder('0')
                                            ->extraInputAttributes(['class' => 'text-end'])
                                            ->suffix(
                                                fn(Forms\Get $get)=>
                                                    $get('../../satuan')?Satuan::find($get('../../satuan'))->nama:'satuan'),
                                    ]),  
                            ])
                            ->defaultItems(0)
                            ->disabled(fn(Forms\Get $get)=>!$get('satuan'))
                            ->reorderable(false)
                            ->reorderableWithButtons(),

                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(2)
                            ->maxLength(255),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('pabrik'),
                                Forms\Components\TextInput::make('kemasan'),
                            ]),
                        
                        Forms\Components\TextInput::make('minimal_stok')
                            ->label('Stok Minimal')
                            ->numeric()
                            // ->mask(RawJs::make(<<<'JS'
                            //     $money($input, ',', '.', 2)
                            // JS))
                            ->placeholder('0')
                            ->default(0)
                            ->extraAttributes(['class' => 'max-w-xs'])
                            ->extraInputAttributes(['class' => 'text-end']),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Checkbox::make('use_margin')
                                    ->label('Gunakan Margin')
                                    ->dehydrated(false)
                                    ->live()
                                    ->extraAttributes(['class' => 'items-end']),
                                Forms\Components\TextInput::make('margin_harga')
                                    ->label('Margin')
                                    ->numeric()
                                    ->required(fn(Forms\Get $get)=>$get('use_margin'))
                                    ->disabled(fn(Forms\Get $get)=>!$get('use_margin'))
                                    ->placeholder('0,00')
                                    ->suffix('%')
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {
                                        if($get('harga_beli')){
                                            $hargaBeli = floatval($get('harga_beli'));
                                            $marginHarga = floatval($state);
                                            $hargaJual = ($hargaBeli + (($hargaBeli * $marginHarga)/100));
                                            return $set('harga_jual', $hargaJual);
                                        }
                                    })
                                    ->live(onBlur: true)
                                    ->extraInputAttributes(['class' => 'text-end']),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('harga_beli')
                                    ->numeric()
                                    
                                    ->placeholder('0,00')
                                    ->extraInputAttributes(['class' => 'text-end']),
                                
                                
                                Forms\Components\TextInput::make('harga_jual')
                                    ->numeric()
                                    ->placeholder('0,00')
                                    ->live(onBlur: true)
                                    ->disabled(fn(Forms\Get $get)=>$get('use_margin'))
                                    ->afterStateUpdated(function (Forms\Set $set, Get $get, ?string $state) {
                                        if($get('harga_beli') && !$get('use_margin')){
                                            $hargaBeli = $get('harga_beli');
                                            $hargaJual = $state;
                                            $marginHarga = number_format(((floatval($hargaJual) - floatval($hargaBeli))/floatval($hargaJual))*100,2);
                                            return $set('margin_harga', $marginHarga);
                                        }
                                    })
                                    ->extraInputAttributes(['class' => 'text-end']),
                            ]),
                        
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan.nama')
                    ->label('Satuan Dasar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategories.nama')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimal_stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('digunakan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat tanggal')
                    ->date('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lastEditedBy.name')
                    ->label('Terakhir diperbarui oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir diperbarui tanggal')
                    ->date('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->before(function(Produk $record){
                            DB::transaction(function () use ($record) {
                                $record->kategories()->detach();
                                $record->multiSatuan()->delete();
                            });
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function(Collection $records){
                            // $records->each->kategories->detach();
                            $records->each(function($record){
                                DB::transaction(function () use ($record) {
                                    $record->kategories()->detach();
                                    $record->multiSatuan()->delete();
                                    $record->delete();
                                });
                            });
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum ada data produk')
            ->emptyStateDescription('Buat data produk melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Produk')
                    ->icon('heroicon-m-plus'),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'view' => Pages\ViewProduk::route('/{record}'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }    
}
