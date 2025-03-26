---
title: "Controllers"
description: "Controllers manage the flow of any web application. In Tempest, attributes are used to route an HTTP request to any class' method, which is responsible for returning a response."
---

## Overview

In Tempest, a route may be associated to any class' method, although this is usually done in dedicated controller classes.

Tempest provides many attributes, named after HTTP verbs, to attach URIs to controller actions. These attributes implement the {`Tempest\Router\Route`} interface, so you can write your own if you need to.

```php app/HomeController.php
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('home.view.php');
    }
}
```

Out of the box, the following attributes are available:

- {`Tempest\Router\Get`}
- {`Tempest\Router\Post`}
- {`Tempest\Router\Delete`}
- {`Tempest\Router\Put`}
- {`Tempest\Router\Patch`}
- {`Tempest\Router\Options`}
- {`Tempest\Router\Connect`}
- {`Tempest\Router\Trace`}
- {`Tempest\Router\Head`}

## Route parameters

You can define dynamic segments in your route URIs by wrapping them in curly braces `{}`. The segment name inside the braces will be passed as a parameter to your controller method.

```php app/AircraftController.php
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class AircraftController
{
    #[Get(uri: '/aircraft/{id}')]
    public function show(int $id): View
    {
        // Fetch the aircraft by ID
        $aircraft = $this->aircraftRepository->getAircraftById($id);

        // Pass the aircraft to the view
        return view('aircraft.view.php', aircraft: $aircraft);
    }
}
```

### Regular expression constraints

You may constrain the format of a route parameter by specifying a regular expression after its name.

For instance, you may only accept numeric identifiers for an `id` parameter by using the following dynamic segment: `{regex}{id:[0-9]+}`. In practice, a route may look like this:

```php app/AircraftController.php
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class AircraftController
{
    #[Get(uri: '/aircraft/{id:[0-9]+}')]
    public function showAircraft(int $id): View
    {
        // …
    }
}
```

### Route model binding

When injecting [database models](./05-database#database-models) in controller actions, Tempest will map the received model identifier from the request to an instance of that model queried from the database.

```php app/AircraftController.php
use Tempest\Router\Get;
use Tempest\Router\Response;
use App\Aircraft;

final readonly class AircraftController
{
    #[Get('/aircraft/{aircraft}')]
    public function show(Aircraft $aircraft): Response { /* … */ }
}
```

If the model cannot be found in the database, Tempest will return a HTTP 404 response without entering the controller action.

### Backed enum binding

You may inject string-backed enumerations to controller actions. Tempest will try to map the corresponding parameter from the URI to an instance of that enum using the [`tryFrom`](https://www.php.net/manual/en/backedenum.tryfrom.php) enum method.

```php app/AircraftController.php
use Tempest\Router\Get;
use Tempest\Router\Response;
use App\AircraftType;

final readonly class AircraftController
{
    #[Get('/aircraft/{type}')]
    public function show(AircraftType $type): Response { /* … */ }
}
```

In the example above, we inject an `AircraftType` enumeration. If the request's `type` parameter has a value specified in that enumeration, it will be passed to the controller action. Otherwise, a HTTP 404 response will be returned without entering the controller method.

```php app/AircraftType.php
enum AircraftType: string
{
    case PC12 = 'pc12';
    case PC24 = 'pc24';
    case SF50 = 'sf50';
}
```

## Generating URIs

Tempest provides a `\Tempest\uri` function that can be used to generate an URI to a controller method. This function accepts the FQCN of the controller or a callable to a method as its first argument, and named parameters as [the rest of its arguments](https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list).

```php
use function Tempest\uri;

// Invokable classes can be referenced directly:
uri(HomeController::class);
// /home

// Classes with named methods are referenced using an array
uri([AircraftController::class, 'store']);
// /aircraft

// Additional URI parameters are passed in as named arguments:
uri([AircraftController::class, 'show'], id: $aircraft->id);
// /aircraft/1
```

:::info
Note that Tempest does not have named routes, and currently doesn't plan on adding them. However, if you have an argument for them, feel free to hop on our [Discord server](/discord) to discuss it.
:::

## Matching the current URI

To determine whether the current request matches a specific controller action, Tempest provides the `\Tempest\is_current_uri` function. This function accepts the same arguments as `uri`, and returns a boolean.

```php
use function Tempest\is_current_uri;

// Current URI is: /aircraft/1

// Providing no argument to the right controller action will match
is_current_uri(AircraftController::class); // true

// Providing the correct arguments to the right controller action will match
is_current_uri(AircraftController::class, id: 1); // true

// Providing invalid arguments to the right controller action will not match
is_current_uri(AircraftController::class, id: 2); // false
```

## Accessing request data

A core pattern of any web application is to access data from the current request. You may do so by injecting {`Tempest\Router\Request`} to a controller action. This class provides access to the request's body, query parameters, method, and other attributes through dedicated class properties.

### Using request classes

In most situations, the data you expect to receive from a request is structured. You expect clients to send specific values, and you want them to follow specific rules.

The idiomatic way to achieve this is by using request classes. They are classes with public properties that correspond to the data you want to retrieve from the request. Tempest will automatically validate these properties using PHP's type system, in addition to optional [validation attributes](../1-tempest-in-depth/02-validation) if needed.

A request class must implement {`Tempest\Router\Request`} and should use the {`Tempest\Router\IsRequest`} trait, which provides the default implementation.

```php app/RegisterAirportRequest.php
use Tempest\Router\Request;
use Tempest\Router\IsRequest;
use Tempest\Validation\Rules\Length;

final class RegisterAirportRequest implements Request
{
    use IsRequest;

    #[Length(min: 10, max: 120)]
    public string $name;

    public ?DateTimeImmutable $registeredAt = null;

    public string $servedCity;
}
```

:::info Interfaces with default implementations
Tempest uses this pattern a lot. Most classes that interact with the framework need to implement an interface, and a corresponding trait with a default implementation will be provided.
:::

Once you have created a request class, you may simply inject it into a controller action. Tempest will take care of filling its properties and validating them, leaving you with a properly-typed object to work with.

```php app/AirportController.php
use Tempest\Router\Post;
use Tempest\Router\Responses\Redirect;
use function Tempest\map;
use function Tempest\uri;

final readonly class AirportController
{
    #[Post(uri: '/airports/register')]
    public function store(RegisterAirportRequest $request): Redirect
    {
        $airport = map($request)->to(Airport::class)->save();

        return new Redirect(uri([self::class, 'show'], id: $airport->id));
    }
}
```

:::info A note on data mapping
The `map()` function is a powerful feature that sets Tempest apart. It allows mapping any data from any source into objects of your choice. You may read more about them in [their documentation](../1-tempest-in-depth/01-mapper).
:::

### Retrieving data directly

For simpler use cases, you may simply retrieve a value from the body or the query parameter using the request's `get` method.

```php app/AircraftController.php
use Tempest\Router\Get;
use Tempest\Router\Request;

final readonly class AircraftController
{
    #[Get(uri: '/aircraft')]
    public function me(Request $request): View
    {
        $icao = $request->get('icao');
        // …
    }
}
```

## Route middleware

Middleware can be applied to handle tasks in between receiving a request and sending a response. To specify a middleware for a route, add it to the `middleware` argument of a route attribute.

```php app/ReceiveInteractionController.php
use Tempest\Router\Get;
use Tempest\Router\Response;

final readonly class ReceiveInteractionController
{
    #[Post('/slack/interaction', middleware: [ValidateWebhook::class])]
    public function __invoke(): Response
    {
        // …
    }
}
```

The middleware class must be an invokable class that implements the {`Tempest\Router\HttpMiddleware`} interface. This interface has an `{php}__invoke()` method that accepts the current request as its first parameter and {`Tempest\Router\HttpMiddlewareCallable`} as its second parameter.

`HttpMiddlewareCallable` is an invokable class that forwards the `$request` to its next step in the pipeline.

```php app/ValidateWebhook.php
use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;
use Tempest\Router\Request;
use Tempest\Router\Response;

final readonly class ValidateWebhook implements HttpMiddleware
{
    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        $signature = $request->headers->get('X-Slack-Signature');
        $timestamp = $request->headers->get('X-Slack-Request-Timestamp');

        // …

        return $next($request);
    }
}
```

## Responses

All requests to a controller action expect a response to be returned to the client. This is done by returning a `{php}View` or a `{php}Response` object.

### View responses

Returning a view is a shorthand for returning a successful response with that view. You may as well use the `{php}view()` function directly to construct a view.

```php app/Aircraft/AircraftController.php
use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class AircraftController
{
    #[Get(uri: '/aircraft/{aircraft}')]
    public function show(Aircraft $aircraft, User $user): View
    {
        return view('./show.view.php',
            aircraft: $aircraft,
            user: $user,
        );
    }
}
```

Tempest has a powerful templating system inspired by modern front-end frameworks. You may read more about views in their [dedicated chapter](./03-views).

### Using built-in response classes

Tempest provides several classes, all implementing the {`Tempest\Router\Response`} interface, mostly named after HTTP statuses.

- `{php}Ok` — the 200 response. Accepts an optional body.
- `{php}Created` — the 201 response. Accepts an optional body.
- `{php}Redirect` — redirects to the specified URI.
- `{php}Download` — downloads a file from the browser.
- `{php}File` — shows a file in the browser.
- `{php}Invalid` — a response with form validation errors, redirecting to the previous page.
- `{php}NotFound` — the 404 response. Accepts an optional body.
- `{php}ServerError` — a 500 server error response.

The following example conditionnally returns a `Redirect`, otherwise letting the user download a file by sending a `Download` response:

```php app/FlightPlanController.php
use Tempest\Router\Get;
use Tempest\Router\Responses\Download;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\Response;

final readonly class FlightPlanController
{
    #[Get('/{flight}/flight-plan/download')]
    public function download(Flight $flight): Response
    {
        $allowed = /* … */;

        if (! $allowed) {
            return new Redirect('/');
        }

        return new Download($flight->flight_plan_path);
    }
}
```

### Sending generic responses

It might happen that you need to dynamically compute the response's status code, and would rather not use a condition to send the corresponding response object.

You may then return an instance of {`Tempest\Router\GenericResponse`}, specifying the status code and an optional body.

```php app/CreateFlightController.php
use Tempest\Router\Get;
use Tempest\Router\Responses\Download;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\GenericResponse;
use Tempest\Router\Response;

final readonly class CreateFlightController
{
    #[Post('/{flight}')]
    public function __invoke(Flight $flight): Response
    {
        $status = /* … */
        $body = /* … */

        return new GenericResponse(
            status: $status,
            body: $body,
        );
    }
}
```

### Using custom response classes

There are situations where you might send the same kind of response in a lot of places, or you might want to have a proper API for sending a structured response.

You may create your own response class by implementing {`Tempest\Router\Response`}, which default implementation is provided by the {`Tempest\Router\IsResponse`} trait:

```php app/AircraftRegistered.php
use Tempest\Router\IsResponse;
use Tempest\Router\Response;
use Tempest\Router\Status;

final class AircraftRegistered implements Response
{
    use IsResponse;

    public function __construct(Aircraft $aircraft)
    {
        $this->status = Status::CREATED;
        $this->flash(
            key: 'success',
            value: "Aircraft {$aircraft->icao_code} was successfully registered."
        );
    }
}
```

### Specifying content types

Tempest is able to automatically infer the response's content type, usually inferred from the request's `Accept` header.

However, you may override the content type manually by specifying the `setContentType` method on `Response` clases. This method accepts a case of {`Tempest\Router\ContentType`}.

```php app/JsonController.php
use Tempest\Router\Get;
use Tempest\Router\ContentType;
use Tempest\Router\Response;
use Tempest\Router\Responses\Ok;

final readonly class JsonController
{
    #[Get('/json')]
    public function json(string $path): Response
    {
        $data = [ /* … */ ];

        return new Ok($data)->setContentType(ContentType::JSON);
    }
}
```

## Custom route attributes

It is often a requirement to have a bunch of routes following the same specifications—for instance, using the same middleware, or the same URI prefix.

To achieve this, you may create your own route attribute, implementing the {`Tempest\Router\Route`} interface. The constructor of the attribute may hold the logic you want to apply to the routes using it.

```php app/RestrictedRoute.php
use Attribute;
use Tempest\Http\Method;
use Tempest\Router\Route;

#[Attribute]
final readonly class RestrictedRoute implements Route
{
    public function __construct(
        public string $uri,
        public Method $method,
        public array $middleware,
    ) {
        $this->uri = $uri;
        $this->method = $method;
        $this->middleware = [
            AuthorizeUserMiddleware::class,
            LogUserActionsMiddleware::class,
            ...$middleware,
        ];
    }
}
```

This attribute can be used in place of the usual route attributes, on controller action methods.

## Deferring tasks

It is sometimes needed, during requests, to perform tasks that would take a few seconds to complete. This could be sending an email, or keeping track of a page visit.

Tempest provides a way to perform that task after the response has been sent, so the client doesn't have to wait until its completion. This is done by passing a callback to the `defer` function:

```php app/TrackVisitMiddleware.php
use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;
use Tempest\Router\Request;
use Tempest\Router\Response;

use function Tempest\defer;
use function Tempest\event;

final readonly class TrackVisitMiddleware implements HttpMiddleware
{
    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        defer(fn () => event(new PageVisited($request->getUri())));

        return $next($request);
    }
}
```

The `defer` callback may accept any parameter that the container can inject.

:::warning
Task deferring only works if [`fastcgi_finish_request()`](https://www.php.net/manual/en/function.fastcgi-finish-request.php) is available within your PHP installation. If it's not available, deferred tasks will still be run, but the client response will only complete after all tasks have been finished.
:::

## Testing

Tempest provides a router testing utility accessible through the `http` property of the [`IntegrationTest`](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Framework/Testing/IntegrationTest.php) test case. You may learn more about testing in the [dedicated chapter](./07-testing).

The router testing utility provides methods for all HTTP verbs. These method return an instance of [`TestResponseHelper`](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Framework/Testing/Http/TestResponseHelper.php), giving access to multiple assertion methods.

```php tests/ProfileControllerTest.php
final class ProfileControllerTest extends IntegrationTestCase
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
