<?php

declare(strict_types=1);

namespace App\Policies\Customers;

use App\Models\User;

class CustomerPolicy
{
    /** @see CustomerPolicy::viewAny() */
    public const VIEW_ANY = 'viewAny';
    /** @see CustomerPolicy::view() */
    public const VIEW = 'view';
    /** @see CustomerPolicy::create() */
    public const CREATE = 'create';
    /** @see CustomerPolicy::update() */
    public const UPDATE = 'update';
    /** @see CustomerPolicy::delete() */
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
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
