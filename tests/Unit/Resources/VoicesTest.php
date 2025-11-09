<?php

use ElevenLabs\Resources\Voices;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use ElevenLabs\Support\Transporter\Response;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    expect($voices)->toBeInstanceOf(Voices::class);
});

it('can set search query', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->search('male voice');

    expect($voices)->toBeInstanceOf(Voices::class);
});

it('can set page size', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->withPageSize(50);

    expect($voices)->toBeInstanceOf(Voices::class);
});

it('lists voices successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": []}');

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    $result = $voices->list();

    expect($result)->toBeInstanceOf(Response::class);
});

it('includes search parameter in request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": []}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (\GuzzleHttp\Psr7\Request $request) {
            $uri = $request->getUri();
            return str_contains($uri, 'search=male');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->search('male');

    $voices->list();
});

it('includes page size parameter in request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": []}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (\GuzzleHttp\Psr7\Request $request) {
            $uri = $request->getUri();
            return str_contains($uri, 'page_size=50');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->withPageSize(50);

    $voices->list();
});
