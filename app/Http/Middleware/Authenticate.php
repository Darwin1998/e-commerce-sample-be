<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?\Illuminate\Http\JsonResponse
    {
        if (! $request->expectsJson()) {
            abort(response()->json(['message' => 'Unauthorized'], 401));
        }

        return null; // ✅ Ensures Laravel does not expect a redirect URL
    }
}
