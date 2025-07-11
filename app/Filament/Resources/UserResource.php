<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Foydalanuvchilar';
    protected static ?string $navigationGroup = 'Boshqaruv';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('Parol')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->maxLength(255),

            Select::make('status')
                ->options([
                    'active' => 'Faol',
                    'inactive' => 'Nofaol',
                    'blocked' => 'Bloklangan',
                ])
                ->required(),

            Select::make('roles')
                ->label('Rollar')
                ->multiple()
                ->relationship('roles', 'name')
                ->preload(),

            Select::make('permissions')
                ->label('Ruxsatlar')
                ->multiple()
                ->relationship('permissions', 'name')
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('roles.name')
                    ->label('Rollar')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Faol',
                        'inactive' => 'Nofaol',
                        'blocked' => 'Bloklangan',
                    ]),
            ])
            ->actions([
                Action::make('bloklash')
                    ->label('Bloklash')
                    ->color('danger')
                    ->action(fn (User $record) => $record->update(['status' => 'blocked']))
                    ->visible(fn (User $record) => $record->status !== 'blocked'),

                Action::make('aktivlashtirish')
                    ->label('Faollashtirish')
                    ->color('success')
                    ->action(fn (User $record) => $record->update(['status' => 'active']))
                    ->visible(fn (User $record) => $record->status !== 'active'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
