<?php

namespace App\Modules\User\Exceptions;

use App\Exceptions\ModuleException;

class UserValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'User validation failed',
            code: self::VALIDATION_ERROR,
            model: 'User',
            context: ['errors' => $errors]
        );
    }
}
