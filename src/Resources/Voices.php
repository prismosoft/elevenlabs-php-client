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

    public function list(): Response
    {
        $queryParams = new QueryParams();

        if ($this->search) {
            $queryParams = $queryParams->withParam('search', $this->search);
        }

        if ($this->pageSize) {
            $queryParams = $queryParams->withParam('page_size', (string) $this->pageSize);
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
