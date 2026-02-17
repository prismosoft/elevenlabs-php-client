<?php

namespace ElevenLabs;

use ElevenLabs\Resources\Music;
use ElevenLabs\Resources\SoundEffects;
use ElevenLabs\Resources\TextToSpeech;
use ElevenLabs\Resources\VoiceDesign;
use ElevenLabs\Resources\VoiceLibrary;
use ElevenLabs\Resources\Voices;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Client as GuzzleClient;

readonly class ElevenLabsClient
{
    public function __construct(
        private Transporter $transporter,
        private BaseUri     $baseUri,
        private Headers     $headers,
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

    public function voiceDesign(): VoiceDesign
    {
        return new VoiceDesign($this->transporter, $this->baseUri, $this->headers);
    }

    public function voiceLibrary(): VoiceLibrary
    {
        return new VoiceLibrary($this->transporter, $this->baseUri, $this->headers);
    }

    public function textToVoice(): VoiceDesign
    {
        return $this->voiceDesign();
    }
}
