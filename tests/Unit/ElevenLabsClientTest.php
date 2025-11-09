<?php

use AdBlast\ElevenLabs\ElevenLabsClient;
use AdBlast\ElevenLabs\Resources\Music;
use AdBlast\ElevenLabs\Resources\SoundEffects;
use AdBlast\ElevenLabs\Resources\TextToSpeech;
use AdBlast\ElevenLabs\Resources\Voices;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
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

it('factory returns factory instance', function () {
    $factory = ElevenLabsClient::factory();

    expect($factory)->toBeInstanceOf(\AdBlast\ElevenLabs\ElevenLabsClientFactory::class);
});