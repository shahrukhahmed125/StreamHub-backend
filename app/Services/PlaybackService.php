<?php

namespace App\Services;

use App\Contracts\PlaybackRepositoryInterface;
use App\Models\VideoAsset;

class PlaybackService
{
    public function __construct(
        private readonly PlaybackRepositoryInterface $playbackRepository,
    ) {}

    public function findReadyManifest(string $slug): ?VideoAsset
    {
        $content = $this->playbackRepository->findPlayableContent($slug);

        if ($content === null) {
            return null;
        }

        return $this->playbackRepository->findReadyHlsManifest($content);
    }
}
