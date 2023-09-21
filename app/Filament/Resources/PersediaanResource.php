<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use App\Models\Persediaan;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PersediaanResource\Pages;
use App\Filament\Resources\PersediaanResource\RelationManagers;

class PersediaanResource extends Resource
{
    protected static ?string $model = Persediaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Daftar Persediaan';

    protected static ?string $pluralModelLabel = 'Data Persediaan';

    protected static ?string $modelLabel = 'Daftar Persediaan';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Persediaan';

    protected static ?string $slug = 'persediaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produks.nama')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('satuans.nama')
                    ->label('Satuan'),
                Tables\Columns\TextColumn::make('lokasis.nama')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok yang tersedia')
                    ->numeric()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_batch')
                    ->label('Nomor Batch'),
                Tables\Columns\TextColumn::make('tgl_exp')
                    ->label('Tanggal Expired')
                    ->alignment(Alignment::Center)
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('idr')
                    ->alignment(Alignment::Center)
                    ->sortable(),
            ])
             ->groups([
                Group::make('produks.nama')
                    ->label('Produk')
                    ->collapsible(),
            ])
            ->defaultGroup('produks.nama')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make()
                    //      ->hidden(! auth()->user()->can('stok: delete')),
                ]),
            ])
            ->poll('3s');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                
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
            'index' => Pages\ListPersediaans::route('/'),
            // 'create' => Pages\CreatePersediaan::route('/create'),
            // 'view' => Pages\ViewPersediaan::route('/{record}'),
            // 'edit' => Pages\EditPersediaan::route('/{record}/edit'),
        ];
    }    
}
