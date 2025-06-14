<?php

declare(strict_types=1);

namespace App\Actions\Customers\Address;

use App\Http\Resources\Customer\AddressResource;
use App\Models\Customer;

class FetchCustomerAddressesAction
{
    public function execute()
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        $addresses = $customer->addresses()->get();

        return AddressResource::collection($addresses);
    }
}
