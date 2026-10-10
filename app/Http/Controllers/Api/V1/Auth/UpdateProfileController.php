<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProfileController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $updatedUser = $this->authService->updateProfile(
            $user,
            $request->validated(),
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'user' => UserResource::make($updatedUser)
                    ->resolve($request),
            ],
        ]);
    }
}
