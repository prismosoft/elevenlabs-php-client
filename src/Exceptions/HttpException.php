<?php

namespace ElevenLabs\Exceptions;

class HttpException extends ElevenLabsException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
