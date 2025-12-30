```html app/Website/x-base.view.php
<!DOCTYPE html>
<html lang="en" class="h-dvh flex flex-col">
  <head>
    <!-- Conditional elements -->
    <title :if="isset($title)">{{ $title }} — Books</title>
    <title :else>Books</title>
    <!-- Built-in Vite integration -->
    <x-vite-tags />
  </head>
  <body class="antialiased flex flex-col grow">
    <x-slot /> <!-- Main slot -->
    <x-slot name="scripts" /> <!-- Named slot -->
  </body>
</html>
```
