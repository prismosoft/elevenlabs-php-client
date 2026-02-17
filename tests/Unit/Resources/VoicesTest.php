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

it('can set all additional filters', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->withNextPageToken('token_123')
        ->withSort('name')
        ->withSortDirection('asc')
        ->withVoiceType('saved')
        ->withCategory('generated')
        ->withFineTuningState('fine_tuned')
        ->withCollectionId('collection_123')
        ->includeTotalCount(false)
        ->withVoiceIds(['voice_1', 'voice_2']);

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

it('includes additional filter parameters in request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": []}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (\GuzzleHttp\Psr7\Request $request) {
            $uri = $request->getUri();
            parse_str($uri->getQuery(), $query);

            return ($query['next_page_token'] ?? null) === 'token_123'
                && ($query['sort'] ?? null) === 'name'
                && ($query['sort_direction'] ?? null) === 'asc'
                && ($query['voice_type'] ?? null) === 'saved'
                && ($query['category'] ?? null) === 'generated'
                && ($query['fine_tuning_state'] ?? null) === 'fine_tuned'
                && ($query['collection_id'] ?? null) === 'collection_123'
                && ($query['include_total_count'] ?? null) === 'false'
                && ($query['voice_ids'] ?? null) === 'voice_1,voice_2';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = (new Voices($transporter, $baseUri, $headers))
        ->withNextPageToken('token_123')
        ->withSort('name')
        ->withSortDirection('asc')
        ->withVoiceType('saved')
        ->withCategory('generated')
        ->withFineTuningState('fine_tuned')
        ->withCollectionId('collection_123')
        ->includeTotalCount(false)
        ->withVoiceIds(['voice_1', 'voice_2']);

    $voices->list();
});
