<?php

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Resources\VoiceLibrary;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use ElevenLabs\Support\Transporter\Response;
use GuzzleHttp\Psr7\Request;
use Mockery;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = new VoiceLibrary($transporter, $baseUri, $headers);

    expect($voiceLibrary)->toBeInstanceOf(VoiceLibrary::class);
});

it('can set shared voices filters', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = (new VoiceLibrary($transporter, $baseUri, $headers))
        ->withPageSize(50)
        ->withCategory('professional')
        ->withGender('male')
        ->withAge('young')
        ->withAccent('american')
        ->withLanguage('english')
        ->withLocale('en-US')
        ->search('narrator')
        ->withUseCases('audiobooks')
        ->withDescriptives('warm')
        ->featured(true)
        ->withMinNoticePeriodDays(7)
        ->includeCustomRates(true)
        ->includeLiveModerated(false)
        ->readerAppEnabled(true)
        ->withOwnerId('owner_123')
        ->withSort('created_at')
        ->withPage(2);

    expect($voiceLibrary)->toBeInstanceOf(VoiceLibrary::class);
});

it('lists shared voices successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": [], "has_more": false}');

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = new VoiceLibrary($transporter, $baseUri, $headers);

    $result = $voiceLibrary->list();

    expect($result)->toBeInstanceOf(Response::class);
});

it('includes shared voices filters in request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voices": [], "has_more": false}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'GET' || $request->getUri()->getPath() !== '/v1/shared-voices') {
                return false;
            }

            parse_str($request->getUri()->getQuery(), $query);

            return ($query['page_size'] ?? null) === '20'
                && ($query['category'] ?? null) === 'professional'
                && ($query['gender'] ?? null) === 'female'
                && ($query['age'] ?? null) === 'middle_aged'
                && ($query['accent'] ?? null) === 'british'
                && ($query['language'] ?? null) === 'english'
                && ($query['locale'] ?? null) === 'en-GB'
                && ($query['search'] ?? null) === 'storyteller'
                && ($query['use_cases'] ?? null) === 'podcasts'
                && ($query['descriptives'] ?? null) === 'clear'
                && ($query['featured'] ?? null) === 'true'
                && ($query['min_notice_period_days'] ?? null) === '3'
                && ($query['include_custom_rates'] ?? null) === 'false'
                && ($query['include_live_moderated'] ?? null) === 'true'
                && ($query['reader_app_enabled'] ?? null) === 'false'
                && ($query['owner_id'] ?? null) === 'public_owner_id'
                && ($query['sort'] ?? null) === 'created_at'
                && ($query['page'] ?? null) === '1';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = (new VoiceLibrary($transporter, $baseUri, $headers))
        ->withPageSize(20)
        ->withCategory('professional')
        ->withGender('female')
        ->withAge('middle_aged')
        ->withAccent('british')
        ->withLanguage('english')
        ->withLocale('en-GB')
        ->search('storyteller')
        ->withUseCases('podcasts')
        ->withDescriptives('clear')
        ->featured(true)
        ->withMinNoticePeriodDays(3)
        ->includeCustomRates(false)
        ->includeLiveModerated(true)
        ->readerAppEnabled(false)
        ->withOwnerId('public_owner_id')
        ->withSort('created_at')
        ->withPage(1);

    $voiceLibrary->list();
});

it('throws exception when adding shared voice without required fields', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = new VoiceLibrary($transporter, $baseUri, $headers);

    expect(fn () => $voiceLibrary->add('', 'voice_id', 'John Smith'))->toThrow(ApiException::class);
});

it('adds shared voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voice_id":"voice-id"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'POST' || $request->getUri()->getPath() !== '/v1/voices/add/public_user_id/voice_id') {
                return false;
            }

            $body = json_decode((string) $request->getBody(), true);

            return ($body['new_name'] ?? null) === 'John Smith';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = new VoiceLibrary($transporter, $baseUri, $headers);

    $result = $voiceLibrary->add('public_user_id', 'voice_id', 'John Smith');

    expect($result)->toBeInstanceOf(Response::class);
});

it('can share voice using alias method', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voice_id":"voice-id"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return $request->getMethod() === 'POST'
                && $request->getUri()->getPath() === '/v1/voices/add/public_user_id/voice_id';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceLibrary = new VoiceLibrary($transporter, $baseUri, $headers);

    $result = $voiceLibrary->share('public_user_id', 'voice_id', 'John Smith');

    expect($result)->toBeInstanceOf(Response::class);
});
