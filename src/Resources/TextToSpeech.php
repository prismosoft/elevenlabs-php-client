<?php

namespace AdBlast\ElevenLabs\Resources;

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\Payload;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use AdBlast\ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;

class TextToSpeech
{
    private string $voiceId;
    private string $text;
    private bool $withTimestamps = false;
    private array $options = [];

    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public function withVoiceId(string $voiceId): self
    {
        $clone = clone $this;
        $clone->voiceId = $voiceId;

        return $clone;
    }

    public function withText(string $text): self
    {
        $clone = clone $this;
        $clone->text = $text;

        return $clone;
    }

    public function withTimestamps(bool $withTimestamps = true): self
    {
        $clone = clone $this;
        $clone->withTimestamps = $withTimestamps;

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
        if (empty($this->voiceId) || empty($this->text)) {
            throw new ApiException('Voice ID and text are required');
        }

        $payload = Payload::fromArray(array_merge([
            'text' => $this->text,
        ], $this->options));

        $endpoint = $this->withTimestamps
            ? "/v1/text-to-speech/{$this->voiceId}/with-timestamps"
            : "/v1/text-to-speech/{$this->voiceId}";

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