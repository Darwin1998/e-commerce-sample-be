<?php

declare(strict_types=1);

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        parent::toArray($request);
        $disk = config('filesystems.default');
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'price' => $this->resource->price,
            'stock' => $this->resource->stock,
            'image' => $disk === 's3'
                ? ($this->resource->image
                    ? Storage::temporaryUrl($this->resource->image, now()->addMinutes(30))
                    : asset('images/default.png'))
                : ($this->resource->image
                    ? Storage::url($this->resource->image)
                    : asset('images/default.png')),
            'view_link' => route('products.show', $this->resource->id),
        ];
    }
}
