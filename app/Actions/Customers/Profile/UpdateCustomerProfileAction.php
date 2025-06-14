<?php

declare(strict_types=1);

namespace App\Actions\Customers\Profile;

use App\Http\Requests\Customer\ProfileRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UpdateCustomerProfileAction
{
    public function execute(ProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var Customer $customer */
        $customer = auth()->user();
        Storage::deleteDirectory($customer->image);

        $path = $request->file('profile_picture')->store('customers/'.$customer->id);

        $customer->update([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'image' => $path,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
        ]);

    }
}
