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

class TextToSpeech
{
    private string $voiceId;
    private string $text;
    private bool $withTimestamps = false;
    private ?bool $enableLogging = null;
    private ?int $optimizeStreamingLatency = null;
    private ?string $outputFormat = null;
    private string $modelId = 'eleven_v3';
    private ?string $languageCode = null;
    private ?array $voiceSettings = null;
    private ?array $pronunciationDictionaryLocators = null;
    private ?int $seed = null;
    private ?string $previousText = null;
    private ?string $nextText = null;
    private ?array $previousRequestIds = null;
    private ?array $nextRequestIds = null;
    private ?bool $usePvcAsIvc = null;
    private ?string $applyTextNormalization = null;
    private ?bool $applyLanguageTextNormalization = null;
    private array $options = [];

    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public function withVoiceId(string $voiceId): self
    {
        $clone = clone $this;
        $clone->voiceId = $voiceId;

        return $clone;
    }

    public function withText(string $text): self
    {
        $clone = clone $this;
        $clone->text = $text;

        return $clone;
    }

    public function withTimestamps(bool $withTimestamps = true): self
    {
        $clone = clone $this;
        $clone->withTimestamps = $withTimestamps;

        return $clone;
    }

    public function withOptions(array $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    public function enableLogging(bool $enableLogging = true): self
    {
        $clone = clone $this;
        $clone->enableLogging = $enableLogging;

        return $clone;
    }

    public function withOptimizeStreamingLatency(int $optimizeStreamingLatency): self
    {
        $clone = clone $this;
        $clone->optimizeStreamingLatency = $optimizeStreamingLatency;

        return $clone;
    }

    public function withOutputFormat(string $outputFormat): self
    {
        $clone = clone $this;
        $clone->outputFormat = $outputFormat;

        return $clone;
    }

    public function withModelId(string $modelId): self
    {
        $clone = clone $this;
        $clone->modelId = $modelId;

        return $clone;
    }

    public function withLanguageCode(string $languageCode): self
    {
        $clone = clone $this;
        $clone->languageCode = $languageCode;

        return $clone;
    }

    public function withVoiceSettings(array $voiceSettings): self
    {
        $clone = clone $this;
        $clone->voiceSettings = $voiceSettings;

        return $clone;
    }

    public function withPronunciationDictionaryLocators(array $pronunciationDictionaryLocators): self
    {
        $clone = clone $this;
        $clone->pronunciationDictionaryLocators = $pronunciationDictionaryLocators;

        return $clone;
    }

    public function withSeed(int $seed): self
    {
        $clone = clone $this;
        $clone->seed = $seed;

        return $clone;
    }

    public function withPreviousText(string $previousText): self
    {
        $clone = clone $this;
        $clone->previousText = $previousText;

        return $clone;
    }

    public function withNextText(string $nextText): self
    {
        $clone = clone $this;
        $clone->nextText = $nextText;

        return $clone;
    }

    public function withPreviousRequestIds(array $previousRequestIds): self
    {
        $clone = clone $this;
        $clone->previousRequestIds = $previousRequestIds;

        return $clone;
    }

    public function withNextRequestIds(array $nextRequestIds): self
    {
        $clone = clone $this;
        $clone->nextRequestIds = $nextRequestIds;

        return $clone;
    }

    public function usePvcAsIvc(bool $usePvcAsIvc = true): self
    {
        $clone = clone $this;
        $clone->usePvcAsIvc = $usePvcAsIvc;

        return $clone;
    }

    public function withApplyTextNormalization(string $applyTextNormalization): self
    {
        $clone = clone $this;
        $clone->applyTextNormalization = $applyTextNormalization;

        return $clone;
    }

    public function applyLanguageTextNormalization(bool $applyLanguageTextNormalization = true): self
    {
        $clone = clone $this;
        $clone->applyLanguageTextNormalization = $applyLanguageTextNormalization;

        return $clone;
    }

    public function generate(): Response
    {
        if (empty($this->voiceId) || empty($this->text)) {
            throw new ApiException('Voice ID and text are required');
        }

        $payloadData = array_merge($this->options, [
            'text' => $this->text,
        ]);

        if ($this->modelId !== null) {
            $payloadData['model_id'] = $this->modelId;
        }

        if ($this->languageCode !== null) {
            $payloadData['language_code'] = $this->languageCode;
        }

        if ($this->voiceSettings !== null) {
            $payloadData['voice_settings'] = $this->voiceSettings;
        }

        if ($this->pronunciationDictionaryLocators !== null) {
            $payloadData['pronunciation_dictionary_locators'] = $this->pronunciationDictionaryLocators;
        }

        if ($this->seed !== null) {
            $payloadData['seed'] = $this->seed;
        }

        if ($this->previousText !== null) {
            $payloadData['previous_text'] = $this->previousText;
        }

        if ($this->nextText !== null) {
            $payloadData['next_text'] = $this->nextText;
        }

        if ($this->previousRequestIds !== null) {
            $payloadData['previous_request_ids'] = $this->previousRequestIds;
        }

        if ($this->nextRequestIds !== null) {
            $payloadData['next_request_ids'] = $this->nextRequestIds;
        }

        if ($this->usePvcAsIvc !== null) {
            $payloadData['use_pvc_as_ivc'] = $this->usePvcAsIvc;
        }

        if ($this->applyTextNormalization !== null) {
            $payloadData['apply_text_normalization'] = $this->applyTextNormalization;
        }

        if ($this->applyLanguageTextNormalization !== null) {
            $payloadData['apply_language_text_normalization'] = $this->applyLanguageTextNormalization;
        }

        $payload = Payload::fromArray($payloadData);

        $queryParams = new QueryParams();

        if ($this->enableLogging !== null) {
            $queryParams = $queryParams->withParam('enable_logging', $this->enableLogging ? 'true' : 'false');
        }

        if ($this->optimizeStreamingLatency !== null) {
            $queryParams = $queryParams->withParam('optimize_streaming_latency', (string) $this->optimizeStreamingLatency);
        }

        if ($this->outputFormat !== null) {
            $queryParams = $queryParams->withParam('output_format', $this->outputFormat);
        }

        $encodedVoiceId = rawurlencode($this->voiceId);

        $endpoint = $this->withTimestamps
            ? "/v1/text-to-speech/{$encodedVoiceId}/with-timestamps"
            : "/v1/text-to-speech/{$encodedVoiceId}";

        $endpoint .= $queryParams->toString();

        $request = new Request(
            'POST',
            $endpoint,
            $this->headers->toArray(),
            $payload->toJson()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }
}
