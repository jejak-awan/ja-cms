<?php

namespace App\Modules\Page\Exceptions;

use App\Exceptions\ModuleException;

class PageValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Page validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Page',
            context: ['errors' => $errors]
        );
    }
}
