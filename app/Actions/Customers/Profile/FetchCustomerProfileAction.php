<?php

declare(strict_types=1);

namespace App\Actions\Customers\Profile;

use App\Http\Resources\Customer\ProfileResource;
use Illuminate\Http\JsonResponse;

class FetchCustomerProfileAction
{
    public function execute(): JsonResponse
    {
        return response()->json([
            'data' => new ProfileResource(auth()->user()),
        ]);
    }
}
