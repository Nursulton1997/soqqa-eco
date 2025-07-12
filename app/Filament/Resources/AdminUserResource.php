<?php

namespace App\Filament\Resources;

use App\Models\AdminUser;
use App\Enums\AdminRole;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\AdminUserResource\Pages;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\BadgeColumn;

class AdminUserResource extends Resource
{
    protected static ?string $model = AdminUser::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Admin Foydalanuvchilar';
    protected static ?string $navigationGroup = 'Admin Boshqaruv';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Asosiy Ma\'lumotlar')
                ->schema([
                    TextInput::make('name')
                        ->label('Ism Familiya')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('phone')
                        ->label('Telefon')
                        ->tel()
                        ->maxLength(255),
                ])
                ->columns(2),

            Section::make('Xavfsizlik')
                ->schema([
                    TextInput::make('password')
                        ->label('Parol')
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->maxLength(255)
                        ->helperText('Kamida 8 ta belgi bo\'lishi kerak'),

                    Select::make('role')
                        ->label('Rol')
                        ->options(AdminRole::options())
                        ->required()
                        ->helperText('Admin rolini tanlang'),

                    Select::make('status')
                        ->label('Holat')
                        ->options([
                            'active' => 'Faol',
                            'inactive' => 'Nofaol',
                            'blocked' => 'Bloklangan',
                        ])
                        ->default('active')
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Ism Familiya')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (AdminRole $state): string => $state->label())
                    ->color(fn (AdminRole $state): string => match ($state) {
                        AdminRole::SUPER_ADMIN => 'danger',
                        AdminRole::ADMINISTRATOR => 'warning',
                        AdminRole::OPERATOR_ADMIN => 'info',
                        AdminRole::CONTENT_MANAGER => 'success',
                        AdminRole::OPERATOR => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'blocked' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Faol',
                        'inactive' => 'Nofaol',
                        'blocked' => 'Bloklangan',
                    }),

                TextColumn::make('last_login_at')
                    ->label('Oxirgi Kirish')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('login_attempts')
                    ->label('Kirish Urinishlari')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 3 ? 'danger' : 'success'),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rol')
                    ->options(AdminRole::options()),

                SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        'active' => 'Faol',
                        'inactive' => 'Nofaol',
                        'blocked' => 'Bloklangan',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ko\'rish'),

                EditAction::make()
                    ->label('Tahrirlash'),

                Action::make('reset_login_attempts')
                    ->label('Kirish Urinishlarini Tozalash')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(fn (AdminUser $record) => $record->update(['login_attempts' => 0]))
                    ->visible(fn (AdminUser $record) => $record->login_attempts > 0)
                    ->requiresConfirmation(),

                Action::make('toggle_status')
                    ->label(fn (AdminUser $record) => $record->status === 'active' ? 'Bloklash' : 'Faollashtirish')
                    ->icon(fn (AdminUser $record) => $record->status === 'active' ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->color(fn (AdminUser $record) => $record->status === 'active' ? 'danger' : 'success')
                    ->action(function (AdminUser $record) {
                        $record->update([
                            'status' => $record->status === 'active' ? 'blocked' : 'active',
                            'login_attempts' => 0,
                        ]);
                    })
                    ->requiresConfirmation(),

                DeleteAction::make()
                    ->label('O\'chirish'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminUsers::route('/'),
            'create' => Pages\CreateAdminUser::route('/create'),
            'edit' => Pages\EditAdminUser::route('/{record}/edit'),
            'view' => Pages\ViewAdminUser::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return auth('admin')->user()?->hasPermission('manage_admins') ?? false;
    }
}
