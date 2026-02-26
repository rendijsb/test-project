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
use App\Repositories\Users\UserRepository;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function index(GetAllUsersRequest $request): UserResourceCollection
    {
        $users = $this->userRepository->getAll(
            $request->getSortBy(),
            $request->getSortDirection(),
            $request->getPerPage(),
            $request->getSearch(),
        );

        return $request->responseResource($users);
    }

    public function store(CreateUserRequest $request): UserResource
    {
        $user = $this->userRepository->create([
            User::NAME => $request->getName(),
            User::EMAIL => $request->getEmail(),
            User::PASSWORD => $request->getPassword(),
            User::ROLE => $request->getRole(),
        ]);

        return $request->responseResource($user);
    }

    public function show(GetUserByIdRequest $request): UserResource
    {
        $user = $this->userRepository->findById($request->getUserId());

        return $request->responseResource($user);
    }

    public function update(UpdateUserRequest $request): UserResource
    {
        $user = $this->userRepository->findById($request->getUserId());

        $data = [
            User::NAME => $request->getName(),
            User::EMAIL => $request->getEmail(),
            User::ROLE => $request->getRole(),
        ];

        if ($request->getPassword()) {
            $data[User::PASSWORD] = $request->getPassword();
        }

        $user = $this->userRepository->update($user, $data);

        return $request->responseResource($user);
    }

    public function destroy(DeleteUserRequest $request): Response
    {
        $user = $this->userRepository->findById($request->getUserId());

        $this->userRepository->delete($user);

        return response()->noContent();
    }
}
