<?php

namespace App\Http\Controllers\Api\V1\Content;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowContentController extends Controller
{
    public function __construct(private readonly ContentService $contentService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): JsonResponse
    {
        $content = $this->contentService->getPublishedContentBySlug($slug);

        if ($content === null) {
            return response()->json([
                'success' => false,
                'message' => 'Content not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Content retrieved successfully.',
            'data' => [
                'content' => ContentResource::make($content)->resolve(),
            ],
        ]);
    }
}
