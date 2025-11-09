<?php

use ElevenLabs\Exceptions\ValidationException;
use ElevenLabs\Support\Transporter\Headers;

it('has default headers', function () {
    $headers = new Headers();

    $array = $headers->toArray();

    expect($array)->toHaveKey('Accept');
    expect($array)->toHaveKey('Content-Type');
    expect($array['Accept'])->toBe('application/json');
    expect($array['Content-Type'])->toBe('application/json');
});

it('can add api key', function () {
    $headers = (new Headers())->withApiKey('test-key');

    $array = $headers->toArray();

    expect($array)->toHaveKey('xi-api-key');
    expect($array['xi-api-key'])->toBe('test-key');
});

it('throws exception for empty api key', function () {
    expect(fn () => (new Headers())->withApiKey(''))->toThrow(ValidationException::class);
});

it('can add authorization', function () {
    $headers = (new Headers())->withAuthorization('test-token');

    $array = $headers->toArray();

    expect($array)->toHaveKey('Authorization');
    expect($array['Authorization'])->toBe('Bearer test-token');
});

it('can change content type', function () {
    $headers = (new Headers())->withContentType('text/plain');

    $array = $headers->toArray();

    expect($array['Content-Type'])->toBe('text/plain');
});
