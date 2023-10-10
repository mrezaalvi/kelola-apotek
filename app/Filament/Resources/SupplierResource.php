<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\SupplierResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SupplierResource\RelationManagers;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Data Supplier';

    protected static ?string $pluralModelLabel = 'Data Supplier';

    protected static ?string $modelLabel = 'Data Supplier';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $slug = 'data-supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Data Supplier')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Supplier')
                            ->maxLength(150)
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
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(64)
                                    ->email(),
                            ]),
                        
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(4)
                            ->cols(20),
                    ]),
                Forms\Components\Section::make('Informasi Data Sales')
                    ->schema([
                        Forms\Components\TextInput::make('sales')
                            ->label('Nama Sales')
                            ->maxLength(150),
                        Forms\Components\Grid::make()
                            ->columns([
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('no_telp_sales')
                                    ->label('No.Telp')
                                    ->maxLength(32)
                                    ->tel(),
                                Forms\Components\TextInput::make('email_sales')
                                    ->label('Email')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(64)
                                    ->email(),
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
                Tables\Columns\TextColumn::make('sales')
                    ->label('Nama Sales')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp_sales')
                    ->label('No.Telp Sales')
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
            ->emptyStateHeading('Belum ada data supplier')
            ->emptyStateDescription('Buat data supplier melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-user')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Supplier')
                    ->icon('heroicon-m-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('10s');
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\ViewSupplier::route('/{record}'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }    
}
