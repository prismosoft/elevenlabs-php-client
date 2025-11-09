<?php

use AdBlast\ElevenLabs\Exceptions\HttpException;
use AdBlast\ElevenLabs\Support\Transporter\HttpTransporter;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;

it('sends request successfully', function () {
    $client = Mockery::mock(ClientInterface::class);
    $request = new Request('GET', '/test');
    $response = new Response(200);

    $client->shouldReceive('send')->with($request)->andReturn($response);

    $transporter = new HttpTransporter($client);

    expect($transporter->request($request))->toBe($response);
});

it('throws http exception on guzzle error', function () {
    $client = Mockery::mock(ClientInterface::class);
    $request = new Request('GET', '/test');
    $exception = new RequestException('Network error', $request);

    $client->shouldReceive('send')->with($request)->andThrow($exception);

    $transporter = new HttpTransporter($client);

    expect(fn () => $transporter->request($request))->toThrow(HttpException::class);
});