<?php

use App\Models\Content;
use App\Models\VideoAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a video asset belongs to its content', function (): void {
    $content = Content::factory()->published()->create();

    $asset = VideoAsset::factory()->create([
        'content_id' => $content->id,
    ]);

    expect($asset->content->is($content))->toBeTrue();
});

test('content can have multiple video assets', function (): void {
    $content = Content::factory()->published()->create();

    VideoAsset::factory()->create([
        'content_id' => $content->id,
        'kind' => 'source',
    ]);

    VideoAsset::factory()->hlsManifest()->create([
        'content_id' => $content->id,
    ]);

    expect($content->videoAssets()->count())->toBe(2);
});

test('an HLS manifest can be marked ready', function (): void {
    $asset = VideoAsset::factory()
        ->hlsManifest()
        ->ready()
        ->create();

    expect($asset->kind)->toBe('hls_manifest')
        ->and($asset->status)->toBe('ready')
        ->and($asset->processed_at)->not->toBeNull();
});
