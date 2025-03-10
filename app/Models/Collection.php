<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'visibility',
        'slug',
        'description',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visibility' => 'boolean',
    ];

    /**
     * Get the user that owns the collection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique slug for the collection.
     *
     * @param string $name
     * @return string
     */
    public static function createUniqueSlug(string $name): string
    {
        $slug = \Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        // Check if a slug with the same name already exists
        while (static::where('slug', 'LIKE', $slug . '%')->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
