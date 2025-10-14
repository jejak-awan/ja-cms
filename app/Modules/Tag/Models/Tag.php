<?php

namespace App\Modules\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\TagFactory::new();
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function articles()
    {
        return $this->morphedByMany(\App\Modules\Article\Models\Article::class, 'taggable');
    }
}
