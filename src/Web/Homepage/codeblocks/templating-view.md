```html
<x-base :title="$this->seo->title">
    <ul>
        <li :foreach="$this->books as $book">
            {{ $book->title }}

            <span :if="$this->showDate($book)">
                <x-tag>
                    {{ $book->publishedAt }}
                </x-tag>
            </span>
        </li>
    </ul>
</x-base>
```
