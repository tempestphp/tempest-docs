```html x-base.view.php
<!DOCTYPE html>
<html lang="en" class="h-dvh flex flex-col">
<head>
	<title :if="isset($title)">{{ $title }} — Bookish</title>
	<title :else>Bookish</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<x-vite-tags />
	<x-slot name="head" />
</head>
<body class="antialiased flex flex-col grow">
	<x-slot />
	<x-slot name="scripts" />
</body>
</html>
```
