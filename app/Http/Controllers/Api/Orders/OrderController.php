<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Orders;

use App\Enums\Orders\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\DeleteOrderRequest;
use App\Http\Requests\Orders\GetAllOrdersRequest;
use App\Http\Requests\Orders\GetCustomerOrdersRequest;
use App\Http\Requests\Orders\GetOrderByIdRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\OrderResourceCollection;
use App\Models\Orders\Order;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(GetAllOrdersRequest $request): OrderResourceCollection
    {
        $query = Order::query()
            ->orderBy($request->getSortBy(), $request->getSortDirection());

        if ($request->getStatus() !== null) {
            $query->where(Order::STATUS, $request->getStatus());
        }

        if ($request->getCustomerId() !== null) {
            $query->where(Order::CUSTOMER_ID, $request->getCustomerId());
        }

        $orders = $query->paginate($request->getPerPage());

        return $request->responseResource($orders);
    }

    public function store(CreateOrderRequest $request): OrderResource
    {
        $order = Order::query()->create([
            Order::CUSTOMER_ID => $request->getCustomerId(),
            Order::USER_ID => auth()->id(),
            Order::STATUS => $request->getStatus() ?? OrderStatusEnum::PENDING->value,
            Order::TOTAL_AMOUNT => $request->getTotalAmount(),
            Order::DESCRIPTION => $request->getDescription(),
            Order::ORDER_DATE => $request->getOrderDate(),
        ]);

        return $request->responseResource($order);
    }

    public function show(GetOrderByIdRequest $request): OrderResource
    {
        $order = Order::query()->findOrFail($request->getOrderId());

        return $request->responseResource($order);
    }

    public function update(UpdateOrderRequest $request): OrderResource
    {
        $order = Order::query()->findOrFail($request->getOrderId());

        $order->update([
            Order::CUSTOMER_ID => $request->getCustomerId(),
            Order::STATUS => $request->getStatus(),
            Order::TOTAL_AMOUNT => $request->getTotalAmount(),
            Order::DESCRIPTION => $request->getDescription(),
            Order::ORDER_DATE => $request->getOrderDate(),
        ]);

        $order->refresh();

        return $request->responseResource($order);
    }

    public function destroy(DeleteOrderRequest $request): Response
    {
        $order = Order::query()->findOrFail($request->getOrderId());
        $order->delete();

        return response()->noContent();
    }

    public function customerOrders(GetCustomerOrdersRequest $request): OrderResourceCollection
    {
        $orders = Order::query()
            ->where(Order::CUSTOMER_ID, $request->getCustomerId())
            ->orderBy($request->getSortBy(), $request->getSortDirection())
            ->paginate($request->getPerPage());

        return $request->responseResource($orders);
    }
}
