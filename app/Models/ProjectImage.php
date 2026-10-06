<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['project_id', 'path', 'alt', 'sort_order'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer'];
    }

    public function project(): BelongsT
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Project::forgetCaches());
        static::deleted(fn () => Project::forgetCaches());
    }

    /**
     * Original file path (as stored).
     */
    public function originalPath(): string
    {
        return $this->path;
    }

    /**
     * Medium WebP variant (~1200px wide) — falls back to original if not yet generated.
     */
    public function mediumPath(): string
    {
        return $this->variantPath('-md', 'webp') ?? $this->path;
    }

    /**
     * Thumbnail WebP variant (~480px wide) — falls back to original.
     */
    public function thumbPath(): string
    {
        return $this->variantPath('-thumb', 'webp') ?? $this->path;
    }

    public function mediumUrl(): string
    {
        return Storage::disk('public')->url($this->mediumPath());
    }

    public function thumbUrl(): string
    {
        return Storage::disk('public')->url($this->thumbPath());
    }

    private function variantPath(string $suffix, string $ext): ?string
    {
        $candidate = preg_replace('/\.[^.]+$/', "{$suffix}.{$ext}", $this->path);

        return Storage::disk('public')->exists($candidate)
            ? $candidate
            : null;
    }
}
