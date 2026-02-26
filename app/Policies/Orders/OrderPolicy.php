<?php

declare(strict_types=1);

namespace App\Policies\Orders;

use App\Models\Orders\Order;
use App\Models\User;

class OrderPolicy
{
    public const VIEW_ANY = 'viewAny';
    public const VIEW = 'view';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->getKey() === $order->getUserId();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
