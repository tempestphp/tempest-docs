<x-component name="x-base">
  <html lang="en" class="h-dvh flex flex-col">
  <head>
    <title :if="isset($fullTitle)">{{ $fullTitle }}</title>
    <title :elseif="isset($title)">{{ $title }} — Tempest</title>
    <title :else>Tempest</title>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Current meta image -->
    <?php
      $metaImageUri = $metaImageUri ?? null;

      if ($metaImageUri === null) {
          $metaType = $meta ?? \App\Front\Meta\MetaType::FRAMEWORK;
          $metaImageUri = $metaType->uri();
      }
    ?>

    <!-- Social -->
    <meta property="og:image" content="<?= $metaImageUri ?>" />
    <meta property="twitter:image" content="<?= $metaImageUri ?>" />
    <meta name="image" content="<?= $metaImageUri ?>" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
    <link rel="manifest" href="/favicon/site.webmanifest" />

    <!-- Vite tags -->
    <x-vite-tags />

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
        document.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: isDark() }}))
      }

			function toggleDarkMode() {
				applyTheme(isDark() ? 'light' : 'dark')
			}

      applyTheme()
    </script>

    <x-slot name="head" />
  </head>
  <body :class="($bodyClass ?? '') . ' relative antialiased flex flex-col grow'">
    <div class="absolute pointer-events-none inset-0 bg-repeat" style="background-image: url(/noise.svg)">
			<div id="command-palette"></div>
		</div>
    <x-header :stargazers="$stargazers" />
    <x-slot />
    <x-footer />
    <x-slot name="scripts" />
  </body>
  </html>
</x-component>
