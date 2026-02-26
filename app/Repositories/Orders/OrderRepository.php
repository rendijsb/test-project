<?php

declare(strict_types=1);

namespace App\Repositories\Orders;

use App\Models\Orders\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function getAll(
        string $sortBy,
        string $sortDirection,
        int $perPage,
        ?string $status = null,
        ?int $customerId = null,
    ): LengthAwarePaginator {
        $query = Order::query()
            ->orderBy($sortBy, $sortDirection);

        if ($status !== null) {
            $query->byStatus($status);
        }

        if ($customerId !== null) {
            $query->byCustomer($customerId);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): Order
    {
        return Order::query()->findOrFail($id);
    }

    public function getByCustomer(
        int $customerId,
        string $sortBy,
        string $sortDirection,
        int $perPage,
    ): LengthAwarePaginator {
        return Order::query()
            ->byCustomer($customerId)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        $order->refresh();

        return $order;
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }
}
