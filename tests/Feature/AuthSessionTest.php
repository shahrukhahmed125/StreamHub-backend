<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

test('an authenticated user can retrieve their profile', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.id', $user->id)
        ->assertJsonPath('data.user.email', $user->email);
});

test('an unauthenticated user cannot retrieve their profile', function (): void {
    $this->getJson('/api/v1/auth/me')
        ->assertUnauthorized();
});

test('an authenticated user can log out', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/auth/logout')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Logged out successfully.');

    $this->assertDatabaseCount('personal_access_tokens', 0);

    // Reset cached authentication state before checking the revoked token.
    Auth::forgetGuards();

    $this->withToken($token)
        ->getJson('/api/v1/auth/me')
        ->assertUnauthorized();
});

test('an unauthenticated user cannot log out', function (): void {
    $this->postJson('/api/v1/auth/logout')
        ->assertUnauthorized();
});
