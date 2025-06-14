<?php

declare(strict_types=1);

namespace App\Actions\Customers\Auth;

use Illuminate\Http\JsonResponse;

class LogoutCustomerAction
{
    public function execute(): JsonResponse
    {
        // Revoke current user's token
        auth()->user()->currentAccessToken()->delete(); // ✅ Deletes only the current token

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
