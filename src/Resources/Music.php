<?php

namespace ElevenLabs\Resources;

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\Payload;
use ElevenLabs\Support\Transporter\Response;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;

class Music
{
    private string $prompt;
    private ?int $duration = null;
    private bool $detailed = false;
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

    public function withDuration(int $duration): self
    {
        $clone = clone $this;
        $clone->duration = $duration;

        return $clone;
    }

    public function detailed(bool $detailed = true): self
    {
        $clone = clone $this;
        $clone->detailed = $detailed;

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
            'prompt' => $this->prompt,
        ], $this->duration ? ['duration_seconds' => $this->duration] : [], $this->options));

        $endpoint = $this->detailed ? '/v1/music/detailed' : '/v1/music';

        $request = new Request(
            'POST',
            $endpoint,
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }
}
