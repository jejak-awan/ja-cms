<?php

namespace App\Modules\Article\Exceptions;

use App\Exceptions\ModuleException;

class ArticleNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $slug = null)
    {
        $message = 'Article not found';
        if ($id) {
            $message = "Article with ID {$id} not found";
        } elseif ($slug) {
            $message = "Article with slug '{$slug}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Article',
            context: ['id' => $id, 'slug' => $slug]
        );
    }
}
