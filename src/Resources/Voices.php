<?php

namespace ElevenLabs\Resources;

use ElevenLabs\Exceptions\ApiException;
use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\QueryParams;
use ElevenLabs\Support\Transporter\Response;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;

class Voices
{
    private ?string $search = null;
    private ?int $pageSize = null;
    private ?string $nextPageToken = null;
    private ?string $sort = null;
    private ?string $sortDirection = null;
    private ?string $voiceType = null;
    private ?string $category = null;
    private ?string $fineTuningState = null;
    private ?string $collectionId = null;
    private ?bool $includeTotalCount = null;
    private ?string $voiceIds = null;

    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public function search(string $query): self
    {
        $clone = clone $this;
        $clone->search = $query;

        return $clone;
    }

    public function withPageSize(int $size): self
    {
        $clone = clone $this;
        $clone->pageSize = $size;

        return $clone;
    }

    public function withNextPageToken(string $token): self
    {
        $clone = clone $this;
        $clone->nextPageToken = $token;

        return $clone;
    }

    public function withSort(string $sort): self
    {
        $clone = clone $this;
        $clone->sort = $sort;

        return $clone;
    }

    public function withSortDirection(string $sortDirection): self
    {
        $clone = clone $this;
        $clone->sortDirection = $sortDirection;

        return $clone;
    }

    public function withVoiceType(string $voiceType): self
    {
        $clone = clone $this;
        $clone->voiceType = $voiceType;

        return $clone;
    }

    public function withCategory(string $category): self
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    public function withFineTuningState(string $state): self
    {
        $clone = clone $this;
        $clone->fineTuningState = $state;

        return $clone;
    }

    public function withCollectionId(string $collectionId): self
    {
        $clone = clone $this;
        $clone->collectionId = $collectionId;

        return $clone;
    }

    public function includeTotalCount(bool $include = true): self
    {
        $clone = clone $this;
        $clone->includeTotalCount = $include;

        return $clone;
    }

    public function withVoiceIds(array|string $voiceIds): self
    {
        $clone = clone $this;

        if (is_array($voiceIds)) {
            $ids = array_map(static fn ($voiceId): string => trim((string) $voiceId), $voiceIds);
            $ids = array_filter($ids, static fn (string $voiceId): bool => $voiceId !== '');

            $clone->voiceIds = $ids === [] ? null : implode(',', $ids);

            return $clone;
        }

        $clone->voiceIds = $voiceIds;

        return $clone;
    }

    public function list(): Response
    {
        $queryParams = new QueryParams();

        if ($this->search !== null) {
            $queryParams = $queryParams->withParam('search', $this->search);
        }

        if ($this->pageSize !== null) {
            $queryParams = $queryParams->withParam('page_size', (string) $this->pageSize);
        }

        if ($this->nextPageToken !== null) {
            $queryParams = $queryParams->withParam('next_page_token', $this->nextPageToken);
        }

        if ($this->sort !== null) {
            $queryParams = $queryParams->withParam('sort', $this->sort);
        }

        if ($this->sortDirection !== null) {
            $queryParams = $queryParams->withParam('sort_direction', $this->sortDirection);
        }

        if ($this->voiceType !== null) {
            $queryParams = $queryParams->withParam('voice_type', $this->voiceType);
        }

        if ($this->category !== null) {
            $queryParams = $queryParams->withParam('category', $this->category);
        }

        if ($this->fineTuningState !== null) {
            $queryParams = $queryParams->withParam('fine_tuning_state', $this->fineTuningState);
        }

        if ($this->collectionId !== null) {
            $queryParams = $queryParams->withParam('collection_id', $this->collectionId);
        }

        if ($this->includeTotalCount !== null) {
            $queryParams = $queryParams->withParam('include_total_count', $this->includeTotalCount ? 'true' : 'false');
        }

        if ($this->voiceIds !== null) {
            $queryParams = $queryParams->withParam('voice_ids', $this->voiceIds);
        }

        $request = new Request(
            'GET',
            '/v2/voices' . $queryParams->toString(),
            $this->headers->toArray()
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function get(string $voiceId, bool $withSettings = true): Response
    {
        if (trim($voiceId) === '') {
            throw new ApiException('Voice id is required');
        }

        $queryParams = (new QueryParams())->withParam('with_settings', $withSettings ? 'true' : 'false');

        $request = new Request(
            'GET',
            '/v1/voices/' . rawurlencode($voiceId) . $queryParams->toString(),
            $this->headers->toArray(),
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function delete(string $voiceId): Response
    {
        if (trim($voiceId) === '') {
            throw new ApiException('Voice id is required');
        }

        $request = new Request(
            'DELETE',
            '/v1/voices/' . rawurlencode($voiceId),
            $this->headers->toArray(),
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function update(string $voiceId, string $name, array $options = []): Response
    {
        if (trim($voiceId) === '' || trim($name) === '') {
            throw new ApiException('Voice id and name are required');
        }

        $multipart = [
            ['name' => 'name', 'contents' => $name],
        ];

        if (array_key_exists('remove_background_noise', $options) && $options['remove_background_noise'] !== null) {
            $multipart[] = [
                'name' => 'remove_background_noise',
                'contents' => $options['remove_background_noise'] ? 'true' : 'false',
            ];
        }

        if (array_key_exists('description', $options) && $options['description'] !== null) {
            $multipart[] = [
                'name' => 'description',
                'contents' => (string) $options['description'],
            ];
        }

        if (array_key_exists('labels', $options) && $options['labels'] !== null) {
            $multipart[] = [
                'name' => 'labels',
                'contents' => is_array($options['labels']) ? json_encode($options['labels']) : (string) $options['labels'],
            ];
        }

        if (array_key_exists('files', $options) && $options['files'] !== null) {
            $files = is_array($options['files']) ? $options['files'] : [$options['files']];

            foreach ($files as $file) {
                $multipart[] = $this->normalizeMultipartFile($file, 'files');
            }
        }

        [$multipartBody, $contentType] = $this->buildMultipartBody($multipart);
        $headers = $this->headers
            ->withContentType($contentType)
            ->toArray();

        $request = new Request(
            'POST',
            '/v1/voices/' . rawurlencode($voiceId) . '/edit',
            $headers,
            $multipartBody,
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function findSimilarVoices(array $options = []): Response
    {
        $multipart = [];

        if (array_key_exists('audio_file', $options) && $options['audio_file'] !== null) {
            $multipart[] = $this->normalizeMultipartFile($options['audio_file'], 'audio_file');
        }

        if (array_key_exists('similarity_threshold', $options) && $options['similarity_threshold'] !== null) {
            $multipart[] = [
                'name' => 'similarity_threshold',
                'contents' => (string) $options['similarity_threshold'],
            ];
        }

        if (array_key_exists('top_k', $options) && $options['top_k'] !== null) {
            $multipart[] = [
                'name' => 'top_k',
                'contents' => (string) $options['top_k'],
            ];
        }

        [$multipartBody, $contentType] = $this->buildMultipartBody($multipart);
        $headers = $this->headers
            ->withContentType($contentType)
            ->toArray();

        $request = new Request(
            'POST',
            '/v1/similar-voices',
            $headers,
            $multipartBody,
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    private function normalizeMultipartFile(mixed $file, string $fieldName): array
    {
        if (is_array($file) && array_key_exists('contents', $file)) {
            $part = [
                'name' => $fieldName,
                'contents' => $file['contents'],
            ];

            if (array_key_exists('filename', $file)) {
                $part['filename'] = $file['filename'];
            }

            if (array_key_exists('headers', $file)) {
                $part['headers'] = $file['headers'];
            }

            return $part;
        }

        if (is_string($file)) {
            if (! is_file($file) || ! is_readable($file)) {
                throw new ApiException('File path does not exist or is not readable: ' . $file);
            }

            $contents = file_get_contents($file);

            if ($contents === false) {
                throw new ApiException('Failed to read file: ' . $file);
            }

            return [
                'name' => $fieldName,
                'contents' => $contents,
                'filename' => basename($file),
            ];
        }

        if (is_resource($file) || $file instanceof StreamInterface) {
            return [
                'name' => $fieldName,
                'contents' => $file,
                'filename' => 'audio_sample',
            ];
        }

        throw new ApiException('File must be a readable file path, stream, resource, or multipart descriptor array');
    }

    private function buildMultipartBody(array $parts): array
    {
        $boundary = '----elevenlabs-' . str_replace('.', '', uniqid('', true));
        $lineBreak = "\r\n";
        $body = '';

        foreach ($parts as $part) {
            $body .= '--' . $boundary . $lineBreak;
            $body .= 'Content-Disposition: form-data; name="' . $part['name'] . '"';

            if (isset($part['filename'])) {
                $body .= '; filename="' . $part['filename'] . '"';
            }

            $body .= $lineBreak;

            if (isset($part['headers']) && is_array($part['headers'])) {
                foreach ($part['headers'] as $headerName => $headerValue) {
                    $body .= $headerName . ': ' . $headerValue . $lineBreak;
                }
            }

            $body .= $lineBreak;
            $body .= $this->normalizeMultipartContents($part['contents']);
            $body .= $lineBreak;
        }

        $body .= '--' . $boundary . '--' . $lineBreak;

        return [$body, 'multipart/form-data; boundary=' . $boundary];
    }

    private function normalizeMultipartContents(mixed $contents): string
    {
        if (is_string($contents)) {
            return $contents;
        }

        if (is_resource($contents)) {
            $resourceContents = stream_get_contents($contents);

            if ($resourceContents === false) {
                return '';
            }

            return $resourceContents;
        }

        if ($contents instanceof StreamInterface) {
            return (string) $contents;
        }

        return (string) $contents;
    }
}
