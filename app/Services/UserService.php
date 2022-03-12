<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshTokenRepository;

class UserService
{
    public function register(array $payload): array
    {
        $attributes = [
            ...$payload,
            'password' => Hash::make($payload['password']),
        ];

        if ($user = User::create($attributes)) {
            return [
                'status' => true,
                'message' => 'You\'ve been successfully registered!',
                'token' => $user->createToken('JWT')->accessToken,
            ];
        }

        return ['status' => false];
    }

    public function login(array $payload): array
    {
        if (Auth::attempt($payload)) {
            $user = Auth::user();

            return [
                'status' => true,
                'token' => $user->createToken('JWT')->accessToken,
            ];
        }

        return [
            'status' => false,
            'message' => 'Wrong username or password!',
        ];
    }

    public function logout(): array
    {
        $token = Auth::user()->token();

        $refreshTokenRepository = app(RefreshTokenRepository::class);

        // Revoke an access token...
        $token->revoke();

        // Revoke all of the token's refresh tokens...
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return [
            'status' => true,
            'message' => 'You\'ve been successfully logout.',
        ];
    }
}
