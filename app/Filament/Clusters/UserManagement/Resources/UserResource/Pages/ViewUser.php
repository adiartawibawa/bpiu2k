<?php

namespace App\Filament\Clusters\UserManagement\Resources\UserResource\Pages;

use App\Filament\Clusters\UserManagement\Resources\UserResource;
use App\Models\User;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.clusters.user-management.pages.view-user';

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
            \Filament\Actions\Action::make('verify')
                ->icon('heroicon-o-check-circle')
                ->action(fn() => $this->record->markAsVerified())
                ->hidden(fn() => $this->record->hasVerifiedEmail())
                ->visible(fn() => auth()->user()->can('verify', $this->record)),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                $this->getProfileInformationSection(),
                $this->getRolesSection(),
                // Additional sections can be added here
            ]);
    }

    protected function getProfileInformationSection(): Components\Section
    {
        return Components\Section::make('Profile Information')
            ->schema([
                Components\ImageEntry::make('avatar_url')
                    ->label('')
                    ->circular()
                    ->size(150),
                Components\TextEntry::make('name'),
                Components\TextEntry::make('username'),
                Components\TextEntry::make('email'),
                Components\TextEntry::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        User::STATUS_ACTIVE => 'success',
                        User::STATUS_INACTIVE => 'danger',
                        User::STATUS_PENDING => 'warning',
                        default => 'gray',
                    }),
                Components\IconEntry::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean(),
            ])
            ->columns(2);
    }

    protected function getRolesSection(): Components\Section
    {
        return Components\Section::make('Roles')
            ->schema([
                Components\RepeatableEntry::make('roles')
                    ->schema([
                        Components\TextEntry::make('name')
                            ->badge(),
                    ])
                    ->columns(1),
            ]);
    }
}
