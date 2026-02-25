<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Http\Resources\Customers\CustomerResource;
use App\Models\Customers\Customer;
use App\Policies\Customers\CustomerPolicy;
use App\Services\Helpers\ValidationHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const COMPANY_NAME = 'companyName';
    public const ADDRESS_LINE_1 = 'addressLine1';
    public const ADDRESS_LINE_2 = 'addressLine2';
    public const CITY = 'city';
    public const POSTAL_CODE = 'postalCode';
    public const COUNTRY = 'country';

    public const CUSTOMER_ID = 'customer';

    public function authorize(): bool
    {
        return $this->user()->can(CustomerPolicy::UPDATE, Customer::class);
    }

    public function rules(): array
    {
        return [
            self::NAME => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(60)],
            self::EMAIL => [ValidationHelper::REQUIRED, ValidationHelper::EMAIL, ValidationHelper::max(60)],
            self::PHONE => [ValidationHelper::NULLABLE, ValidationHelper::STRING, ValidationHelper::max(10)],
            self::COMPANY_NAME => [ValidationHelper::NULLABLE, ValidationHelper::STRING, ValidationHelper::max(50)],
            self::ADDRESS_LINE_1 => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(50)],
            self::ADDRESS_LINE_2 => [ValidationHelper::NULLABLE, ValidationHelper::STRING, ValidationHelper::max(50)],
            self::CITY => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(50)],
            self::POSTAL_CODE => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(10)],
            self::COUNTRY => [ValidationHelper::REQUIRED, ValidationHelper::STRING, ValidationHelper::max(50)],
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

    public function getName(): string
    {
        return $this->input(self::NAME);
    }

    public function getEmail(): string
    {
        return $this->input(self::EMAIL);
    }

    public function getPhone(): ?string
    {
        return $this->input(self::PHONE);
    }

    public function getCompanyName(): ?string
    {
        return $this->input(self::COMPANY_NAME);
    }

    public function getAddressLine1(): string
    {
        return $this->input(self::ADDRESS_LINE_1);
    }

    public function getAddressLine2(): ?string
    {
        return $this->input(self::ADDRESS_LINE_2);
    }

    public function getCity(): string
    {
        return $this->input(self::CITY);
    }

    public function getPostalCode(): string
    {
        return $this->input(self::POSTAL_CODE);
    }

    public function getCountry(): string
    {
        return $this->input(self::COUNTRY);
    }
}