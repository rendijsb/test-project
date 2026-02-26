<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Models\User;
use App\Policies\Users\UserPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    public const USER_ID = 'user';

    public function authorize(): bool
    {
        return $this->user()->can(UserPolicy::DELETE, User::class);
    }

    public function rules(): array
    {
        return [
            self::USER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(User::TABLE, User::ID)],
        ];
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
}
