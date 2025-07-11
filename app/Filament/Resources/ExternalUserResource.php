<?php

namespace App\Filament\Resources;

use App\Models\ExternalUser;
use Filament\Resources\Resource;
use App\Filament\Resources\ExternalUserResource\Pages;

class ExternalUserResource extends Resource
{
    protected static ?string $model = ExternalUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Mobil foydalanuvchilar';
    protected static ?string $navigationGroup = 'Maʼlumotlar';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExternalUsers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
