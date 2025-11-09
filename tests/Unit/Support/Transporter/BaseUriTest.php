<?php

use AdBlast\ElevenLabs\Support\Transporter\BaseUri;

it('has default base uri', function () {
    $baseUri = new BaseUri();

    expect((string) $baseUri)->toBe('https://api.elevenlabs.io');
});

it('can be instantiated with custom base uri', function () {
    $baseUri = new BaseUri('https://custom.api.com');

    expect((string) $baseUri)->toBe('https://custom.api.com');
});