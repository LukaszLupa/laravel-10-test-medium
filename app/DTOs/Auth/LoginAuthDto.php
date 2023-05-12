<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

class LoginAuthDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
