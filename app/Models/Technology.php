<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public static function generateSlug($name)
    {
        return Str::slug($name, '-');
    }

    /**
     * The project that belong to the Technology
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function project(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
