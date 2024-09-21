<x-component name="x-button">
    <a class="
        font-bold
        p-4 py-2
        border-2
        border-b-4 hover:border-b-2
        rounded border-white
    " href="<?= $this->uri ?? '#' ?>">
        <x-slot />
    </a>
</x-component>