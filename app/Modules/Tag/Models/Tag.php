<?php

namespace App\Modules\Tag\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // ...model properties & relationships

    public function articles()
    {
        return $this->belongsToMany(\App\Modules\Article\Models\Article::class);
    }
}
