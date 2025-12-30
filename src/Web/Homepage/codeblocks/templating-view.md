```html src/Books/index.view.php
<x-base :title="$this->seo->title">
  <ul>
    <li :foreach="$this->books as $book">
      <!-- Title -->
      <span>{{ $book->title }}</span>

      <!-- Metadata -->
      <span :if="$this->showDate($book)">
        <x-badge variant="outline">
          {{ $book->publishedAt }}
        </x-badge>
      </span>
    </li>
  </ul>
</x-base>
```
