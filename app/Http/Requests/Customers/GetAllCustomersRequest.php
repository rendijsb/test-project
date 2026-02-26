<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Http\Resources\Customers\CustomerResourceCollection;
use App\Models\Customers\Customer;
use App\Policies\Customers\CustomerPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class GetAllCustomersRequest extends FormRequest
{
    private const SORT_BY = 'sortBy';
    private const SORT_DIRECTION = 'sortDirection';

    private const SORT_ASC = 'asc';
    private const SORT_DESC = 'desc';

    private const DEFAULT_SORT_BY_KEY = 'name';
    private const DEFAULT_SORT_BY = Customer::NAME;
    private const DEFAULT_SORT_DIRECTION = self::SORT_ASC;

    private const SORTABLE_COLUMNS = [
        'name' => Customer::NAME,
        'email' => Customer::EMAIL,
        'phone' => Customer::PHONE,
        'companyName' => Customer::COMPANY_NAME,
        'city' => Customer::CITY,
        'country' => Customer::COUNTRY,
    ];

    public function authorize(): bool
    {
        return $this->user()->can(CustomerPolicy::VIEW_ANY, Customer::class);
    }

    public function rules(): array
    {
        return [
            self::SORT_BY => [ValidationHelper::SOMETIMES, ValidationHelper::STRING, ValidationHelper::in(array_keys(self::SORTABLE_COLUMNS))],
            self::SORT_DIRECTION => [ValidationHelper::SOMETIMES, ValidationHelper::STRING, ValidationHelper::in([self::SORT_ASC, self::SORT_DESC])],
        ];
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

    public function responseResource(Collection $collection): CustomerResourceCollection
    {
        return new CustomerResourceCollection($collection);
    }
}