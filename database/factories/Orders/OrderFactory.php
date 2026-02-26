<?php

declare(strict_types=1);

namespace Database\Factories\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    use WithFaker;

    protected $model = Order::class;

    public function definition(): array
    {
        return [
            Order::CUSTOMER_ID => Customer::factory(),
            Order::USER_ID => User::factory(),
            Order::STATUS => $this->faker->randomElement(OrderStatusEnum::cases())->value,
            Order::TOTAL_AMOUNT => $this->faker->randomFloat(2, 10, 5000),
            Order::DESCRIPTION => $this->faker->optional()->sentence(),
            Order::PRODUCT_NAME => $this->faker->words(3, true),
            Order::QUANTITY => $this->faker->numberBetween(1, 100),
            Order::ORDER_DATE => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
