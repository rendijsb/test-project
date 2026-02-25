<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Http\Resources\Customers\CustomerResource;
use App\Models\Customers\Customer;
use App\Policies\Customers\CustomerPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class GetCustomerByIdRequest extends FormRequest
{
    public const CUSTOMER_ID = 'customer';

    public function authorize(): bool
    {
        return $this->user()->can(CustomerPolicy::VIEW, Customer::class);
    }

    public function rules(): array
    {
        return [
            self::CUSTOMER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Customer::class, Customer::ID)],
        ];
    }

    public function responseResource(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            self::CUSTOMER_ID => $this->getCustomerId(),
        ]);
    }

    public function getCustomerId(): int
    {
        return (int)$this->route(self::CUSTOMER_ID);
    }
}