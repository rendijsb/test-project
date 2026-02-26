<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\CreateCustomerRequest;
use App\Http\Requests\Customers\DeleteCustomerRequest;
use App\Http\Requests\Customers\GetAllCustomersRequest;
use App\Http\Requests\Customers\GetCustomerByIdRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Resources\Customers\CustomerResource;
use App\Http\Resources\Customers\CustomerResourceCollection;
use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllCustomersRequest $request): CustomerResourceCollection
    {
        $customers = Customer::query()
            ->orderBy($request->getSortBy(), $request->getSortDirection())
            ->get();

        return $request->responseResource($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request): CustomerResource
    {
        $customer = Customer::query()->create([
            Customer::NAME => $request->getName(),
            Customer::EMAIL => $request->getEmail(),
            Customer::PHONE => $request->getPhone(),
            Customer::COMPANY_NAME => $request->getCompanyName(),
            Customer::ADDRESS_LINE_1 => $request->getAddressLine1(),
            Customer::ADDRESS_LINE_2 => $request->getAddressLine2(),
            Customer::CITY => $request->getCity(),
            Customer::POSTAL_CODE => $request->getPostalCode(),
            Customer::COUNTRY => $request->getCountry(),
        ]);

        return $request->responseResource($customer);
    }

    /**
     * Display the specified resource.
     * @throws ModelNotFoundException
     */
    public function show(GetCustomerByIdRequest $request): CustomerResource
    {
        $customer = Customer::query()->findOrFail($request->getCustomerId());

        return $request->responseResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request): CustomerResource
    {
        $customer = Customer::query()->findOrFail($request->getCustomerId());

        $customer->update([
            Customer::NAME => $request->getName(),
            Customer::EMAIL => $request->getEmail(),
            Customer::PHONE => $request->getPhone(),
            Customer::COMPANY_NAME => $request->getCompanyName(),
            Customer::ADDRESS_LINE_1 => $request->getAddressLine1(),
            Customer::ADDRESS_LINE_2 => $request->getAddressLine2(),
            Customer::CITY => $request->getCity(),
            Customer::POSTAL_CODE => $request->getPostalCode(),
            Customer::COUNTRY => $request->getCountry(),
        ]);

        $customer->refresh();

        return $request->responseResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCustomerRequest $request): Response
    {
        $customer = Customer::query()->findOrFail($request->getCustomerId());

        $customer->delete();

        return response()->noContent();
    }
}
