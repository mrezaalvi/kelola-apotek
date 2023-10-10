<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lokasi;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
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
                            ->default('rak')
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => Str::upper($state)),
                Tables\Columns\IconColumn::make('digunakan')
                    ->label('Digunakan?')
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
                    ->default('-')
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
                    ->default('Belum pernah diperbarui')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->groups([
                Tables\Grouping\Group::make('jenis')
                    ->collapsible(true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary')
                        ->mutateFormDataUsing(function (array $data): array {
                            $data['last_edited_by'] = auth()->id();
                    
                            return $data;
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function(Collection $records){
                            $records->each(function($record){
                                DB::transaction(function () use ($record) {
                                    if($record->nama != 'GUDANG UTAMA')
                                        $record->delete();
                                });
                            });
                        })
                        ->hidden(! auth()->user()->can('location: delete')),
                ]),
            ])
            ->emptyStateHeading('Belum ada data lokasi')
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Lokasi')
                    ->icon('heroicon-m-plus'),
            ])
            ->paginated([10, 25, 50])
            ->poll('10s');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLokasis::route('/'),
        ];
    }    
}
