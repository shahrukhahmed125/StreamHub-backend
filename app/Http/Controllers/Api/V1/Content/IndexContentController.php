<?php

namespace App\Http\Controllers\Api\V1\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\IndexContentRequest;
use App\Http\Resources\ContentResource;
use App\Services\ContentService;
use Illuminate\Http\Request;

class IndexContentController extends Controller
{
    public function __construct(private readonly ContentService $contentService) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexContentRequest $request)
    {
        $filters = $request->validated();

        $perPage = (int) ($filters['per_page'] ?? 20);

        unset($filters['per_page']);

        $contents = $this->contentService->getPublishedContent(
            $filters,
            $perPage,
        );

        return response()->json([
            'success' => true,
            'message' => 'Content retrieved successfully.',
            'data' => ContentResource::collection($contents)
                ->response()
                ->getData(true),
        ]);
    }
}
