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

class VoiceLibrary
{
    private ?int $pageSize = null;
    private ?string $category = null;
    private ?string $gender = null;
    private ?string $age = null;
    private ?string $accent = null;
    private ?string $language = null;
    private ?string $locale = null;
    private ?string $search = null;
    private ?string $useCases = null;
    private ?string $descriptives = null;
    private ?bool $featured = null;
    private ?int $minNoticePeriodDays = null;
    private ?bool $includeCustomRates = null;
    private ?bool $includeLiveModerated = null;
    private ?bool $readerAppEnabled = null;
    private ?string $ownerId = null;
    private ?string $sort = null;
    private ?int $page = null;

    public function __construct(
        private Transporter $transporter,
        private BaseUri $baseUri,
        private Headers $headers,
    ) {}

    public function withPageSize(int $pageSize): self
    {
        $clone = clone $this;
        $clone->pageSize = $pageSize;

        return $clone;
    }

    public function withCategory(string $category): self
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    public function withGender(string $gender): self
    {
        $clone = clone $this;
        $clone->gender = $gender;

        return $clone;
    }

    public function withAge(string $age): self
    {
        $clone = clone $this;
        $clone->age = $age;

        return $clone;
    }

    public function withAccent(string $accent): self
    {
        $clone = clone $this;
        $clone->accent = $accent;

        return $clone;
    }

    public function withLanguage(string $language): self
    {
        $clone = clone $this;
        $clone->language = $language;

        return $clone;
    }

    public function withLocale(string $locale): self
    {
        $clone = clone $this;
        $clone->locale = $locale;

        return $clone;
    }

    public function search(string $search): self
    {
        $clone = clone $this;
        $clone->search = $search;

        return $clone;
    }

    public function withUseCases(string $useCases): self
    {
        $clone = clone $this;
        $clone->useCases = $useCases;

        return $clone;
    }

    public function withDescriptives(string $descriptives): self
    {
        $clone = clone $this;
        $clone->descriptives = $descriptives;

        return $clone;
    }

    public function featured(bool $featured = true): self
    {
        $clone = clone $this;
        $clone->featured = $featured;

        return $clone;
    }

    public function withMinNoticePeriodDays(int $minNoticePeriodDays): self
    {
        $clone = clone $this;
        $clone->minNoticePeriodDays = $minNoticePeriodDays;

        return $clone;
    }

    public function includeCustomRates(bool $includeCustomRates = true): self
    {
        $clone = clone $this;
        $clone->includeCustomRates = $includeCustomRates;

        return $clone;
    }

    public function includeLiveModerated(bool $includeLiveModerated = true): self
    {
        $clone = clone $this;
        $clone->includeLiveModerated = $includeLiveModerated;

        return $clone;
    }

    public function readerAppEnabled(bool $readerAppEnabled = true): self
    {
        $clone = clone $this;
        $clone->readerAppEnabled = $readerAppEnabled;

        return $clone;
    }

    public function withOwnerId(string $ownerId): self
    {
        $clone = clone $this;
        $clone->ownerId = $ownerId;

        return $clone;
    }

    public function withSort(string $sort): self
    {
        $clone = clone $this;
        $clone->sort = $sort;

        return $clone;
    }

    public function withPage(int $page): self
    {
        $clone = clone $this;
        $clone->page = $page;

        return $clone;
    }

    public function list(): Response
    {
        $queryParams = new QueryParams();

        if ($this->pageSize !== null) {
            $queryParams = $queryParams->withParam('page_size', (string) $this->pageSize);
        }

        if ($this->category !== null) {
            $queryParams = $queryParams->withParam('category', $this->category);
        }

        if ($this->gender !== null) {
            $queryParams = $queryParams->withParam('gender', $this->gender);
        }

        if ($this->age !== null) {
            $queryParams = $queryParams->withParam('age', $this->age);
        }

        if ($this->accent !== null) {
            $queryParams = $queryParams->withParam('accent', $this->accent);
        }

        if ($this->language !== null) {
            $queryParams = $queryParams->withParam('language', $this->language);
        }

        if ($this->locale !== null) {
            $queryParams = $queryParams->withParam('locale', $this->locale);
        }

        if ($this->search !== null) {
            $queryParams = $queryParams->withParam('search', $this->search);
        }

        if ($this->useCases !== null) {
            $queryParams = $queryParams->withParam('use_cases', $this->useCases);
        }

        if ($this->descriptives !== null) {
            $queryParams = $queryParams->withParam('descriptives', $this->descriptives);
        }

        if ($this->featured !== null) {
            $queryParams = $queryParams->withParam('featured', $this->featured ? 'true' : 'false');
        }

        if ($this->minNoticePeriodDays !== null) {
            $queryParams = $queryParams->withParam('min_notice_period_days', (string) $this->minNoticePeriodDays);
        }

        if ($this->includeCustomRates !== null) {
            $queryParams = $queryParams->withParam('include_custom_rates', $this->includeCustomRates ? 'true' : 'false');
        }

        if ($this->includeLiveModerated !== null) {
            $queryParams = $queryParams->withParam('include_live_moderated', $this->includeLiveModerated ? 'true' : 'false');
        }

        if ($this->readerAppEnabled !== null) {
            $queryParams = $queryParams->withParam('reader_app_enabled', $this->readerAppEnabled ? 'true' : 'false');
        }

        if ($this->ownerId !== null) {
            $queryParams = $queryParams->withParam('owner_id', $this->ownerId);
        }

        if ($this->sort !== null) {
            $queryParams = $queryParams->withParam('sort', $this->sort);
        }

        if ($this->page !== null) {
            $queryParams = $queryParams->withParam('page', (string) $this->page);
        }

        $request = new Request(
            'GET',
            '/v1/shared-voices' . $queryParams->toString(),
            $this->headers->toArray(),
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function add(string $publicUserId, string $voiceId, string $newName): Response
    {
        if (trim($publicUserId) === '' || trim($voiceId) === '' || trim($newName) === '') {
            throw new ApiException('Public user id, voice id and new name are required');
        }

        $payload = Payload::fromArray([
            'new_name' => $newName,
        ]);

        $request = new Request(
            'POST',
            '/v1/voices/add/' . rawurlencode($publicUserId) . '/' . rawurlencode($voiceId),
            $this->headers->toArray(),
            $payload->toJson(),
        );

        $response = $this->transporter->request($request);

        return new Response($response);
    }

    /**
     * @throws ApiException
     */
    public function share(string $publicUserId, string $voiceId, string $newName): Response
    {
        return $this->add($publicUserId, $voiceId, $newName);
    }
}
