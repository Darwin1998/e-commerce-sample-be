<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductResource\Pages;

use App\Actions\Products\CreateProductAction;
use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateProductAction::class)->execute($data);
    }
}
