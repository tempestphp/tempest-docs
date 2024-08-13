<x-component name="x-base">
    <html lang="en">
    <head>
        <title><?= ($this->title ?? null) ? "{$this->title} | Tempest" : "Tempest" ?></title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <x-slot name="styles"/>
        <link href="/main.css" rel="stylesheet">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
    </head>
    <body class="relative font-sans antialiased">

    <div class="px-4 text-center py-4 bg-[#4f95d1] font-bold text-white w-full z-[99] mb-4 flex items-center gap-2 justify-center">
        <img src="/favicon/favicon-32x32.png" alt="favicon" class="h-[20px] hidden md:inline-block">
        <span>
        Tempest is still a <span class="hl-attribute text-white">work in progress</span>. Visit our <a href="https://github.com/tempestphp/tempest-framework/issues" class="underline hover:no-underline">GitHub</a> or
        <a href="https://discord.gg/pPhpTGUMPQ" class="underline hover:no-underline">Discord</a>
    </span>
    </div>

    <x-slot />

    <x-slot name="scripts"></x-slot>
    </body>
    </html>
</x-component>