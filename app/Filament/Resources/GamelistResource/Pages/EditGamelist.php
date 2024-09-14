<?php

namespace App\Filament\Resources\GamelistResource\Pages;

use App\Filament\Resources\GamelistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGamelist extends EditRecord
{
    protected static string $resource = GamelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
