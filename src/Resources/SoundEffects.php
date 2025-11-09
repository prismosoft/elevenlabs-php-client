<?php

namespace AdBlast\ElevenLabs\Resources;

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\Payload;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use AdBlast\ElevenLabs\Support\Transporter\Transporter;
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