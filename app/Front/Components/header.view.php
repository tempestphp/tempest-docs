<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;
?>

<x-component name="header">

  <header class="flex flex-wrap gap-4 items-center text-white justify-between max-w-screen-xl mx-auto py-8  w-full px-4">
    <x-tempest-logo />

    <nav class="flex flex-wrap justify-center w-full sm:w-auto gap-4 sm:gap-8 items-center text-white text-[15px] font-light">
<!--      <a href="/blog">Blog</a>-->
      <a href="/console">Console</a>
      <a href="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Documentation</a>
      <a href="https://github.com/tempestphp/tempest-framework" target="_blank" rel="noopener noreferrer"><img src="/img/github.svg" class="hidden sm:inline-block sm:size-[1.5rem]" alt="TempestPHP on Github" /></a>
    </nav>
  </header>
</x-component>