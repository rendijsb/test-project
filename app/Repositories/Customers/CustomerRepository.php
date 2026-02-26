<?php

declare(strict_types=1);

namespace App\Repositories\Customers;

use App\Models\Customers\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository
{
    public function getAll(string $sortBy, string $sortDirection, int $perPage): LengthAwarePaginator
    {
        return Customer::query()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function findById(int $id): Customer
    {
        return Customer::query()->findOrFail($id);
    }

    public function create(array $data): Customer
    {
        return Customer::query()->create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);
        $customer->refresh();

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }
}
