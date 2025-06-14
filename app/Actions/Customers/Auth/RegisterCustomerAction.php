<?php

declare(strict_types=1);

namespace App\Actions\Customers\Auth;

use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class RegisterCustomerAction
{
    public function execute(RegisterCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();

        $customer = Customer::create($data);

        return response()->json($customer);
    }
}
