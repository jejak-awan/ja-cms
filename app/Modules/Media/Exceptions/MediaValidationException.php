<?php

namespace App\Modules\Media\Exceptions;

use App\Exceptions\ModuleException;

class MediaValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Media validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Media',
            context: ['errors' => $errors]
        );
    }
}
