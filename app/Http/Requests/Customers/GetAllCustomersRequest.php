<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Http\Resources\Customers\CustomerResourceCollection;
use App\Models\Customers\Customer;
use App\Policies\Customers\CustomerPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class GetAllCustomersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(CustomerPolicy::VIEW_ANY, Customer::class);
    }

    public function responseResource(Collection $collection): CustomerResourceCollection
    {
        return new CustomerResourceCollection($collection);
    }
}