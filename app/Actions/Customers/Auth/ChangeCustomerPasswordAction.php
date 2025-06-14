<?php

declare(strict_types=1);

namespace App\Actions\Customers\Auth;

use App\Http\Requests\Customer\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;

class ChangeCustomerPasswordAction
{
    public function execute(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $user->update([
            'password' => bcrypt($data['new_password']),
        ]);

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Your password has been changed. Please login.',
        ]);
    }
}
