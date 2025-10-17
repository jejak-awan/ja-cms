<?php

namespace App\Modules\Seo\Exceptions;

use App\Exceptions\ModuleException;

class SeoException extends ModuleException
{
    public function __construct(string $message = 'SEO error', ?string $modelType = null, int $code = self::INTERNAL_ERROR)
    {
        parent::__construct(
            message: $message,
            code: $code,
            model: 'Seo',
            context: ['model_type' => $modelType]
        );
    }

    public static function notFound(string $modelType, int $modelId): self
    {
        return new self("SEO data for {$modelType} (ID: {$modelId}) not found", $modelType, self::NOT_FOUND);
    }

    public static function validationFailed(array $errors, string $modelType): self
    {
        $exception = new self('SEO validation failed', $modelType, self::VALIDATION_ERROR);
        $exception->context = ['errors' => $errors];
        return $exception;
    }
}
