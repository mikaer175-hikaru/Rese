<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    private const DEFAULT_PASSWORD = 'password123';

    private const ADMIN_EMAIL = 'admin@example.com';
    private const OWNER_EMAIL = 'owner@example.com';
    private const USER_EMAIL  = 'user@example.com';

    public function run(): void
    {
        $this->seedAdminUser();
        $this->seedOwnerUser();
        $this->seedNormalUser();
    }

    private function seedAdminUser(): void
    {
        $this->upsertUser(
            self::ADMIN_EMAIL,
            'Admin User',
            [
                'role'     => 'admin',
                'is_admin' => true,
            ]
        );
    }

    private function seedOwnerUser(): void
    {
        $this->upsertUser(
            self::OWNER_EMAIL,
            'Owner User',
            [
                'role'     => 'owner',
                'is_owner' => true,
            ]
        );
    }

    private function seedNormalUser(): void
    {
        $this->upsertUser(
            self::USER_EMAIL,
            'Normal User',
            [
                'role' => 'user',
            ]
        );
    }

    /**
     * email をキーに更新/作成します（2回 seed しても壊れない）。
     */
    private function upsertUser(string $email, string $name, array $roleAttributes = []): void
    {
        $attributes = [
            'name'              => $name,
            'email'             => $email,
            'password'          => Hash::make(self::DEFAULT_PASSWORD),
            'remember_token'    => Str::random(10),
            'email_verified_at' => now(),
        ];

        $attributes = $this->filterByExistingColumns('users', $attributes);

        $roleAttributes = $this->filterByExistingColumns('users', $roleAttributes);

        User::updateOrCreate(
            ['email' => $email],
            array_merge($attributes, $roleAttributes)
        );
    }

    /**
     * 指定テーブルに存在するカラムのみ残します。
     */
    private function filterByExistingColumns(string $table, array $attributes): array
    {
        $filtered = [];

        foreach ($attributes as $key => $value) {
            if (!Schema::hasColumn($table, $key)) {
                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }
}
