<?php

namespace App\Repositories;

use App\Contracts\ContentRepositoryInterface;
use App\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContentRepository implements ContentRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublished(
        array $filters = [],
        int $perPage = 20,
    ): LengthAwarePaginator {
        $query = Content::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%'.$filters['search'].'%');
        }

        return $query
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug): ?Content
    {
        return Content::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->first();
    }
}
