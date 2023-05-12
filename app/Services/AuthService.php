<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Auth\LoginAuthDto;
use App\DTOs\Auth\RegisterAuthDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    public static function register(RegisterAuthDto $dto): void
    {
        UserService::create(
            name: $dto->name,
            email: $dto->email,
            password: $dto->password
        );
    }

    public static function login(LoginAuthDto $dto): string
    {
        /** @var User $user */
        $user = UserService::findByEmail($dto->email);

        if (! $user
            || ! Hash::check($dto->password, $user->password)
            || ! $user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user->createToken('Token name')->plainTextToken;
    }
}
