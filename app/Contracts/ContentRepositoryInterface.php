<?php

namespace App\Contracts;

use App\Models\Content;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContentRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginatePublished(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function findPublishedBySlug(string $slug): ?Content;
}
