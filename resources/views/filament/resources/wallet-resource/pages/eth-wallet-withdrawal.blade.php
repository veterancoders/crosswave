<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-8">
        {{ $this->form }}

        <x-filament::button type="submit">
            Proceed
        </x-filament::button><span> Withdrawal Request approval usually takes 1-2 Business days.</span>

        
    </form>
</x-filament::page>