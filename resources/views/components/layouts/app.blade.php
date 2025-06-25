<x-layouts.app.sidebar :title="$title ?? null" :menuItems="$menuItems">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
