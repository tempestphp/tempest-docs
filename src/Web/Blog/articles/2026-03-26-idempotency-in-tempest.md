---
title: Idempotency in Tempest
description: We've recently added an idempotency feature into Tempest to help you avoid code running twice when it shouldn't.
tag: release
author: brent
---

Oftentimes you need to ensure an operation only runs once: creating payments, generating invoices, provisioning resources, and what not; you want to prevent these things happening twice or more when they should only happen once. That's where our new idempotency package comes in. You can now mark routes and commands with the `#[Idempotent]` attribute to make sure they won't be run multiple times when they shouldn't.

Here's an example of a controller action:

```php
use Tempest\Idempotency\Attributes\Idempotent;
use Tempest\Router\Post;

final readonly class OrderController
{
    #[Idempotent]
    #[Post('/orders')]
    public function create(CreateOrderRequest $request): Response
    {
        $order = $this->orderService->create($request);

        return new GenericResponse(
            status: Status::CREATED,
            body: ['id' => $order->id],
        );
    }
}
```

Whenever this controller action is called, the `#[Idempotent]` attribute will make sure it only runs once within the context of an "[idempotency key](https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Idempotency-Key)", and return a cached result for subsequent requests.

This "idempotency key", by the way, is a header the client sends; any request with the same idempotency key will be considered "the same".

```txt
POST /orders HTTP/1.1
Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000
Content-Type: application/json

{"product": "widget", "quantity": 3}
```

Similar to idempotent routes, Tempest also supports idempotent commands. You can tag either a command or its handler with the same `#[Idempotent]` attribute:

```php
use Tempest\Idempotency\Attributes\Idempotent;
use Tempest\CommandBus\CommandHandler;

final class ImportInvoicesHandler
{
    #[Idempotent]
    #[CommandHandler]
    public function handleImportInvoices(ImportInvoicesCommand $command): void
    {}
}
```

By default, command idempotency is determined by the command's payload. However, commands can also implement the {b`Tempest\Idempotency\HasIdempotencyKey`} interface to provide a key which determines uniqueness (similar to the HTTP header for routes):

```php
use Tempest\Idempotency\Attributes\Idempotent;
use Tempest\Idempotency\HasIdempotencyKey;

#[Idempotent]
final readonly class ProcessPaymentCommand implements HasIdempotencyKey
{
    public function __construct(
        public string $paymentId,
        public int $amount,
    ) {}

    public function getIdempotencyKey(): string
    {
        return $this->paymentId;
    }
}
```

Finally, idempotency can be configured in many ways as well. You can [read all about it in the docs](/3.x/features/idempotency).