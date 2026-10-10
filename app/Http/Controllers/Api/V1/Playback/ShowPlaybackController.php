<?php

namespace App\Http\Controllers\Api\V1\Playback;

use App\Http\Controllers\Controller;
use App\Services\PlaybackService;
use Illuminate\Http\JsonResponse;

class ShowPlaybackController extends Controller
{
    public function __construct(
        private readonly PlaybackService $playbackService,
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): JsonResponse
    {
        $manifest = $this->playbackService->findReadyManifest($slug);

        if ($manifest === null) {
            return response()->json([
                'success' => false,
                'message' => 'Playback is not available.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Playback asset is ready.',
            'data' => [
                'format' => 'hls',
                'status' => 'ready',
            ],
        ]);
    }
}
