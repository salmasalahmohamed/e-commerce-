<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->disabled(),
            TextInput::make('email')->disabled(),
        ];
    }

}
