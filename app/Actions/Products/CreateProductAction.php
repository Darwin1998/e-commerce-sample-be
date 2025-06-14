<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Product;

class CreateProductAction
{
    public function execute(array $data): Product
    {
        return Product::create($data);
    }
}
