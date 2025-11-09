<?php

namespace ElevenLabs\Support\Transporter;

use ElevenLabs\Exceptions\HttpException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpTransporter implements Transporter
{
    public function __construct(
        private ClientInterface $client,
    ) {}

    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (\Throwable $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
