<?php

use AdBlast\ElevenLabs\Support\Transporter\QueryParams;

it('starts empty', function () {
    $params = new QueryParams();

    expect($params->toString())->toBe('');
    expect($params->toArray())->toBe([]);
});

it('can add parameters', function () {
    $params = (new QueryParams())
        ->withParam('key', 'value')
        ->withParam('another', 'param');

    expect($params->toArray())->toBe(['key' => 'value', 'another' => 'param']);
});

it('converts to query string', function () {
    $params = (new QueryParams())
        ->withParam('search', 'test')
        ->withParam('page', '1');

    $queryString = $params->toString();

    expect($queryString)->toContain('?');
    expect($queryString)->toContain('search=test');
    expect($queryString)->toContain('page=1');
});