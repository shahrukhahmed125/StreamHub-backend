<?php

use App\Models\Content;
use App\Models\VideoAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('published content with a ready HLS manifest reports playback readiness', function (): void {
    $content = Content::factory()->published()->create([
        'slug' => 'sample-movie',
    ]);

    VideoAsset::factory()
        ->hlsManifest()
        ->ready()
        ->create([
            'content_id' => $content->id,
        ]);

    $this->getJson('/api/v1/contents/sample-movie/playback')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.format', 'hls')
        ->assertJsonPath('data.status', 'ready')
        ->assertJsonMissingPath('data.path');
});

test('content without a ready HLS manifest cannot be played', function (): void {
    $content = Content::factory()->published()->create([
        'slug' => 'pending-movie',
    ]);

    VideoAsset::factory()->create([
        'content_id' => $content->id,
        'status' => 'pending',
    ]);

    $this->getJson('/api/v1/contents/pending-movie/playback')
        ->assertNotFound()
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Playback is not available.');
});

test('unpublished content cannot be played', function (): void {
    Content::factory()->create([
        'slug' => 'draft-movie',
        'status' => 'draft',
        'published_at' => null,
    ]);

    $this->getJson('/api/v1/contents/draft-movie/playback')
        ->assertNotFound()
        ->assertJsonPath('success', false);
});

test('unknown content cannot be played', function (): void {
    $this->getJson('/api/v1/contents/does-not-exist/playback')
        ->assertNotFound()
        ->assertJsonPath('success', false);
});
