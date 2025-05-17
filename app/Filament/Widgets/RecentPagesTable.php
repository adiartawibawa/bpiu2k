<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPagesTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Pages')
            ->query(
                Page::with('author')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('author.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                    }),
                TextColumn::make('layout'),
                TextColumn::make('published_at')
                    ->dateTime(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->paginated(false);
    }
}
