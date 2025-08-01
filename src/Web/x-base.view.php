<?php
use App\Web\Meta\MetaType;

?>

<html lang="en" class="flex flex-col h-dvh scroll-smooth">
<head>
    <!-- Meta title -->
    <?php

    $title = match (true) {
        isset($fullTitle) => $fullTitle,
        isset($title) => "{$title} — Tempest",
        default => 'Tempest',
    }; ?>

    <title>{{ $title }}</title>

    <link :if="$meta['canonical'] ?? null" rel="canonical" :href="$meta['canonical']" />
    <link rel="alternate" type="application/rss+xml" title="Tempest RSS" href="https://tempestphp.com/rss" />

    <meta name="title" :content="$title">
    <meta name="twitter:title" :content="$title">
    <meta property="og:title" :content="$title">
    <meta itemprop="name" :content="$title">

    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Meta description -->
    <?php $description = match (true) {
        isset($description) => $description,
        default => 'Tempest is a modern framework designed to enable developers to write as little framework-specific code as possible, so that they can focus on application code instead.',
    }; ?>

    <meta name="description" :content="$description">
    <meta name="twitter:description" :content="$description">
    <meta property="og:description" :content="$description">
    <meta itemprop="description" :content="$description">

    <!-- Meta image -->
    <?php $metaImageUri ??= ($metaType ?? \App\Web\Meta\MetaType::HOME)->uri(); ?>
    <meta property="og:image" :content="$metaImageUri"/>
    <meta property="twitter:image" :content="$metaImageUri"/>
    <meta name="image" :content="$metaImageUri"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta property="og:type" content="article">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png"/>
    <link rel="manifest" href="/favicon/site.webmanifest"/>

    <!-- Vite tags -->
    <x-vite-tags/>

    <!-- Dark mode -->
    <script>
        function isDark() {
            return localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        }

        function applyTheme(theme = undefined) {
            if (theme) {
                localStorage.theme = theme
            }

            document.documentElement.classList.toggle('dark', isDark())
            document.dispatchEvent(new CustomEvent('theme-changed', {detail: {isDark: isDark()}}))
        }

        function toggleDarkMode() {
            applyTheme(isDark() ? 'light' : 'dark')
        }

        applyTheme()
    </script>

    <x-slot name="head"/>
</head>
<body :class="trim($bodyClass ?? '')" class="relative antialiased flex flex-col grow selection:bg-(--ui-primary)/20 selection:text-(--ui-primary) font-sans text-(--ui-text) bg-(--ui-bg) scheme-light dark:scheme-dark !overflow-visible !pr-0">
<div class="absolute inset-0 bg-repeat pointer-events-none" style="background-image: url(/noise.svg)">
    <div id="command-palette"></div>
</div>
<x-header :stargazers="$this->stargazers"/>
<x-slot/>
<x-footer/>
<x-slot name="scripts"/>
<x-template :if="$copyCodeBlocks ?? false">
    <template id="copy-template">
        <button class="absolute group top-2 right-2 size-7 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition text-(--ui-text-dimmed) bg-(--ui-bg-muted) rounded border border-(--ui-border) hover:text-(--ui-text-highlighted)">
            <x-icon name="tabler:copy" class="absolute size-5"/>
            <x-icon name="tabler:copy-check-filled" class="absolute opacity-0 group-[[data-copied]]:opacity-100 size-5 transition"/>
        </button>
    </template>
</x-template>
</body>
</html>
