<?php

namespace ElevenLabs\Support\Transporter;

use ElevenLabs\Exceptions\ValidationException;

class Headers
{
    private array $headers = [];

    public function __construct(array $headers = [])
    {
        $this->headers = array_merge([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ], $headers);
    }

    public function withApiKey(string $apiKey): self
    {
        if (empty($apiKey)) {
            throw new ValidationException('API key cannot be empty');
        }

        $clone = clone $this;
        $clone->headers['xi-api-key'] = $apiKey;

        return $clone;
    }

    public function withAuthorization(string $token): self
    {
        $clone = clone $this;
        $clone->headers['Authorization'] = 'Bearer ' . $token;

        return $clone;
    }

    public function withContentType(string $contentType): self
    {
        $clone = clone $this;
        $clone->headers['Content-Type'] = $contentType;

        return $clone;
    }

    public function toArray(): array
    {
        return $this->headers;
    }
}
