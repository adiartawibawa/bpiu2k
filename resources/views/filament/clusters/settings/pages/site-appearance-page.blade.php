<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
    </x-filament-panels::form>

    @if ($logo = $this->data['site_logo'] ?? null)
        <div
            class="p-4 mt-6 bg-white shadow-sm fi-section rounded-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="mb-4 text-lg font-medium fi-section-header-heading text-gray-950 dark:text-white">
                Current Branding Preview
            </h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Logo</h4>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $logo) }}" alt="Current Logo" class="object-contain h-16">
                        <a href="{{ asset('storage/' . $logo) }}" target="_blank"
                            class="text-sm font-medium text-primary-600 hover:text-primary-500 hover:underline dark:text-primary-500 dark:hover:text-primary-400">
                            View Full Size
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Color Scheme</h4>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 border border-gray-200 rounded-full dark:border-gray-700"
                                style="background-color: {{ $this->data['primary_color'] ?? '#3b82f6' }}"></div>
                            <span>Primary</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 border border-gray-200 rounded-full dark:border-gray-700"
                                style="background-color: {{ $this->data['secondary_color'] ?? '#64748b' }}"></div>
                            <span>Secondary</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('colorPreview', () => ({
                    primaryColor: @js($this->data['primary_color'] ?? '#3b82f6'),
                    secondaryColor: @js($this->data['secondary_color'] ?? '#64748b'),

                    init() {
                        this.$watch('primaryColor', (value) => {
                            if (value) {
                                document.dispatchEvent(new CustomEvent('primary-color-updated', {
                                    detail: {
                                        color: value
                                    }
                                }));
                            }
                        });

                        this.$watch('secondaryColor', (value) => {
                            if (value) {
                                document.dispatchEvent(new CustomEvent('secondary-color-updated', {
                                    detail: {
                                        color: value
                                    }
                                }));
                            }
                        });
                    }
                }));
            });
        </script>
    @endscript
</x-filament-panels::page>
