---
title: OAuth in Tempest 2.2
description: Tempest 2.2 gets a new OAuth integration which makes authentication super simple
author: brent
tag: release
---

Authentication is a challenging problem to solve. It's not just about logging a user in and session management, it's also about allowing them to manage their profile, email confirmation and password reset flows, custom authentication forms, 2FA, and what not. Ever since the start of Tempest, we've tried a number of approaches to have a built-in authentication layer that ships with the framework, and every time the solution felt suboptimal.

There is one big shortcut when it comes to authentication, though: outsource it to others. In other words: OAuth. Everything account-related can be managed by providers like Google, Meta, Apple, Discord, Slack, Microsoft, etc. All the while the implementation on our side stays incredibly simple. With the newest Tempest 2.2 release, we've added a firm foundation for OAuth support, backed by the incredible work done by the [PHP League](https://oauth2-client.thephpleague.com/). Here's how it works.

Tempest comes with support for many OAuth providers (thanks to the PHP League, again):

- [**GitHub**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/GitHubOAuthConfig.php)
- [**Google**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/GoogleOAuthConfig.php)
- [**Facebook**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/FacebookOAuthConfig.php)
- [**Discord**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/DiscordOAuthConfig.php)
- [**Instagram**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/InstagramOAuthConfig.php)
- [**LinkedIn**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/LinkedInOAuthConfig.php)
- [**Microsoft**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/MicrosoftOAuthConfig.php)
- [**Slack**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/SlackOAuthConfig.php)
- [**Apple**](https://github.com/tempestphp/tempest-framework/blob/main/packages/auth/src/OAuth/Config/AppleOAuthConfig.php)
- Any other OAuth platform by using {b`Tempest\Auth\OAuth\Config\GenericOAuthConfig`}.

Whatever OAuth providers you want to support, it's as easy as making a config file for them like so:

```php app/Auth/github.config.php
use Tempest\Auth\OAuth\Config\GitHubOAuthConfig;

return new GitHubOAuthConfig(
    tag: 'github',
    clientId: env('GITHUB_CLIENT_ID'),
    clientSecret: env('GITHUB_CLIENT_SECRET'),
    redirectTo: [GitHubAuthController::class, 'handleCallback'],
    scopes: ['user:email'],
);
```

```php app/Auth/discord.config.php
use Tempest\Auth\OAuth\Config\DiscordOAuthConfig;

return new DiscordOAuthConfig(
    tag: 'discord',
    clientId: env('DISCORD_CLIENT_ID'),
    clientSecret: env('DISCORD_CLIENT_SECRET'),
    redirectTo: [DiscordAuthController::class, 'callback'],
);
```

Now we're ready to go. Generating a login link can be done by using the {b`Tempest\Auth\OAuth\OAuthClient`} interface:

```php
namespace App\Auth;

use Tempest\Auth\OAuth\OAuthClient;
use Tempest\Container\Tag;
use Tempest\Router\Get;

final readonly class DiscordAuthController
{
    public function __construct(
        #[Tag('discord')] 
        private OAuthClient $oauth,
    ) {}

    #[Get('/auth/discord')]
    public function redirect(): Redirect
    {
        return $this->oauth->createRedirect();
    }
    
    // …
}
```

Note how we're using [tagged singletons](/2.x/essentials/container#tagged-singletons) to inject our {b`Tempest\Auth\OAuth\OAuthClient`} instance. These tags come from the provider-specific configurations, and you can have as many different OAuth clients as you'd like. Finally, after a user was redirected and has authenticated with the OAuth provider, they will end up in the callback action, where we can authenticate the user on our side:

```php
namespace App\Auth;

use Tempest\Auth\Authentication\Authenticatable;
use Tempest\Auth\OAuth\OAuthClient;
use Tempest\Auth\OAuth\OAuthUser;
use Tempest\Container\Tag;
use Tempest\Router\Get;

final readonly class DiscordAuthController
{
    public function __construct(
        #[Tag('discord')] 
        private OAuthClient $oauth,
    ) {}
    
    #[Get('/auth/discord')]
    public function redirect(): Redirect
    {
        return $this->oauth->createRedirect();
    }
    
    #[Get('/auth/discord/callback')]
    public function callback(Request $request): Redirect
    {
        $this->oauth->authenticate(
            $request,
            function (OAuthUser $user): Authenticatable {
                return query(User::class)->updateOrCreate([
                    'email' => $user->email,
                ], [
                    'discord_id' => $user->id,
                    'username' => $user->nickname,
                ]);
            }
        )

        return new Redirect('/');
    }
}
```

As you can see, there's still a little bit of manual work involved within the OAuth callback action. That's because Tempest doesn't make any assumptions on how "users" are modeled within your project and thus you'll have to create or store those user credentials somewhere yourself. However, we also acknowledge that some kind of "default flow" would be useful for projects that just need a simple OAuth login with a range of providers. That's why we're now working on adding an OAuth installer: it will prompt you which providers to add in your project, prepare all config objects and controllers for you, and will assume you're using our built-in [user integration](/2.x/features/authentication).

All in all, I think this is a very solid base to build upon. You can read more about using Tempest's OAuth integration in the [docs](/2.x/features/oauth), and make sure to [join our Discord](/discord) if you want to stay in touch!