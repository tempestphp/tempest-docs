---
title: Roadmap
---

Tempest is still a work in progress, and we're actively working towards a stable 1.0 release. We keep track of that progress on [GitHub](https://github.com/tempestphp/tempest-framework/milestones).

You're more than welcome to [contribute to Tempest](https://github.com/tempestphp/tempest-framework), and can even work on features in future milestones if anything is of particular interest to you. The best way to get in touch about Tempest development is to [join our Discord server](https://discord.gg/pPhpTGUMPQ).

## Version stability

Tempest is going through several stability stages:

- **Alpha**: during this phase, we'll add new features and refactorings, there will be breaking changes.
- **Beta**: we focus on bug fixing in preparation for the next major release
- **Stable**: the final release

Tempest is currently in the last 1.0 alpha version, the next tagged version will be beta.1, and thus focus on preparing for a stable 1.0 release.

## Experimental features

Given the size of the project, we decided to mark a couple of features as _experimental_. These features might still change after a stable 1.0 release has been tagged. Our goal is to rid all experimental components before Tempest 2.0. Here's the list of experimental features:

- [tempest/view](/main/essentials/views): you can use both [Twig](/main/essentials/views#using-twig) or [Blade](/main/essentials/views#using-blade) as alternatives.
- [The command bus](/main/tempest-in-depth/commands): you can plug in any other command bus if you'd like.
- [Authentication and authorization](/main/tempest-in-depth/auth): the current implementation is very lightweight, and we welcome people to experiment with more complex implementations as third-party packages before committing to a framework-provided solution.
- [ORM](/main/essentials/models): you can use existing ORMs like [Doctrine](https://www.doctrine-project.org/) as an alternative
- [The DateTime component](https://github.com/tempestphp/tempest-framework/tree/main/src/Tempest/DateTime): you can use [Carbon](https://carbon.nesbot.com/docs/) or [Psl](https://github.com/azjezz/psl) as alternatives

Please note that we're committed to making all of these components stable as soon as possible. In order to do so, we will need real-life feedback from the community. By marking these components as experimental, we acknowledge that we probably won't get it right from the get-go, and we want to be clear about that up front.

## Upcoming features

Apart from experimental features, we're also aware that Tempest isn't feature-complete yet. Here's a list of items on our todo-list, feel free to contact us via [GitHub](https://github.com/tempestphp/tempest-framework) or [Discord](https://discord.gg/pPhpTGUMPQ) if you'd like to suggest other features, or want to help out with one of these:

- Internationalization support
- Dedicated support for API development
- Mail support
- HTMX support combined with tempest/view
- Form builder
- Event bus and command bus improvements (transport support, async messaging, event sourcing, …)
- Queuing and messaging components