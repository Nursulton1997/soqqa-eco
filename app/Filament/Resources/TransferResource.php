<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferResource\Pages;
use App\Filament\Resources\TransferResource\RelationManagers;
use App\Models\Transfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;


    protected static ?string $navigationLabel = 'Tranzaksiyalar';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';
    protected static ?string $navigationGroup = 'Ma\'lumotlar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')->required()->numeric(),
                Forms\Components\TextInput::make('amount')->required()->numeric(),
                Forms\Components\Select::make('credit_state')
                    ->options([
                        'SUCCESS' => 'Success',
                        'PENDING' => 'Pending',
                        'FAILED' => 'Failed',
                    ])->required(),
                Forms\Components\DateTimePicker::make('created_at'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('amount')->money('UZS', true),
                Tables\Columns\BadgeColumn::make('credit_state')
                    ->colors([
                        'success' => 'SUCCESS',
                        'warning' => 'PENDING',
                        'danger' => 'FAILED',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransfers::route('/'),
        ];
    }
}
