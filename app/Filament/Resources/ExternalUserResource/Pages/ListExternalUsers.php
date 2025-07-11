<?php

namespace App\Filament\Resources\ExternalUserResource\Pages;

use App\Filament\Resources\ExternalUserResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ListExternalUsers extends ListRecords
{
    protected static string $resource = ExternalUserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Faol',
                        'inactive' => 'Nofaol',
                        'blocked' => 'Bloklangan',
                    ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
