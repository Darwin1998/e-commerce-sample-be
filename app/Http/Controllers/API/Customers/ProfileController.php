<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Customers;

use App\Actions\Customers\Profile\FetchCustomerProfileAction;
use App\Actions\Customers\Profile\UpdateCustomerProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProfileRequest;

class ProfileController extends Controller
{
    public function getProfile()
    {
        return app(FetchCustomerProfileAction::class)->execute();
    }

    public function update(ProfileRequest $request)
    {
        return app(UpdateCustomerProfileAction::class)->execute($request);
    }
}
