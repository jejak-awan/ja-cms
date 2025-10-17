<?php

namespace App\Exceptions;

use Exception;

class ModuleException extends Exception
{
    /**
     * Error code constants
     */
    const NOT_FOUND = 404;
    const VALIDATION_ERROR = 422;
    const UNAUTHORIZED = 403;
    const CONFLICT = 409;
    const INTERNAL_ERROR = 500;

    /**
     * The model that caused the exception
     */
    protected ?string $model = null;

    /**
     * Additional context data
     */
    protected array $context = [];

    /**
     * Constructor
     */
    public function __construct(
        string $message = '',
        int $code = self::INTERNAL_ERROR,
        ?string $model = null,
        array $context = [],
        ?Exception $previous = null
    ) {
        $this->model = $model;
        $this->context = $context;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the model that caused this exception
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Set the model
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get context data
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Add context data
     */
    public function addContext(string $key, mixed $value): self
    {
        $this->context[$key] = $value;
        return $this;
    }

    /**
     * Convert exception to array for API response
     */
    public function toArray(): array
    {
        return [
            'error' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'model' => $this->model,
                'context' => $this->context,
            ],
        ];
    }

    /**
     * Convert exception to JSON for API response
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Get HTTP status code
     */
    public function getHttpStatusCode(): int
    {
        return match($this->getCode()) {
            self::NOT_FOUND => 404,
            self::VALIDATION_ERROR => 422,
            self::UNAUTHORIZED => 403,
            self::CONFLICT => 409,
            default => 500,
        };
    }

    /**
     * Log exception
     */
    public function log(): void
    {
        \Log::error($this->getMessage(), [
            'exception' => get_class($this),
            'model' => $this->model,
            'code' => $this->getCode(),
            'context' => $this->context,
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
