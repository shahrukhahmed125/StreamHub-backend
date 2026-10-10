<?php

namespace App\Http\Controllers\Api\V1\Genre;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Services\GenreService;
use Illuminate\Http\JsonResponse;

class IndexGenreController extends Controller
{
    public function __construct(
        private readonly GenreService $genreService,
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $genres = $this->genreService->getAllGenres();

        return response()->json([
            'success' => true,
            'message' => 'Genres retrieved successfully.',
            'data' => [
                'genres' => GenreResource::collection($genres)
                    ->resolve(),
            ],
        ]);
    }
}
