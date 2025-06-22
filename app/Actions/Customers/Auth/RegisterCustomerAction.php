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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('customers', 's3');
            $data['image'] = $path;
        }
        $data['password'] = bcrypt($data['password']);
        Customer::create($data);

        return response()->json(['message' => 'Registration Success!'], 201);
    }
}
