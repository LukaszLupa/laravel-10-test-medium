<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

class RegisterAuthDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }
}
