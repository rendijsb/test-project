<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\GetAllUsersRequest;
use App\Http\Requests\Users\GetUserByIdRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Users\UserResourceCollection;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(GetAllUsersRequest $request): UserResourceCollection
    {
        $query = User::query();

        $search = $request->getSearch();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where(User::NAME, 'like', '%' . $search . '%')
                    ->orWhere(User::EMAIL, 'like', '%' . $search . '%');
            });
        }

        $users = $query
            ->orderBy($request->getSortBy(), $request->getSortDirection())
            ->paginate($request->getPerPage());

        return $request->responseResource($users);
    }

    public function store(CreateUserRequest $request): UserResource
    {
        $user = User::query()->create([
            User::NAME => $request->getName(),
            User::EMAIL => $request->getEmail(),
            User::PASSWORD => $request->getPassword(),
            User::ROLE => $request->getRole(),
        ]);

        return $request->responseResource($user);
    }

    public function show(GetUserByIdRequest $request): UserResource
    {
        $user = User::query()->findOrFail($request->getUserId());

        return $request->responseResource($user);
    }

    public function update(UpdateUserRequest $request): UserResource
    {
        $user = User::query()->findOrFail($request->getUserId());

        $data = [
            User::NAME => $request->getName(),
            User::EMAIL => $request->getEmail(),
            User::ROLE => $request->getRole(),
        ];

        if ($request->getPassword()) {
            $data[User::PASSWORD] = $request->getPassword();
        }

        $user->update($data);
        $user->refresh();

        return $request->responseResource($user);
    }

    public function destroy(DeleteUserRequest $request): Response
    {
        $user = User::query()->findOrFail($request->getUserId());

        $user->delete();

        return response()->noContent();
    }
}
