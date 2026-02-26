<?php

declare(strict_types=1);

namespace App\Http\Resources\Orders;

use App\Models\Orders\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public $resource = Order::class;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'customerId' => $this->resource->getCustomerId(),
            'userId' => $this->resource->getUserId(),
            'status' => $this->resource->getStatus()->value,
            'totalAmount' => $this->resource->getTotalAmount(),
            'description' => $this->resource->getDescription(),
            'orderDate' => $this->resource->getOrderDate(),
        ];
    }
}
