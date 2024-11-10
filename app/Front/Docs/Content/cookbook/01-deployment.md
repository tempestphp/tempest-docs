---
title: Deployment strategies
---

Tempest applications running on different platforms.

## PHP's built-in server.

Tempest contains PHP's built-in server. You can use this to access your application. This is usually used during development, but is also sufficient for smaller applications.

```console
~ ./tempest

<h2>General</h2>
 <strong><em>serve</strong></em> [<em>host</em>='localhost'] [<em>port</em>=8000] [<em>publicDir</em>='public/']
```

### Reverse proxy in front of the built-in server

#### Apache HTTP Server (httpd) / mod_proxy

```text
ProxyPreserveHost On
ProxyPass / http://localhost:8000/
ProxyPassReverse / http://localhost:8000/
```

## PublicDIR-Rewriting (DocumentRoot)

Sometimes you need to deploy your application in an environment where you cannot easily change the server configuration or only the document root of the domain.

#### Apache HTTP Server (httpd) / mod_rewrite: .htaccess

```text
{:hl-comment:# public/.htaccess:}
<IfModule mod_rewrite.c>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

</IfModule>
```
