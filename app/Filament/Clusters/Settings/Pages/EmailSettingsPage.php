<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EmailSettingsPage extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.clusters.settings.pages.email-settings-page';

    protected static ?string $navigationLabel = 'Email Settings';

    protected static ?string $navigationGroup = 'System Configuration';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'mail_mailer' => Setting::get('mail_mailer', config('mail.default')),
            'mail_host' => Setting::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port' => Setting::get('mail_port', config('mail.mailers.smtp.port')),
            'mail_username' => Setting::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_password' => Setting::get('mail_password'),
            'mail_encryption' => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')),
            'mail_test_address' => Setting::get('mail_test_address'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sender Information')
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->email()
                            ->required()
                            ->label('From Email Address'),

                        TextInput::make('mail_from_name')
                            ->required()
                            ->label('From Name'),
                    ])->columns(2),

                Section::make('Mail Configuration')
                    ->schema([
                        Select::make('mail_mailer')
                            ->label('Mail Driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'postmark' => 'Postmark',
                            ])
                            ->required()
                            ->live(),

                        TextInput::make('mail_host')
                            ->label('SMTP Host')
                            ->required(fn($get) => $get('mail_mailer') === 'smtp'),

                        TextInput::make('mail_port')
                            ->label('SMTP Port')
                            ->numeric()
                            ->required(fn($get) => $get('mail_mailer') === 'smtp'),

                        TextInput::make('mail_username')
                            ->label('SMTP Username'),

                        TextInput::make('mail_password')
                            ->label('SMTP Password')
                            ->password(),

                        Select::make('mail_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                '' => 'None',
                            ]),
                    ])->columns(2),

                Section::make('Test Email')
                    ->schema([
                        TextInput::make('mail_test_address')
                            ->label('Test Email Address')
                            ->email(),

                        Toggle::make('send_test_email')
                            ->label('Send Test Email')
                            ->hidden(),
                    ]),
            ])
            ->statePath('data');
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

            Action::make('sendTestEmail')
                ->label('Send Test Email')
                ->color('gray')
                ->icon('heroicon-o-envelope')
                ->action('sendTestEmail')
                ->hidden(fn() => empty($this->data['mail_test_address'])),
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

    public function sendTestEmail(): void
    {
        // Implement your test email sending logic here
        $this->notify('success', 'Test email sent successfully');
    }
}
