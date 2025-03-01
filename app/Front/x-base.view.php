<?php

use App\Front\Meta\MetaType;

?>
<x-component name="x-base">
    <html lang="en">

    <head>
        <title :if="$title ?? null">{{ $title }} | Tempest</title>
        <title :else>Tempest</title>

        <?php
        $metaImageUri = $metaImageUri ?? null;

        if ($metaImageUri === null) {
            $metaType = $meta ?? MetaType::FRAMEWORK;
            $metaImageUri = $metaType->uri();
        }
        ?>

        <meta property="og:image" content="<?= $metaImageUri ?>"/>
        <meta property="twitter:image" content="<?= $metaImageUri ?>"/>
        <meta name="image" content="<?= $metaImageUri ?>"/>
        <meta name="twitter:card" content="summary_large_image"/>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=source-code-pro:500|archivo:700,900" rel="stylesheet"/>
        <link href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" rel="stylesheet"/>
        <link href="/main.css" rel="stylesheet">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">

        <x-slot name="styles"/>
        <x-slot name="head" />
    </head>

    <body class="relative font-sans antialiased">
    <x-slot/>
    <x-slot name="scripts" />
    <script src="https://cdn.jsdelivr.net/npm/@docsearch/js@3"></script>
    </body>

    </html>
</x-component>
