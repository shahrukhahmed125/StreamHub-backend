<?php

use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('published content can be viewed by slug without authentication', function (): void {
    $content = Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'The Batman',
            'slug' => 'the-batman',
            'description' => 'A detective investigates crime in Gotham.',
        ]);

    $this->getJson('/api/v1/contents/the-batman')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Content retrieved successfully.')
        ->assertJsonPath('data.content.id', $content->id)
        ->assertJsonPath('data.content.title', 'The Batman')
        ->assertJsonPath('data.content.slug', 'the-batman')
        ->assertJsonPath(
            'data.content.description',
            'A detective investigates crime in Gotham.',
        );
});

test('draft content cannot be viewed by slug', function (): void {
    Content::factory()->movie()->create([
        'title' => 'Unreleased Movie',
        'slug' => 'unreleased-movie',
    ]);

    $this->getJson('/api/v1/contents/unreleased-movie')
        ->assertNotFound()
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Content not found.')
        ->assertJsonPath('data', null);
});

test('nonexistent content slug returns not found', function (): void {
    $this->getJson('/api/v1/contents/does-not-exist')
        ->assertNotFound()
        ->assertJsonPath('success', false);
});

test('content scheduled for future publication cannot be viewed', function (): void {
    Content::factory()
        ->movie()
        ->published()
        ->create([
            'title' => 'Future Movie',
            'slug' => 'future-movie',
            'published_at' => now()->addDay(),
        ]);

    $this->getJson('/api/v1/contents/future-movie')
        ->assertNotFound();
});
