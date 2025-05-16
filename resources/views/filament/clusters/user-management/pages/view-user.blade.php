<x-filament-panels::page>
    {{-- Profile Header Section --}}
    <section aria-labelledby="profile-heading">
        <x-filament::card class="overflow-hidden !p-0">
            <header class="px-6 py-4 border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                <h2 id="profile-heading" class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    {{ __('User Profile Overview') }}
                </h2>
            </header>

            <div class="p-6">
                {{ $this->infolist }}
            </div>
        </x-filament::card>
    </section>

    {{-- User Metrics Grid --}}
    <div class="mt-4 space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            {{-- User Statistics Card --}}
            <x-filament::card class="stat-card" aria-labelledby="user-stats-heading">
                <x-filament::section heading="User Statistics" icon="heroicon-o-chart-bar"
                    description="Key metrics about this user's activity" id="user-stats-heading">

                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Posts Created</dt>
                            <dd class="font-medium">{{ number_format($this->record->posts_count ?? 0) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Pages Created</dt>
                            <dd class="font-medium">{{ number_format($this->record->pages_count ?? 0) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Active</dt>
                            <dd class="font-medium">
                                {{ $this->record->last_active_at?->diffForHumans() ?? __('Never') }}
                            </dd>
                        </div>
                    </dl>
                </x-filament::section>
            </x-filament::card>

            {{-- Account Status Card --}}
            <x-filament::card class="stat-card" aria-labelledby="account-status-heading">
                <x-filament::section heading="Account Status" icon="heroicon-o-shield-check"
                    description="Current account state and verification" id="account-status-heading">

                    <dl class="space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Account Status</dt>
                            <dd>
                                <x-filament::badge :color="match ($this->record->status) {
                                    'active' => 'success',
                                    'inactive' => 'danger',
                                    default => 'warning',
                                }" size="sm">
                                    {{ Str::headline($this->record->status) }}
                                </x-filament::badge>
                            </dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">Email Verified</dt>
                            <dd>
                                <x-filament::icon :icon="$this->record->hasVerifiedEmail()
                                    ? 'heroicon-o-check-circle'
                                    : 'heroicon-o-x-circle'" :color="$this->record->hasVerifiedEmail() ? 'success' : 'danger'" size="h-5 w-5" />
                            </dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">2FA Enabled</dt>
                            <dd>
                                <x-filament::icon :icon="$this->record->two_factor_enabled
                                    ? 'heroicon-o-check-circle'
                                    : 'heroicon-o-x-circle'" :color="$this->record->two_factor_enabled ? 'success' : 'gray'" size="h-5 w-5" />
                            </dd>
                        </div>
                    </dl>
                </x-filament::section>
            </x-filament::card>
        </div>

        {{-- Activity Log Section --}}
        @if (filament()->hasPlugin('activity-log'))
            <x-filament::card class="stat-card" aria-labelledby="activity-log-heading">
                <x-filament::section heading="Recent Activity" icon="heroicon-o-clipboard-document-list"
                    description="Last 5 actions performed by this user" id="activity-log-heading">

                    @livewire(\Filament\Plugins\ActivityLog\Widgets\RecentActivitiesWidget::class, [
                        'subjectType' => get_class($this->record),
                        'subjectId' => $this->record->id,
                        'limit' => 5,
                    ])
                </x-filament::section>
            </x-filament::card>
        @endif
    </div>

    {{-- Enhanced Styling --}}
    @push('styles')
        <style>
            [aria-labelledby="profile-heading"] .user-profile-avatar {
                border: 3px solid rgb(var(--primary-500));
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .dark [aria-labelledby="profile-heading"] .user-profile-avatar {
                border-color: rgb(var(--primary-400));
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25);
            }

            .stat-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .dark .stat-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.25), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush
</x-filament-panels::page>
