<?php

namespace App\Filament\Resources\ApotekerResource\Pages;

use App\Filament\Resources\ApotekerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewApoteker extends ViewRecord
{
    protected static string $resource = ApotekerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->url(fn()=>ApotekerResource::getUrl()),
            Actions\EditAction::make()
                ->icon('heroicon-m-pencil'),
        ];
    }
}
