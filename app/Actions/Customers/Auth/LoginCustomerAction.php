<?php

declare(strict_types=1);

namespace App\Actions\Customers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCustomerAction
{
    public function execute(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('customer')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $customer = Auth::guard('customer')->user();

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }
}
