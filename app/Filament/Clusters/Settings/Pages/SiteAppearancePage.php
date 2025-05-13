<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
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

    public function mount(): void
    {
        $this->form->fill([
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'primary_color' => Setting::get('primary_color', '#3b82f6'),
            'secondary_color' => Setting::get('secondary_color', '#64748b'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Branding')
                    ->schema([
                        FileUpload::make('site_logo')
                            ->label('Logo')
                            ->directory('settings')
                            ->image()
                            ->maxSize(2048)
                            ->imagePreviewHeight('100')
                            ->downloadable()
                            ->openable()
                            ->panelAspectRatio('2:1'),

                        FileUpload::make('site_favicon')
                            ->label('Favicon')
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
                            ->hex()
                            ->live()
                            ->helperText('Main brand color used throughout the site'),

                        ColorPicker::make('secondary_color')
                            ->label('Secondary Color')
                            ->hex()
                            ->live()
                            ->helperText('Accent color used for secondary elements'),
                    ])
                    ->columns(2),
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
}
