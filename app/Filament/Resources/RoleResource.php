<?php

namespace App\Filament\Resources;

use App\View\Components;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Grup Pengguna';

    protected static ?string $pluralModelLabel = 'Grup Pengguna';

    protected static ?string $modelLabel = 'Group Pengguna';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $slug = 'grup-pengguna';

    protected static ?string $recordTitleAttribute = 'name';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Grup Pengguna'),
                        // Forms\Components\Select::make('permissions')
                        //     ->label('Izin Akses')
                        //     ->multiple()
                        //     ->relationship('permissions', 'alias')
                        //     ->searchable()
                        //     ->preload(),
                        Components\PermissionList::make('permissions')
                            ->label('Izin Akses')
                            ->relationship(
                                'permissions',
                                titleAttribute: 'alias',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('group'),
                            ),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Grup Pengguna')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('permissions.alias')
                    ->label('Izin Akses')
                    ->badge()
                    ->limitList(3)
                    ->separator(','),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data grup pengguna')
            ->emptyStateDescription('Buat data grup pengguna melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Grup Pengguna')
                    ->icon('heroicon-m-plus'),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }    
}
