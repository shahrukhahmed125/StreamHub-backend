<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a user can register successfully', function (): void {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'StrongPass123!',
        'password_confirmation' => 'StrongPass123!',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Account registered successfully.')
        ->assertJsonPath('data.user.name', 'Test User')
        ->assertJsonPath('data.user.email', 'testuser@example.com')
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token',
                'token_type',
            ],
        ]);

    $user = User::query()
        ->where('email', 'testuser@example.com')
        ->firstOrFail();

    expect($user->password)->not->toBe('StrongPass123!');
    expect(Hash::check('StrongPass123!', $user->password))->toBeTrue();

    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
});

test('registration requires valid input', function (): void {
    $response = $this->postJson('/api/v1/auth/register', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password',
        ]);
});

test('registration rejects duplicate email addresses', function (): void {
    User::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Another User',
        'email' => 'existing@example.com',
        'password' => 'StrongPass123!',
        'password_confirmation' => 'StrongPass123!',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertDatabaseCount('users', 1);
});
