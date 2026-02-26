<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use App\Http\Resources\Orders\OrderResource;
use App\Models\Orders\Order;
use App\Policies\Orders\OrderPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderByIdRequest extends FormRequest
{
    public const ORDER_ID = 'order';

    public function authorize(): bool
    {
        return $this->user()->can(OrderPolicy::VIEW, Order::class);
    }

    public function rules(): array
    {
        return [
            self::ORDER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Order::TABLE, Order::ID)],
        ];
    }

    public function responseResource(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            self::ORDER_ID => $this->getOrderId(),
        ]);
    }

    public function getOrderId(): int
    {
        return (int) $this->route(self::ORDER_ID);
    }
}
