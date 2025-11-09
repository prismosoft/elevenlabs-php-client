<?php

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Resources\TextToSpeech;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use GuzzleHttp\Psr7\Request;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = new TextToSpeech($transporter, $baseUri, $headers);

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set voice id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123');

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set text', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withText('Hello world');

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can enable timestamps', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withTimestamps();

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set options', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withOptions(['stability' => 0.5]);

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('throws exception when generating without voice id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withText('Hello world');

    expect(fn () => $tts->generate())->toThrow(ApiException::class);
});

it('throws exception when generating without text', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123');

    expect(fn () => $tts->generate())->toThrow(ApiException::class);
});

it('generates speech successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');
    $response = new Response($psrResponse);

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world');

    $result = $tts->generate();

    expect($result)->toBeInstanceOf(Response::class);
});

it('uses timestamps endpoint when enabled', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return str_contains($request->getUri()->getPath(), 'with-timestamps');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world')
        ->withTimestamps();

    $tts->generate();
});