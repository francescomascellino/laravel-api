<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Type;

class Project extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['type_id', 'title', 'slug', 'thumb', 'description', 'github', 'link'];

    // CHECK IF ALREADY EXISTS WHEN ADDING ENTRIES
    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }

    /**
     * Get the type that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class); // THIS PROJECT BELONGS TO A TYPE
    }

    /**
     * The technologies that belong to the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }
}
