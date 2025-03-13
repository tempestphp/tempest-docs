<?php

use Tempest\Vite\BuildConfig;
use Tempest\Vite\ViteConfig;

return new ViteConfig(
    build: new BuildConfig(entrypoints: [
        'src/Web/main.css',
        'src/Web/Homepage/rain.ts',
        'src/Web/Homepage/leaves.ts',
        'src/Web/CommandPalette/command-palette.ts',
    ]),
);
