```html
<!-- x-base.view.php -->

<x-component name="x-base">
    <html lang="en">
        <head>
            <title :if="$title ?? null">{{ $title }} | Tempest</title>
            <title :else>Tempest</title>
            
            <x-slot name="styles" />
        </head>

        <body class="relative font-sans antialiased">
            <x-slot/>
        
            <x-slot name="scripts" />
        </body>
    </html>
</x-component>
```