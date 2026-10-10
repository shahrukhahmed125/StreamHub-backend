<?php

namespace App\Contracts;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

interface GenreRepositoryInterface
{
    /**
     * @return Collection<int, Genre>
     */
    public function getAll(): Collection;
}
