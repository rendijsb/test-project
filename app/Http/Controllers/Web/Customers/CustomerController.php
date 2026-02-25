<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Customers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('customers.index');
    }

    public function show(int $customer): View
    {
        return view('customers.show', [
            'customerId' => $customer,
        ]);
    }
}
