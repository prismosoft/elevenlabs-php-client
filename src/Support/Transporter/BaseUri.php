<?php

namespace AdBlast\ElevenLabs\Support\Transporter;

class BaseUri
{
    public function __construct(
        private string $baseUri = 'https://api.elevenlabs.io',
    ) {}

    public function __toString(): string
    {
        return $this->baseUri;
    }
}