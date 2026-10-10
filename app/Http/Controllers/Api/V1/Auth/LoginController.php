<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $validated = $request->validated();

        $result = $this->authService->login(
            $validated['email'],
            $validated['password'],
        );

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => UserResource::make($result['user'])
                    ->resolve($request),
                'token' => $result['token'],
                'token_type' => 'Bearer',
            ],
        ]);
    }
}
