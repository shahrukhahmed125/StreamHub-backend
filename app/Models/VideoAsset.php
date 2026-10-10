<?php

namespace App\Models;

use Database\Factories\VideoAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoAsset extends Model
{
    /** @use HasFactory<VideoAssetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content_id',
        'kind',
        'disk',
        'path',
        'status',
        'mime_type',
        'file_size_bytes',
        'duration_seconds',
        'width',
        'height',
        'processed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size_bytes' => 'integer',
            'duration_seconds' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
