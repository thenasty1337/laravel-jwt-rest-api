<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('wallet_id')
                    ->relationship('wallet', 'uuid')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->step(0.00000001),
                Forms\Components\Select::make('transaction_type')
                    ->options([
                        'deposit' => 'Deposit',
                        'withdrawal' => 'Withdrawal',
                        'exchange' => 'Exchange',
                        'game_win' => 'Game Win',
                        'transfer' => 'Transfer',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('exchange_rate')
                    ->numeric()
                    ->step(0.00000001),
                Forms\Components\TextInput::make('deposit_address'),
                Forms\Components\TextInput::make('deposited_from'),
                Forms\Components\Select::make('game_id')
                    ->relationship('game', 'name'),
                Forms\Components\Select::make('transfer_to')
                    ->relationship('transferTo', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->searchable(),
                Tables\Columns\TextColumn::make('wallet.uuid'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('usd'),
                Tables\Columns\TextColumn::make('transaction_type'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type'),
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
