<?php

namespace AdBlast\ElevenLabs\Support\Transporter;

use AdBlast\ElevenLabs\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    public function __construct(
        private ResponseInterface $response,
    ) {}

    public function isSuccessful(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getBody(): string
    {
        return $this->response->getBody()->getContents();
    }

    public function toArray(): array
    {
        $body = $this->getBody();

        if (empty($body)) {
            return [];
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Invalid JSON response: ' . json_last_error_msg());
        }

        return $data;
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function getHeader(string $name): ?string
    {
        $headers = $this->response->getHeader($name);

        return $headers[0] ?? null;
    }
}