<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Data Kategori';

    protected static ?string $pluralModelLabel = 'Data Kategori';

    protected static ?string $modelLabel = 'Data Kategori';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-kategori';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kategori')
                            ->unique(ignoreRecord: true)
                            ->autofocus()
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Toggle::make('digunakan')
                            ->label('Digunakan')
                            ->default(true),        
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\IconColumn::make('digunakan')
                    ->label('Digunakan?')
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
            ->filters([
                //
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
            ->emptyStateHeading('Belum ada data kategori')
            ->emptyStateDescription('Buat data kategori melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Kategori')
                    ->icon('heroicon-m-plus'),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKategoris::route('/'),
        ];
    }    
}
