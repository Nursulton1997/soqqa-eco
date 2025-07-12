<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Enums\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('superadmin123'),
                'role' => AdminRole::SUPER_ADMIN,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'role' => AdminRole::ADMINISTRATOR,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Operator Admin',
                'email' => 'operatoradmin@admin.com',
                'password' => Hash::make('operatoradmin123'),
                'role' => AdminRole::OPERATOR_ADMIN,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Content Manager',
                'email' => 'content@admin.com',
                'password' => Hash::make('content123'),
                'role' => AdminRole::CONTENT_MANAGER,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@admin.com',
                'password' => Hash::make('operator123'),
                'role' => AdminRole::OPERATOR,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($adminUsers as $adminData) {
            AdminUser::firstOrCreate(
                ['email' => $adminData['email']],
                $adminData
            );
        }

        $this->command->info('Admin userlar yaratildi:');
        $this->command->info('Super Admin: superadmin@admin.com / superadmin123');
        $this->command->info('Administrator: admin@admin.com / admin123');
        $this->command->info('Operator Admin: operatoradmin@admin.com / operatoradmin123');
        $this->command->info('Content Manager: content@admin.com / content123');
        $this->command->info('Operator: operator@admin.com / operator123');
    }
}
