<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    public function __invoke(
        string $name,
        string $email,
        string $password,
        string $username,
        string $avatar,
    ): User {
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'avatar' => $avatar,
        ]);

        event(new Registered($user));

        return $user;
    }
}
