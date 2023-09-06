## Upgrade apps from 2.x to 3.x
 - The `redirect()` method no longer calls `exit()` after sending the content. This is up to the developer now.
 - It is now possible to use `setAmountInteger(integer $value)` and `setMoney(Money $money)`
 - HTTPPlug is used. Guzzle will be installed when using `omnipay/omnipay`, otherwise you need to require your own implementation (see http://docs.php-http.org/en/latest/clients.html)
 - The package is renamed from `omnipay/omnipay` to `league/omnipay` and no longer installs all gateways by default.
## Upgrade Gateways from 2.x to 3.x

The primary difference is the HTTP Client. We are now using HTTPlug (http://httplug.io/) but rely on our own interface.

### Breaking
- Change typehint from Guzzle ClientInterface to `Omnipay\Common\Http\ClientInterface`
- `$client->get('..')`/`$client->post('..')` etc are removed, you can call `$client->request('GET', '')`.
- No need to call `$request->send()`, requests are sent directly.
- Instead of `$client->createRequest(..)` you can create+send the request directly with `$client->request(..)`.
- When sending a JSON body, convert the body to a string with `json_encode()` and set the correct Content-Type.
- The response is a PSR-7 Response object. You can call `$response->getBody()->getContents()` to get the body as a string.
- `$response->json()` and `$response->xml()` are gone, but you can implement the logic directly.
- An HTTP Client is no longer added by default by `omnipay/common`, but `omnipay/omnipay` will add Guzzle. 
Gateways should not rely on Guzzle or other clients directly.
- `$body` should be a string (eg. `http_build_query($data)` or `json_encode($data)` instead of just `$data`).
- The `$headers` parameters should be an `array` (not `null`, but can be empty)

Examples:
```php
// V2 XML:
 $response = $this->httpClient->post($this->endpoint, null, $data)->send();
 $result = $httpResponse->xml();

// V3 XML:
 $response = $this->httpClient->request('POST', $this->endpoint, [], http_build_query($data));
 $result = simplexml_load_string($httpResponse->getBody()->getContents());
```

```php
// Example JSON request:

 $response = $this->httpClient->request('POST', $this->endpoint, [
     'Accept' => 'application/json',
     'Content-Type' => 'application/json',
 ], json_encode($data));
 
 $result = json_decode($response->getBody()->getContents(), true);
```

#### Testing

PHPUnit is upgraded to PHPUnit 6. Common issues:

- `setExpectedException()` is removed
```php
// PHPUnit 5:
$this->setExpectedException($class, $message);

// PHPUnit 6:
$this->expectException($class);
$this->expectExceptionMessage($message);
```

- Tests that do not perform any assertions, will be marked as risky. This can be avoided by annotating them with ` @doesNotPerformAssertions`

- You should remove the `Mockery\Adapter\Phpunit\TestListener` in phpunit.xml.dist

