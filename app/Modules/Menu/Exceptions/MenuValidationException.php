<?php

namespace App\Modules\Menu\Exceptions;

use App\Exceptions\ModuleException;

class MenuValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Menu validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Menu',
            context: ['errors' => $errors]
        );
    }
}
