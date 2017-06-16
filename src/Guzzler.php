<?php

namespace Guzzler;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class Guzzler
{
    protected $handler;

    public function __construct()
    {
        $this->handler = new MockHandler();
    }

    public function respondWith($body = '', $status_code = 200, $headers = [])
    {
        $this->handler->append(
            new Response($status_code, $headers, $body)
        );

        return $this;
    }

    public function hijack()
    {
        app()->instance(
            HandlerStack::class,
            HandlerStack::create($this->handler)
        );

        return $this;
    }
}
