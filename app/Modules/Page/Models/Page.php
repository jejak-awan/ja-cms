<?php

namespace App\Modules\Page\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\User\Models\User;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'template', 'featured_image',
        'status', 'order', 'is_homepage', 'meta_title', 'meta_description',
        'meta_keywords', 'user_id', 'published_at'
    ];

    protected $casts = [
        'is_homepage' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public static function homepage()
    {
        return static::where('is_homepage', true)->published()->first();
    }

    public static function templates()
    {
        return [
            'default' => 'Default Template',
            'home' => 'Homepage Template',
            'about' => 'About Page Template',
            'contact' => 'Contact Page Template',
            'custom' => 'Custom Template'
        ];
    }
}
