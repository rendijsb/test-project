<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Policies\Orders\OrderPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public const ORDER_ID = 'order';
    public const CUSTOMER_ID = 'customerId';
    public const STATUS = 'status';
    public const TOTAL_AMOUNT = 'totalAmount';
    public const DESCRIPTION = 'description';
    public const PRODUCT_NAME = 'productName';
    public const QUANTITY = 'quantity';
    public const ORDER_DATE = 'orderDate';

    public function authorize(): bool
    {
        $order = Order::query()->findOrFail($this->getOrderId());

        return $this->user()->can(OrderPolicy::UPDATE, $order);
    }

    public function rules(): array
    {
        return [
            self::ORDER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Order::TABLE, Order::ID)],
            self::CUSTOMER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Customer::TABLE, Customer::ID)],
            self::STATUS => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::enum(OrderStatusEnum::class)],
            self::TOTAL_AMOUNT => [ValidationHelper::REQUIRED, ValidationHelper::NUMERIC, ValidationHelper::decimal(0, 2), ValidationHelper::min(0)],
            self::DESCRIPTION => [ValidationHelper::NULLABLE, ValidationHelper::STRING, ValidationHelper::max(1000)],
            self::PRODUCT_NAME => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(255)],
            self::QUANTITY => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::min(1)],
            self::ORDER_DATE => [ValidationHelper::REQUIRED, ValidationHelper::DATE],
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

    public function getCustomerId(): int
    {
        return (int) $this->input(self::CUSTOMER_ID);
    }

    public function getStatus(): string
    {
        return $this->input(self::STATUS);
    }

    public function getTotalAmount(): string
    {
        return $this->input(self::TOTAL_AMOUNT);
    }

    public function getDescription(): ?string
    {
        return $this->input(self::DESCRIPTION);
    }

    public function getProductName(): string
    {
        return $this->input(self::PRODUCT_NAME);
    }

    public function getQuantity(): int
    {
        return (int) $this->input(self::QUANTITY);
    }

    public function getOrderDate(): string
    {
        return $this->input(self::ORDER_DATE);
    }
}
