<?php

namespace App\Modules\Tag\Exceptions;

use App\Exceptions\ModuleException;

class TagValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Tag validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Tag',
            context: ['errors' => $errors]
        );
    }
}
