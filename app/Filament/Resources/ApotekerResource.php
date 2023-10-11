<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Support;
use Filament\Infolists;
use App\Models\Apoteker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\ApotekerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ApotekerResource\RelationManagers;

class ApotekerResource extends Resource
{
    protected static ?string $model = Apoteker::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Data Apoteker';

    protected static ?string $pluralModelLabel = 'Data Apoteker';

    protected static ?string $modelLabel = 'Data Apoteker';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-apoteker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Data Apoteker')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Apoteker')
                            ->maxLength(150)
                            ->autocomplete(false)
                            ->required(),
                        Forms\Components\Grid::make()
                            ->columns([
                                'lg' => 2
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('no_telp')
                                    ->label('No.Telp')
                                    ->maxLength(32)
                                    ->tel()
                                    ->autocomplete(false)
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(64)
                                    ->email()
                                    ->autocomplete(false),
                            ]),
                        
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(4)
                            ->cols(20),
                    ]),
                Forms\Components\Section::make('Berkas Apoteker')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('stra_no')
                                    ->label('No. STRA')
                                    ->maxLength(150)
                                    ->autocomplete(false)
                                    ->required(),
                                Forms\Components\Grid::make()
                                    ->columns([
                                        'default' => 1,
                                        'lg' => 2,
                                    ])
                                    ->schema([
                                        Forms\Components\DatePicker::make('stra_exp_date')
                                            ->label('Berlaku sampai tanggal')
                                            ->format('d/m/Y')
                                            ->minDate(now())
                                            ->maxDate(now()->addYear(10)),
                                        Forms\Components\FileUpload::make('stra_file')
                                            ->label('Berkas STRA')
                                            ->getUploadedFileNameForStorageUsing(
                                                fn (Forms\Get $get): string => "stra-".$get('stra_no').".pdf",
                                            )
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->downloadable()
                                            ->disk('file-apotek')
                                            ->directory('apoteker')
                                            ->visibility('public')
                                            ->hint('Format berkas *.pdf'),
                                    ]),
                            ]),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('sipa_no')
                                    ->label('No. SIPA')
                                    ->maxLength(150)
                                    ->autocomplete(false)
                                    ->required(),
                                Forms\Components\Grid::make()
                                    ->columns([
                                        'default' => 1,
                                        'lg' => 2,
                                    ])
                                    ->schema([
                                        Forms\Components\DatePicker::make('sipa_exp_date')
                                            ->label('Berlaku sampai tanggal')
                                            ->format('d/m/Y')
                                            ->minDate(now())
                                            ->maxDate(now()->addYear(10)),
                                        Forms\Components\FileUpload::make('sipa_file')
                                            ->label('Berkas SIPA')
                                            ->getUploadedFileNameForStorageUsing(
                                                fn (Forms\Get $get): string => "sipa-".$get('sipa_no').".pdf",
                                            )
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->downloadable()
                                            ->disk('file-apotek')
                                            ->directory('apoteker')
                                            ->visibility('public')
                                            ->hint('Format berkas *.pdf'),
                                    ]),
                            ]),
                    ]),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Toggle::make('digunakan')
                            ->label('Digunakan?')
                            ->default('true'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No.Telp')
                    ->sortable()
                    ->searchable(),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                ]),
                
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(! auth()->user()->can('supplier: delete')),
                ]),
            ])
            ->emptyStateHeading('Belum ada data apoteker')
            ->emptyStateDescription('Buat data apoteker melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-user')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Apoteker')
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
                Infolists\Components\Section::make('Informasi Data Apoteker')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('nama')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(Support\Enums\FontWeight::Bold)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 4,
                            ]),
                        Infolists\Components\TextEntry::make('no_telp')
                            ->label('No. Telp')
                            ->copyable()
                            ->copyMessage('Salin!')
                            ->copyMessageDuration(1500)
                            ->columnSpan([
                                'lg' => 1,
                            ]),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->default('-')
                            ->copyable()
                            ->copyMessage('Salin!')
                            ->copyMessageDuration(1500)
                            ->columnSpan([
                                'lg' => 1,
                            ]),
                        Infolists\Components\TextEntry::make('alamat')
                            ->label('Alamat')
                            ->default('-')
                            ->copyable()
                            ->copyMessage('Salin!')
                            ->copyMessageDuration(1500)
                            ->columnSpan([
                                'lg' => 4,
                            ]),
                    ])
                    ->columns([
                        'default' => 1,
                        'lg' => 4,
                    ]),
                Infolists\Components\Section::make('Berkas Apoteker')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Infolists\Components\Grid::make([
                            'default' => 1,
                            'lg' => 4,
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('stra_no')
                                ->label('No. STRA')
                                ->copyable()
                                ->copyMessage('Salin!')
                                ->copyMessageDuration(1500)
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            Infolists\Components\TextEntry::make('stra_exp_date')
                                ->label('Berlaku sampai')
                                ->dateTime('j F Y')
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            Infolists\Components\TextEntry::make('stra_file')
                                ->label('Berkas STRA')
                                ->formatStateUsing(function (string $state): string {
                                    if(!$state)
                                        return $state;
                                    $state = explode('/', $state);
                                    return $state[1];
                                })
                                ->url(fn (Apoteker $record): string => Storage::disk('files-apotek')->url($record->stra_file))
                                ->openUrlInNewTab()
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            ]),
                        Infolists\Components\Grid::make([
                            'default' => 1,
                            'lg' => 4,
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('sipa_no')
                                ->label('No. SIPA')
                                ->copyable()
                                ->copyMessage('Salin!')
                                ->copyMessageDuration(1500)
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            Infolists\Components\TextEntry::make('sipa_exp_date')
                                ->label('Berlaku sampai')
                                ->dateTime('j F Y')
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            Infolists\Components\TextEntry::make('sipa_file')
                                ->label('Berkas SIPA')
                                ->formatStateUsing(function (string $state): string {
                                    if(!$state)
                                        return $state;
                                    $state = explode('/', $state);
                                    return $state[1];
                                })
                                ->url(fn (Apoteker $record): string => Storage::disk('files-apotek')->url($record->sipa_file))
                                ->openUrlInNewTab()
                                ->columnSpan([
                                    'lg' => 1,
                                ]),
                            ]),
                    ]),
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
            'index' => Pages\ListApotekers::route('/'),
            'create' => Pages\CreateApoteker::route('/create'),
            'view' => Pages\ViewApoteker::route('/{record}'),
            'edit' => Pages\EditApoteker::route('/{record}/edit'),
        ];
    }    
}
