<?php

namespace App\Models;

use Database\Factories\ContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    /** @use HasFactory<ContentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'type',
        'title',
        'slug',
        'description',
        'release_date',
        'poster_path',
        'backdrop_path',
        'maturity_rating',
        'status',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'content_genre');
    }

    public function videoAssets(): HasMany
    {
        return $this->hasMany(VideoAsset::class);
    }
}
