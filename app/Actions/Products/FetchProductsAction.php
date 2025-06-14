<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Http\Resources\Products\ProductsResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FetchProductsAction
{
    public function execute(): AnonymousResourceCollection
    {
        return ProductsResource::collection(Product::paginate(10));
    }
}
