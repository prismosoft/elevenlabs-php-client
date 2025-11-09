<?php

use ElevenLabs\Exceptions\HttpException;

it('can be instantiated', function () {
    $exception = new HttpException('Test message');

    expect($exception)->toBeInstanceOf(HttpException::class);
    expect($exception->getMessage())->toBe('Test message');
});

it('can have a code', function () {
    $exception = new HttpException('Test message', 404);

    expect($exception->getCode())->toBe(404);
});

it('can have a previous exception', function () {
    $previous = new \Exception('Previous');
    $exception = new HttpException('Test message', 0, $previous);

    expect($exception->getPrevious())->toBe($previous);
});
