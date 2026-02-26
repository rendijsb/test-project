<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Enums\Users\UserRoleEnum;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Policies\Users\UserPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public const USER_ID = 'user';
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const ROLE = 'role';

    public function authorize(): bool
    {
        return $this->user()->can(UserPolicy::UPDATE, User::class);
    }

    public function rules(): array
    {
        return [
            self::USER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(User::TABLE, User::ID)],
            self::NAME => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(255)],
            self::EMAIL => [ValidationHelper::REQUIRED, ValidationHelper::EMAIL, ValidationHelper::max(255), ValidationHelper::uniqueOnDatabase(User::TABLE, User::EMAIL)->ignore($this->getUserId())],
            self::PASSWORD => [ValidationHelper::NULLABLE, ValidationHelper::STRING, ValidationHelper::min(8)],
            self::ROLE => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::enum(UserRoleEnum::class)],
        ];
    }

    public function responseResource(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            self::USER_ID => $this->getUserId(),
        ]);
    }

    public function getUserId(): int
    {
        return (int) $this->route(self::USER_ID);
    }

    public function getName(): string
    {
        return $this->input(self::NAME);
    }

    public function getEmail(): string
    {
        return $this->input(self::EMAIL);
    }

    public function getPassword(): ?string
    {
        return $this->input(self::PASSWORD);
    }

    public function getRole(): string
    {
        return $this->input(self::ROLE);
    }
}
