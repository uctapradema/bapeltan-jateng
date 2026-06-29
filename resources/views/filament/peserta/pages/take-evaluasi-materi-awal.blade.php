<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::button type="submit">
            Simpan Jawaban
        </x-filament::button>
    </form>
</x-filament::page>
