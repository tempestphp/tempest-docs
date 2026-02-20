---
title: Tempest View with source mapping
description: Tempest 3.2 improves View debugging by introducing source maps.
tag: release
author: brent
---

With Tempest 3.2, we've made a significant improvement for debugging view files. Let me set the scene: Tempest Views are compiled to normal PHP files, and if you were to encounter a runtime error in those compiled files, the stack trace would look something like this:

![](/img/view-source-mapping-before.png)

As you can see, there's little useful information in this stack trace; the line numbers are messed up as well. If you wanted to debug this error, you'd have to open the compiled view and read through a lot of compiled (and frankly, ugly) code. Ever since we switched to our own view lexer, we wanted to fix this issue; even when a runtime error occurred in a compiled view, we wanted the stack trace to point to the source file.

And that's exactly what we did!

![](/img/view-source-mapping-after.png)
