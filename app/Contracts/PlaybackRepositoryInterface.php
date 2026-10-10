<?php

namespace App\Contracts;

use App\Models\Content;
use App\Models\VideoAsset;

interface PlaybackRepositoryInterface
{
    public function findPlayableContent(string $slug): ?Content;

    public function findReadyHlsManifest(Content $content): ?VideoAsset;
}
