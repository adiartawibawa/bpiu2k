<?php

namespace App\Filament\Clusters\Cms\Resources\PostResource\Pages;

use App\Exports\PostsExport;
use App\Filament\Clusters\Cms\Resources\PostResource;
use App\Imports\PostsImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Ekspor')
                ->outlined()
                ->action(fn() => Excel::download(new PostsExport, 'posts.xlsx'))
                ->icon('heroicon-o-arrow-down-tray'),

            Action::make('import')
                ->label('Impor')
                ->outlined()
                ->modalHeading('Impor Post dari Excel')
                ->modalSubmitActionLabel('Impor')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::queueImport(new PostsImport, $data['file']);
                })
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make(),
        ];
    }
}
