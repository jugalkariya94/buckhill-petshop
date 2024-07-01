<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class UsedToken extends Model
{
    use Prunable;
    protected $fillable = ['token'];

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        // Delete all records older than a month
        return static::where('created_at', '<=', now()->subMonth());
    }
}
