<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a user can log in successfully', function (): void {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => Hash::make('StrongPass123!'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'StrongPass123!',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Login successful.')
        ->assertJsonPath('data.user.email', 'login@example.com')
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type',
            ],
        ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
});

test('login rejects an incorrect password', function (): void {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => Hash::make('StrongPass123!'),
    ]);

    $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'WrongPass123!',
    ])
        ->assertUnauthorized();

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('login rejects an unknown email address', function (): void {
    $this->postJson('/api/v1/auth/login', [
        'email' => 'unknown@example.com',
        'password' => 'StrongPass123!',
    ])
        ->assertUnauthorized();

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('login requires valid input', function (): void {
    $this->postJson('/api/v1/auth/login', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
});
