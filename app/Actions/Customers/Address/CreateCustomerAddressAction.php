<?php

declare(strict_types=1);

namespace App\Actions\Customers\Address;

use App\Http\Requests\Customer\AddressRequest;
use App\Models\Customer;

class CreateCustomerAddressAction
{
    public function execute(AddressRequest $request)
    {
        $data = $request->validated();

        /** @var Customer $customer */
        $customer = auth()->user();

        $customer->addresses()->create($data);

        return response()->json([
            'message' => 'Your address has been created.',
        ]);
    }
}
