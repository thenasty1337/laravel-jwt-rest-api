<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GamelistResource\Pages;
use App\Filament\Resources\GamelistResource\RelationManagers;
use App\Models\Gamelist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\BooleanFilter;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class GamelistResource extends Resource
{
    protected static ?string $model = Gamelist::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),

                Forms\Components\TextInput::make('type')
                    ->required()
                    ->label('Game Type'),

                Forms\Components\TextInput::make('category')
                    ->required()
                    ->label('Category'),

                Forms\Components\TextInput::make('id_hash')
                    ->label('ID Hash'),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image(),

                Forms\Components\FileUpload::make('image_portrait')
                    ->label('Image Portrait')
                    ->image(),

                Forms\Components\Toggle::make('freerounds_supported')
                    ->label('Free Rounds Supported')
                    ->inline(false),

                Forms\Components\Toggle::make('play_for_fun_supported')
                    ->label('Play For Fun Supported')
                    ->inline(false),

                Forms\Components\TextInput::make('index_rating')
                    ->numeric()
                    ->label('Index Rating'),

                Forms\Components\Toggle::make('new')
                    ->label('New Game')
                    ->inline(false),

                Forms\Components\Toggle::make('popular')
                    ->label('Popular Game')
                    ->inline(false),

                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->inline(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->square()
                    ->height(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('index_rating')
                    ->label('Index Rating')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('freerounds_supported')
                    ->label('Free Rounds'),

                Tables\Columns\BooleanColumn::make('play_for_fun_supported')
                    ->label('Play for Fun'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(function () {
                        return \App\Models\Gamelist::pluck('category', 'category')->toArray();
                    })
                    ->label('Category'),

                Filter::make('active')
                    ->label('Active'),

                Filter::make('new')
                    ->label('New'),

                Filter::make('popular')
                    ->label('Popular'),

                SelectFilter::make('type')
                    ->options([
                        'video-slots' => 'Video Slots',
                        'table-games' => 'Table Games',
                        'poker' => 'Poker',
                        // Add more types as needed
                    ])
                    ->label('Game Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGamelists::route('/'),
            'create' => Pages\CreateGamelist::route('/create'),
            'edit' => Pages\EditGamelist::route('/{record}/edit'),
        ];
    }
}
