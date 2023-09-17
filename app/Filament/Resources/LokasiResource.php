<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lokasi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LokasiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LokasiResource\RelationManagers;

class LokasiResource extends Resource
{
    protected static ?string $model = Lokasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Data Lokasi';

    protected static ?string $pluralModelLabel = 'Data Lokasi';

    protected static ?string $modelLabel = 'Data Lokasi';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-lokasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lokasi')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\Select::make('jenis')
                            ->label('Jenis')
                            ->options([
                                'gudang' => 'Gudang',
                                'rak' => 'Rak',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('digunakan')
                            ->label('Digunakan?')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lokasi')
                    ->icon(fn($record)=>($record->nama == 'GUDANG UTAMA')?'heroicon-m-lock-closed':'')
                    ->iconPosition(IconPosition::After)
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->formatStateUsing(fn (string $state): string => Str::upper($state)),
                Tables\Columns\IconColumn::make('digunakan')
                    ->label('Digunakan?')
                    ->boolean(),
            ])
            ->groups([
                Tables\Grouping\Group::make('jenis')
                    ->collapsible(true),
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
            ->emptyStateHeading('Belum ada data lokasi')
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Lokasi')
                    ->icon('heroicon-m-plus'),
            ])
            ->paginated([10, 25, 50]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLokasis::route('/'),
        ];
    }    
}
