<?php

use AdBlast\ElevenLabs\Support\Transporter\Payload;

it('can be created from array', function () {
    $payload = Payload::fromArray(['key' => 'value']);

    expect($payload->toArray())->toBe(['key' => 'value']);
});

it('can convert to json', function () {
    $payload = Payload::fromArray(['key' => 'value']);

    expect($payload->toJson())->toBe('{"key":"value"}');
});

it('can check if empty', function () {
    $emptyPayload = new Payload();
    $nonEmptyPayload = Payload::fromArray(['key' => 'value']);

    expect($emptyPayload->isEmpty())->toBeTrue();
    expect($nonEmptyPayload->isEmpty())->toBeFalse();
});