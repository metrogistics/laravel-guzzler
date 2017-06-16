<?php

if (! function_exists('guzzle')) {
    function guzzle(array $params = [])
    {
        return app(\GuzzleHttp\Client::class, $params);
    }
}
