<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\Http\Resources\Customers\CustomerResourceCollection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class GetAllCustomersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function responseResource(Collection $collection): CustomerResourceCollection
    {
        return new CustomerResourceCollection($collection);
    }
}