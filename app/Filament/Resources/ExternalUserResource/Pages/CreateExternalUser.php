<?php

namespace App\Filament\Resources\ExternalUserResource\Pages;

use App\Filament\Resources\ExternalUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExternalUser extends CreateRecord
{
    protected static string $resource = ExternalUserResource::class;
}
