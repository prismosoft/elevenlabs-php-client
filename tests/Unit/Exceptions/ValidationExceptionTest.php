<?php

use AdBlast\ElevenLabs\Exceptions\ValidationException;

it('can be instantiated', function () {
    $exception = new ValidationException('Test message');

    expect($exception)->toBeInstanceOf(ValidationException::class);
    expect($exception->getMessage())->toBe('Test message');
});

it('can have a code', function () {
    $exception = new ValidationException('Test message', 400);

    expect($exception->getCode())->toBe(400);
});

it('can have a previous exception', function () {
    $previous = new \Exception('Previous');
    $exception = new ValidationException('Test message', 0, $previous);

    expect($exception->getPrevious())->toBe($previous);
});