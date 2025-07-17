---
title: Mailing with Tempest
description: The newest Tempest release adds mailing support
author: brent
tag: Release
---

Mailing is a pretty crucial feature for many apps, and I'm happy that we tagged Tempest 1.4 today, which includes mailing support. We didn't invent mailing from scratch though, we decided to build on top of the excellent Mailer component provided by Symfony (including all of its transport drivers) and build a small layer on top of those that fits well within Tempest.

Here's what an email looks like in Tempest:

```php
use Tempest\Mail\Attachment;
use Tempest\Mail\Email;
use Tempest\Mail\Envelope;
use Tempest\Mail\HasAttachments;
use Tempest\View\View;
use function Tempest\view;

final class WelcomeEmail implements Email, HasAttachments
{
    public function __construct(
        private readonly User $user,
    ) {}

    public Envelope $envelope {
        get => new Envelope(
            subject: 'Welcome',
            to: $this->user->email,
        );
    }

    public string|View $html {
        get => view('welcome.view.php', user: $this->user);
    }
    
    public array $attachments {
        get => [
            Attachment::fromFilesystem(__DIR__ . '/welcome.pdf')
        ];
    }
}
```

And here is how you'd use it:

```php
use Tempest\Mail\Mailer;
use Tempest\Mail\GenericEmail;
 
final class UserEventHandlers
{
    public function __construct(
        private readonly Mailer $mailer,
    ) {}

    #[EventHandler]
    public function onCreated(UserCreated $userCreated): void
    {
        $this->mailer->send(new WelcomeEmail($userCreated->user));

        $this->success('Done');
    }
}
```

We have built-in support for SMTP, Amazon SES, and Postmark; as well as the ability to add any transport you'd like, as long as there's a Symfony driver for it. Next, we have convenient testing helpers:

```php
public function test_welcome_mail()
{
    $this->mailer
        ->send(new WelcomeEmail($this->user))
        ->assertSentTo($this->user->email)
        ->assertAttached('welcome.pdf');
}
```

And a lot of other niceties you can discover in [the docs](/docs/features/mail).

Finally, we're playing with a handful of ideas for future improvements as well. For example, tagging emails as `#[AsyncEmail]`, which would automatically send them to our async command bus and handle them in the background:

```php
// Work in progress!

#[AsyncEmail]
final class WelcomeEmail implements Email, HasAttachments
{ /* … */ }
```

And there's also an idea to model emails as views, instead of PHP classes:

```php
$mailer->send('welcome.view.php', user: $user);
```

```html welcome.view.php
<!-- Work in progress! -->

<x-email subject="Welcome!" :to="$user->to">
    <h1>Welcome {{ $user->name }}!</h1>
    
    <p>
        Please activate your account by visiting this link: {{ $user->activationLink }}
    </p>
</x-email>
```

Mailing is the first big feature we release after Tempest 1.0. We decided to mark all new features as experimental for a couple of releases. This gives us the opportunity to fix any oversights there might be with the design we came up with. Because, let's be real: we're not perfect, and we rarely write code that's perfect from the get-go. We hope that enough enthusiasts will try out our new mailing component though, and provide us with the feedback we need to make it even better. If you want to know how to do that, then [Discord](/discord) is the place to be!