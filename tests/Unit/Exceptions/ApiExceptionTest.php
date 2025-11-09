<?php

use ElevenLabs\Exceptions\ApiException;

it('can be instantiated', function () {
    $exception = new ApiException('Test message');

    expect($exception)->toBeInstanceOf(ApiException::class);
    expect($exception->getMessage())->toBe('Test message');
});

it('can have a code', function () {
    $exception = new ApiException('Test message', 500);

    expect($exception->getCode())->toBe(500);
});

it('can have a previous exception', function () {
    $previous = new \Exception('Previous');
    $exception = new ApiException('Test message', 0, $previous);

    expect($exception->getPrevious())->toBe($previous);
});
