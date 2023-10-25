<?php

namespace App\Filament\Resources\ProdukResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Satuan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PersediaanRelationManager extends RelationManager
{
    protected static string $relationship = 'persediaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\Select::make('satuan_id')
                                    ->label('Satuan')
                                    // ->relationship('satuans','nama')
                                    ->options(
                                        // Satuan::all()->pluck('nama', 'id')
                                        function(){
                                            $satuanId = [];
                                            if($this->ownerRecord->satuan_id)
                                                $satuanId = collect($this->ownerRecord->satuan_id);
                                            
                                            if($this->ownerRecord->multiSatuan()->count()>0)
                                                $satuanId = $satuanId->merge($this->ownerRecord->multiSatuan()->pluck('satuan_lanjutan'));
                                            return Satuan::whereIn('id', $satuanId)->pluck('nama','id');
                                             
                                        }
                                    ),
                                Forms\Components\Select::make('lokasi_id')
                                    ->label('Lokasi')
                                    ->relationship('lokasis','nama')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('no_batch')
                                    ->label('No.Batch'),
                                Forms\Components\DatePicker::make('tgl_exp')
                                    ->label('Tanggal Kadaluarsa'),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('stok')
                                    ->label('Jumlah Stok')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->numeric()
                                    ->required(),
                            ]),
                        
                        
                    ]),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('produks.nama')
            ->columns([
                Tables\Columns\TextColumn::make('satuans.nama')
                    ->label('Satuan')
                    ->disabled(fn(string $operation) => $operation == 'edit'),
                Tables\Columns\TextColumn::make('lokasis.nama')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok yang tersedia')
                    ->numeric()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_batch')
                    ->label('Nomor Batch')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_exp')
                    ->label('Tanggal Expired')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('idr')
                    ->alignEnd()
                    ->sortable(),
            ])
            ->defaultSort('no_batch')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Batch')
                    ->icon('heroicon-m-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        $data['ref'] = null;
                        return $data;
                    })
                    ->using(function (array $data, string $model): Model {
                        dd($data);
                        return $model::create($data);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Batch'),
            ]);
    }
}
