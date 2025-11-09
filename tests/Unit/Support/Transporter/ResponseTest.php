<?php

use AdBlast\ElevenLabs\Exceptions\ApiException;
use AdBlast\ElevenLabs\Support\Transporter\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Psr\Http\Message\StreamInterface;

it('checks if successful', function () {
    $successResponse = new Response(new PsrResponse(200));
    $errorResponse = new Response(new PsrResponse(404));

    expect($successResponse->isSuccessful())->toBeTrue();
    expect($errorResponse->isSuccessful())->toBeFalse();
});

it('gets status code', function () {
    $response = new Response(new PsrResponse(201));

    expect($response->getStatusCode())->toBe(201);
});

it('gets body content', function () {
    $stream = Mockery::mock(StreamInterface::class);
    $stream->shouldReceive('getContents')->andReturn('test body');

    $psrResponse = new PsrResponse(200, [], $stream);
    $response = new Response($psrResponse);

    expect($response->getBody())->toBe('test body');
});

it('parses json body', function () {
    $json = '{"key": "value"}';
    $stream = Mockery::mock(StreamInterface::class);
    $stream->shouldReceive('getContents')->andReturn($json);

    $psrResponse = new PsrResponse(200, [], $stream);
    $response = new Response($psrResponse);

    expect($response->toArray())->toBe(['key' => 'value']);
});

it('throws exception for invalid json', function () {
    $invalidJson = '{"key": "value"';
    $stream = Mockery::mock(StreamInterface::class);
    $stream->shouldReceive('getContents')->andReturn($invalidJson);

    $psrResponse = new PsrResponse(200, [], $stream);
    $response = new Response($psrResponse);

    expect(fn () => $response->toArray())->toThrow(ApiException::class);
});

it('gets headers', function () {
    $psrResponse = new PsrResponse(200, ['Content-Type' => 'application/json']);
    $response = new Response($psrResponse);

    expect($response->getHeaders())->toHaveKey('Content-Type');
});

it('gets specific header', function () {
    $psrResponse = new PsrResponse(200, ['Content-Type' => ['application/json']]);
    $response = new Response($psrResponse);

    expect($response->getHeader('Content-Type'))->toBe('application/json');
    expect($response->getHeader('Non-Existent'))->toBeNull();
});