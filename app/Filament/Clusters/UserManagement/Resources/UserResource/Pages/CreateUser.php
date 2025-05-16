<?php

namespace App\Filament\Clusters\UserManagement\Resources\UserResource\Pages;

use App\Filament\Clusters\UserManagement\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
