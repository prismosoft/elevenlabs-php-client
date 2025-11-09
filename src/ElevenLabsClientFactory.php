<?php

namespace AdBlast\ElevenLabs;

use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use GuzzleHttp\Client as GuzzleClient;

class ElevenLabsClientFactory
{
    private ?string $apiKey = null;
    private ?string $baseUri = null;

    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function withBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function make(): ElevenLabsClient
    {
        $headers = new Headers();

        if ($this->apiKey) {
            $headers = $headers->withApiKey($this->apiKey);
        }

        $baseUri = new BaseUri($this->baseUri ?? 'https://api.elevenlabs.io');

        $guzzleClient = new GuzzleClient([
            'base_uri' => (string) $baseUri,
            'headers' => $headers->toArray(),
        ]);

        $transporter = new HttpTransporter($guzzleClient);

        return new ElevenLabsClient($transporter, $baseUri, $headers);
    }
}