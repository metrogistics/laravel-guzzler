# Laravel Guzzler

Laravel Guzzler is a package that makes it easy to test application logic that makes HTTP requests. Guzzle includes functionality to test requests, but you would need to be able to pass configuration parameters to the Guzzle object from within your tests and that is usually impractical. This package makes it a cinch:

```
test_my_function_returns_client_id()
{
    Guzzler::respondWith(json_encode(['client_id' => 123]))->hijack();

    $client_id = myFunctionThatReturnsAClientIdFromAnApiRequest();

    $this->assertEquals(123, $client_id);
}
```

## Installation

```
composer require pstephan1187/laravel-guzzler
```

Add the service provider to your app config file:

```
Guzzler\GuzzlerServiceProvider::class,
```

Optionally, add the facade (The documentation assumes you do this):

```
'Guzzler' => Guzzler\Facades\Guzzler::class,
```

## Usage

Anywhere you would normally `new GuzzleHttp\Client($params)`, you would replace that with `guzzle($params)`;

```
$client = guzzle(['base_uri' => 'https://www.mysite.com']);
```

When it comes time to test your method, you will hijack the HTTP request and return a custom response:

```
Guzzler::respondWith($body, $status_code, $headers)->hijack();
```

You can chain multiple responses as well:

```
Guzzler::respondWith($body, $status_code, $headers)// gets returned the first time guzzle makes an HTTP request
    ->respondWith($second_body, $second_status_code, $second_headers)// gets returned the second time guzzle makes an HTTP request
    ->respondWith($third_body, $third_status_code, $third_headers)// gets returned the third time guzzle makes an HTTP request
    ->hijack();
```
