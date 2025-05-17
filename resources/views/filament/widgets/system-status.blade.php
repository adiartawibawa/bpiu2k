<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            System Status
        </x-slot>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->getStatusData() as $label => $value)
                <div
                    class="p-4 bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $label }}
                    </div>
                    <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
