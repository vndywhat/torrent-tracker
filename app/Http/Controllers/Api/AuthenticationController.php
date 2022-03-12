<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\UserLoginRequest;
use App\Http\Requests\Authentication\UserLogoutRequest;
use App\Http\Requests\Authentication\UserRegistrationRequest;
use App\Services\UserService;

class AuthenticationController extends Controller
{
    public function login(UserLoginRequest $request, UserService $service)
    {
        $response = $service->login($request->validated());

        return response()->json($response, ($response['status']) ? 200 : 401);
    }

    public function logout(UserLogoutRequest $request, UserService $service)
    {
        $response = $service->logout();

        return response()->json($response, ($response['status']) ? 200 : 400);
    }

    public function registration(UserRegistrationRequest $request, UserService $service)
    {
        $response = $service->register($request->validated());
        return response()->json($response, ($response['status']) ? 201 : 500);
    }
}
