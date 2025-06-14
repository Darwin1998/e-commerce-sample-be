<?php

declare(strict_types=1);

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Str;

class CreateCustomerAction
{
    public function execute(array $data): Customer
    {
        $data['password'] = bcrypt(Str::random(6));

        return Customer::create($data);
    }
}
