<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

final class UserService
{
    public static function create(string $name, string $email, string $password): Model|Builder
    {
        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public static function findByEmail(string $email): Builder|Model|null
    {
        return User::query()->where('email', $email)->first();
    }
}
