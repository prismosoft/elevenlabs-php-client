<?php

use ElevenLabs\ElevenLabsClient;
use ElevenLabs\Resources\Music;
use ElevenLabs\Resources\SoundEffects;
use ElevenLabs\Resources\TextToSpeech;
use ElevenLabs\Resources\VoiceDesign;
use ElevenLabs\Resources\Voices;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use GuzzleHttp\Client as GuzzleClient;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client)->toBeInstanceOf(ElevenLabsClient::class);
});

it('returns text to speech resource', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->textToSpeech())->toBeInstanceOf(TextToSpeech::class);
});

it('returns sound effects resource', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->soundEffects())->toBeInstanceOf(SoundEffects::class);
});

it('returns music resource', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->music())->toBeInstanceOf(Music::class);
});

it('returns voices resource', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->voices())->toBeInstanceOf(Voices::class);
});

it('returns voice design resource', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->voiceDesign())->toBeInstanceOf(VoiceDesign::class);
});

it('returns text to voice resource alias', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $client = new ElevenLabsClient($transporter, $baseUri, $headers);

    expect($client->textToVoice())->toBeInstanceOf(VoiceDesign::class);
});

it('factory returns factory instance', function () {
    $factory = ElevenLabsClient::factory();

    expect($factory)->toBeInstanceOf(\ElevenLabs\ElevenLabsClientFactory::class);
});
