---
title: Backward Considerations
author: Brent
description: How to truly get out of the way
---

Tempest's tagline says it will "get out of your way". One of the core principles I'm trying to maintain when designing Tempest is that whenever the framework can find the answer automatically, the user shouldn't be bothered by manually providing it. Of course, we make sure that users still can take control whenever they need to, but the 95% use case should be solved as frictionless as possible.

Ok, that's a nice bunch of words, but what does that actually mean "getting out of the way"? Let me give you a couple of examples.

### Discover everything

> I am wondering what is the type of contract between Tempest and end users. Since the main idea is to "get out of your way" and wire everything via attributes (auto-discovery), what keeps the backward compatibility? 🤔 — [Greg](https://bsky.app/profile/codito.dev/post/3lbcqnn6wu22w)
