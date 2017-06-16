<?php

use Guzzler\Guzzler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Guzzler\GuzzlerServiceProvider;
use Guzzler\Facades\Guzzler as GuzzlerFacade;

class GuzzlerTest extends TestCase
{
    public function test_facade_returns_new_guzzler_object()
    {
        $this->assertInstanceOf(
            Guzzler::class,
            GuzzlerFacade::getFacadeRoot()
        );
    }

    public function test_ioc_contains_guzzle_request_handler()
    {
        $this->assertTrue(
            app()->bound(HandlerStack::class)
        );
    }

    public function test_guzzler_will_use_handler_from_ioc()
    {
        $client = guzzle();

        $handler_class = $this->getPrivateProperty(
            $client->getConfig('handler'),
            'handler'
        );

        $this->assertInstanceOf(
            \Closure::class,
            $handler_class
        );
    }

    public function test_guzzler_will_replace_handler_stack_in_ioc()
    {
        GuzzlerFacade::hijack();

        $client = guzzle();

        $handler_class = $this->getPrivateProperty(
            $client->getConfig('handler'),
            'handler'
        );

        $this->assertInstanceOf(
            MockHandler::class,
            $handler_class
        );
    }

    public function test_guzzler_respects_guzzle_params()
    {
        GuzzlerFacade::respondWith()->hijack();
        $request = guzzle(['base_uri' => 'example.com']);

        $this->assertEquals('example.com', $request->getConfig('base_uri'));
    }

    public function test_guzzler_can_hijack_http_requests()
    {
        GuzzlerFacade::respondWith('test', 250, ['Fake-Header' => 'Fake-Value'])->hijack();
        $response = guzzle()->get('example.com');

        $this->assertEquals('test', $response->getBody()->getContents());
        $this->assertEquals(250, $response->getStatusCode());
        $this->assertEquals(['Fake-Value'], $response->getHeader('Fake-Header'));
    }

    protected function getPackageProviders($app)
    {
        return [GuzzlerServiceProvider::class];
    }

    protected function getPrivateProperty($object, $property)
    {
        $ref = new \ReflectionObject($object);
        $prop = $ref->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($object);
    }
}
