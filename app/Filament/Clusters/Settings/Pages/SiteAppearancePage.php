<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\SiteAppearance;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class SiteAppearancePage extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.clusters.settings.pages.site-appearance-page';

    protected static ?string $navigationLabel = 'Appearance';

    protected static ?string $navigationGroup = 'System Configuration';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    private SiteAppearance $settings;

    public function mount(SiteAppearance $settings): void
    {
        $this->settings = $settings;
        $this->form->fill([
            'primary_color' => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
            'logo_path' => $settings->logo_path,
            'favicon_path' => $settings->favicon_path,
            'dark_mode' => $settings->dark_mode,
            'font_family' => $settings->font_family,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Branding')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo_path')
                            ->label('Logo')
                            ->collection('logo_path')
                            ->directory('settings')
                            ->image()
                            ->maxSize(2048)
                            ->imagePreviewHeight('100')
                            ->downloadable()
                            ->openable()
                            ->panelAspectRatio('2:1'),

                        SpatieMediaLibraryFileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->collection('favicon_path')
                            ->directory('settings')
                            ->image()
                            ->maxSize(512)
                            ->imagePreviewHeight('50')
                            ->helperText('Recommended size: 32x32 or 64x64 pixels'),
                    ])
                    ->columns(2),

                Section::make('Colors')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Primary Color')
                            ->rgb()
                            ->live()
                            ->helperText('Main brand color used throughout the site'),

                        ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->rgb()
                            ->live()
                            ->helperText('Accent color used for secondary elements'),

                        Toggle::make('dark_mode')
                            ->label('Enable Dark Mode')
                            ->live(),
                    ])
                    ->columns(2),

                Section::make('Typography')
                    ->schema([
                        TextInput::make('font_family')
                            ->label('Font Family')
                            ->default('Inter')
                            ->helperText('Change the main font family of your site'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Appearance Settings')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check-circle'),

            Action::make('reset')
                ->label('Reset to Defaults')
                ->color('gray')
                ->action('resetSettings')
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to reset all appearance settings to default values?')
                ->icon('heroicon-o-arrow-path'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            DB::transaction(function () use ($data) {
                $this->settings->fill($data);
                $this->settings->save();

                // Update runtime configuration
                config([
                    'filament.dark_mode' => $data['dark_mode'] ?? false,
                ]);
            });

            Notification::make()
                ->title('Appearance settings saved successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to save appearance settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetSettings(): void
    {
        try {
            DB::transaction(function () {
                $defaults = (new SiteAppearance())->toArray();
                $this->settings->fill($defaults);
                $this->settings->save();
                $this->form->fill($defaults);
            });

            Notification::make()
                ->title('Appearance settings reset to defaults')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to reset settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
