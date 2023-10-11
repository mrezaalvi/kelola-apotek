<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Forms\Components\PasswordInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Data Pengguna';

    protected static ?string $pluralModelLabel = 'Data Pengguna';

    protected static ?string $modelLabel = 'Data Pengguna';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $slug = 'data-pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->required(),
                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->maxLength(50)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->unique(ignoreRecord: true)
                            ->email(),
                        PasswordInput::make('password')
                            ->maxLength(64)
                            ->required()
                            ->label('Kata Sandi')
                            ->visibleOn('create'),
                        Forms\Components\Select::make('roles')
                            ->label('Grup Pengguna')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif?')
                            ->default('true'),
                        
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Grup Pengguna')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif?')
                    ->boolean()
            ])
            ->defaultSort('name')
            ->filters([
                // 
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),  
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(! auth()->user()->can('user: delete')),
                ]),
            ])
            ->emptyStateHeading('Belum ada data pengguna')
            ->emptyStateDescription('Buat data pengguna melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-user')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Data Pengguna')
                    ->icon('heroicon-m-plus'),
            ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(!auth()->user()->hasRole('superuser'))
            return parent::getEloquentQuery()->where('username','!=', 'superuser');
        
        return parent::getEloquentQuery();
    }
}
