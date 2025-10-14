<?php

namespace App\Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seo extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\SeoFactory::new();
    }

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'no_index',
        'no_follow',
    ];

    protected $casts = [
        'no_index' => 'boolean',
        'no_follow' => 'boolean',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
