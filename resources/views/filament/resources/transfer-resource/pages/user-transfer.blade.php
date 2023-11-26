<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit">
            Submit
        </x-filament::button>
    </form>
</x-filament::page>