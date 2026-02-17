<?php

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Resources\VoiceDesign;
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

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    expect($voiceDesign)->toBeInstanceOf(VoiceDesign::class);
});

it('throws exception when designing without voice description', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    expect(fn () => $voiceDesign->design(''))->toThrow(ApiException::class);
});

it('designs voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"previews":[],"text":"sample"}');

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    $result = $voiceDesign->design('A sassy squeaky mouse');

    expect($result)->toBeInstanceOf(Response::class);
});

it('includes output format in design request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"previews":[],"text":"sample"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return str_contains((string) $request->getUri(), 'output_format=mp3_22050_32');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    $voiceDesign->design('A sassy squeaky mouse', [], 'mp3_22050_32');
});

it('throws exception when creating without required fields', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    expect(fn () => $voiceDesign->create('', 'desc', 'generated-id'))->toThrow(ApiException::class);
});

it('creates voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"voice_id":"voice-id"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'POST' || $request->getUri()->getPath() !== '/v1/text-to-voice') {
                return false;
            }

            $body = json_decode((string) $request->getBody(), true);

            return ($body['voice_name'] ?? null) === 'Sassy squeaky mouse'
                && ($body['voice_description'] ?? null) === 'A sassy squeaky mouse'
                && ($body['generated_voice_id'] ?? null) === '37HceQefKmEi3bGovXjL';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    $result = $voiceDesign->create(
        'Sassy squeaky mouse',
        'A sassy squeaky mouse',
        '37HceQefKmEi3bGovXjL'
    );

    expect($result)->toBeInstanceOf(Response::class);
});

it('throws exception when remixing without required fields', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    expect(fn () => $voiceDesign->remix('', 'Make the voice higher pitch'))->toThrow(ApiException::class);
});

it('remixes voice successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], '{"previews":[],"text":"sample"}');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            if ($request->getMethod() !== 'POST' || $request->getUri()->getPath() !== '/v1/text-to-voice/21m00Tcm4TlvDq8ikWAM/remix') {
                return false;
            }

            parse_str($request->getUri()->getQuery(), $query);
            $body = json_decode((string) $request->getBody(), true);

            return ($query['output_format'] ?? null) === 'mp3_22050_32'
                && ($body['voice_description'] ?? null) === 'Make the voice have a higher pitch.';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    $result = $voiceDesign->remix(
        '21m00Tcm4TlvDq8ikWAM',
        'Make the voice have a higher pitch.',
        [],
        'mp3_22050_32'
    );

    expect($result)->toBeInstanceOf(Response::class);
});

it('throws exception when streaming without generated voice id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    expect(fn () => $voiceDesign->stream(''))->toThrow(ApiException::class);
});

it('streams voice preview successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio stream');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return $request->getMethod() === 'GET'
                && $request->getUri()->getPath() === '/v1/text-to-voice/generated_voice_id/stream';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $voiceDesign = new VoiceDesign($transporter, $baseUri, $headers);

    $result = $voiceDesign->stream('generated_voice_id');

    expect($result)->toBeInstanceOf(Response::class);
});
