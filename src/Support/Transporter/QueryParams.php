<?php

namespace ElevenLabs\Support\Transporter;

class QueryParams
{
    public function __construct(
        private array $params = [],
    ) {}

    public function withParam(string $key, string $value): self
    {
        $clone = clone $this;
        $clone->params[$key] = $value;

        return $clone;
    }

    public function toString(): string
    {
        if (empty($this->params)) {
            return '';
        }

        return '?' . http_build_query($this->params);
    }

    public function toArray(): array
    {
        return $this->params;
    }
}
