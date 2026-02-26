<?php

declare(strict_types=1);

namespace Database\Factories\Customers;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    use WithFaker;

    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            Customer::NAME => $this->faker->name(),
            Customer::EMAIL => $this->faker->unique()->safeEmail(),
            Customer::PHONE => $this->faker->numerify('########'),
            Customer::COMPANY_NAME => substr($this->faker->company(), 0, 30),
            Customer::ADDRESS_LINE_1 => $this->faker->streetAddress(),
            Customer::ADDRESS_LINE_2 => $this->faker->optional()->secondaryAddress(),
            Customer::CITY => $this->faker->city(),
            Customer::POSTAL_CODE => $this->faker->postcode(),
            Customer::COUNTRY => $this->faker->country(),
        ];
    }
}
