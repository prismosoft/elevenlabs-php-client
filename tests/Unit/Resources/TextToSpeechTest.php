<?php

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Resources\TextToSpeech;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\HttpTransporter;
use ElevenLabs\Support\Transporter\Response;
use GuzzleHttp\Psr7\Request;

it('can be instantiated', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = new TextToSpeech($transporter, $baseUri, $headers);

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set voice id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123');

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set text', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withText('Hello world');

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can enable timestamps', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withTimestamps();

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set options', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withOptions(['stability' => 0.5]);

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('can set all text-to-speech endpoint fields', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world')
        ->enableLogging(false)
        ->withOptimizeStreamingLatency(3)
        ->withOutputFormat('mp3_44100_128')
        ->withModelId('eleven_multilingual_v2')
        ->withLanguageCode('en')
        ->withVoiceSettings([
            'stability' => 0.5,
            'similarity_boost' => 0.8,
            'style' => 0.1,
            'use_speaker_boost' => true,
            'speed' => 1.1,
        ])
        ->withPronunciationDictionaryLocators([
            [
                'pronunciation_dictionary_id' => 'dict_1',
                'version_id' => 'ver_1',
            ],
        ])
        ->withSeed(1234)
        ->withPreviousText('Before text')
        ->withNextText('After text')
        ->withPreviousRequestIds(['req_1'])
        ->withNextRequestIds(['req_2'])
        ->usePvcAsIvc()
        ->withApplyTextNormalization('auto')
        ->applyLanguageTextNormalization();

    expect($tts)->toBeInstanceOf(TextToSpeech::class);
});

it('throws exception when generating without voice id', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withText('Hello world');

    expect(fn () => $tts->generate())->toThrow(ApiException::class);
});

it('throws exception when generating without text', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123');

    expect(fn () => $tts->generate())->toThrow(ApiException::class);
});

it('generates speech successfully', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');
    $response = new Response($psrResponse);

    $transporter->shouldReceive('request')
        ->once()
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world');

    $result = $tts->generate();

    expect($result)->toBeInstanceOf(Response::class);
});

it('uses timestamps endpoint when enabled', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            return str_contains($request->getUri()->getPath(), 'with-timestamps');
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world')
        ->withTimestamps();

    $tts->generate();
});

it('includes query parameters in request', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            parse_str($request->getUri()->getQuery(), $query);

            return ($query['enable_logging'] ?? null) === 'false'
                && ($query['optimize_streaming_latency'] ?? null) === '3'
                && ($query['output_format'] ?? null) === 'mp3_44100_128';
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world')
        ->enableLogging(false)
        ->withOptimizeStreamingLatency(3)
        ->withOutputFormat('mp3_44100_128');

    $tts->generate();
});

it('includes supported payload fields in request body', function () {
    $transporter = Mockery::mock(HttpTransporter::class);
    $psrResponse = new \GuzzleHttp\Psr7\Response(200, [], 'audio data');

    $transporter->shouldReceive('request')
        ->once()
        ->with(Mockery::on(function (Request $request) {
            $payload = json_decode((string) $request->getBody(), true);

            return ($payload['text'] ?? null) === 'Hello world'
                && ($payload['model_id'] ?? null) === 'eleven_multilingual_v2'
                && ($payload['language_code'] ?? null) === 'en'
                && ($payload['voice_settings']['stability'] ?? null) === 0.5
                && ($payload['voice_settings']['similarity_boost'] ?? null) === 0.8
                && ($payload['voice_settings']['style'] ?? null) === 0.1
                && ($payload['voice_settings']['use_speaker_boost'] ?? null) === true
                && ($payload['voice_settings']['speed'] ?? null) === 1.1
                && ($payload['pronunciation_dictionary_locators'][0]['pronunciation_dictionary_id'] ?? null) === 'dict_1'
                && ($payload['pronunciation_dictionary_locators'][0]['version_id'] ?? null) === 'ver_1'
                && ($payload['seed'] ?? null) === 1234
                && ($payload['previous_text'] ?? null) === 'Before text'
                && ($payload['next_text'] ?? null) === 'After text'
                && ($payload['previous_request_ids'][0] ?? null) === 'req_1'
                && ($payload['next_request_ids'][0] ?? null) === 'req_2'
                && ($payload['use_pvc_as_ivc'] ?? null) === true
                && ($payload['apply_text_normalization'] ?? null) === 'auto'
                && ($payload['apply_language_text_normalization'] ?? null) === true;
        }))
        ->andReturn($psrResponse);

    $baseUri = new BaseUri();
    $headers = new Headers();

    $tts = (new TextToSpeech($transporter, $baseUri, $headers))
        ->withVoiceId('voice-123')
        ->withText('Hello world')
        ->withModelId('eleven_multilingual_v2')
        ->withLanguageCode('en')
        ->withVoiceSettings([
            'stability' => 0.5,
            'similarity_boost' => 0.8,
            'style' => 0.1,
            'use_speaker_boost' => true,
            'speed' => 1.1,
        ])
        ->withPronunciationDictionaryLocators([
            [
                'pronunciation_dictionary_id' => 'dict_1',
                'version_id' => 'ver_1',
            ],
        ])
        ->withSeed(1234)
        ->withPreviousText('Before text')
        ->withNextText('After text')
        ->withPreviousRequestIds(['req_1'])
        ->withNextRequestIds(['req_2'])
        ->usePvcAsIvc(true)
        ->withApplyTextNormalization('auto')
        ->applyLanguageTextNormalization(true);

    $tts->generate();
});
