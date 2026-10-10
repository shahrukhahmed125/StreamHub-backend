<?php

use App\Models\Content;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('published content appears in the catalog', function (): void {
    Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'The StreamHub Movie',
            'slug' => 'the-streamhub-movie',
        ]);

    $response = $this->getJson('/api/v1/contents');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Content retrieved successfully.')
        ->assertJsonPath('data.data.0.title', 'The StreamHub Movie')
        ->assertJsonPath('data.data.0.type', 'movie');
});

test('draft content does not appear in the catalog', function (): void {
    Content::factory()
        ->movie()
        ->create([
            'title' => 'Draft Movie',
            'slug' => 'draft-movie',
        ]);

    $response = $this->getJson('/api/v1/contents');

    $response
        ->assertOk()
        ->assertJsonMissing([
            'title' => 'Draft Movie',
        ]);
});

test('catalog can filter content by type', function (): void {
    Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'Published Movie',
            'slug' => 'published-movie',
        ]);

    Content::factory()
        ->tvShow()
        ->published()
        ->create([
            'title' => 'Published TV Show',
            'slug' => 'published-tv-show',
        ]);

    $response = $this->getJson('/api/v1/contents?type=movie');

    $response
        ->assertOk()
        ->assertJsonPath('data.data.0.type', 'movie')
        ->assertJsonCount(1, 'data.data');
});

test('catalog rejects an invalid content type', function (): void {
    $response = $this->getJson('/api/v1/contents?type=invalid');

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['type']);
});

test('catalog can filter published content by genre', function (): void {
    $action = Genre::factory()->create([
        'name' => 'Action',
        'slug' => 'action',
    ]);

    $actionMovie = Content::factory()->published()->create([
        'title' => 'Action Movie',
        'slug' => 'action-movie',
    ]);

    $actionMovie->genres()->attach($action);

    Content::factory()->published()->create([
        'title' => 'Romance Movie',
        'slug' => 'romance-movie',
    ]);

    $response = $this->getJson('/api/v1/contents?genre=action');

    $response
        ->assertOk()
        ->assertJsonFragment([
            'title' => 'Action Movie',
        ])
        ->assertJsonMissing([
            'title' => 'Romance Movie',
        ]);
});

test('catalog rejects an unknown genre slug', function (): void {
    $response = $this->getJson('/api/v1/contents?genre=unknown-genre');

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors('genre');
});

test('catalog supports search by title', function (): void {
    Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'The Batman',
            'slug' => 'the-batman',
        ]);

    Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'Spider Man',
            'slug' => 'spider-man',
        ]);

    $response = $this->getJson('/api/v1/contents?search=Batman');

    $response
        ->assertOk()
        ->assertJsonPath('data.data.0.title', 'The Batman')
        ->assertJsonCount(1, 'data.data');
});

test('catalog includes genres for each published content item', function (): void {
    $genre = Genre::factory()->create([
        'name' => 'Science Fiction',
        'slug' => 'science-fiction',
    ]);

    $content = Content::factory()->published()->create([
        'title' => 'Space Adventure',
        'slug' => 'space-adventure',
    ]);

    $content->genres()->attach($genre);

    $response = $this->getJson('/api/v1/contents');

    $response
        ->assertOk()
        ->assertJsonPath('data.data.0.title', 'Space Adventure')
        ->assertJsonPath('data.data.0.genres.0.name', 'Science Fiction')
        ->assertJsonPath('data.data.0.genres.0.slug', 'science-fiction');
});
