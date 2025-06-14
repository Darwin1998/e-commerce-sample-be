<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Spatie\Permission\Models\Role;

class CreateUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole(Role::find($data['roles'])->name);

        return $user;
    }
}
