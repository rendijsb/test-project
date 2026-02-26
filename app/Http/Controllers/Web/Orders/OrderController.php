<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Orders;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('orders.index');
    }

    public function show(int $order): View
    {
        return view('orders.show', [
            'orderId' => $order,
        ]);
    }
}
