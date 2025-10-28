---
title: "RE: the journey this far" 
description: Replying to someone trying out Tempest 
author: brent
tag: thoughts
---

I recently stumbled upon a blogpost by Vyygir describing their first steps with Tempest, and I loved reading it. There were some good things, some bad things, and it's this kind of real-life feedback that is invaluable for Tempest to grow. I hope more people will do it in the future. Reading through it, I had some thoughts that I think might be a valuable addition, so I figured I'd do a "reply-style" blog post. You can read the [original one here](https://starle.sh/tempest-the-journey-thus-far), but I'll quote the parts I'm replying to over here as well.

> Let's start positively, purely so I can demonstrate that I'm not here to shit on someone's hard work.

Thank you! Appreciate it. What's especially good is that some of the design goals we set out from the very start are acknowledged by so many people who try out Tempest. It's great validation that there is indeed a need for it.

> There. I've done the positive bits. Now I can <s>be negative</s> provide my thoughts on my own experiences without feeling bad.

Don't feel bad, it's nice to hear good things, but even better what can be improved!

## The Structure

> I know this doesn't sound very open-minded but the build-your-whatever mindset that exists with Tempest, I feel, presents the same problem that I currently have with React: if you don't know how to actually build software that can scale well, then you're going to build something painfully unmaintainable that you'll hate in a few months. […] Shipping with some expected structures, even if it's a templated setup option, feels as though it'd offer more guidance and denote a structure from the offset, with expectancy.

I actually agree with Vyygir. Starting from a completely empty src directory can feel disorienting. It's actually on our roadmap to have two or three scaffold projects, which you can choose from based on your preference. We haven't gotten to that stage yet because, honestly, we're still trying to figure it out ourselves. Maybe we should stop using that excuse and just build _something_. [Noted](https://github.com/tempestphp/tempest-framework/issues/1665).

That being said, I've experimented a lot, and I've refactored a lot. The one thing that sets Tempest apart from other frameworks is that it truly *does not care* about how your project is structured, and thus also doesn't care about refactorings. You can move everything around, and everything will keep working (given that you clear discovery caches in production). So even if you run into issues down the line, refactoring your project shouldn't be hard.

## Discovery

Moving on to Vyygir's thoughts about discovery:

> Let me start with this: I love the idea of Discovery. Composer takes us part-way there but Tempest's Discovery implementation absolutely nailed the execution.

Thank you! <small>Bracing for impact</small>

> That being said... I definitely missed the scope of what Discovery can do.

Ah, yes. This highlights a crucial drawback in our documentation. I did write a blog post about discovery to [explain it more in depth](https://tempestphp.com.test/blog/discovery-explained), but it's rather hidden. Our docs currently assume too much that people already understand the concept of discovery, and this might be confusing to newcomers (Vyygir definitely isn't the only one). Also, [noted](https://github.com/tempestphp/tempest-framework/issues/1666).

However, there was one critique about discovery that I didn't fully understand:

> I had an idea that I'd use Discovery to find my entries in ./entries/*.md and then load them into a repository. I even tried it. But the major problem I was hitting was that my EntryRepository wasn't actually in the container at the point of discovery which, when you read through the bootstrap steps actually makes a lot of sense.

The way Vyygir describes it should indeed work, and I'm curious to learn why it didn't. It's actually how discovery works at its core: it scans files (PHP files or any you'd like) and registers the result in some kind of dependency. Usually it's a singleton config, but it can be anything that is available in the container. 

As a sidenote: Vyygir mentions that he let go of the idea after seeing the [source code of my blog](https://github.com/brendt/stitcher.io/blob/main/app/Blog/BlogPostRepository.php#L75) (where I do a runtime filescan on one directory instead of leveraging discovery). A good rule of thumb is to rely on discovery when file locations are unknown: discovery will be scanning your whole project and relevant vendor sources, and your specific discovery classes that interact with that scanning cycle. If you already know which folder will contain all relevant files (a content directory with markdown files, for example), then you're better off just directly interacting with that folder instead of relying on discovery.  

Nevertheless, discovery should technically work for Vyygir's use case (up to you whether you want to use it or not). Maybe ha was running into an underlying issue, maybe something else was at play. Anyway, Vyygir, if you're reading this let me know, and I'm happy to help you debug.

## The Structure: Again but Different

> I had to make a last minute revision to the structure when I realised that DiscoveryLocation was not pleased with me trying to use a full cache strategy on views whilst having them outside of `src`.

Ok so, Vyygir wants their view files to live outside of `src`. While I personally disagree with this approach (IMO view files are an equally important part of a project's "source" as anything else), I also don't mind people who want to do it differently. That's the whole point of Tempest's flexibility: do it your way.

Vyygir ran into an issue: view files weren't discovered outside of `src`. This is, again, something [we should document](https://github.com/tempestphp/tempest-framework/issues/1667).

The solution is actually pretty simple: Tempest will discover any PSR-4 valid namespace. So if you want your view files to live outside of `src` or `app` or whatever, just add a namespace for it in composer.json:

```json
"autoload": {
    "psr-4": {
        "App\\": "src/",
        "Views\\": "views/"
    },
}
```

Your view files themselves don't need a namespace, mind you; this namespace is only here to tell Tempest that `views/` is a directory it should scan. Of course, if you happened to add a class in the `Views` namespace (like, for example, a [custom view object](/2.x/essentials/views#using-dedicated-view-objects)), then be my guest!

## What's wrong with abstractions?

> I get the usage of interfaces in the degree they are. But my god, sometimes, finding a reference is painful.
>
> I feel like nearly everything is pointing to a generic upper layer that only vaguely implies what might exist when you're trying to understand how a segment of functionality works to, you know, implement something. And, because of how new Tempest is, not everything is fully documented yet. And the public use cases are slim pickings.

I get it. The combination of interface + trait isn't the most ideal, and you might be tempted to ask "why not use an abstract class instead?" I have a philosophy on why I prefer interfaces over abstract classes, and I've written and spoken about it many times before:

- [https://stitcher.io/blog/extends-vs-implements](https://stitcher.io/blog/extends-vs-implements)
- [https://stitcher.io/blog/is-a-or-acts-as](https://stitcher.io/blog/is-a-or-acts-as)
- [https://www.youtube.com/watch?v=HK9W5A-Doxc](https://www.youtube.com/watch?v=HK9W5A-Doxc)

The tl;dr is that my view on inheritance is inspired by modern languages like Rust and Go, instead of following the "classic C++-style inheritance" we've become used to over the past decades.

PHP being PHP though, there are some drawbacks. More specifically that you need both the interface and trait, which introduces some complexity. That being said, I still believe that this approach is better than a classic inheritance tree, and I wish — oh how I wish — that PHP would solve it. Again, I've talked and written about this before, and even made a suggestion to internals:

- [https://www.youtube.com/watch?v=lXsbFXYwxWU](https://www.youtube.com/watch?v=lXsbFXYwxWU)
- [https://externals.io/message/125305#125305](https://externals.io/message/125305#125305)

Unfortunately, we haven't gotten a proper solution yet. My hope is that interface default methods will come back on the table, and the problem that Vyygir describes will be solved.

I would really encourage you to read up on the topic though, because as soon as it clicks, I find I almost never want to rely on abstract classes again, and my code becomes a lot more simple.

## View Syntax

>I'm going to be honest, I just struggle to parse this mentally in comparison to something like Twig. This is almost definitely a problem unique to me (because my brain don't do the working right). I just wanted to mention it though.

That's fair. That's why we have [built-in support for Twig and Blade](/2.x/essentials/views#using-other-engines) as well. We're actively working on a PhpStorm plugin for Tempest View, which will make life easier. 

## `DateTime` (no, not that one)

> Oh. Tempest's DateTime uses... a whole other formatting structure that I'm totally unfamiliar with. Sigh. Do I want to spend the time to figure this out?

Ok so, story time. We wanted a DateTime library that was more powerful than PHP's built-in datetime, so that you could more easily work with date time objects. Stuff like adding or subtracting days, an easier interface to create datetime objects, … (you can read about it [here](https://tempestphp.com/2.x/features/datetime)).

There were two options: [Carbon](https://carbon.nesbot.com/docs/) or the [PSL](https://github.com/azjezz/psl) implementation. We went with the second one (and added a wrapper for it within the framework). 

IMO, we've made a mistake. Here's what I dislike about:

- We have `Tempest\DateTime\DateTime`, which has a naming collision with `\DateTime`. I cannot count the number of times where I accidentally imported the wrong library
- Having used Carbon for years, it's really annoying getting used to another API, eg: `plusDay()` instead of `addDay()`, etc.
- The date format. Oh how I dislike the date format. Just to clarify, PSL's implementation relies on [the standardized ICU spec](https://unicode-org.github.io/icu/userguide/format_parse/datetime/#formatting-dates-and-times), which in fact is more widely used than PHP's "built-in" datetime formatting. For example, with Tempest's implementation you write `$dateTime->format('yyyy-MM-dd HH:mm:ss')` instead of `$dateTime->format('Y-m-d H:i:s')`. You could argue that this just requires some "getting used to", but I, for one, haven't gotten used to it, so I can imagine how frustrating it is for newcomers. 

That being said, we should also note that using Tempest's implementation is totally opt-in. You can choose to use either PHP's built-in `\DateTime`, or `Carbon` instead. However, how to do so is also undocumented. Again, [noted](https://github.com/tempestphp/tempest-framework/issues/1668).

## In conclusion

I'm so thankful for Vyygir taking the time to write down their thoughts. I'm also happy that most of their pain points come down to improving the docs, more than anything else; and this feedback will make Tempest better. Thank you!

