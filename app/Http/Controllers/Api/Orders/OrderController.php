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
use App\Services\Repositories\Orders\OrderRepository;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function index(GetAllOrdersRequest $request): OrderResourceCollection
    {
        $orders = $this->orderRepository->getAll(
            $request->getSortBy(),
            $request->getSortDirection(),
            $request->getPerPage(),
            $request->getStatus(),
            $request->getCustomerId(),
        );

        return $request->responseResource($orders);
    }

    public function store(CreateOrderRequest $request): OrderResource
    {
        $order = $this->orderRepository->create([
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
        $order = $this->orderRepository->findById($request->getOrderId());

        return $request->responseResource($order);
    }

    public function update(UpdateOrderRequest $request): OrderResource
    {
        $order = $this->orderRepository->findById($request->getOrderId());

        $order = $this->orderRepository->update($order, [
            Order::CUSTOMER_ID => $request->getCustomerId(),
            Order::STATUS => $request->getStatus(),
            Order::TOTAL_AMOUNT => $request->getTotalAmount(),
            Order::DESCRIPTION => $request->getDescription(),
            Order::ORDER_DATE => $request->getOrderDate(),
        ]);

        return $request->responseResource($order);
    }

    public function destroy(DeleteOrderRequest $request): Response
    {
        $order = $this->orderRepository->findById($request->getOrderId());
        $this->orderRepository->delete($order);

        return response()->noContent();
    }

    public function customerOrders(GetCustomerOrdersRequest $request): OrderResourceCollection
    {
        $orders = $this->orderRepository->getByCustomer(
            $request->getCustomerId(),
            $request->getSortBy(),
            $request->getSortDirection(),
            $request->getPerPage(),
        );

        return $request->responseResource($orders);
    }
}
