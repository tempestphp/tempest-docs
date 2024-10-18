```html
<x-base title="Home">
    <x-post :foreach="$this->posts as $post">
        {!! $post->title !!}

        <span :if="$this->showDate($post)">
            {{ $post->date }}
        </span>
        <span :else>
            -
        </span>
    </x-post>
    <div :forelse>
        <p>It's quite empty here…</p>
    </div>
    
    <x-footer />
</x-base>
```