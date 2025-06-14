<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Customers;

use App\Actions\Customers\Address\CreateCustomerAddressAction;
use App\Actions\Customers\Address\FetchCustomerAddressesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;

class AddressController extends Controller
{
    public function index()
    {
        return app(FetchCustomerAddressesAction::class)->execute();
    }

    public function store(AddressRequest $request)
    {
        return app(CreateCustomerAddressAction::class)->execute($request);
    }
}
