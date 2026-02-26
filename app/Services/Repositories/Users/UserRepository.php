<?php

declare(strict_types=1);

namespace App\Services\Repositories\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getAll(
        string $sortBy,
        string $sortDirection,
        int $perPage,
        ?string $search = null,
        ?int $excludeId = null,
    ): LengthAwarePaginator {
        $query = User::query();

        if ($excludeId !== null) {
            $query->exclude($excludeId);
        }

        if ($search) {
            $query->search($search);
        }

        return $query
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function findById(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        $user->refresh();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
