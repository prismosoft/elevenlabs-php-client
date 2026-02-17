<?php

namespace ElevenLabs\Resources;

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\Payload;
use ElevenLabs\Support\Transporter\QueryParams;
use ElevenLabs\Support\Transporter\Response;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;

class VoiceDesign
{
    public function __construct(
        private Transporter      $transporter,
        private readonly BaseUri $baseUri,
        private readonly Headers $headers,
    ) {}

    /**
     * @throws ApiException
     */
    public function design(string $voiceDescription, array $options = [], ?string $outputFormat = null): Response
    {
        if (trim($voiceDescription) === '') {
            throw new ApiException('Voice description is required');
        }

        $payload = Payload::fromArray(array_merge([
            'voice_description' => $voiceDescription,
        ], $options));

        $queryParams = new QueryParams();

        if ($outputFormat !== null) {
            $queryParams = $queryParams->withParam('output_format', $outputFormat);
        }

        $request = new Request(
            'POST',
            '/v1/text-to-voice/design' . $queryParams->toString(),
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function create(string $voiceName, string $voiceDescription, string $generatedVoiceId, array $options = []): Response
    {
        if (trim($voiceName) === '' || trim($voiceDescription) === '' || trim($generatedVoiceId) === '') {
            throw new ApiException('Voice name, voice description and generated voice id are required');
        }

        $payload = Payload::fromArray(array_merge([
            'voice_name' => $voiceName,
            'voice_description' => $voiceDescription,
            'generated_voice_id' => $generatedVoiceId,
        ], $options));

        $request = new Request(
            'POST',
            '/v1/text-to-voice',
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function remix(string $voiceId, string $voiceDescription, array $options = [], ?string $outputFormat = null): Response
    {
        if (trim($voiceId) === '' || trim($voiceDescription) === '') {
            throw new ApiException('Voice id and voice description are required');
        }

        $payload = Payload::fromArray(array_merge([
            'voice_description' => $voiceDescription,
        ], $options));

        $queryParams = new QueryParams();

        if ($outputFormat !== null) {
            $queryParams = $queryParams->withParam('output_format', $outputFormat);
        }

        $request = new Request(
            'POST',
            "/v1/text-to-voice/{$voiceId}/remix" . $queryParams->toString(),
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function stream(string $generatedVoiceId): Response
    {
        if (trim($generatedVoiceId) === '') {
            throw new ApiException('Generated voice id is required');
        }

        $request = new Request(
            'GET',
            "/v1/text-to-voice/{$generatedVoiceId}/stream",
            $this->headers->toArray(),
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }
}
