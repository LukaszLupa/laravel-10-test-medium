<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\Auth\LoginAuthDto;
use App\DTOs\Auth\RegisterAuthDto;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function login(LoginAuthRequest $request): JsonResponse
    {
        $accessToken = AuthService::login(
            new LoginAuthDto(
                email: $request->input('email'),
                password: $request->input('password')
            )
        );

        return new JsonResponse(
            data: compact('accessToken'),
            status: Response::HTTP_OK
        );
    }

    public function register(RegisterAuthRequest $request): JsonResponse
    {
        AuthService::register(
            new RegisterAuthDto(
                name: $request->input('name'),
                email: $request->input('email'),
                password: $request->input('password'),
            )
        );

        return new JsonResponse(
            data: [],
            status: Response::HTTP_CREATED
        );
    }
}
