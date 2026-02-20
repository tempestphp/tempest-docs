---
title: Tempest View with source mapping
description: Tempest 3.2 improves View debugging by introducing source maps.
tag: release
author: brent
---

With Tempest 3.2, we've made a significant improvement for debugging view files. For context: Tempest Views are compiled to normal PHP files, and if you were to encounter a runtime error in those compiled files (unknown variables, missing imports, etc.) — in those cases the stack trace used to look something like this:

![](/img/view-source-mapping-before.png)

As you can see, there's little useful information here: it points to the compiled file, the line numbers are messed up as well, and in general you wouldn't know the source of the problem. If you wanted to debug this error, you'd have to open the compiled view and read through a lot of compiled (and frankly, ugly) code. Ever since we switched to our own view parser though, we wanted to fix this issue. Even when a runtime error occurred in a compiled view, we want the stack trace to point to the source file.

And that's exactly what we did: we now keep track of the source file and line numbers while parsing Tempest View files, and from that data, we can resolve the correct stack trace when an error occurs:

![](/img/view-source-mapping-after.png)

This was a crucial feature to make Tempest View truly developer-friendly. Special thanks to [Márk](https://github.com/tempestphp/tempest-framework/pull/1980) for implementing it!