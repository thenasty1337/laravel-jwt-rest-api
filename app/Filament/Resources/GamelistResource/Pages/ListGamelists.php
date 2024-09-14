<?php

namespace App\Filament\Resources\GamelistResource\Pages;

use App\Filament\Resources\GamelistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGamelists extends ListRecords
{
    protected static string $resource = GamelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
