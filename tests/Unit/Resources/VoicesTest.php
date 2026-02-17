<?php

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Resources\Voices;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use ElevenLabs\Support\Transporter\Response;
use GuzzleHttp\Psr7\Request;
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

it('throws exception when getting a voice without id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    expect(fn () => $voices->get(''))->toThrow(ApiException::class);
});

it('gets voice metadata successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voice_id":"voice_123"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'GET' || $request->getUri()->getPath() !== '/v1/voices/voice_123') {
                return false;
            }

            parse_str($request->getUri()->getQuery(), $query);

            return ($query['with_settings'] ?? null) === 'false';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    $result = $voices->get('voice_123', false);

    expect($result)->toBeInstanceOf(Response::class);
});

it('throws exception when deleting a voice without id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    expect(fn () => $voices->delete(''))->toThrow(ApiException::class);
});

it('deletes voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"status":"ok"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return $request->getMethod() === 'DELETE'
                && $request->getUri()->getPath() === '/v1/voices/voice_123';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    $result = $voices->delete('voice_123');

    expect($result)->toBeInstanceOf(Response::class);
});

it('throws exception when updating a voice without required fields', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    expect(fn () => $voices->update('', 'Test Voice'))->toThrow(ApiException::class);
    expect(fn () => $voices->update('voice_123', ''))->toThrow(ApiException::class);
});

it('updates voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"status":"ok"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'POST' || $request->getUri()->getPath() !== '/v1/voices/voice_123/edit') {
                return false;
            }

            $contentType = $request->getHeaderLine('Content-Type');
            $body = (string) $request->getBody();

            return str_contains($contentType, 'multipart/form-data; boundary=')
                && str_contains($body, 'name="name"')
                && str_contains($body, 'My Updated Voice')
                && str_contains($body, 'name="remove_background_noise"')
                && str_contains($body, 'false')
                && str_contains($body, 'name="description"')
                && str_contains($body, 'Updated description')
                && str_contains($body, 'name="labels"')
                && str_contains($body, '{"language":"en","accent":"american"}')
                && str_contains($body, 'name="files"; filename="sample.wav"');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    $result = $voices->update('voice_123', 'My Updated Voice', [
        'remove_background_noise' => false,
        'description' => 'Updated description',
        'labels' => ['language' => 'en', 'accent' => 'american'],
        'files' => [
            [
                'contents' => 'fake audio data',
                'filename' => 'sample.wav',
            ],
        ],
    ]);

    expect($result)->toBeInstanceOf(Response::class);
});

it('throws exception when updating a voice with invalid file path', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    expect(fn () => $voices->update('voice_123', 'Voice Name', [
        'files' => ['/path/that/does/not/exist.wav'],
    ]))->toThrow(ApiException::class);
});

it('lists similar voices successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": [], "has_more": false}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'POST' || $request->getUri()->getPath() !== '/v1/similar-voices') {
                return false;
            }

            $contentType = $request->getHeaderLine('Content-Type');
            $body = (string) $request->getBody();

            return str_contains($contentType, 'multipart/form-data; boundary=')
                && str_contains($body, 'name="audio_file"; filename="sample.wav"')
                && str_contains($body, 'name="similarity_threshold"')
                && str_contains($body, '0.85')
                && str_contains($body, 'name="top_k"')
                && str_contains($body, '10');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voices = new Voices($transporter, $baseUri, $headers);

    $result = $voices->findSimilarVoices([
        'audio_file' => [
            'contents' => 'audio-bytes',
            'filename' => 'sample.wav',
        ],
        'similarity_threshold' => 0.85,
        'top_k' => 10,
    ]);

    expect($result)->toBeInstanceOf(Response::class);
});
