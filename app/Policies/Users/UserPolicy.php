<?php

declare(strict_types=1);

namespace App\Policies\Users;

use App\Models\User;

class UserPolicy
{
    /** @see UserPolicy::viewAny() */
    public const VIEW_ANY = 'viewAny';
    /** @see UserPolicy::view() */
    public const VIEW = 'view';
    /** @see UserPolicy::create() */
    public const CREATE = 'create';
    /** @see UserPolicy::update() */
    public const UPDATE = 'update';
    /** @see UserPolicy::delete() */
    public const DELETE = 'delete';

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user): bool
    {
        return $user->isAdmin();
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
