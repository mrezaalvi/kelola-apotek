<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationLabel = 'Izin Akses';

    protected static ?string $pluralModelLabel = 'Izin Akses';

    protected static ?string $modelLabel = 'Izin Akses';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $slug = 'izin-akses';

    protected static ?string $recordTitleAttribute = 'name';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('name')
                //     ->label('Nama Izin Akses'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alias')
                    ->label('Nama Izin Akses')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Izin Akses')
                    ->sortable(),
            ])
            ->defaultSort('group')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ActionGroup::make([
                //     Tables\Actions\EditAction::make(), 
                //     Tables\Actions\DeleteAction::make(),
                // ]),            
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //          ->hidden(! auth()->user()->can('permission: delete')),
                // ]),
            ])
            ->emptyStateHeading('Belum ada data izin akses')
            // ->emptyStateDescription('Buat data izin akses melalui tombol dibawah ini.')
            ->emptyStateIcon('heroicon-o-lock-closed')
            // ->emptyStateActions([
            //     Tables\Actions\CreateAction::make()
            //         ->label('Buat Data Pengguna')
            //         ->icon('heroicon-m-plus'),
            // ])
            ->groups([
                Tables\Grouping\Group::make('group')
                ->collapsible(true),
            ])
            ->defaultGroup('group')
            ->paginated([10, 25, 50])
            ->poll('10s');;
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
            'index' => Pages\ListPermissions::route('/'),
            // 'create' => Pages\CreatePermission::route('/create'),
            // 'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }    
}
