<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state,string $operation) =>   $operation=='create'?$set('slug', Str::slug($state)):null),

        TextInput::make('slug')
                    ->required()->unique(Category::class,'slug'),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
