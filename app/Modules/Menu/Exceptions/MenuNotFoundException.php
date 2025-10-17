<?php

namespace App\Modules\Menu\Exceptions;

use App\Exceptions\ModuleException;

class MenuNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $slug = null)
    {
        $message = 'Menu not found';
        if ($id) {
            $message = "Menu with ID {$id} not found";
        } elseif ($slug) {
            $message = "Menu with slug '{$slug}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Menu',
            context: ['id' => $id, 'slug' => $slug]
        );
    }
}
