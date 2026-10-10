<?php

namespace App\Services;

use App\Contracts\GenreRepositoryInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreService
{
    public function __construct(
        private readonly GenreRepositoryInterface $genreRepository,
    ) {}

    /**
     * @return Collection<int, Genre>
     */
    public function getAllGenres(): Collection
    {
        return $this->genreRepository->getAll();
    }
}
