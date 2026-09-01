<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Developer',
                'slug' => Role::DEVELOPER,
                'description' => 'Super admin with full system access.',
            ],
            [
                'name' => 'Admin',
                'slug' => Role::ADMIN,
                'description' => 'Operational manager for services and transactions.',
            ],
            [
                'name' => 'Worker',
                'slug' => Role::WORKER,
                'description' => 'Game booster / joki worker.',
            ],
            [
                'name' => 'Customer',
                'slug' => Role::USER,
                'description' => 'End customer / buyer of services.',
            ],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['slug' => $role['slug']],
                $role,
            );
        }
    }
}
