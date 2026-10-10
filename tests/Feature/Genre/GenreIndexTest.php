<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public genre endpoint returns genres in alphabetical order', function (): void {
    Genre::factory()->create([
        'name' => 'Thriller',
        'slug' => 'thriller',
    ]);

    Genre::factory()->create([
        'name' => 'Action',
        'slug' => 'action',
    ]);

    $response = $this->getJson('/api/v1/genres');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath(
            'message',
            'Genres retrieved successfully.',
        )
        ->assertJsonPath('data.genres.0.name', 'Action')
        ->assertJsonPath('data.genres.1.name', 'Thriller');
});

test('public genre endpoint returns an empty list when no genres exist', function (): void {
    $response = $this->getJson('/api/v1/genres');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.genres', []);
});
