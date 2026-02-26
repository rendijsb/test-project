<?php

namespace Database\Seeders;

use App\Enums\Users\UserRoleEnum;
use App\Models\Customers\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@mail.com'],
            [
                'name' => 'Test User',
                'role' => UserRoleEnum::ADMIN,
                'password' => bcrypt('password'),
            ],
        );

        Customer::factory(50)->create();
    }
}
