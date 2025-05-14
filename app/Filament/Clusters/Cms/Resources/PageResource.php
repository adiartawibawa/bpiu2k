<?php

namespace App\Filament\Clusters\Cms\Resources;

use App\Filament\Clusters\Cms;
use App\Filament\Clusters\Cms\Resources\PageResource\Pages;
use App\Filament\Clusters\Cms\Resources\PageResource\RelationManagers;
use App\Filament\Clusters\Cms\Resources\PageResource\RelationManagers\MediaRelationManager;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Cms::class;

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('pages/attachments'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->default('draft'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->hidden(fn(Forms\Get $get) => $get('status') !== 'published'),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Schedule Publish')
                            ->native(false)
                            ->minDate(now())
                            ->displayFormat('d M Y H:i')
                            ->hidden(fn($get) => $get('status') !== 'draft')
                            ->helperText('Content will be automatically published at the specified time.'),

                        Forms\Components\Select::make('layout')
                            ->options([
                                'default' => 'Default',
                                'full-width' => 'Full Width',
                                'sidebar-left' => 'Sidebar Left',
                                'sidebar-right' => 'Sidebar Right',
                            ])
                            ->default('default'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(160),
                    ]),

                Forms\Components\Section::make('Featured Image')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->directory('pages/featured-images')
                            ->imageEditor(),
                    ]),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('preview')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => route('pages.preview', $record))
                        ->hidden(fn($record) => !$record?->exists)
                        ->openUrlInNewTab(),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),
                Tables\Columns\TextColumn::make('layout')
                    ->badge(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->color(fn($record) => $record->scheduled_at && $record->scheduled_at <= now() ? 'danger' : 'gray')
                    ->description(fn($record) => $record->scheduled_at && $record->scheduled_at <= now()
                        ? 'Past the schedule'
                        : null),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->description(fn($record) => $record->scheduled_at && $record->scheduled_at <= now()
                        ? '⚠️ Jadwal telah lewat - periksa kembali'
                        : null),
                Tables\Columns\IconColumn::make('is_scheduled')
                    ->label('')
                    ->icon(fn($record) => $record->scheduled_at ? 'heroicon-o-clock' : null)
                    ->tooltip(fn($record) => $record->scheduled_at
                        ? 'Will be published on ' . $record->scheduled_at->format('d M Y H:i')
                        : null),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                Tables\Filters\Filter::make('scheduled')
                    ->label('Scheduled Content')
                    ->query(fn($query) => $query->scheduled()),
                Tables\Filters\SelectFilter::make('layout')
                    ->options([
                        'default' => 'Default',
                        'full-width' => 'Full Width',
                        'sidebar-left' => 'Sidebar Left',
                        'sidebar-right' => 'Sidebar Right',
                    ]),
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from'),
                        Forms\Components\DatePicker::make('published_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['published_from'], fn($query) => $query->whereDate('published_at', '>=', $data['published_from']))
                            ->when($data['published_until'], fn($query) => $query->whereDate('published_at', '<=', $data['published_until']));
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Preview')
                    ->color('gray')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Page $record): string => route('pages.preview', $record))
                    ->hidden(fn(Page $record): bool => $record->status === 'archived')
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('publish_now')
                    ->label('Publikasikan Sekarang')
                    ->icon('heroicon-o-check-circle')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                            'scheduled_at' => null,
                        ]);
                    })
                    ->visible(fn($record) => $record->status === 'draft' && $record->scheduled_at),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            MediaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
