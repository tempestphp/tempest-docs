<x-component name="x-base">
    <html lang="en">
    <head>
        <title><?= ($this->title ?? null) ? "{$this->title} | Tempest" : "Tempest" ?></title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <x-slot name="styles"/>
        <link href="/main.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=source-code-pro:500" rel="stylesheet" />

        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
    </head>
    <body class="relative font-sans antialiased">

    <x-slot />

    <x-slot name="scripts"></x-slot>
    </body>
    </html>
</x-component>