<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Models\Customers\Customer;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCustomerRequest extends FormRequest
{
    public const CUSTOMER_ID = 'customer';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::CUSTOMER_ID => [ValidationHelper::REQUIRED, ValidationHelper::INTEGER, ValidationHelper::existsOnDatabase(Customer::class, Customer::ID)],
        ];
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