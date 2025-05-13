<?php

namespace App\Filament\Clusters\Cms\Resources\PostResource\RelationManagers;

use App\Filament\Clusters\Cms\Resources\PostResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activity Log';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Action'),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('event')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn($record) => $record ? "Activity Details: {$record->description}" : 'No Record')
                    ->modalContent(fn($record) => $record ? view('filament.clusters.settings.resources.activities.activity-log-details', [
                        'activity' => $record,
                    ]) : 'Record not found')
                    ->hidden(fn($record): bool => $record === null)
                    ->disabled(fn($record): bool => $record === null),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])->defaultSort('created_at', 'desc');
    }
}
