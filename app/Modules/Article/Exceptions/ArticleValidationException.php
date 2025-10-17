<?php

namespace App\Modules\Article\Exceptions;

use App\Exceptions\ModuleException;

class ArticleValidationException extends ModuleException
{
    public function __construct(array $errors = [])
    {
        parent::__construct(
            message: 'Article validation failed',
            code: self::VALIDATION_ERROR,
            model: 'Article',
            context: ['errors' => $errors]
        );
    }
}
