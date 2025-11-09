<?php

namespace AdBlast\ElevenLabs\Support\Transporter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Transporter
{
    public function request(RequestInterface $request): ResponseInterface;
}