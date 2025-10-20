<?php

namespace App\Exception;

class ValidationException extends \RuntimeException
{
    public function __construct(
        public readonly array $errors,
        $message = 'Validation error(s)',
        $code = 422,
        ?\Throwable $previous = null,
    )
    {
        parent::__construct($message, $code, $previous);
    }
}