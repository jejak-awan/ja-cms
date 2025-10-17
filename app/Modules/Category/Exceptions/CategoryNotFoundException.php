<?php

namespace App\Modules\Category\Exceptions;

use App\Exceptions\ModuleException;

class CategoryNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $slug = null)
    {
        $message = 'Category not found';
        if ($id) {
            $message = "Category with ID {$id} not found";
        } elseif ($slug) {
            $message = "Category with slug '{$slug}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Category',
            context: ['id' => $id, 'slug' => $slug]
        );
    }
}
