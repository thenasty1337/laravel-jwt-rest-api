<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('User Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('username'),
                        Infolists\Components\ImageEntry::make('avatar')
                            ->circular(),
                        Infolists\Components\TextEntry::make('uuid'),
                        Infolists\Components\TextEntry::make('email_verified_at')
                            ->dateTime(),
                        Infolists\Components\IconEntry::make('is_admin')
                            ->boolean(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Wallets')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('wallets')
                            ->schema([
                                Infolists\Components\TextEntry::make('uuid'),
                                Infolists\Components\TextEntry::make('currency'),
                                Infolists\Components\TextEntry::make('balance')
                                    ->money('usd'),
                                Infolists\Components\IconEntry::make('is_default')
                                    ->boolean(),
                            ])
                            ->columns(4),
                    ]),
                Infolists\Components\Section::make('Recent Transactions')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('recentTransactions')
                            ->schema([
                                Infolists\Components\TextEntry::make('uuid'),
                                Infolists\Components\TextEntry::make('amount')
                                    ->money('usd'),
                                Infolists\Components\TextEntry::make('transaction_type'),
                                Infolists\Components\TextEntry::make('status'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->dateTime(),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }
}
