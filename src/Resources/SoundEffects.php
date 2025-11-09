<?php

namespace ElevenLabs\Resources;

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\Payload;
use ElevenLabs\Support\Transporter\Response;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;

class SoundEffects
{
    private string $prompt;
    private array $options = [];

    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public function withPrompt(string $prompt): self
    {
        $clone = clone $this;
        $clone->prompt = $prompt;

        return $clone;
    }

    public function withOptions(array $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    public function generate(): Response
    {
        if (empty($this->prompt)) {
            throw new ApiException('Prompt is required');
        }

        $payload = Payload::fromArray(array_merge([
            'text' => $this->prompt,
        ], $this->options));

        $request = new Request(
            'POST',
            '/v1/sound-generation',
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }
}
