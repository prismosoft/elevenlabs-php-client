<?php

namespace AdBlast\ElevenLabs;

use AdBlast\ElevenLabs\Resources\Music;
use AdBlast\ElevenLabs\Resources\SoundEffects;
use AdBlast\ElevenLabs\Resources\TextToSpeech;
use AdBlast\ElevenLabs\Resources\Voices;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use AdBlast\ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Client as GuzzleClient;

class ElevenLabsClient
{
    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public static function factory(): ElevenLabsClientFactory
    {
        return new ElevenLabsClientFactory();
    }

    public function textToSpeech(): TextToSpeech
    {
        return new TextToSpeech($this->transporter, $this->baseUri, $this->headers);
    }

    public function soundEffects(): SoundEffects
    {
        return new SoundEffects($this->transporter, $this->baseUri, $this->headers);
    }

    public function music(): Music
    {
        return new Music($this->transporter, $this->baseUri, $this->headers);
    }

    public function voices(): Voices
    {
        return new Voices($this->transporter, $this->baseUri, $this->headers);
    }
}