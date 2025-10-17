<?php

namespace App\Modules\Setting\Exceptions;

use App\Exceptions\ModuleException;

class SettingException extends ModuleException
{
    public function __construct(string $message = 'Setting error', ?string $key = null, int $code = self::INTERNAL_ERROR)
    {
        parent::__construct(
            message: $message,
            code: $code,
            model: 'Setting',
            context: ['key' => $key]
        );
    }

    public static function keyNotFound(string $key): self
    {
        return new self("Setting key '{$key}' not found", $key, self::NOT_FOUND);
    }

    public static function validationFailed(array $errors): self
    {
        $exception = new self('Setting validation failed');
        $exception->context = ['errors' => $errors];
        $exception->code = self::VALIDATION_ERROR;
        return $exception;
    }
}
