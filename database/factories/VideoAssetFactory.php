<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\VideoAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoAsset>
 */
class VideoAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content_id' => Content::factory(),
            'kind' => 'source',
            'disk' => 'local',
            'path' => 'videos/source/'.fake()->uuid().'.mp4',
            'status' => 'pending',
            'mime_type' => 'video/mp4',
            'file_size_bytes' => fake()->numberBetween(1_000_000, 500_000_000),
            'duration_seconds' => fake()->numberBetween(60, 7200),
            'width' => 1920,
            'height' => 1080,
            'processed_at' => null,
        ];
    }

    public function ready(): static
    {
        return $this->state(fn (): array => [
            'status' => 'ready',
            'processed_at' => now(),
        ]);
    }

    public function hlsManifest(): static
    {
        return $this->state(fn (): array => [
            'kind' => 'hls_manifest',
            'path' => 'videos/hls/'.fake()->uuid().'/master.m3u8',
            'mime_type' => 'application/vnd.apple.mpegurl',
        ]);
    }
}
