```html
<!DOCTYPE html>
<html lang="en" class="h-dvh flex flex-col">
<head>
    <title :if="isset($title)">{{ $title }} — Bookish</title>
    <title :else>Bookish</title>
	
    <x-vite-tags />
    
    <x-slot name="head" />
</head>
<body class="antialiased flex flex-col grow">
    <x-slot />
    <x-slot name="scripts" />
</body>
</html>
```
