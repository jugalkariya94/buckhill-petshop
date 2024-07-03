<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['uuid', 'title', 'slug', 'content', 'metadata'];

    protected $casts = [
        'metadata' => 'json',
    ];

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Generate initial slug
            $slug = Str::slug($post->title);
            $originalSlug = $slug;

            // Check if the slug exists
            $count = 0;
            while (static::where('slug', $slug)->exists()) {
                $count++;
                $slug = "{$originalSlug}-{$count}";
            }

            $post->slug = $slug;
        });
    }

}
