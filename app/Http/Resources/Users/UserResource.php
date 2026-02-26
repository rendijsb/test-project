<?php

declare(strict_types=1);

namespace App\Http\Resources\Users;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $resource = User::class;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute(User::NAME),
            'email' => $this->resource->getAttribute(User::EMAIL),
            'role' => $this->resource->getAttribute(User::ROLE)->value,
        ];
    }
}
