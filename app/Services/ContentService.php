<?php

namespace App\Services;

use App\Contracts\ContentRepositoryInterface;
use App\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContentService
{
    public function __construct(private readonly ContentRepositoryInterface $contentRepository) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function getPublishedContent(
        array $filters = [],
        int $perPage = 20,
    ): LengthAwarePaginator {
        return $this->contentRepository->paginatePublished(
            $filters,
            $perPage,
        );
    }

    public function getPublishedContentBySlug(string $slug): ?Content
    {
        return $this->contentRepository->findPublishedBySlug($slug);
    }
}
