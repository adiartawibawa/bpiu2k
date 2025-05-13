<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GeneralSettingsPage extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.clusters.settings.pages.general-settings-page';

    protected static ?string $navigationLabel = 'General Settings';

    protected static ?string $navigationGroup = 'System Configuration';

    protected static ?int $navigationSort = 0;

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_description' => Setting::get('site_description'),
            'timezone' => Setting::get('timezone', config('app.timezone')),
            'maintenance_mode' => (bool) Setting::get('maintenance_mode', false),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site Information')
                    ->schema([
                        TextInput::make('site_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('site_description')
                            ->maxLength(255),

                        TextInput::make('timezone')
                            ->required()
                            ->hintIcon('heroicon-o-globe-alt')
                            ->hintIconTooltip('Server time: ' . now()->format('Y-m-d H:i:s'))
                            ->live(),
                    ])->columns(1),

                Section::make('System')
                    ->schema([
                        Toggle::make('maintenance_mode')
                            ->label('Enable Maintenance Mode')
                            ->helperText('When enabled, only administrators can access the site'),
                    ]),
            ])
            ->statePath('data')
            ->model(Setting::class);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save')
                ->color('primary')->icon('heroicon-o-check-circle'),

            Action::make('reset')
                ->label('Reset Default')
                ->color('danger')
                ->icon('heroicon-o-arrow-path')
                ->action('resetSettings')
                ->requiresConfirmation()
                ->modalDescription('Yakin ingin mengembalikan ke pengaturan default?'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            DB::transaction(function () use ($data) {
                foreach ($data as $key => $value) {
                    Setting::set($key, $value, static::$cluster);
                }
            });

            Notification::make()
                ->title('Pengaturan berhasil disimpan')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal menyimpan pengaturan')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetSettings(): void
    {
        try {
            $defaults = $this->getDefaultSettings();

            DB::transaction(function () use ($defaults) {
                foreach ($defaults as $key => $value) {
                    Setting::set($key, $value, static::$cluster);
                }
            });

            $this->form->fill($defaults);

            Notification::make()
                ->title('Berhasil')
                ->body('Pengaturan telah direset ke default')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Gagal mereset pengaturan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getDefaultSettings(): array
    {
        return [
            'site_name' => config('app.name'),
            'site_logo' => null,
            'primary_color' => '#3b82f6',
            // Tambahkan default values lainnya
        ];
    }
}
