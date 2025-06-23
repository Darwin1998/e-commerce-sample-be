<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        parent::toArray($request);

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone_number' => $this->resource->phone_number,
            'profile_picture' => Storage::temporaryUrl($this->resource->image, now()->addMinutes(30)),
            'addresses' => AddressResource::collection($this->resource->addresses),
        ];
    }
}
