<?php

namespace App\Repositories;

use App\Contracts\GenreRepositoryInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreRepository implements GenreRepositoryInterface
{
    /**
     * @return Collection<int, Genre>
     */
    public function getAll(): Collection
    {
        return Genre::query()
            ->orderBy('name')
            ->get();
    }
}
