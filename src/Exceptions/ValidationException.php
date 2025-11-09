<?php

namespace AdBlast\ElevenLabs\Exceptions;

class ValidationException extends ElevenLabsException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}