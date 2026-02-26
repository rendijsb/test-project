<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();
        $customers = Customer::all();

        if ($users->isEmpty() || $customers->isEmpty()) {
            return;
        }

        Order::factory(30)->create([
            Order::USER_ID => fn () => $users->random()->getKey(),
            Order::CUSTOMER_ID => fn () => $customers->random()->getKey(),
        ]);
    }
}
