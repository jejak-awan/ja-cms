<?php

namespace App\Modules\Tag\Exceptions;

use App\Exceptions\ModuleException;

class TagNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $slug = null)
    {
        $message = 'Tag not found';
        if ($id) {
            $message = "Tag with ID {$id} not found";
        } elseif ($slug) {
            $message = "Tag with slug '{$slug}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Tag',
            context: ['id' => $id, 'slug' => $slug]
        );
    }
}
