<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return array{user: User, token: string}
     */
    public function register(array $data): array
    {
        $user = $this->userRepository->create($data);

        $token = $user->createToken('streamhub-api')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
