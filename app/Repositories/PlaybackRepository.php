<?php

namespace App\Repositories;

use App\Contracts\PlaybackRepositoryInterface;
use App\Models\Content;
use App\Models\VideoAsset;

class PlaybackRepository implements PlaybackRepositoryInterface
{
    public function findPlayableContent(string $slug): ?Content
    {
        return Content::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->first();
    }

    public function findReadyHlsManifest(Content $content): ?VideoAsset
    {
        return $content->videoAssets()
            ->where('kind', 'hls_manifest')
            ->where('status', 'ready')
            ->whereNotNull('processed_at')
            ->first();
    }
}
