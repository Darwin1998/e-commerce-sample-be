<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Http\Resources\Products\ProductsResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowProductAction
{
    public function execute(Product $product): JsonResponse
    {
        return response()->json(['data' => new ProductsResource($product)]);
    }
}
