<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Satuan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SatuanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SatuanResource\RelationManagers;

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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('produks_count')
                    ->label('Digunakan sebagai Satuan Dasar')
                    ->counts('produks')
                    ->alignment(Alignment::Center)
                    ->sortable(),
                Tables\Columns\TextColumn::make('multi_satuan_count')
                    ->label('Digunakan sebagai Multi Satuan')
                    ->counts('multiSatuan')
                    ->alignment(Alignment::Center)
                    ->sortable(),
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
            ->defaultSort('nama')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function($record, Tables\Actions\DeleteAction $action){
                        if(($record->produks()->count()>0) || ($record->multiSatuan()->count()>0))
                        {
                            Notification::make()
                                ->warning()
                                ->title('Data '.$record->nama.' telah digunakan pada data yang lain!')
                                ->body('Silahkan ubah atau hapus data yang terhubung dengan data ini')
                                ->send();
                            $action->cancel();
                        }
                    }),        
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function($records){
                            $recordDeleted = 0;
                            $itemCantDelete = "";
                            foreach($records as $record)
                            {
                                // dd($record->multiSatuan()->count());
                                if(($record->produks()->count()<1) && ($record->multiSatuan()->count()<1))
                                {
                                    if($record->delete())
                                        $recordDeleted++;
                                }
                            }
                            if($recordDeleted<$records->count())
                                Notification::make()
                                    ->warning()
                                    ->title('Beberapa data mungkin tidak dapat dihapus')
                                    ->body('Data berhasil dihapus : '.$recordDeleted
                                    .'<br>Data gagal dihapus : '.$records->count() - $recordDeleted)
                                    ->send();
                            else
                                Notification::make()
                                    ->success()
                                    ->title('Semua data berhasil dihapus')
                                    ->body('Seluruh data yang dipilih berhasil dihapus')
                                    ->send();
                        })
                        ->hidden(! auth()->user()->can('unit: delete')),
                ]),
            ])
            ->emptyStateHeading('Belum ada data satuan')
            ->emptyStateDescription('Buat data satuan melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Satuan')
                    ->icon('heroicon-m-plus'),
            ])
            ->paginated([10, 25, 50]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSatuans::route('/'),
        ];
    }    
}
