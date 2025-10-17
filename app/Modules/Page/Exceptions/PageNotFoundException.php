<?php

namespace App\Modules\Page\Exceptions;

use App\Exceptions\ModuleException;

class PageNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $slug = null)
    {
        $message = 'Page not found';
        if ($id) {
            $message = "Page with ID {$id} not found";
        } elseif ($slug) {
            $message = "Page with slug '{$slug}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Page',
            context: ['id' => $id, 'slug' => $slug]
        );
    }
}
