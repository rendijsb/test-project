<?php

declare(strict_types=1);

namespace App\Http\Requests\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Http\Resources\Orders\OrderResourceCollection;
use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Policies\Orders\OrderPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;

class GetAllOrdersRequest extends FormRequest
{
    private const SORT_BY = 'sortBy';
    private const SORT_DIRECTION = 'sortDirection';
    private const PER_PAGE = 'perPage';
    private const STATUS = 'status';
    private const CUSTOMER_ID = 'customerId';
    private const SORT_ASC = 'asc';
    private const SORT_DESC = 'desc';
    private const DEFAULT_PER_PAGE = 10;
    private const MAX_PER_PAGE = 100;
    private const DEFAULT_SORT_BY_KEY = 'orderDate';
    private const DEFAULT_SORT_BY = Order::ORDER_DATE;
    private const DEFAULT_SORT_DIRECTION = self::SORT_DESC;

    private const SORTABLE_COLUMNS = [
        'orderDate' => Order::ORDER_DATE,
        'status' => Order::STATUS,
        'totalAmount' => Order::TOTAL_AMOUNT,
        'customerId' => Order::CUSTOMER_ID,
    ];

    public function authorize(): bool
    {
        return $this->user()->can(OrderPolicy::VIEW_ANY, Order::class);
    }

    public function rules(): array
    {
        return [
            self::PER_PAGE => [ValidationHelper::SOMETIMES, ValidationHelper::INTEGER, ValidationHelper::min(1), ValidationHelper::max(self::MAX_PER_PAGE)],
            self::SORT_BY => [ValidationHelper::SOMETIMES, ValidationHelper::STRING, ValidationHelper::in(array_keys(self::SORTABLE_COLUMNS))],
            self::SORT_DIRECTION => [ValidationHelper::SOMETIMES, ValidationHelper::STRING, ValidationHelper::in([self::SORT_ASC, self::SORT_DESC])],
            self::STATUS => [ValidationHelper::SOMETIMES, ValidationHelper::STRING, ValidationHelper::enum(OrderStatusEnum::class)],
            self::CUSTOMER_ID => [ValidationHelper::SOMETIMES, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Customer::TABLE, Customer::ID)],
        ];
    }

    public function getPerPage(): int
    {
        return (int) $this->validated(self::PER_PAGE, self::DEFAULT_PER_PAGE);
    }

    public function getSortBy(): string
    {
        $key = $this->validated(self::SORT_BY, self::DEFAULT_SORT_BY_KEY);

        return self::SORTABLE_COLUMNS[$key] ?? self::DEFAULT_SORT_BY;
    }

    public function getSortDirection(): string
    {
        return $this->validated(self::SORT_DIRECTION, self::DEFAULT_SORT_DIRECTION);
    }

    public function getStatus(): ?string
    {
        return $this->validated(self::STATUS);
    }

    public function getCustomerId(): ?int
    {
        $value = $this->validated(self::CUSTOMER_ID);

        return $value !== null ? (int) $value : null;
    }

    public function responseResource(LengthAwarePaginator $paginator): OrderResourceCollection
    {
        return new OrderResourceCollection($paginator);
    }
}
