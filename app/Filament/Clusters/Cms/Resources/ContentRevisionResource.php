<?php

namespace App\Filament\Clusters\Cms\Resources;

use App\Filament\Clusters\Cms;
use App\Filament\Clusters\Cms\Resources\ContentRevisionResource\Pages;
use App\Filament\Clusters\Cms\Resources\ContentRevisionResource\RelationManagers;
use App\Models\Activity;
use App\Models\Page;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentRevisionResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $cluster = Cms::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Content Type')
                    ->formatStateUsing(fn($state) => class_basename($state)),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('causer.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject_type')
                    ->options([
                        Post::class => 'Post',
                        Page::class => 'Page',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(fn($record) => view('filament.clusters.cms.resources.revisions.content-revisions', [
                        'record' => $record,
                    ])),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContentRevisions::route('/'),
        ];
    }
}
