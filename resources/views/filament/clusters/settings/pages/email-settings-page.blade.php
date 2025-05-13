<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
    </x-filament-panels::form>

    @script
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('mailSettings', () => ({
                    init() {
                        this.$watch('mailDriver', (value) => {
                            const smtpFields = [
                                'mail_host',
                                'mail_port',
                                'mail_username',
                                'mail_password',
                                'mail_encryption'
                            ];

                            smtpFields.forEach(fieldName => {
                                const field = this.$el.querySelector(
                                    `[name="data.${fieldName}"]`);
                                if (field) {
                                    field.closest('.fi-field').style.display =
                                        value === 'smtp' ? 'block' : 'none';
                                }
                            });
                        });
                    }
                }));
            });
        </script>
    @endscript
</x-filament-panels::page>
