<?php

namespace App\Modules\Category\Exceptions;

use App\Exceptions\ModuleException;

class CategoryValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Category validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Category',
            context: ['errors' => $errors]
        );
    }
}
