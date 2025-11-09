<?php

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Resources\SoundEffects;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $soundEffects = new SoundEffects($transporter, $baseUri, $headers);

    expect($soundEffects)->toBeInstanceOf(SoundEffects::class);
});

it('can set prompt', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $soundEffects = (new SoundEffects($transporter, $baseUri, $headers))
        ->withPrompt('A door creaking open');

    expect($soundEffects)->toBeInstanceOf(SoundEffects::class);
});

it('can set options', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $soundEffects = (new SoundEffects($transporter, $baseUri, $headers))
        ->withOptions(['duration' => 5]);

    expect($soundEffects)->toBeInstanceOf(SoundEffects::class);
});

it('throws exception when generating without prompt', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $soundEffects = new SoundEffects($transporter, $baseUri, $headers);

    expect(fn () => $soundEffects->generate())->toThrow(ApiException::class);
});

it('generates sound effect successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'sound data');
    $response = new Response($psrResponse);

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $soundEffects = (new SoundEffects($transporter, $baseUri, $headers))
        ->withPrompt('A door creaking open');

    $result = $soundEffects->generate();

    expect($result)->toBeInstanceOf(Response::class);
});