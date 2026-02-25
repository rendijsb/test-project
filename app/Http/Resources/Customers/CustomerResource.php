<?php

declare(strict_types=1);

namespace App\Http\Resources\Customers;

use App\Models\Customers\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public $resource = Customer::class;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'email' => $this->resource->getEmail(),
            'phone' => $this->resource->getPhone(),
            'addressLine1' => $this->resource->getAddressLine1(),
            'addressLine2' => $this->resource->getAddressLine2(),
            'city' => $this->resource->getCity(),
            'postalCode' => $this->resource->getPostalCode(),
            'country' => $this->resource->getCountry(),
        ];
    }
}