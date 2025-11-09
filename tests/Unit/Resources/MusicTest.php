<?php

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Resources\Music;
use AdBlast\ElevenLabs\Support\Transporter\BaseUri;
use AdBlast\ElevenLabs\Support\Transporter\Headers;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = new Music($transporter, $baseUri, $headers);

    expect($music)->toBeInstanceOf(Music::class);
});

it('can set prompt', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->withPrompt('Upbeat electronic track');

    expect($music)->toBeInstanceOf(Music::class);
});

it('can set duration', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->withDuration(30);

    expect($music)->toBeInstanceOf(Music::class);
});

it('can enable detailed mode', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->detailed();

    expect($music)->toBeInstanceOf(Music::class);
});

it('can set options', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->withOptions(['genre' => 'electronic']);

    expect($music)->toBeInstanceOf(Music::class);
});

it('throws exception when generating without prompt', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = new Music($transporter, $baseUri, $headers);

    expect(fn () => $music->generate())->toThrow(ApiException::class);
});

it('generates music successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'music data');

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->withPrompt('Upbeat electronic track');

    $result = $music->generate();

    expect($result)->toBeInstanceOf(Response::class);
});

it('uses detailed endpoint when enabled', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'music data');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (\GuzzleHttp\Psr7\Request $request) {
            return str_contains($request->getUri()->getPath(), '/detailed');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $music = (new Music($transporter, $baseUri, $headers))
        ->withPrompt('Upbeat electronic track')
        ->detailed();

    $music->generate();
});