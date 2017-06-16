<?php

namespace Guzzler;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\ServiceProvider;

class GuzzlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Guzzler::class, function(){
            return new Guzzler();
        });

        $this->app->bind(HandlerStack::class, function(){
            return HandlerStack::create();
        });

        $this->app->bind(Client::class, function($app, $params){
            $params = array_merge(
                ['handler' => $app[HandlerStack::class]],
                $params
            );

            return new Client($params);
        });
    }
}
