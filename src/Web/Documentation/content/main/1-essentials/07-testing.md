---
title: Testing
---

Testing is a crucial part of any application. Tempest uses [PHPUnit](https://phpunit.de/) as its testing framework.
The default project scaffold contains a `tests` directory with an example test.

## Running tests

In order to run tests, you can use the following command

```
vendor/bin/phpunit
// or
composer phpunit
```

For more information about PHPUnit please refer to the [official documentation](https://phpunit.de/documentation.html).

## HTTP testing

The tests folder contains an `IntegrationTestCase`. Extending this class you helper methods to make integration
tests easy. For http tests you can use the `$this->http` property to make requests. All possible HTTP methods are
available.

The following example will test a `GET` request to `/account/profile`.

```php
class ProfileControllerTest extends IntegrationTestCase
{
    public function test_can_render_profile(): void
    {
        $response = $this->http
            ->get('/account/profile')
            ->assertOk()
            ->assertSee('My Profile');
    }
}
```

### Request body

To send a request body, pass an array as the second parameter to `post()`

```php
public function test_create_user(): void
{
    $this->http->post('/users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
    ]);
}
```

### Testing headers

You can supply request headers as a second parameter to `get`.

```php
public function test_home(): void
{
    $this->http->get('/', ['X-Api-key', '123456']);
}
```

Testing response headers can be done with the `assertHeader` method.

```php
public function test_home(): void
{
    $this->http
        ->get('/')
        ->assertHasHeader('X-Framework') // assert header exists
        ->assertHeaderContains('Content-Type', 'text/html'); // assert header contains a value
}
```
