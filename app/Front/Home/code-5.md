```html
<x-base :title="$this->seo->title">
    <ul>
        <li :foreach="$this->posts as $post">
            {{ $post->title }}
            
            <span :if="$this->showDate($post)">
                <x-tag>
                    {{ $post->date }}
                </x-tag>
            </span>
        </li>
    </ul>
</x-base>
```