<?php

namespace App\Enums;

enum AdminRole: string
{
    case ADMINISTRATOR = 'administrator';
    case OPERATOR = 'operator';
    case CONTENT_MANAGER = 'content_manager';
    case OPERATOR_ADMIN = 'operator_admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::OPERATOR => 'Operator',
            self::CONTENT_MANAGER => 'Kontent Menejer',
            self::OPERATOR_ADMIN => 'Operator Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => [
                'manage_admins',
                'manage_users',
                'manage_content',
                'manage_settings',
                'view_analytics',
                'manage_system',
                'delete_records',
                'export_data',
            ],
            self::ADMINISTRATOR => [
                'manage_users',
                'manage_content',
                'view_analytics',
                'manage_settings',
                'export_data',
            ],
            self::OPERATOR_ADMIN => [
                'manage_users',
                'view_analytics',
                'manage_content',
            ],
            self::CONTENT_MANAGER => [
                'manage_content',
                'view_analytics',
            ],
            self::OPERATOR => [
                'view_users',
                'view_content',
                'view_analytics',
            ],
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
