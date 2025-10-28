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

> I know this doesn't sound very open-minded but the build-your-whatever mindset that exists with Tempest, I feel, presents the same problem that I currently have with React: if you don't know how to actually build software that can scale well, then you're going to build something painfully unmaintainable that you'll hate in a few months. […] Shipping with some expected structures, even if it's a templated setup option, feels as though it'd offer more guidance and denote a structure from the offset, with expectancy.

I actually agree with Vyygir. Starting from a completely empty src directory can feel disorienting. It's actually on our roadmap to have two or three scaffold projects, which you can choose from based on your preference. We haven't gotten to that stage yet because, honestly, we're still trying to figure it out ourselves. Maybe we should stop using that excuse and just build _something_. [Noted](https://github.com/tempestphp/tempest-framework/issues/1665).

That being said, I've experimented a lot, and I've refactored a lot. The one thing that sets Tempest apart from other frameworks is that it truly *does not care* about how your project is structured, and thus also doesn't care about refactorings. You can move everything around, and everything will keep working (given that you clear discovery caches in production). So even if you run into issues down the line, refactoring your project shouldn't be hard.

Moving on to Vyygir's thoughts about discovery:

> Let me start with this: I love the idea of Discovery. Composer takes us part-way there but Tempest's Discovery implementation absolutely nailed the execution.

Thank you! <small>Bracing for impact</small>

> That being said... I definitely missed the scope of what Discovery can do.

Ah, yes. This highlights a crucial drawback in our documentation. I did write a blog post about discovery to [explain it more in depth](https://tempestphp.com.test/blog/discovery-explained), but it's rather hidden. Our docs currently assume too much that people already understand the concept of discovery, and this might be confusing to newcomers (Vyygir definitely isn't the only one). Also, [noted](https://github.com/tempestphp/tempest-framework/issues/1666).

However, there was one critique about discovery that I didn't fully understand:

> I had an idea that I'd use Discovery to find my entries in ./entries/*.md and then load them into a repository. I even tried it. But the major problem I was hitting was that my EntryRepository wasn't actually in the container at the point of discovery which, when you read through the bootstrap steps actually makes a lot of sense.

The way Vyygir describes it should indeed work, and I'm curious to learn why it didn't. It's actually how discovery works: it scans files (PHP files or any you'd like), and registers the result in some kind of dependency. Usually it's a singleton config, but it can be anything that available in the container. Vyygir actually mentions that he let go of the idea after seeing the [source code of my blog](https://github.com/brendt/stitcher.io/blob/main/app/Blog/BlogPostRepository.php#L75) (where I do a runtime filescan instead of leveraging discovery), but here I am thinking "why didn't I consider using Discovery for this???"

All of that to say: discovery should work for this use case (up to you whether you want to use it or not, but it should work). Maybe Vyygir was running into an underlying issue, maybe something else was at play. Anyway, Vyygir, if you're reading this let me know and I'm happy to help you debug.

> I had to make a last minute revision to the structure when I realised that DiscoveryLocation was not pleased with me trying to use a full cache strategy on views whilst having them outside of `src`.

Ok so, Vyygir wants his view files to live outside of `src`. While I personally disagree with this design (IMO view files are an equally important part of a project's "source" as anything else), I also don't mind people who want to do it differently. That's the whole point of Tempest's design: do it your way.

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

> I get the usage of interfaces in the degree they are. But my god, sometimes, finding a reference is painful.
>
> I feel like nearly everything is pointing to a generic upper layer that only vaguely implies what might exist when you're trying to understand how a segment of functionality works to, you know, implement something. And, because of how new Tempest is, not everything is fully documented yet. And the public use cases are slim pickings.

Ok so. I get it. The combination of interface + trait isn't the most ideal. I have a philosophy on why I prefer interfaces to abstract classes, and I've written and spoken about it many times before:

- [https://stitcher.io/blog/extends-vs-implements](https://stitcher.io/blog/extends-vs-implements)
- [https://stitcher.io/blog/is-a-or-acts-as](https://stitcher.io/blog/is-a-or-acts-as)
- [https://www.youtube.com/watch?v=HK9W5A-Doxc](https://www.youtube.com/watch?v=HK9W5A-Doxc)

The tl;dr is that my view on inheritance is more inspired by modern languages like Rust, instead of following the "classic C++-style inheritance" we've become used to over the past decades.

Within PHP, it's not ideal though. Specifically, because you need both the interface and trait, which introduces some complexity. That being said, I still believe that this approach is better than a classic inheritance tree, and I wish — oh how I wish — that PHP would solve it. Again, I've talked and written about this before, and even made a suggestion to internals:

- [https://www.youtube.com/watch?v=lXsbFXYwxWU](https://www.youtube.com/watch?v=lXsbFXYwxWU)
- [https://externals.io/message/125305#125305](https://externals.io/message/125305#125305)

Unfortunately, we haven't gotten a proper solution yet. My hope is that interface default methods will come back on the table, and the problem that Vyygir describes will be solved.