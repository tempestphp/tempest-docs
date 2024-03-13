---
title: Test
---

Tempest is a PHP MVC framework that gets out of your way. Its design philosophy is that developers should write as little framework-related code as possible, so that they can focus on application code instead.


```php
final <hljs keyword>readonly</hljs> class BlogPostController
{
    #[<hljs type>Get</hljs>(<hljs value>'/blog'</hljs>)]
    public function index(): <hljs type>View</hljs>
    { /* … */ }
    
    #[<hljs type>Get</hljs>(<hljs value>'/blog/{post}'</hljs>)]
    public function show(<hljs type>Post</hljs> $post): <hljs type>Response</hljs>
    { /* … */ }
}
```
