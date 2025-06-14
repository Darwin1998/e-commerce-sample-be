<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Customers;

use App\Actions\Customers\Auth\ChangeCustomerPasswordAction;
use App\Actions\Customers\Auth\LoginCustomerAction;
use App\Actions\Customers\Auth\LogoutCustomerAction;
use App\Actions\Customers\Auth\RegisterCustomerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ChangePasswordRequest;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return app(LoginCustomerAction::class)->execute($request);
    }

    public function logout()
    {
        return app(LogoutCustomerAction::class)->execute();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return app(ChangeCustomerPasswordAction::class)->execute($request);
    }

    public function register(RegisterCustomerRequest $request)
    {
        return app(RegisterCustomerAction::class)->execute($request);
    }
}
