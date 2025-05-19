<?php

namespace App\Filament\Clusters\Cms\Resources;

use App\Filament\Clusters\Cms;
use App\Filament\Clusters\Cms\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $cluster = Cms::class;

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $navigationGroup = 'Menus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('location')
                            ->options([
                                'header' => 'Header',
                                'footer' => 'Footer',
                                'sidebar' => 'Sidebar',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'header' => 'primary',
                        'footer' => 'gray',
                        'sidebar' => 'success',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_items')
                    ->label('Manage Items')
                    ->icon('heroicon-o-queue-list')
                    ->url(fn(Menu $record): string => route('filament.admin.cms.resources.menu-items.tree', ['record' => $record])),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
            // 'items' => Pages\ManageMenuItems::route('/{record}/items'),
        ];
    }
}
