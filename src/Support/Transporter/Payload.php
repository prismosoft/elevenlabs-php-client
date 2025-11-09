<?php

namespace AdBlast\ElevenLabs\Support\Transporter;

class Payload
{
    public function __construct(
        private array $data = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }
}