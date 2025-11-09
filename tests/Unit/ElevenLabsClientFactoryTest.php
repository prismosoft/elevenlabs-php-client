<?php

use ElevenLabs\ElevenLabsClient;
use ElevenLabs\ElevenLabsClientFactory;

it('can create client with api key', function () {
    $client = ElevenLabsClient::factory()
        ->withApiKey('test-key')
        ->make();

    expect($client)->toBeInstanceOf(ElevenLabsClient::class);
});

it('can create client with custom base uri', function () {
    $client = ElevenLabsClient::factory()
        ->withBaseUri('https://custom.api.com')
        ->make();

    expect($client)->toBeInstanceOf(ElevenLabsClient::class);
});

it('can create client with both api key and base uri', function () {
    $client = ElevenLabsClient::factory()
        ->withApiKey('test-key')
        ->withBaseUri('https://custom.api.com')
        ->make();

    expect($client)->toBeInstanceOf(ElevenLabsClient::class);
});
