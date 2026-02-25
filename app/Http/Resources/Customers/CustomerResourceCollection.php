<?php

declare(strict_types=1);

namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerResourceCollection extends ResourceCollection
{
    public $collects = CustomerResource::class;
}