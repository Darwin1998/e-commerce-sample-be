<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Products;

use App\Actions\Products\FetchProductsAction;
use App\Actions\Products\ShowProductAction;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        return app(FetchProductsAction::class)->execute();
    }

    public function show(Product $product)
    {
        return app(ShowProductAction::class)->execute($product);
    }
}
