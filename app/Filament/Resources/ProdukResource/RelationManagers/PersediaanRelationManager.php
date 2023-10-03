<?php

namespace App\Filament\Resources\ProdukResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PersediaanRelationManager extends RelationManager
{
    protected static string $relationship = 'persediaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lokasis')
                    ->relationship('lokasis','nama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('produks.nama')
            ->columns([
                Tables\Columns\TextColumn::make('satuans.nama')
                    ->label('Satuan'),
                Tables\Columns\TextColumn::make('lokasis.nama')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok yang tersedia')
                    ->numeric()
                    ->alignment(Alignment::Center),
                Tables\Columns\TextColumn::make('no_batch')
                    ->label('Nomor Batch')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tgl_exp')
                    ->label('Tanggal Expired')
                    ->alignment(Alignment::Center)
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_beli')
                    ->money('idr')
                    ->alignment(Alignment::Center)
                    ->sortable(),
            ])
            ->defaultSort('no_batch')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
