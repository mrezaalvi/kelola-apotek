<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SatuanResource\Pages;
use App\Filament\Resources\SatuanResource\RelationManagers;
use App\Models\Satuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SatuanResource extends Resource
{
    protected static ?string $model = Satuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Data Satuan';

    protected static ?string $pluralModelLabel = 'Data Satuan';

    protected static ?string $modelLabel = 'Data Satuan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-satuan';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Satuan')
                            ->unique(ignoreRecord: true)
                            ->autofocus()
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Toggle::make('digunakan')
                            ->label('Digunakan?')
                            ->default(true)
                            ->required(),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Satuan')
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
            ->emptyStateHeading('Belum ada data satuan')
            ->emptyStateDescription('Buat data satuan melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Satuan')
                    ->icon('heroicon-m-plus'),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSatuans::route('/'),
        ];
    }    
}
