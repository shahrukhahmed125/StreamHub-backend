<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an authenticated user can update their profile', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'username' => 'old_username',
        'bio' => null,
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withToken($token)
        ->patchJson('/api/v1/auth/profile', [
            'name' => 'New Name',
            'username' => 'new_username',
            'bio' => 'I love movies and TV shows.',
        ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Profile updated successfully.')
        ->assertJsonPath('data.user.name', 'New Name')
        ->assertJsonPath('data.user.username', 'new_username')
        ->assertJsonPath('data.user.bio', 'I love movies and TV shows.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'username' => 'new_username',
        'bio' => 'I love movies and TV shows.',
    ]);
});

test('a user can keep their existing username', function (): void {
    $user = User::factory()->create([
        'username' => 'my_username',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withToken($token)
        ->patchJson('/api/v1/auth/profile', [
            'username' => 'my_username',
        ])
        ->assertOk()
        ->assertJsonPath('data.user.username', 'my_username');
});

test('a user cannot claim another users username', function (): void {
    User::factory()->create([
        'username' => 'taken_username',
    ]);

    $user = User::factory()->create([
        'username' => 'my_username',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withToken($token)
        ->patchJson('/api/v1/auth/profile', [
            'username' => 'taken_username',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['username']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => 'my_username',
    ]);
});

test('profile updates reject invalid input', function (): void {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $this->withToken($token)
        ->patchJson('/api/v1/auth/profile', [
            'name' => '',
            'username' => 'invalid username!',
            'bio' => str_repeat('a', 501),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'username',
            'bio',
        ]);
});

test('an unauthenticated user cannot update a profile', function (): void {
    $this->patchJson('/api/v1/auth/profile', [
        'name' => 'New Name',
        'username' => 'new_username',
    ])->assertUnauthorized();
});
