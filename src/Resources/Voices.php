<?php

namespace ElevenLabs\Resources;

use ElevenLabs\Support\Transporter\BaseUri;
use ElevenLabs\Support\Transporter\Headers;
use ElevenLabs\Support\Transporter\QueryParams;
use ElevenLabs\Support\Transporter\Response;
use ElevenLabs\Support\Transporter\Transporter;
use GuzzleHttp\Psr7\Request;

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
}
