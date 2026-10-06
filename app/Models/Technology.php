<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'slug', 'icon'];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    protected static function booted(): void
    {
        static::saving(function (self $tech) {
            if (blank($tech->slug) && filled($tech->name)) {
                $tech->slug = Str::slug($tech->name);
            }
        });
    }
}
